<?php

namespace App\Services;

use App\Models\HotelInsuranceWeather;
use App\Models\DenyList;
use App\Models\Hotel;
use App\Models\HotelControl;
use Illuminate\Support\Facades\DB;

class BrHotelRegisterService
{
    /**
     * ホテルコードの取得
     * ※YYYYMM(年月) + 今月の４桁の連番を取得
     *
     * @return string
     */
    public function getHotelCd(): string
    {
        $sql = <<<SQL
            select
                ifnull(
                    max(hotel_cd) + 1
                    , concat(date_format(now(), '%Y%m'), '0001')
                ) as hotel_cd
            from
                hotel
            where
                hotel_cd LIKE concat(date_format(now(), '%Y%m'), '%')
        SQL;
        $result = DB::select($sql);
        return $result[0]->hotel_cd;
    }

    /**
     * ホテルコードの取得
     * ※ 3YYYMM(年月) + 今月の４桁の連番を取得
     *
     * @return string
     */
    public function getHotelCdSanpu(): string
    {
        $sql = <<<SQL
            select
                ifnull(
                    max(hotel_cd) + 1
                    , concat(
                        '3'
                        , substring(date_format(now(), '%Y%m'), 2)
                        , '0001'
                    )
                ) as hotel_cd
            from
                hotel
            where
                hotel_cd LIKE concat(
                    '3'
                    , substring(date_format(now(), '%Y%m'), 2)
                    , '%'
                )
        SQL;
        $result = DB::select($sql);
        return $result[0]->hotel_cd;
    }

    /**
     * Undocumented function
     *
     * @param [type] $hotel
     * @param [type] $hotelInsuranceWeather
     * @param [type] $denyLists
     * @return array
     */
    public function store($hotel, $hotelInsuranceWeather, $denyLists): array
    {
        $errorMessages = [];
        DB::beginTransaction();

        try {
            $newHotel = Hotel::create($hotel);
            HotelInsuranceWeather::create($hotelInsuranceWeather);
            foreach ($denyLists as $denyList) {
                $denyList['deny_cd'] = $this->getDenyListSequence();
                DenyList::create($denyList);
            }

            if (!$newHotel->wasRecentlyCreated) {
                $errorMessages[] = '登録に失敗しました。';
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e);
            $errorMessages[] = '登録に失敗しました。';
        }

        if (count($errorMessages) > 0) {
            DB::rollBack();
        } else {
            DB::commit();
        }
        return $errorMessages;
    }

    public function makeHotelInsuranceWeatherData($hotelCd, $hotel, $actionCd): array
    {
        $data = $this->getAmedasAddress(
            $hotel['pref_id'],
            $hotel['city_id'],
            $hotel['ward_id'],
            $hotel['address']
        );

        if (count($data) === 0) {
            $insuranceStatus = HotelInsuranceWeather::INSURANCE_STATUS_STOP_ETERNAL;
            $amedasCd = null;
        } else {
            if ($this->isSkyAddress($hotel['pref_id'], $hotel['address'])) {
                $insuranceStatus = HotelInsuranceWeather::INSURANCE_STATUS_STOP_WINTER;
            } else {
                $insuranceStatus = HotelInsuranceWeather::INSURANCE_STATUS_AVAILABLE;
            }
            $amedasCd = $data[0]->amedas_cd;
        }

        $hotelInsuranceWeather = [
            'hotel_cd'          => $hotelCd,
            'insurance_status'  => $insuranceStatus,
            'amedas_cd'         => $amedasCd,
            'entry_cd'          => $actionCd,
            'modify_cd'         => $actionCd,
        ];
        return $hotelInsuranceWeather;
    }

    /**
     * 住所から観測所の設定
     *
     * MEMO: 移植元は public\app\_common\models\Core\Insurance\Weather.php
     *
     * @param [type] $prefId
     * @param [type] $cityId
     * @param [type] $wardId
     * @param [type] $address
     * @return array
     */
    private function getAmedasAddress(
        $prefId,
        $cityId,
        $wardId,
        $address
    ): array {
        $sql = <<<SQL
            select
                jbr_id,
                amedas_cd,
                amedas_nm
            from
                mast_amedas
            where
                pref_id = :pref_id
                and city_id = :city_id
                and (
                    ward_id = :ward_id1
                    or :address like concat('%', town_nm, '%')
                    or  (
                        :ward_id2 is null
                        and town_nm is null
                    )
                )
        SQL;

        $a_amedas = DB::select($sql, [
            'pref_id' => $prefId,
            'city_id' => $cityId,
            'ward_id1' => $wardId,
            'ward_id2' => $wardId,
            'address' => $address,
        ]);

        // MEMO: 移植元では、エラー通知メールを送信している。
        // MEMO: 移植元 public\app\_common\models\Core\Insurance\Weather.php set_amedas_address
        if (count($a_amedas) === 0) {
            \Illuminate\Support\Facades\Log::error('[JBR][Warning] アメダス設置場所取得失敗');
        }

        // $this->amedas_cd = $a_amedas[0]['amedas_cd'];
        // $this->amedas_nm = $a_amedas[0]['amedas_nm'];
        return $a_amedas;
    }

    /**
     * 住所から冬季停止の設定
     *
     * MEMO: 移植元は public\app\_common\models\Core\Insurance\Weather.php
     *
     * @param [type] $as_pref_id
     * @param [type] $as_address
     * @return bool
     */
    private function isSkyAddress(
        $as_pref_id,
        $as_address
    ): bool {
        $sql = <<<SQL
            select
                1
            from
                sky_area
            where
                pref_id = :pref_id
                and :address like concat(address, '%')
        SQL;
        $a_sky = DB::select($sql, [
            'pref_id' => $as_pref_id,
            'address' => $as_address,
        ]);
        return count($a_sky) > 0;
    }

    public function makeDenyListsData($hotelCd, $actionCd): array
    {
        $result = [];

        // TODO: hard coding logic, magic number: AC 用にカスタム
        $denyPartners = [
            'jetstar' => '3016007888', // ジェットスター販売開始時にコメント外す
            'msd'     => '2000005100',
            'spring'  => '3018009900', // 春秋航空 国内DP
        ];

        // 拒否リスト作成
        foreach ($denyPartners as $partnerName => $partnerCd) {
            // 登録するデータ設定
            $sequence = $this->getDenyListSequence();
            $result[] = [
                'deny_cd'    => str_pad($sequence, 5, 0, STR_PAD_LEFT),
                'partner_cd' => $partnerCd,
                'hotel_cd'   => $hotelCd,
                'deny_type'  => DenyList::DENY_TYPE_PARTNER,
                'entry_cd'   => $actionCd,
                'modify_cd'  => $actionCd,
            ];

            // アークスリー または 春秋航空 は「運用」の2レコード目を作成する
            if ($partnerName === 'jetstar' or $partnerName === 'spring') {
                $sequence = $this->getDenyListSequence();
                $result[] = [
                    'deny_cd'    => str_pad($sequence, 5, 0, STR_PAD_LEFT),
                    'partner_cd' => $partnerCd,
                    'hotel_cd'   => $hotelCd,
                    'deny_type'  => DenyList::DENY_TYPE_OPERATION,
                    'entry_cd'   => $actionCd,
                    'modify_cd'  => $actionCd,
                ];
            }
        }
        return $result;
    }

    /**
     * 拒否コードの取得
     * ※YYYY + 5桁の連番を取得
     *
     * MEMO: 移植元では、 oracle の sequence 取得のファンクションを利用しているらしい
     * HACK: 単に、 mysql の auto increment ではダメなのか？
     *
     * @return string
     */
    private function getDenyListSequence(): string
    {
        $year = date('Y');
        $sql = <<<SQL
            select
                ifnull(
                    max(deny_cd) + 1
                    , concat({$year}, '00001')
                ) as deny_cd
            from
                deny_list
            where
                deny_cd LIKE concat({$year}, '%')
        SQL;
        $result = DB::select($sql);
        return $result[0]->deny_cd;
    }
}
