<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\Hotel;
use App\Models\MastRoute;
use App\Models\MastStation;
use App\Models\HotelStation;
use App\Models\HotelControl;
use App\Http\Requests\HtlHotelStationRequest;

class HtlHotelStationController extends _commonController
{
    /**
     * 一覧
     */
    public function list(Request $request)
    {
        $target_cd = $request->input('target_cd');
        $a_request_hotel_station = $request->input('HotelStation');

        // バリデーションエラー時はエラーメッセージ取得
        $errors = $request->session()->get('errors', []);

        // 交通アクセスリスト取得
        $a_hotel_stations['values']  = $this->getHotelStation($target_cd, '', ['station_nm' => '']);

        if (isset($a_request_hotel_station)) {
            return view('ctl.htlhotelstation.list', [
                'target_cd' => $target_cd,
                'a_hotel_station' => $a_hotel_stations,
                'station_id' => $a_request_hotel_station['station_id'],
                'traffic_way' => $a_request_hotel_station['traffic_way'],
                'errors'        => $errors
            ]);
        } else {
            return view('ctl.htlhotelstation.list', [
                'target_cd' => $target_cd,
                'a_hotel_station' => $a_hotel_stations,
                'errors'        => $errors
            ]);
        }
    }

    /**
     *　登録画面
     *
     * @return \Illuminate\Http\Response
     */
    public function new(Request $request)
    {
        //リクエスト情報
        $target_cd = $request->input('target_cd');
        $a_request_hotel_station = $request->input('HotelStation');

        // バリデーションエラー時はエラーメッセージ取得
        $errors = $request->session()->get('errors', []);

        $hotel = new Hotel();
        $a_hotel = $hotel->where(['hotel_cd' => $target_cd])->first();
        $a_hotel_stations['minute']  = '';
        $a_hotel_stations['traffic_way']  = '';

        // 路線が選択されていない場合、路線一覧を取得
        if (!isset($a_request_hotel_station['route_id'])) {
            $a_mast_routes = $this->getMastRoutes(['pref_id' => $a_hotel['pref_id']]);
            $errors = ['路線を選択してください。'];
            $judge_status = 1;

            // 駅が選択されていない場合、駅一覧を取得
        } elseif (!isset($a_request_hotel_station['station_id'])) {
            // 路線情報を取得
            $a_mast_routes = $this->getMastRoutes(['pref_id' => $a_hotel['pref_id']]);
            $mast_routes_model = new  MastRoute();
            $a_mast_route = $mast_routes_model->where(['route_id' => $a_request_hotel_station['route_id']])->first();
            $a_mast_stations = $this->getMastStations(
                [
                    'pref_id'  => $a_hotel['pref_id'],
                    'route_id' => $a_request_hotel_station['route_id']
                ]
            );

            if ($a_request_hotel_station['route_id'] != 'B2001') {
                $errors = ['駅を選択してください。'];
            }
            $judge_status = 2;

            // 路線、駅が選択された場合に交通情報を入力
        } elseif (isset($a_request_hotel_station['stationbtn']) && $a_request_hotel_station['stationbtn'] != "") {
            // 路線情報を取得
            $a_mast_routes = $this->getMastRoutes(['pref_id' => $a_hotel['pref_id']]);
            $mast_routes_model = new  MastRoute();
            $a_mast_route = $mast_routes_model->where(['route_id' => $a_request_hotel_station['route_id']])->first();
            $a_mast_stations = $this->getMastStations(
                [
                    'pref_id'  => $a_hotel['pref_id'],
                    'route_id' => $a_request_hotel_station['route_id']
                ]
            );
            // 駅情報を取得
            $mast_stations_model = new  MastStation();
            $a_mast_station = $mast_stations_model->where(['station_id' => $a_request_hotel_station['station_id']])->first();
            $judge_status = 3;
        } else {
            // 路線情報を取得
            $a_mast_routes = $this->getMastRoutes(['pref_id' => $a_hotel['pref_id']]);
            $mast_routes_model = new  MastRoute();
            $a_mast_route = $mast_routes_model->where(['route_id' => $a_request_hotel_station['route_id']])->first();
            $a_mast_stations = $this->getMastStations(
                [
                    'pref_id'  => $a_hotel['pref_id'],
                    'route_id' => $a_request_hotel_station['route_id']
                ]
            );
            $judge_status = 4;
        }
        if ($judge_status == 1) {
            return view('ctl.htlhotelstation.new', [
                'target_cd' => $target_cd,
                'a_mast_routes' => $a_mast_routes,      // 路線一覧
                'a_hotel_station' => $a_hotel_stations,
                'errors'        => $errors
            ]);
        } elseif ($judge_status == 2) {
            return view('ctl.htlhotelstation.new', [
                'target_cd' => $target_cd,
                'a_mast_routes' => $a_mast_routes,      // 路線一覧
                'a_mast_route' => $a_mast_route,        // 路線情報
                'a_mast_stations' => $a_mast_stations,  // 駅一覧
                'a_hotel_station' => $a_hotel_stations,
                'errors'        => $errors
            ]);
        } elseif ($judge_status == 3) {
            return view('ctl.htlhotelstation.new', [
                'target_cd' => $target_cd,
                'a_mast_routes' => $a_mast_routes,
                'a_mast_route' => $a_mast_route,
                'a_mast_station' => $a_mast_station,
                'a_mast_stations' => $a_mast_stations,   // 駅一覧
                'stationbtn' => $a_request_hotel_station['stationbtn'], // 駅指定ボタンクリック
                'a_hotel_station' => $a_hotel_stations,
                'errors'        => $errors
            ]);
        } elseif ($judge_status == 4) {
            return view('ctl.htlhotelstation.new', [
                'target_cd' => $target_cd,
                'a_mast_routes' => $a_mast_routes,      // 路線一覧
                'a_mast_route' => $a_mast_route,        // 路線情報
                'a_mast_stations' => $a_mast_stations,  // 駅一覧
                'a_hotel_station' => $a_hotel_stations,
                'errors'        => $errors
            ]);
        }
    }

    /**
     *新規登録処理
     *
     * @param HtlHotelStationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(HtlHotelStationRequest $request)
    {
        $target_cd = $request->input('target_cd');
        $a_request_hotel_station = $request->input('HotelStation');
        $actionCd = $this->getActionCd();

        $hotel_stations_model = new HotelStation();

        // 登録済みのレコードがないか確認し、登録済の場合はエラー
        $Hotel_Station_value = $hotel_stations_model->where(
            [
                'hotel_cd'      =>  $target_cd,
                'station_id'    => $a_request_hotel_station['station_id'],
                'traffic_way'   => $a_request_hotel_station['traffic_way']
            ]
        )->first();

        if (!empty($Hotel_Station_value)) {
            $errors = ['登録された内容はすでに存在しています。'];
            $a_hotel_stations['values']  = $this->getHotelStation($target_cd, '', ['station_nm' => '']);    // 交通アクセスリスト取得
            return view('ctl.htlhotelstation.list', [
                'target_cd'       => $target_cd,
                'a_hotel_station' => $a_hotel_stations,
                'station_id'      => $a_request_hotel_station['station_id'],
                'traffic_way'     => $a_request_hotel_station['traffic_way'],
                'errors'          => $errors
            ]);
        }

        // 登録可能な場合、order_noを生成
        $now_order_no = $hotel_stations_model->select('order_no')->where('hotel_cd', $target_cd)->orderBy('order_no', 'desc')->first();
        if ($now_order_no == null) {
            $n_order_no = 0;
        } else {
            $n_order_no = $now_order_no->order_no + 1;
        }

        // 登録データ設定
        $a_attributes['hotel_cd'] = $target_cd;
        $a_attributes['entry_cd'] = $actionCd;
        $a_attributes['entry_ts'] = now();
        $a_attributes['modify_cd'] = $actionCd;
        $a_attributes['modify_ts'] = now();

        try {
            // トランザクション開始
            DB::beginTransaction();

            $hotel_stations_create = $hotel_stations_model->create([
                'hotel_cd'         => $target_cd,                                   // ホテルコード
                'station_id'       => $a_request_hotel_station['station_id'],       // 駅ID
                'traffic_way'      => $a_request_hotel_station['traffic_way'],      // 交通手段
                'order_no'         => $n_order_no,                                  // 表示順
                'minute'           => $a_request_hotel_station['minute'],           // 分
                'entry_cd'         => $a_attributes['entry_cd'],
                'entry_ts'         => $a_attributes['entry_ts'],
                'modify_cd'        => $a_attributes['modify_cd'],
                'modify_ts'        => $a_attributes['modify_ts']
            ]);

            if (is_null($hotel_stations_create)) {
                // ロールバック
                DB::rollback();
                // エラーメッセージ
                return $this->new($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => ['ご希望の交通アクセスデータを登録できませんでした。']]);
            }

            // 施設情報ページの更新依頼
            $hotel_stations_model->hotelModify($a_attributes);

            // コミット
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        // 交通アクセスリスト取得
        $a_hotel_stations['values']  = $this->getHotelStation($target_cd, '', ['station_nm' => '']);    // 交通アクセスリスト取得

        if (isset($a_request_hotel_station)) {
            return view('ctl.htlhotelstation.list', [
                'target_cd'       => $target_cd,
                'a_hotel_station' => $a_hotel_stations,
                'station_id'      => $a_request_hotel_station['station_id'],
                'traffic_way'     => $a_request_hotel_station['traffic_way'],
                'guides'          => ['登録完了しました。']

            ]);
        } else {
            return view('ctl.htlhotelstation.list', [
                'target_cd'       => $target_cd,
                'a_hotel_station' => $a_hotel_stations,
                'guides'          => ['登録完了しました。']
            ]);
        }
    }

    /**
     * 更新画面
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        // ターゲットコード
        $target_cd = $request->input('target_cd');
        $a_request_hotel_station = $request->input('HotelStation');
        // 路線情報を取得
        $mast_routes_model = new mastRoute();
        $a_mast_route = $mast_routes_model->where(['route_id' => $a_request_hotel_station['route_id']])->first();

        // 駅情報を取得
        $mast_stations_model = new mastStation();
        $a_mast_station = $mast_stations_model->where(['station_id' => $a_request_hotel_station['station_id']])->first();

        // 施設の最寄駅を取得
        $hotel_stations_model = new HotelStation();
        $a_hotel_station = $hotel_stations_model->where(
            [
                'hotel_cd' => $target_cd,
                'station_id'  =>  $a_request_hotel_station['station_id'],
                'traffic_way' =>  $a_request_hotel_station['traffic_way']
            ]
        )->first();

        return view('ctl.htlhotelstation.edit', [
            'target_cd'       => $target_cd,
            'a_mast_route'    => $a_mast_route,
            'a_mast_station'  => $a_mast_station,
            'a_hotel_station' => $a_hotel_station
        ]);
    }

    /**
     * 更新処理
     *
     * @param HtlHotelStationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(HtlHotelStationRequest $request)
    {
        // リクエストパラメータの取得
        $target_cd = $request->input('target_cd');
        $a_request_hotel_station = $request->input('HotelStation');
        $actionCd = $this->getActionCd();

        $hotel_stations_model = new HotelStation();

        // 交通手段を変更する場合、登録済のレコードがないか確認し、登録済みの場合はエラー
        if ($a_request_hotel_station['traffic_way'] != $a_request_hotel_station['old_traffic_way']) {
            $Hotel_Station_value = $hotel_stations_model->where(
                [
                    'hotel_cd'      =>  $target_cd,
                    'station_id'    => $a_request_hotel_station['station_id'],
                    'traffic_way'   => $a_request_hotel_station['traffic_way']
                ]
            )->exists();
            if ($Hotel_Station_value) {
                $errors = ['登録された内容はすでに存在しています。'];
                $a_hotel_stations['values']  = $this->getHotelStation($target_cd, '', ['station_nm' => '']);  // 交通アクセスリスト取得

                return view('ctl.htlhotelstation.list', [
                    'target_cd'       => $target_cd,
                    'a_hotel_station' => $a_hotel_stations,
                    'station_id'      => $a_request_hotel_station['station_id'],
                    'traffic_way'     => $a_request_hotel_station['traffic_way'],
                    'errors'          => $errors
                ]);
            }
        }

        // エラーではない場合は、登録データを設定
        $a_attributes['hotel_cd'] = $target_cd;
        $a_attributes['entry_cd'] = $actionCd;
        $a_attributes['entry_ts'] = now();
        $a_attributes['modify_cd'] = $actionCd;
        $a_attributes['modify_ts'] = now();

        try {
            // トランザクション開始
            DB::beginTransaction();

            $hotel_stations_update = $hotel_stations_model->where([
                'hotel_cd'    => $target_cd,
                'station_id'  => $a_request_hotel_station['station_id'],
                'traffic_way' => $a_request_hotel_station['old_traffic_way']
            ])->update([
                'traffic_way' => $a_request_hotel_station['traffic_way'],
                'minute'      => $a_request_hotel_station['minute'],
                'modify_cd'   => $a_attributes['modify_cd'],
                'modify_ts'   => $a_attributes['modify_ts']
            ]);

            if ($hotel_stations_update == 0) {
                // ロールバック
                DB::rollback();
                // エラーメッセージ
                return $this->list($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => ['ご希望の交通アクセスデータを更新できませんでした。']]);
            }

            // 施設情報ページの更新依頼
            $hotel_stations_model->hotelModify($a_attributes);

            // コミット
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        // 交通アクセスリスト取得
        $a_hotel_stations['values']  = $this->getHotelStation($target_cd, '', ['station_nm' => '']);

        // list アクションに転送します
        return view('ctl.htlhotelstation.list', [
            'target_cd'       => $target_cd,
            'a_hotel_station' => $a_hotel_stations,
            'station_id'      => $a_request_hotel_station['station_id'],
            'traffic_way'     => $a_request_hotel_station['traffic_way'],
            'guides'          => ['更新完了しました。']
        ]);
    }

    /**
     * 削除処理
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // リクエストパラメータの取得
        $target_cd = $request->input('target_cd');
        $a_request_hotel_station = $request->input('HotelStation');
        $actionCd = $this->getActionCd();

        try {
            // トランザクション開始
            DB::beginTransaction();

            if (!$this->deleteStations($a_request_hotel_station['station_id'], $a_request_hotel_station['traffic_way'], $target_cd)) {
                // エラーメッセージ
                DB::rollback();
                // エラーメッセージ
                $errors = ['ご希望の交通アクセスデータを削除できませんでした。'];
                $a_hotel_stations['values']  = $this->getHotelStation($target_cd, '', ['station_nm' => '']);    // 交通アクセスリスト取得

                return view('ctl.htlhotelstation.list', [
                    'target_cd'       => $target_cd,
                    'a_hotel_station' => $a_hotel_stations,
                    'station_id'      => $a_request_hotel_station['station_id'],
                    'traffic_way'     => $a_request_hotel_station['traffic_way'],
                    'errors'          => $errors
                ]);
            }

            // 施設情報ページの更新依頼
            $hotel_stations_model = new HotelStation();
            $a_attributes['hotel_cd'] = $target_cd;
            $a_attributes['entry_cd'] = $actionCd;
            $a_attributes['entry_ts'] = now();
            $a_attributes['modify_cd'] = $actionCd;
            $a_attributes['modify_ts'] = now();

            $hotel_stations_model->hotelModify($a_attributes);

            // コミット
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        // 交通アクセスリスト取得
        $a_hotel_stations['values']  = $this->getHotelStation($target_cd, '', ['station_nm' => '']);

        return view('ctl.htlhotelstation.list', [
            'target_cd'       => $target_cd,
            'a_hotel_station' => $a_hotel_stations,
            'a_hotel_link'    => $a_request_hotel_station,
            'guides'          => ['削除が完了しました。']
        ]);
    }

    /**
     * 並び替え処理
     *
     * @return \Illuminate\Http\Response
     */
    public function move(Request $request)
    {
        // リクエストパラメータの取得
        $target_cd = $request->input('target_cd');
        $a_request_hotel_station = $request->input('HotelStation');
        $top_button = $request->input('top');
        $bottom_button = $request->input('bottom');
        $up_button = $request->input('up');
        $down_button = $request->input('down');
        $actionCd = $this->getActionCd();

        try {
            // トランザクション開始
            DB::beginTransaction();

            //「先頭へ」
            if (!empty($top_button)) {
                $this->moveStations('top', $a_request_hotel_station['station_id'], $a_request_hotel_station['traffic_way'], $target_cd);

                //「末尾へ」
            } elseif (!empty($bottom_button)) {
                $this->moveStations('bottom', $a_request_hotel_station['station_id'], $a_request_hotel_station['traffic_way'], $target_cd);

                //「上へ」
            } elseif (!empty($up_button)) {
                $this->moveStations('up', $a_request_hotel_station['station_id'], $a_request_hotel_station['traffic_way'], $target_cd);

                //「下へ」
            } elseif (!empty($down_button)) {
                $this->moveStations('down', $a_request_hotel_station['station_id'], $a_request_hotel_station['traffic_way'], $target_cd);
            }

            // 施設情報ページの更新依頼
            $hotel_stations_model = new HotelStation();
            $a_attributes['hotel_cd'] = $target_cd;
            $a_attributes['entry_cd'] = $actionCd;
            $a_attributes['entry_ts'] = now();
            $a_attributes['modify_cd'] = $actionCd;
            $a_attributes['modify_ts'] = now();

            $hotel_stations_model->hotelModify($a_attributes);

            // コミット
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        // 交通アクセスリスト取得
        $a_hotel_stations['values']  = $this->getHotelStation($target_cd, '', ['station_nm' => '']);

        return view('ctl.htlhotelstation.list', [
            'target_cd'       => $target_cd,
            'a_hotel_station' => $a_hotel_stations,
            'a_hotel_link'    => $a_request_hotel_station,
            'guides'          => ['並び替えが完了致しました。']
        ]);
    }

    /**
     * 並び替え初期化処理
     *
     * @return \Illuminate\Http\Response
     */
    public function defaultorder(Request $request)
    {
        // リクエストパラメータの取得
        $target_cd = $request->input('target_cd');
        $a_request_hotel_station = $request->input('HotelStation');
        $actionCd = $this->getActionCd();

        $hotel_control = new HotelControl();
        $a_hotel_control = $hotel_control->where(['hotel_cd' => $target_cd])->first();
        if ($a_hotel_control['stock_type'] == 2) {
            $errors = ['日本旅行提供の施設は表示順序を変更できません。必要な場合は開発部までお問い合わせください。'];
            // 交通アクセスリスト取得
            $a_hotel_stations['values']  = $this->getHotelStation($target_cd, '', ['station_nm' => '']);
            return view('ctl.htlhotelstation.list', [
                'target_cd'       => $target_cd,
                'a_hotel_station' => $a_hotel_stations,
                'errors'          => $errors
            ]);
        }

        try {
            // トランザクション開始
            DB::beginTransaction();

            // 並び替え初期化
            $this->defaultOrderStations($target_cd);


            // 施設情報ページの更新依頼
            $hotel_stations_model = new HotelStation();
            $a_attributes['hotel_cd'] = $target_cd;
            $a_attributes['entry_cd'] = $actionCd;
            $a_attributes['entry_ts'] = now();
            $a_attributes['modify_cd'] = $actionCd;
            $a_attributes['modify_ts'] = now();

            $hotel_stations_model->hotelModify($a_attributes);

            // コミット
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        // 交通アクセスリスト取得
        $a_hotel_stations['values']  = $this->getHotelStation($target_cd, '', ['station_nm' => '']);

        return view('ctl.htlhotelstation.list', [
            'target_cd'       => $target_cd,
            'a_hotel_station' => $a_hotel_stations,
            'a_hotel_link'    => $a_request_hotel_station,
            'guides'          => ['並び替えが完了致しました。']
        ]);
    }

    /**
     * ホテルに紐づく交通アクセスリストの取得
     *
     * @return array
     */
    private function getHotelStation($targetCd, $an_count = null, $aa_conditions = [], $aa_priority = [])
    {

        $parameters = [];

        $s_pri_route_id     = ['select' => '', 'order' => ''];
        $s_pri_route_nm     = ['select' => '', 'order' => ''];
        $s_pri_station_id   = [
            'select' => '', 'order' => ''
        ];
        $s_pri_station_nm   = ['select' => '', 'order' => ''];
        $s_pri_station_nms  = ['select' => '', 'order' => ''];
        $s_station_nm = '';

        if (array_key_exists('station_nm', $aa_conditions) && !empty($aa_conditions['station_nm'])) {
            $s_station_nm = 'and mast_stations.station_nm = :station_nm';
            $parameters['station_nm'] = $aa_conditions['station_nm'];
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
        return DB::select($sql, $parameters);
    }

    /**
     * 路線マスタを取得
     *
     * @param array{
     *   pref_id: int,
     * } $aa_conditions 検索条件
     * @return array
     */
    public function getMastRoutes($aa_conditions)
    {
        try {
            if (!empty($aa_conditions['pref_id'])) {
                $s_pref_id = '	and	pref_id = :pref_id or pref_id is null';
            }

            $s_sql =
                <<<SQL
					select	distinct
							mast_routes.route_id,
							mast_routes.route_nm,
							mast_routes.railway_nm
					from	mast_routes,
						(
							select	*
							from	mast_stations
							where	null is null
								{$s_pref_id}
						) q1
					where	mast_routes.route_id = q1.route_id
						and	mast_routes.display_status = 1
						and	mast_routes.route_status   <> 2
					order by	mast_routes.route_id
SQL;

            return ['values' => DB::select($s_sql, $aa_conditions)];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 駅マスタを取得
     *
     * @param array{
     *   pref_id: int,
     *   route_id: string,
     * } $aa_conditions 検索条件
     * @return array
     */
    public function getMastStations($aa_conditions)
    {
        try {
            // 都道府県ID、路線IDがどちらもあるとき
            if (!empty($aa_conditions['pref_id']) and !empty($aa_conditions['route_id'])) {
                $s_pref_id = '	and	mast_stations.pref_id = :pref_id';
                $s_route_id = '	and	mast_stations.route_id = :route_id';

                $s_sql =
                    <<<SQL
						select	*
						from	mast_stations
						where	null is null
							and	display_status = 1
							{$s_pref_id}
							{$s_route_id}
						order by order_no,
								station_id
SQL;

                // 都道府県IDのみあるとき
            } elseif (!empty($aa_conditions['pref_id'])) {
                $s_pref_id = '	and	mast_stations.pref_id = :pref_id';

                $s_sql =
                    <<<SQL
						select	*
						from	mast_stations
						where	null is null
							{$s_pref_id}
						order by order_no,
								station_id
SQL;

                // 路線IDのみあるとき
            } elseif (!empty($aa_conditions['route_id'])) {
                $s_route_id = '	route_map.route_id = :route_id';

                $s_sql =
                    <<<SQL
						select	mast_stations.station_id,
								mast_stations.station_nm,
								mast_stations.display_status,
								mast_stations.wgs_lat_d,
								mast_stations.wgs_lng_d,
								mast_stations.station_type,
								mast_stations.pref_id,
								mast_stations.station_group_id,
								mast_stations.route_id,
								mast_stations.entry_cd,
								mast_stations.entry_ts,
								mast_stations.modify_cd,
								mast_stations.modify_ts,
								mast_stations.station_kn,
								mast_stations.station_rn,
								mast_stations.station_status,
								mast_stations.postal_cd,
								mast_stations.address,
								mast_stations.altitube,
								mast_stations.altitube_home,
								mast_stations.open_ymd,
								mast_stations.close_ymd,
								mast_stations.keyword,
								q1.order_no,
								q1.next_station_id
						from	mast_stations,
							(
								select	route_id,
										station_id1 as station_id,
										station_id2 as next_station_id,
										order_no
								from	route_map
								where	{$s_route_id}
							) q1
						where	mast_stations.route_id = q1.route_id
							and	mast_stations.station_id = q1.station_id
						order by q1.order_no
SQL;
            } else {
                $s_sql =
                    <<<SQL
						select	*
						from	mast_stations
						where	null is null
							and	display_status = 1
						order by order_no,
								station_id
SQL;
            }


            // データの取得
            return ['values' => DB::select($s_sql, $aa_conditions)];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 最寄り駅削除処理
     *
     * @param string $an_station_id  最寄駅コード
     * @param string $an_traffic_way 交通手段
     * @param string $target_cd      施設コード
     * @return int
     */
    public function deleteStations($an_station_id, $an_traffic_way, $target_cd)
    {
        // 削除対象を末尾に移動させる
        $this->moveStations('bottom', $an_station_id, $an_traffic_way, $target_cd);

        $hotel_stations_model = new HotelStation();

        //ホテルコードに絡むデータ全削除
        return $hotel_stations_model->where(
            [
                'hotel_cd'    => $target_cd,
                'station_id'  => $an_station_id,
                'traffic_way' => $an_traffic_way
            ]
        )->delete();
    }

    /**
     * 施設最寄駅の表示順変更
     *
     * @param string $as_action      top：先頭 bottom： 末尾 up：上 down：下
     * @param string $an_station_id  最寄駅コード
     * @param string $an_traffic_way 交通手段
     * @param string $target_cd      施設コード
     * @return array
     */
    public function moveStations($as_action, $an_station_id, $an_traffic_way, $target_cd)
    {
        // 交通アクセスリスト取得
        $a_stations  = $this->getHotelStation($target_cd, '', ['station_nm' => '']);
        $actionCd = $this->getActionCd();

        // 表示順の調整
        $n_move = 1;

        for ($n_cnt = 0; $n_cnt < count($a_stations); $n_cnt++) {
            if ($as_action == 'top') {
                if ($n_cnt == 0) {
                    $n_move++;
                }
                if (
                    $a_stations[$n_cnt]->station_id == $an_station_id
                    && $a_stations[$n_cnt]->traffic_way == $an_traffic_way
                ) {
                    $a_stations[$n_cnt]->move_order_no = null;
                    $a_stations[$n_cnt]->move_order_no = 1;
                } else {
                    $a_stations[$n_cnt]->move_order_no = null;
                    $a_stations[$n_cnt]->move_order_no = $n_move++;
                }
            } elseif ($as_action == 'bottom') {
                if (
                    $a_stations[$n_cnt]->station_id == $an_station_id
                    && $a_stations[$n_cnt]->traffic_way == $an_traffic_way
                ) {
                    $a_stations[$n_cnt]->move_order_no = null;
                    $a_stations[$n_cnt]->move_order_no = count($a_stations);
                } else {
                    $a_stations[$n_cnt]->move_order_no = null;
                    $a_stations[$n_cnt]->move_order_no = $n_move++;
                }
            } elseif ($as_action == 'up') {
                if (
                    $a_stations[$n_cnt]->station_id == $an_station_id
                    && $a_stations[$n_cnt]->traffic_way == $an_traffic_way
                ) {
                    // 更新対象駅が先頭の場合は、変更しない
                    if ($n_cnt == 0) {
                        return true;
                    }
                    $a_stations[$n_cnt]->move_order_no = null;
                    $a_stations[$n_cnt]->move_order_no = $n_move - 1;
                    $a_stations[$n_cnt - 1]->move_order_no = $n_move++;
                } else {
                    $a_stations[$n_cnt]->move_order_no = null;
                    $a_stations[$n_cnt]->move_order_no = $n_move++;
                }
            } elseif ($as_action == 'down') {
                if (
                    $a_stations[$n_cnt]->station_id == $an_station_id
                    && $a_stations[$n_cnt]->traffic_way == $an_traffic_way
                ) {
                    // 更新対象駅が末尾の場合は、変更しない
                    if ($n_cnt + 1 ==  count($a_stations)) {
                        return true;
                    }
                    $a_stations[$n_cnt + 1]->move_order_no = null;
                    $a_stations[$n_cnt + 1]->move_order_no = $n_move++;
                    $a_stations[$n_cnt]->move_order_no = $n_move++;
                } else {
                    if (empty($a_stations[$n_cnt]->move_order_no)) {
                        $a_stations[$n_cnt]->move_order_no = null;
                        $a_stations[$n_cnt]->move_order_no = $n_move++;
                    }
                }
            }
        }

        $hotel_stations_model = new HotelStation();

        try {
            // 表示順の更新
            for ($n_cnt = 0; $n_cnt < count($a_stations); $n_cnt++) {
                if ($a_stations[$n_cnt]->move_order_no != $a_stations[$n_cnt]->order_no) {
                    $hotel_stations_model->where(
                        [
                            'hotel_cd'    => $target_cd,
                            'station_id'  => $a_stations[$n_cnt]->station_id,
                            'traffic_way' => $a_stations[$n_cnt]->traffic_way
                        ]
                    )->update(
                        // 順位を再構築
                        [
                            'order_no'   => $a_stations[$n_cnt]->move_order_no,
                            'modify_ts'  => now(),
                            'modify_cd'  => $actionCd,
                        ]
                    );
                }
            }
            return true;

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 施設最寄駅の表示順の初期化
     *
     * 移動方法、時間、路線ID、駅ID の順番で並び変える
     *
     * @param string $target_cd      施設コード
     */
    public function defaultOrderStations($target_cd)
    {
        // 交通アクセスリスト取得
        $a_stations = $this->getHotelStation($target_cd, '', ['station_nm' => '']);

        $actionCd = $this->getActionCd();

        foreach ($a_stations as $key => $value) {
            $a_sort1[$key] = $value->traffic_way;
            $a_sort2[$key] = $value->minute;
            $a_sort3[$key] = $value->route_id;
            $a_sort4[$key] = $value->station_id;
        }

        array_multisort($a_sort1, SORT_ASC, $a_sort2, SORT_ASC, $a_sort3, SORT_ASC, $a_sort4, SORT_ASC, $a_stations);

        foreach ($a_stations as $key => $value) {
            $a_sort1[$key] = $value->traffic_way;
            $a_sort2[$key] = $value->minute;
            $a_sort3[$key] = $value->route_id;
            $a_sort4[$key] = $value->station_id;
        }

        $hotel_stations_model = new HotelStation();

        try {
            // 表示順の更新
            for ($n_cnt = 0; $n_cnt < count($a_stations); $n_cnt++) {
                if ($n_cnt + 1 != $a_stations[$n_cnt]->order_no) {
                    $hotel_stations_model->where(
                        [
                            'hotel_cd'    => $target_cd,
                            'station_id'  => $a_stations[$n_cnt]->station_id,
                            'traffic_way' => $a_stations[$n_cnt]->traffic_way
                        ]
                    )->update( // 順位を再構築
                        [
                            'order_no'  => ($n_cnt + 1),
                            'modify_ts' => now(),
                            'modify_cd' => $actionCd
                        ]
                    );
                }
            }
            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
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
        $userId = \Illuminate\Support\Facades\Session::get("user_id");   // TODO: ユーザー情報取得のキーは仮です
        $actionCd = $controllerName . "/" . $actionName . "." . $userId;

        return $actionCd;
    }
}
