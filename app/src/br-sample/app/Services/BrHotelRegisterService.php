<?php

namespace App\Services;

use App\Models\DenyList;
use App\Models\Hotel;
use App\Models\HotelAccount;
use App\Models\HotelControl;
use App\Models\HotelInsuranceWeather;
use App\Models\HotelNotify;
use App\Models\HotelPerson;
use App\Models\HotelStatus;
use App\Models\HotelSystemVersion;
use App\Util\Models_Cipher;
use Illuminate\Support\Facades\DB;

class BrHotelRegisterService
{
    /**
     * ホテルコードの取得
     * ※都道府県コード(2桁) + 年2桁 + 月2桁 + 4桁
     *
     * @return string
     */
    public function getHotelCd($prefId): string
    {
        $prefix = sprintf('%02d', $prefId) . date('ym');
        $sql = <<<SQL
            select
                ifnull(
                    max(hotel_cd) + 1
                    , :start_cd
                ) as hotel_cd
            from
                hotel
            where
                hotel_cd LIKE concat(:prefix, '%')
        SQL;
        $result = DB::select($sql, [
            'start_cd'  => $prefix . '0001',
            'prefix'    => $prefix,
        ]);
        return sprintf('%010d', $result[0]->hotel_cd);
    }

    /**
     * DB 登録処理を実行
     *
     * @param array $hotel
     * @param array $hotelInsuranceWeather
     * @param array $denyLists
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

    /**
     * hotel テーブルに登録するデータを整形
     *
     * @param string $hotelCd
     * @param array $inputHotel
     * @return array
     */
    public function makeHotelData($hotelCd, $inputHotel): array
    {
        $actionCd = $this->getActionCd();
        return [
            'hotel_cd'          => $hotelCd,
            'hotel_category'    => $inputHotel['hotel_category'],
            'hotel_nm'          => $inputHotel['hotel_nm'],
            'hotel_kn'          => $inputHotel['hotel_kn'],
            'hotel_old_nm'      => $inputHotel['hotel_old_nm'],
            'postal_cd'         => $inputHotel['postal_cd'],
            'pref_id'           => $inputHotel['pref_id'],
            'city_id'           => $inputHotel['city_id'],
            'ward_id'           => array_key_exists('ward_id', $inputHotel) ? $inputHotel['ward_id'] : null,
            'address'           => $inputHotel['address'],
            'tel'               => $inputHotel['tel'],
            'fax'               => $inputHotel['fax'],
            'room_count'        => $inputHotel['room_count'],
            'check_in'          => $inputHotel['check_in'],
            'check_in_end'      => $inputHotel['check_in_end'],
            'check_in_info'     => $inputHotel['check_in_info'],
            'check_out'         => $inputHotel['check_out'],
            'midnight_status'   => $inputHotel['midnight_status'],
            'accept_status'     => Hotel::ACCEPT_STATUS_STOPPING,

            // ※初期登録時に自動更新だと登録中にもかかわらずbatch処理が走ると勝手に受付状態を停止中→受付中へ変更してしまう為。 バグ3196対応
            'accept_auto'       => Hotel::ACCEPT_AUTO_MANUAL,

            'accept_dtm'        => date("Y-m-d H:i:s"),
            'entry_cd'          => $actionCd,
            'modify_cd'         => $actionCd,
        ];
    }

    /**
     * hotel_insurance_weather に登録するデータを整形
     *
     * @param string $hotelCd
     * @param array $inputHotel
     * @return array
     */
    public function makeHotelInsuranceWeatherData($hotelCd, $inputHotel): array
    {
        $actionCd = $this->getActionCd();
        $data = $this->getAmedasAddress(
            $inputHotel['pref_id'],
            $inputHotel['city_id'],
            $inputHotel['ward_id'],
            $inputHotel['address']
        );

        if (count($data) === 0) {
            $insuranceStatus = HotelInsuranceWeather::INSURANCE_STATUS_STOP_ETERNAL;
            $amedasCd = null;
        } else {
            if ($this->isSkyAddress($inputHotel['pref_id'], $inputHotel['address'])) {
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
     * @param string $prefId
     * @param string $cityId
     * @param string $wardId
     * @param string $address
     * @return array
     */
    private function getAmedasAddress($prefId, $cityId, $wardId, $address): array
    {
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

        $mastAmedas = DB::select($sql, [
            'pref_id' => $prefId,
            'city_id' => $cityId,
            'ward_id1' => $wardId,
            'ward_id2' => $wardId,
            'address' => $address,
        ]);

        // MEMO: 移植元では、エラー通知メールを送信している。
        // MEMO: 移植元 public\app\_common\models\Core\Insurance\Weather.php set_amedas_address
        if (count($mastAmedas) === 0) {
            \Illuminate\Support\Facades\Log::error('[JBR][Warning] アメダス設置場所取得失敗');
        }

        return $mastAmedas;
    }

    /**
     * 住所から冬季停止の設定
     *
     * MEMO: 移植元は public\app\_common\models\Core\Insurance\Weather.php
     *
     * @param string $prefId
     * @param string $address
     * @return bool
     */
    private function isSkyAddress($prefId, $address): bool
    {
        $sql = <<<SQL
            select
                1
            from
                sky_area
            where
                pref_id = :pref_id
                and :address like concat(address, '%')
        SQL;
        $skyAreas = DB::select($sql, [
            'pref_id' => $prefId,
            'address' => $address,
        ]);
        return count($skyAreas) > 0;
    }

    /**
     * deny_list に登録するデータを整形
     *
     * @param string $hotelCd
     * @return array
     */
    public function makeDenyListsData($hotelCd): array
    {
        $actionCd = $this->getActionCd();
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

    public function storeManagement($hotelAccount, $hotelPerson, $hotelStatus): array
    {
        $errorMessages = [];
        DB::beginTransaction();

        try {
            HotelAccount::create($hotelAccount);
            HotelPerson::create($hotelPerson);
            HotelStatus::create($hotelStatus);
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

    /**
     * hotel_account に登録するデータを整形
     *
     * @param string $hotelCd
     * @param array $inputHotelAccount
     * @return array
     */
    public function makeHotelAccountData($hotelCd, $inputHotelAccount): array
    {
        $actionCd = $this->getActionCd();

        // パスワード暗号化
        $cipher = new Models_Cipher(config('settings.cipher_key'));
        $encryptedPassword = $cipher->encrypt($inputHotelAccount['password']);

        return [
            'hotel_cd'          => $hotelCd,
            'account_id_begin'  => $inputHotelAccount['account_id_begin'],
            'account_id'        => strtoupper($inputHotelAccount['account_id_begin']),
            'accept_status'     => $inputHotelAccount['accept_status'],
            'password'          => $encryptedPassword,
            'entry_cd'          => $actionCd,
            'modify_cd'         => $actionCd,
        ];
    }

    /**
     * hotel_person に登録するデータを整形
     *
     * @param string $hotelCd
     * @param array $inputHotelPerson
     * @return array
     */
    public function makeHotelPersonData($hotelCd, $inputHotelPerson): array
    {
        $actionCd = $this->getActionCd();

        // メールアドレス暗号化
        $cipher = new Models_Cipher(config('settings.cipher_key'));
        $encryptedPersonEmail = $cipher->encrypt($inputHotelPerson['person_email']);
        return [
            'hotel_cd'      => $hotelCd,
            'person_post'   => $inputHotelPerson['person_post'],
            'person_nm'     => $inputHotelPerson['person_nm'],
            'person_tel'    => $inputHotelPerson['person_tel'],
            'person_fax'    => $inputHotelPerson['person_fax'],
            'person_email'  => $encryptedPersonEmail,
            'entry_cd'      => $actionCd,
            'modify_cd'     => $actionCd,
        ];
    }

    /**
     * hotel_status に登録するデータを整形
     *
     * @param string $hotelCd
     * @param array $inputHotelStatus
     * @return array
     */
    public function makeHotelStatusData($hotelCd, $inputHotelStatus): array
    {
        $actionCd = $this->getActionCd();
        return [
            'hotel_cd' => $hotelCd,
            'entry_status'  => $inputHotelStatus['entry_status'],
            'contract_ymd'  => $inputHotelStatus['contract_ymd'],
            'open_ymd'      => $inputHotelStatus['open_ymd'],
            // 'close_dtm'     => $inputHotelStatus['close_dtm'],
            'entry_cd'      => $actionCd,
            'modify_cd'     => $actionCd,
        ];
    }
    public function storeStatus($hotelNotify, $hotelControl, $hotelSystemVersion): array
    {
        $errorMessages = [];
        DB::beginTransaction();

        try {
            HotelNotify::create($hotelNotify);
            HotelControl::create($hotelControl);
            HotelSystemVersion::create($hotelSystemVersion);
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

    /**
     * hotel_notify に登録するデータを整形
     *
     * @param string $hotelCd
     * @param array $inputHotelNotify
     * @param array $notifyDevices
     * @return array
     */
    public function makeHotelNotifyData($hotelCd, $inputHotelNotify, $notifyDevices): array
    {
        $actionCd = $this->getActionCd();

        $notifyDeviceValue = 0;
        foreach ($notifyDevices as $value) {
            $notifyDeviceValue += $value;
        }

        // メールアドレス 暗号化
        $cipher = new Models_Cipher(config('settings.cipher_key'));
        $encryptedNotifyEmail = $cipher->encrypt($inputHotelNotify['notify_email']);

        return [
            'hotel_cd'      => $hotelCd,
            'notify_status' => $inputHotelNotify['notify_status'],
            'notify_device' => $notifyDeviceValue,
            'neppan_status' => $inputHotelNotify['neppan_status'],
            // 'notify_no'     => $inputHotelNotify['notify_no'],
            'notify_email'  => $encryptedNotifyEmail,
            'notify_fax'    => $inputHotelNotify['notify_fax'],
            'faxpr_status'  => $inputHotelNotify['faxpr_status'],
            'entry_cd'      => $actionCd,
            'modify_cd'     => $actionCd,
        ];
    }

    /**
     * hotel_control に登録するデータを整形
     *
     * @param string $hotelCd
     * @param array $inputHotelControl
     * @return array
     */
    public function makeHotelControlData($hotelCd, $inputHotelControl): array
    {
        $actionCd = $this->getActionCd();
        return [
            'hotel_cd'          => $hotelCd,
            'stock_type'        => $inputHotelControl['stock_type'],
            'checksheet_send'   => $inputHotelControl['checksheet_send'],
            'charge_round'      => $inputHotelControl['charge_round'],
            'stay_cap'          => $inputHotelControl['stay_cap'],
            'management_status' => $inputHotelControl['management_status'],
            'akafu_status'      => $inputHotelControl['akafu_status'],
            'entry_cd'          => $actionCd,
            'modify_cd'         => $actionCd,
        ];
    }

    /**
     * hotel_system_version に登録するデータを整形
     *
     * @param string $hotelCd
     * @return array
     */
    public function makeHotelSystemVersionData($hotelCd): array
    {
        $actionCd = $this->getActionCd();
        return [
            'hotel_cd'      => $hotelCd,
            'system_type'   => HotelSystemVersion::SYSTEM_TYPE_PLAN, // 固定値
            'version'       => HotelSystemVersion::VERSION_2, // 固定値
            'entry_cd'      => $actionCd,
            'modify_cd'     => $actionCd,
        ];
    }

    /**
     * 数値をビットに分解した配列に変換する（通知媒体）
     *
     * @param int $notifyDeviceValue
     * @return array
     */
    public function divideNotifyDeviceValueToArray($notifyDeviceValue): array
    {
        $notifyDevices = [];
        $array = [
            HotelNotify::NOTIFY_DEVICE_FAX,
            HotelNotify::NOTIFY_DEVICE_EMAIL,
            // HotelNotify::NOTIFY_DEVICE_OPERATOR,
            HotelNotify::NOTIFY_DEVICE_LINCOLN,
        ];
        foreach ($array as $value) {
            if ($notifyDeviceValue & (1 << $value)) {
                $notifyDevices[] = (1 << $value);
            }
        }
        return $notifyDevices;
    }

    /**
     * コントローラ名とアクション名を取得して、ユーザーIDと連結
     * ユーザーID取得は暫定の為、書き換え替えが必要です。
     *
     * MEMO: app/Models/common/CommonDBModel.php から移植したもの
     * HACK: 適切に共通化したいか。
     * @return string
     */
    private function getActionCd()
    {
        $path = explode("@", \Illuminate\Support\Facades\Route::currentRouteAction());
        $pathList = explode('\\', $path[0]);
        $controllerName = str_replace("Controller", "", end($pathList)); // コントローラ名
        $actionName = $path[1]; // アクション名
        $userId = \Illuminate\Support\Facades\Session::get("user_id"); // TODO: ユーザー情報取得のキーは仮です
        $actionCd = $controllerName . "/" . $actionName . "." . $userId;
        return $actionCd;
    }
}
