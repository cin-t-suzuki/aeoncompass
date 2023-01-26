<?php

namespace App\Http\Controllers\ctl;

use App\Common\Traits;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelAmenity;
use App\Models\HotelAccount;
use App\Models\HotelBathTax;
use App\Models\HotelCancelPolicy;
use App\Models\HotelCancelRate;
use App\Models\HotelCard;
use App\Models\HotelControl;
use App\Models\HotelFacility;
use App\Models\HotelFacilityRoom;
use App\Models\HotelInfo;
use App\Models\HotelInform;
use App\Models\HotelLink;
use App\Models\HotelNearby;
use App\Models\HotelNotify;
use App\Models\HotelReceipt;
use App\Models\HotelService;
use App\Models\HotelStatus;
use App\Models\HotelStatusJr;
use App\Models\MastPref;
use App\Models\MastCity;
use App\Models\HotelModify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Util\Models_Cipher;
use Exception;

class HtlHotelController extends _commonController
{
    use Traits;

    public function show(Request $request)
    {
        $targetCd = $request->input('target_cd');

        $aa_hotel = Hotel::find($targetCd);
        $a_hotel_info = HotelInfo::find($targetCd);
        $a_hotel_bath_tax = HotelBathTax::find($targetCd);
        $a_hotel_bath_tax_flg = 1;
        if ($aa_hotel->city_id == \App\Models\MastCity::CITY_ID_BEPPU) {
            // 別府市は宿泊税の金額に応じて入湯税額が変わるためこの機能を使えないようにFLGをセット
            // HACK: magic number
            $a_hotel_bath_tax_flg = 0;
        }

        // HACK: 移植元では、 get_hotel_xxx() 系を Hotel モデルにまとめている。
        // hotel_cdをセット

        // $a_hotelrate             = $models_hotel->get_hotel_rates();              // 料率の一覧データを配列で取得

        $a_hotel_links          = $this->getHotelLinks($targetCd);          // 施設リンクの取得
        $a_hotel_inform_cancel  = $this->getHotelInformCancels($targetCd);  // 施設注意事項情報の取得
        $a_hotel_inform_free    = $this->getHotelInformFrees($targetCd);    // 施設その他記入欄情報の取得
        $a_hotel_card           = $this->getHotelCards($targetCd);          // 利用可能カードの取得
        $a_hotel_amenities      = $this->getHotelAmenities($targetCd);      // アメニティの取得
        $a_hotel_services       = $this->getHotelServices($targetCd);       // サービス の取得
        $a_hotel_nearbies       = $this->getHotelNearbies($targetCd);       // 周辺情報の取得
        $a_hotel_facilities     = $this->getHotelFacilities($targetCd);     // 設備 の取得
        $a_hotel_facility_rooms = $this->getHotelFacilityRooms($targetCd);  // 部屋設備 の取得
        $a_hotel_cancel_rates   = $this->getHotelCancelRates($targetCd);

        // 施設キャンセルポリシー情報
        $a_hotel_cancel_policy  = HotelCancelPolicy::find($targetCd);

        // 情報のセット
        $a_hotel_receipt = HotelReceipt::find($targetCd);
        $a_hotel_control = HotelControl::find($targetCd);

        $a_amenity          = $this->getAmenities($a_hotel_amenities);          // アメニティ取得
        $a_service          = $this->getServices($a_hotel_services);            // サービス取得
        $a_nearby           = $this->getNearbies($a_hotel_nearbies);            // 周辺情報取得
        $a_facility         = $this->getFacilities($a_hotel_facilities);        // 設備取得
        $a_facility_room    = $this->getFacilityRooms($a_hotel_facility_rooms); // 部屋設備取得
        $a_hotel_stations    = $this->getHotelStation($targetCd, '', ['station_nm' => '']);    // 交通アクセスリスト取得

        $a_hotel_station = [];
        $count = 0;
        foreach ($a_hotel_stations as $station) {
            $a_hotel_station[] = $station;
            $count += 1;
            if ($count == 3) {
                break;
            }
        }
        $a_hotel_station = collect($a_hotel_station);

        return view('ctl.htlHotel.show', [
            'target_cd' => $targetCd,

            'hotels'                    => null, // 移植元で、 controller から渡されているが、使われていなさそう
            'a_hotel_info'              => $a_hotel_info,
            'a_hotel_links'             => $a_hotel_links,
            'a_hotel_card'              => $a_hotel_card,
            'a_hotel_inform_cancel'     => $a_hotel_inform_cancel,

            'a_hotel_inform_free'       => $a_hotel_inform_free,
            'a_hotel_amenities'         => $a_hotel_amenities,
            'a_hotel_services'          => $a_hotel_services,
            'a_hotel_nearbies'          => $a_hotel_nearbies,
            'a_hotel_facilities'        => $a_hotel_facilities,

            'a_hotel_facility_rooms'    => $a_hotel_facility_rooms,
            'a_amenity'                 => $a_amenity,
            'a_service'                 => $a_service,
            'a_nearby'                  => $a_nearby,
            'a_facility'                => $a_facility,

            'a_facility_room'           => $a_facility_room,
            'a_hotel_station'           => $a_hotel_station,
            'a_hotel_control'           => $a_hotel_control,
            'a_hotel_cancel_policy'     => $a_hotel_cancel_policy,
            'a_hotel_cancel_rates'      => $a_hotel_cancel_rates,

            'a_hotel_receipt'           => $a_hotel_receipt,
            'a_hotel_bath_tax'          => $a_hotel_bath_tax,
            'a_hotel_bath_tax_flg'      => $a_hotel_bath_tax_flg,

        ]);
    }

    // 施設情報登録内容の変更
    public function edit(Request $request)
    {
        try {

            // エラーメッセージの設定
            if ($request->session()->has('errors')) {
                // エラーメッセージ があれば、入力を保持して表示
                $errorList = $request->session()->pull('errors');
                $this->addErrorMessageArray($errorList);
            }

            $targetCd = $request->input('target_cd');

            // 特定施設の設備(hotel_facility)の取得
            $a_hotel_facilities = $this->getHotelFacilities($targetCd);

            // リクエストの値の取得＆置き換え
            $HotelFacility = $request->input('Hotel_Facility');
            if (!empty($HotelFacility)) {
                $a_req_hotel_facility = $HotelFacility;

                // 駐車場の有無のチェックボックスの値を置き換え
                if (is_array($a_hotel_facilities['values'])) {
                    foreach ($a_hotel_facilities['values'] as $key => $value) {
                        if ($value['element_id'] == 9) {
                            $a_hotel_facilities['values'][$value['element_id']]['element_value_id'] = $a_req_hotel_facility[$value['element_id']];
                        }
                    }
                }
            }

            // 特定施設のリンクの取得
            $a_hotel_links = DB::table('hotel_link')->where('type', 1)->where('hotel_cd', $targetCd)->get(); //   type ウェブサイトタイプ  1:施設トップページ 2:携帯トップページ 3:その他ページ

            // 施設情報取得
            $a_hotel = Hotel::find($targetCd);

            // 都道府県、市の取得
            $a_pref = MastPref::where('pref_id', $a_hotel['pref_id'])->first();
            $a_city = MastCity::where('city_id', $a_hotel['city_id'])->first();

            // リクエストの値の取得＆置き換え
            $Hotel = $request->input('Hotel');
            if (!empty($Hotel)) {
                $a_req_hotel = $Hotel;

                // リクエストの値の取得
                $a_hotel['postal_cd']    = $a_req_hotel['postal_cd'];
                $a_hotel['address']      = $a_req_hotel['address'];
                $a_hotel['tel']          = $a_req_hotel['tel'];
                $a_hotel['fax']          = $a_req_hotel['fax'];
                $a_hotel['room_count']   = $a_req_hotel['room_count'];
                $a_hotel['check_in']     = $a_req_hotel['check_in'];
                $a_hotel['check_in_end'] = $a_req_hotel['check_in_end'];
                $a_hotel['check_out']    = $a_req_hotel['check_out'];
            }

            // 施設アカウント情報
            $a_hotel_account = HotelAccount::find($targetCd);

            // 施設状況情報
            $a_hotel_status = HotelStatus::find($targetCd);

            // 施設通知取得
            $a_hotel_notify = HotelNotify::find($targetCd);

            // メッセージの取得
            $s_notify_message = $this->makeNotifyMessages($a_hotel_notify);

            return view('ctl.htlHotel.edit', [
                'target_cd'         => $targetCd,
                'hotel'             => $a_hotel,
                'pref'              => $a_pref,
                'city'              => $a_city,
                'notify_message'    => $s_notify_message,
                'hotel_account'     => $a_hotel_account,
                'hotel_status'      => $a_hotel_status,
                'hotel_notify'      => $a_hotel_notify,
                'hotel_facilities'  => $a_hotel_facilities,
                'hotel_links'       => $a_hotel_links
            ]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 施設情報登録内容の更新
    public function update(Request $request)
    {
        $a_hotel  = $request->input('Hotel');
        $targetCd = $request->input('target_cd');

        try {
            // トランザクション開始
            DB::beginTransaction();

            // ホテルのインスタンスの取得
            $o_hotel          = new Hotel();

            $a_find_hotel = $o_hotel->find(['hotel_cd' => $targetCd])->first();

            // バリデート
            $a_attributes = [];
            $a_attributes['hotel_cd'] = $targetCd;
            $a_attributes['postal_cd'] = $a_hotel['postal_cd'];
            $a_attributes['address'] = $a_hotel['address'];
            $a_attributes['tel'] = $a_hotel['tel'];
            $a_attributes['fax'] = $a_hotel['fax'];
            $a_attributes['room_count'] = $a_hotel['room_count'];
            $a_attributes['check_in'] = $a_hotel['check_in'];
            $a_attributes['check_in_end'] = $a_hotel['check_in_end'];
            $a_attributes['check_out'] = $a_hotel['check_out'];

            // バリデート結果を判断
            $errorList = [];
            $errorList = $o_hotel->validation($a_attributes);

            if (count($errorList) > 0) {
                return $this->edit($request, ['target_cd' => $targetCd])->with(['errors' => $errorList]);
            }

            $a_attributes['modify_cd'] = 'modify_cd'; // TODO $this->box->info->env->action_cd
            $a_attributes['modify_ts'] = now();

            $hotel_update = $o_hotel->where(['hotel_cd' => $targetCd])
                ->update([
                    'hotel_cd' => $a_attributes['hotel_cd'],
                    'postal_cd' => $a_attributes['postal_cd'],
                    'address' => $a_attributes['address'],
                    'tel' => $a_attributes['tel'],
                    'fax' => $a_attributes['fax'],
                    'room_count' => $a_attributes['room_count'],
                    'check_in' => $a_attributes['check_in'],
                    'check_in_end' => $a_attributes['check_in_end'],
                    'check_out' => $a_attributes['check_out'],
                    'modify_cd' =>  $a_attributes['modify_cd'],
                    'modify_ts' => $a_attributes['modify_ts']
                ]);

            // 更新後失敗した場合editアクションへ
            if (!$hotel_update) {
                // ロールバック
                DB::rollback();

                // editアクションに転送します
                return $this->edit($request, ['target_cd' => $targetCd])->with(['errors' => '更新に失敗しました。']);
            }

            // 施設情報ページの更新依頼
            $existsHotelModify = HotelModify::where('hotel_cd', $targetCd)->exists();
            if (!$existsHotelModify) {
                $a_attributes['entry_cd'] = 'entry_cd'; // TODO $this->box->info->env->action_cd
                $a_attributes['entry_ts'] = now();
            }
            $o_hotel->hotel_modify($a_attributes);

            //-------------------------------
            // JRセット参画施設の場合
            //-------------------------------
            $o_hotel_status_jr      = new HotelStatusJr();
            $a_find_hotel_status_jr = $o_hotel_status_jr->find(['hotel_cd' => $targetCd])->first();
            $b_is_rejudge           = false;
            if (!empty($a_find_hotel_status_jr)) {
                // 施設の郵便番号・住所・TEL・FAXのいずれかが更新された場合は再審査状態に変更
                if ($a_find_hotel['postal_cd'] != $a_hotel['postal_cd']) {
                    $b_is_rejudge = true;
                }

                if ($a_find_hotel['address'] !== $a_hotel['address']) {
                    $b_is_rejudge = true;
                }

                if ($a_find_hotel['tel'] !== $a_hotel['tel']) {
                    $b_is_rejudge = true;
                }

                if (($a_find_hotel['fax'] ?? '') !== $a_hotel['fax']) {
                    $b_is_rejudge = true;
                }

                // 再審査が必要な場合は状態を更新
                if ($b_is_rejudge) {
                    // バリデート
                    $a_attributes = [];
                    $a_attributes['hotel_cd'] = $targetCd;
                    $a_attributes['active_status'] = $a_find_hotel_status_jr['active_status'];
                    $a_attributes['judge_status'] = $a_find_hotel_status_jr['judge_status'];
                    $a_attributes['last_modify_dtm'] = now();

                    $errorList = [];
                    $errorList = $o_hotel_status_jr->validation($a_attributes);

                    if (count($errorList) > 0) {
                        return $this->edit($request, ['target_cd' => $targetCd])->with(['errors' => $errorList]);
                    }

                    // 更新
                    $hotel_status_jr_update = $o_hotel_status_jr->where([
                        'hotel_cd' => $targetCd
                    ])->update([
                        'last_modify_dtm' => now(),
                        'modify_cd' => 'action_cd', // TODO $this->box->info->env->action_cd
                        'modify_ts' => now(),
                    ]);

                    if (!$hotel_status_jr_update) {
                        // ロールバック
                        DB::rollback();

                        // 入力画面へ
                        return $this->edit($request, ['target_cd' => $targetCd])->with(['errors' => '更新に失敗しました。']);
                    }
                }
            }

            // コミット
            DB::commit();

            // 登録完了後に登録内容の取得
            // 特定施設のリンクの取得
            $a_hotel_links = DB::table('hotel_link')->where('type', 1)->where('hotel_cd', $targetCd)->get(); //   type ウェブサイトタイプ  1:施設トップページ 2:携帯トップページ 3:その他ページ

            // 施設情報取得
            $a_hotel = Hotel::find($targetCd);

            // 都道府県、市の取得            
            $a_pref = MastPref::where('pref_id', $a_hotel['pref_id'])->first();
            $a_city = MastCity::where('city_id', $a_hotel['city_id'])->first();


            // 施設アカウント情報
            $a_hotel_account = HotelAccount::find($targetCd);

            // 施設状況情報
            $a_hotel_status = HotelStatus::find($targetCd);

            // 施設通知取得
            $a_hotel_notify = HotelNotify::find($targetCd);

            // メッセージの取得
            $s_notify_message = $this->makeNotifyMessages($a_hotel_notify);

            return view('ctl.htlHotel.update', [
                'target_cd'         => $targetCd,
                'hotel'             => $a_hotel,
                'pref'              => $a_pref,
                'city'              => $a_city,
                'notify_message'    => $s_notify_message,
                'hotel_account'     => $a_hotel_account,
                'hotel_status'      => $a_hotel_status,
                'hotel_notify'      => $a_hotel_notify,
                'hotel_links'       => $a_hotel_links,
                'guides'            => ['以下の内容で登録しました。']
            ]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // HACK: 以下の getHotelXxx 系のメソッド、定義場所要検討（工数次第か）
    // Zend の Model から Laravel の Model に移すのは、役割上適切ではないと判断したため、 Controller に実装している。
    // Controller が Fat になるため、 Service 層を導入するのがよいか

    private function getHotelLinks($targetCd)
    {
        return HotelLink::where('hotel_cd', $targetCd)->get();
    }
    private function getHotelInformCancels($targetCd)
    {
        return HotelInform::where('hotel_cd', $targetCd)
            ->where('inform_type', 0) // HACK: magic number
            ->orderBy('order_no', 'asc')
            ->get();
    }
    private function getHotelInformFrees($targetCd)
    {
        return HotelInform::where('hotel_cd', $targetCd)
            ->where('inform_type', 1) // HACK: magic number
            ->orderBy('order_no', 'asc')
            ->get();
    }
    private function getHotelCards($targetCd)
    {
        $sql = <<<SQL
            select
                q1.hotel_cd,
                mast_card.card_id,
                mast_card.card_type,
                mast_card.card_nm
            from
                mast_card
                inner join (
                    select
                        hotel_card.hotel_cd,
                        hotel_card.card_id
                    from
                        hotel_card
                    where
                        hotel_card.hotel_cd = :hotel_cd
                ) q1
                    on mast_card.card_id = q1.card_id
            order by
                mast_card.card_type,
                mast_card.card_id
        SQL;
        return collect(DB::select($sql, ['hotel_cd' => $targetCd]));
    }
    private function getHotelAmenities($targetCd)
    {
        $sql = <<<SQL
            select
                q2.element_id,
                q2.element_value_id,
                q2.element_nm,
                mast_hotel_element_value.element_value_text
            from
                mast_hotel_element_value
                inner join (
                    select
                        mast_hotel_element.element_nm,
                        mast_hotel_element.order_no,
                        q1.element_id,
                        q1.element_value_id
                    from
                        mast_hotel_element
                        inner join (
                            select
                                hotel_amenity.element_id,
                                hotel_amenity.element_value_id
                            from
                                hotel_amenity
                            where
                                hotel_amenity.hotel_cd = :hotel_cd
                                and hotel_amenity.element_value_id != 0
                        ) q1
                            on mast_hotel_element.element_id = q1.element_id
                ) q2
                    on mast_hotel_element_value.element_id = q2.element_id
                    and mast_hotel_element_value.element_value_id = q2.element_value_id
            order by
                q2.order_no
        SQL;

        return collect(DB::select($sql, ['hotel_cd' => $targetCd]));
    }
    private function getHotelServices($targetCd)
    {
        $sql = <<<SQL
            select
                q2.element_id,
                q2.element_value_id,
                q2.element_nm,
                mast_hotel_element_value.element_value_text
            from
                mast_hotel_element_value
                inner join (
                    select
                        mast_hotel_element.element_nm,
                        mast_hotel_element.order_no,
                        q1.element_id,
                        q1.element_value_id
                    from
                        mast_hotel_element
                        inner join (
                            select
                                hotel_service.element_id,
                                hotel_service.element_value_id
                            from
                                hotel_service
                            where
                                hotel_service.hotel_cd = :hotel_cd
                                and hotel_service.element_value_id != 0
                        ) q1
                            on mast_hotel_element.element_id = q1.element_id
                ) q2
                    on mast_hotel_element_value.element_id = q2.element_id
                    and mast_hotel_element_value.element_value_id = q2.element_value_id
            order by
                q2.order_no
        SQL;
        return collect(DB::select($sql, ['hotel_cd' => $targetCd]));
    }
    private function getHotelNearbies($targetCd)
    {
        $sql = <<<SQL
            select
                q2.element_id,
                q2.element_value_id,
                q2.element_nm,
                mast_hotel_element_value.element_value_text
            from
                mast_hotel_element_value
                inner join (
                    select
                        mast_hotel_element.element_nm,
                        mast_hotel_element.order_no,
                        q1.element_id,
                        q1.element_value_id
                    from
                        mast_hotel_element
                        inner join (
                            select
                                hotel_nearby.element_id,
                                hotel_nearby.element_value_id
                            from
                                hotel_nearby
                            where
                                hotel_nearby.hotel_cd = :hotel_cd
                                and hotel_nearby.element_value_id != 0
                        ) q1
                            on mast_hotel_element.element_id = q1.element_id
                ) q2
                    on mast_hotel_element_value.element_id = q2.element_id
                    and mast_hotel_element_value.element_value_id = q2.element_value_id
            order by
                q2.order_no
        SQL;

        return collect(DB::select($sql, ['hotel_cd' => $targetCd]));
    }
    private function getHotelFacilities($targetCd)
    {
        $sql = <<<SQL
            select
                q2.element_id,
                q2.element_value_id,
                q2.element_nm,
                mast_hotel_element_value.element_value_text
            from
                mast_hotel_element_value
                inner join (
                    select
                        mast_hotel_element.element_nm,
                        mast_hotel_element.order_no,
                        q1.element_id,
                        q1.element_value_id
                    from
                        mast_hotel_element
                        inner join (
                            select
                                hotel_facility.element_id,
                                hotel_facility.element_value_id
                            from
                                hotel_facility
                            where
                                hotel_facility.hotel_cd = :hotel_cd
                                and hotel_facility.element_value_id != 0
                        ) q1
                            on mast_hotel_element.element_id = q1.element_id
                ) q2
                    on mast_hotel_element_value.element_id = q2.element_id
                    and mast_hotel_element_value.element_value_id = q2.element_value_id
            order by
                q2.order_no
        SQL;

        return collect(DB::select($sql, ['hotel_cd' => $targetCd]));
    }
    private function getHotelFacilityRooms($targetCd)
    {
        $sql = <<<SQL
            select
                q2.element_id,
                q2.element_value_id,
                q2.element_nm,
                mast_hotel_element_value.element_value_text
            from
                mast_hotel_element_value
                inner join (
                    select
                        mast_hotel_element.element_nm,
                        mast_hotel_element.order_no,
                        q1.element_id,
                        q1.element_value_id
                    from
                        mast_hotel_element
                        inner join (
                            select
                                hotel_facility_room.element_id,
                                hotel_facility_room.element_value_id
                            from
                                hotel_facility_room
                            where
                                hotel_facility_room.hotel_cd = :hotel_cd
                                and hotel_facility_room.element_value_id != 0
                        ) q1
                        on mast_hotel_element.element_id = q1.element_id
                ) q2
                    on mast_hotel_element_value.element_id = q2.element_id
                    and mast_hotel_element_value.element_value_id = q2.element_value_id
            order by
                q2.order_no
        SQL;

        return collect(DB::select($sql, ['hotel_cd' => $targetCd]));
    }
    private function getHotelCancelRates($targetCd)
    {
        return HotelCancelRate::where('hotel_cd', $targetCd)->orderBy('days', 'asc')->get();
        $sql = <<<SQL
            select
                hotel_cd,
                days,
                cancel_rate,
                policy_status
            from
                hotel_cancel_rate
            where
                hotel_cd = :hotel_cd
            order by days asc
        SQL;
        return collect(DB::select($sql, ['hotel_cd' => $targetCd]));
    }
    /**
     * Undocumented function
     *
     * HACK: 定義場所要検討
     *
     * @param [type] $a_hotel_amenities
     * @return array
     */
    private function getAmenities($a_hotel_amenities)
    {
        // MEMO: 要は重複を排除して、表示数を3個に制限するものと思われる。
        // HACK: （工数次第）表示に関するロジックは view に任せたい気もする。

        $a_amenity = [];
        $old = "";
        $amenity_count = 0;

        if (is_countable($a_hotel_amenities)) {
            foreach ($a_hotel_amenities as $key => $value) {
                if ($amenity_count == 3) {
                    break;
                }
                if ($value->element_value_text != 'なし') {
                    if ($value->element_id != $old) {
                        $a_amenity[$key]['element_nm'] = $value->element_nm;
                        $amenity_count++;
                    }
                    $old = $value->element_id;
                }
            }
        }

        return $a_amenity;
    }
    private function getServices($a_hotel_services)
    {
        // MEMO: 要は重複を排除して、表示数を3個に制限するものと思われる。
        // HACK: （工数次第）表示に関するロジックは view に任せたい気もする。

        $a_service = [];
        $old = "";
        $service_count = 0;
        if (is_countable($a_hotel_services)) {
            foreach ($a_hotel_services as $key => $value) {
                if ($service_count == 3) {
                    break;
                }
                if ($value->element_value_text != 'なし') {
                    if ($value->element_id != $old) {
                        $a_service[$key]['element_nm'] = $value->element_nm;
                        $service_count++;
                    }
                    $old = $value->element_id;
                }
            }
        }
        return $a_service;
    }
    private function getNearbies($a_hotel_nearbies)
    {

        $a_nearby = [];
        $old = "";
        $nearby_count = 0;
        if (is_countable($a_hotel_nearbies)) {
            foreach ($a_hotel_nearbies as $key => $value) {
                if ($nearby_count == 3) {
                    break;
                }
                if ($value->element_value_text != 'なし') {
                    if ($value->element_id != $old) {
                        $a_nearby[$key]['element_nm'] = $value->element_nm;
                        $nearby_count++;
                    }
                    $old = $value->element_id;
                }
            }
        }
        return $a_nearby;
    }
    private function getFacilities($a_hotel_facilities)
    {

        $a_facility = [];
        $old = "";
        $facility_count = 0;
        if (is_countable($a_hotel_facilities)) {
            foreach ($a_hotel_facilities as $key => $value) {
                if ($facility_count == 3) {
                    break;
                }
                if ($value->element_value_text != 'なし') {
                    if ($value->element_id != $old) {
                        $a_facility[$key]['element_nm'] = $value->element_nm;
                        $facility_count++;
                    }
                    $old = $value->element_id;
                }
            }
        }
        return $a_facility;
    }
    private function getFacilityRooms($a_hotel_facility_rooms)
    {

        $a_facility_room = [];
        $old = "";
        $facility_room_count = 0;
        if (is_countable($a_hotel_facility_rooms)) {
            foreach ($a_hotel_facility_rooms as $key => $value) {
                if ($facility_room_count == 3) {
                    break;
                }
                if ($value->element_value_text != 'なし') {
                    if ($value->element_id != $old) {
                        $a_facility_room[$key]['element_nm'] = $value->element_nm;
                        $facility_room_count++;
                    }
                    $old = $value->element_id;
                }
            }
        }
        return $a_facility_room;
    }
    private function getHotelStation($targetCd, $an_count = null, $aa_conditions = [], $aa_priority = [])
    {

        $parameters = [];

        $s_pri_route_id     = ['select' => '', 'order' => ''];
        $s_pri_route_nm     = ['select' => '', 'order' => ''];
        $s_pri_station_id   = ['select' => '', 'order' => ''];
        $s_pri_station_nm   = ['select' => '', 'order' => ''];
        $s_pri_station_nms  = ['select' => '', 'order' => ''];
        $s_station_nm = '';

        if (array_key_exists('station_nm', $aa_conditions) && !empty($aa_conditions['station_nm'])) {
            $s_station_nm = 'and mast_stations.station_nm = :station_nm';
            $parameters['station_nm'] = $aa_conditions['station_nm'];
        }

        // 優先順位を設定
        if (!(empty($aa_priority['route_id']))) {
            $s_pri_route_id['select'] = 'decode(q3.route_id, :route_id, 0, 1) as order_route_id,';
            $s_pri_route_id['order'] = 'order_route_id,';
            $parameters['route_id'] = $aa_priority['route_id'];
        }

        if (!(empty($aa_priority['route_nm']))) {
            $s_pri_route_nm['select'] = 'decode(q3.route_nm, :route_nm, 0, 1) as order_route_nm,';
            $s_pri_route_nm['order'] = 'order_route_nm,';
            $parameters['route_nm'] = $aa_priority['route_nm'];
        }

        if (!(empty($aa_priority['station_id']))) {
            $s_pri_station_id['select'] = 'decode(mast_stations.station_id, :station_id, 0, 1) as order_station_id,';
            $s_pri_station_id['order'] = 'order_station_id,';
            $parameters['station_id'] = $aa_priority['station_id'];
        }

        if (!(empty($aa_priority['station_nm']))) {
            $s_pri_station_nm['select'] = 'decode(mast_stations.station_nm, :station_nm, 0, 1) as order_station_nm,';
            $s_pri_station_nm['order'] = 'order_station_nm,';
            $parameters['station_nm'] = $aa_priority['station_nm'];
        }

        if (!(empty($aa_priority['station_nms']))) {
            foreach ($aa_priority['station_nms'] as $key => $value) {
                $s_pri_station_nms['select'] .= 'decode(mast_stations.station_nm, :station_nms' . $key . ', 0, 1) as order_station_nms' . $key . ',';
                $s_pri_station_nms['order']  .= 'order_station_nms' . $key . ',';
                $parameters['station_nms' . $key] = $value;
            }
        }

        $sql = <<<SQL
            select
                {$s_pri_route_id['select']}
                {$s_pri_route_nm['select']}
                {$s_pri_station_id['select']}
                {$s_pri_station_nm['select']}
                {$s_pri_station_nms['select']}
                mast_stations.station_id,
                q3.route_id,
                case
                    when substr(q3.railway_nm, 1, 2) = 'ＪＲ' then 'ＪＲ' || q3.route_nm
                    when substr(q3.route_nm, 1, 2) = 'ＪＲ' then 'ＪＲ' || q3.route_nm
                    else q3.route_nm
                end as route_nm,
                q3.railway_nm,
                q3.traffic_way,
                q3.order_no,
                q3.minute,
                mast_stations.station_nm,
                mast_stations.pref_id,
                mast_stations.wgs_lat_d,
                mast_stations.wgs_lng_d
            from
                mast_stations
                inner join (
                    select
                        q2.station_id,
                        mast_routes.route_id,
                        mast_routes.route_nm,
                        mast_routes.railway_nm,
                        q2.traffic_way,
                        q2.order_no,
                        q2.minute
                    from
                        mast_routes
                        inner join (
                            select
                                q1.station_id,
                                q1.traffic_way,
                                q1.order_no,
                                q1.minute,
                                mast_stations.route_id
                            from
                                mast_stations
                                inner join (
                                    select
                                        hotel_stations.station_id,
                                        hotel_stations.hotel_cd,
                                        hotel_stations.traffic_way,
                                        hotel_stations.order_no,
                                        hotel_stations.minute
                                    from
                                        hotel_stations
                                    where
                                        hotel_stations.hotel_cd = :hotel_cd
                                ) q1
                                    on mast_stations.station_id = q1.station_id
                        ) q2
                            on mast_routes.route_id = q2.route_id
                ) q3
                    on mast_stations.station_id = q3.station_id
            where 1 = 1
                {$s_station_nm}
            order by
                {$s_pri_route_id['order']}
                {$s_pri_route_nm['order']}
                {$s_pri_station_id['order']}
                {$s_pri_station_nm['order']}
                {$s_pri_station_nms['order']}
                q3.order_no,
                q3.traffic_way,
                q3.minute
        SQL;

        $parameters['hotel_cd'] = $targetCd;
        return collect(DB::select($sql, $parameters));
    }

    // 予約の通知欄のメーセージ作成
    //   find で取得したhotel_notifyの配列
    private function makeNotifyMessages($a_hotel_notify)
    {
        try {
            // メールアドレス 復号
            $cipher = new Models_Cipher(config('settings.cipher_key'));
            $decrypt_email = $cipher->decrypt($a_hotel_notify['notify_email']);

            // 初期化
            $s_notify_message = "";

            // ２進数を展開し一致するビットに変換
            $a_notify_device = $this->to_shift($a_hotel_notify['notify_device'], true);
            // 昇順に並び替え（tpl用）
            sort($a_notify_device);

            // 通知ステータスによって,予約通知FAX欄の表示を切り替える。　notify_status　0:通知しない 1:通知する 2:オペレータ連絡
            // 通知ステータスが【通知しない】の場合 
            if ($a_hotel_notify['notify_status'] == 0) {
                $s_notify_message = "非通知となっています。予約者データは管理画面よりご確認下さい。";

                // 通知ステータスが【通知する】の場合
            } elseif ($a_hotel_notify['notify_status'] == 1) {

                // １件だけの時
                if (count($a_notify_device) == 1) {
                    // 通知媒体がオペレータ連絡のみの場合
                    if ($a_notify_device[0] == 4) {
                        // メッセージを返す
                        return "イオンコンパス社スタッフから適宜ご通知いたします。";
                    }
                }

                // 制御フラグ
                $b_device_first = true;

                // 通知媒体に該当するものを表示
                foreach ($a_notify_device as $value) {

                    // 通知媒体によって,予約通知FAX欄の表示を切り替える。　notify_device　 	1:ファックス 2:電子メール 3:ファックス＋電子メール
                    // 「通知媒体」が『ファックス』の場合
                    if ($value == 1) {
                        if ($b_device_first != true) {
                            $s_notify_message .= "と";
                        }
                        $s_notify_message .= "ファックス番号「 " . $a_hotel_notify['notify_fax'] . " 」";
                        $b_device_first = false;

                        // 「通知媒体」が『電子メール』の場合
                    } elseif ($value == 2) {
                        if ($b_device_first != true) {
                            $s_notify_message .= "と";
                        }
                        $s_notify_message .= "電子メールアドレス「 " . $decrypt_email . " 」";
                        $b_device_first = false;
                    }
                }

                // 配列の要素にリンカーンが存在すれば
                if ((in_array(8, $a_notify_device))) {
                    // メッセージを返す
                    $s_notify_message .= "リンカーン経由にてご通知いたします。";

                    // メッセージが空じゃなければ文言を追加
                } elseif (!empty($s_notify_message)) {

                    $s_notify_message .= "にご通知いたします。";
                }
            }

            return $s_notify_message;

            //各メソッドでExceptionが投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // ２進数を展開し一致するビットもしくは位に変換します。
    //
    //  as_value 数字を設定
    //  ab_bits  true ビットで返却 false 位で返却
    //
    //  example
    //    bits = true
    //      > 30
    //        >> array(2, 4, 8, 16)
    //    bits = false
    //      > 30
    //        >> array(1, 2, 3, 4)
    public function to_shift($as_value, $ab_bits = true)
    {
        try {
            if ($as_value <= 0) {
                return null;
            }

            $buf_value = 1;

            $n_cnt = 0;
            while (
                $buf_value <= $as_value
            ) {
                $buf_value <<= 1;
                $bits[] = [$buf_value / 2, $n_cnt];    // ビットと位を保持
                $n_cnt++;
            }
            // ビットで逆順に並び替え
            rsort($bits);

            // 一致するビットと位を取得
            for ($n_cnt = 0; $n_cnt < count($bits); $n_cnt++) {
                if ($bits[$n_cnt][0] <= $as_value) {
                    $a_bits[] = $bits[$n_cnt][0];
                    $a_position[] = $bits[$n_cnt][1];
                    $as_value = $as_value - $bits[$n_cnt][0];
                }
            }

            // ビットを返却
            if ($ab_bits) {
                return $a_bits;

                // 位を返却
            } else {
                return $a_position;
            }

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
}
