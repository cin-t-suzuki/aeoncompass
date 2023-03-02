<?php

namespace App\Services;

use App\Common\Traits;
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
use Exception;
use Illuminate\Http\Request;
use App\Services\SearchService;

class QueryService
{
    use Traits;

    //モデルにするか、サービスにするか
    // 元ソースではQueryモデルだが、Serviceでいいのでは？でこちらを作成
    // ディレクトリ構造含め、これで問題ないか？


    // 施設一覧
    public function hotelMethod(Request $request) //protected→publicでいいか
    {
        // 入力パラメータの確認を行う。
        if (!$this->hotelCheck()) { //遷移先要修正
            return false;
        }


        // 地域リスト作成
        $this->setBreadcrumbsArea($request);

        // Search モデルを生成
        $this->_o_search  = new models_Search();
            //同サービス内へ記載

        // 再検索フォーム
        // $this->_assign->search_condition['form'] = $this->_o_search->to_strunct_search_form($this->_request);
        $search_condition['form'] = $this->toStrunctSearchForm($request); //$this->_o_search→$this->へ変更

        // 検索条件などの整理および作成
        if (!$this->_create_condition()) {
            return false;
        }

        // 一覧部分
        // property
        $this->set_partner_cd($request->input('partner_cd')); //$this->_o_search→$this->へ変更

        // 空室プランの取得
        $a_hotels = $this->plan($this->_a_conditions, $this->_s_sort, $this->_a_options, $this->_a_offsets, $this->_s_view);
         //$this->_o_search→$this->へ変更

        $this->_a_result = $a_hotels;

        // アサイン情報を取得
        if (!$this->_index_assign($a_hotels)) {
            return false;
        }

        // キーワード検索の場合、駅、地域、ランドマークを取得
        if ($request->input('keywords')) {
            $o_models_station  = new models_Station();
            $o_models_landmark = new models_Landmark();
            $o_models_area     = new models_Area();

            $a_stations  = $o_models_station->search(array('keywords' => $request->input('keywords')));
            $a_landmarks = $o_models_landmark->search(array('keywords' => $request->input('keywords')));
            $a_areas     = $o_models_area->search(array('keywords' => $request->input('keywords')));

            $this->_assign->keywords['words']    = $this->correct_params('keywords');
            $this->_assign->keywords['landmark'] = count($a_landmarks['values']);
            $this->_assign->keywords['station']  = count($a_stations['values']);
            $this->_assign->keywords['places']   = count($a_areas['values']);

            foreach (nvl($a_stations['values'], array()) as $n_cnt => $values) {
                $this->_assign->index['areas'][0]['prefs'][0]['railwaies'][0]['routes'][0]['stations'][$n_cnt]['station_id']  = $values['station_id'];
                $this->_assign->values['stations'][$values['station_id']]['station_nm']   = $values['station_nm'];
            }

            foreach (nvl($a_landmarks['values'], array()) as $n_cnt => $values) {
                $this->_assign->index['areas'][0]['landmarks'][$n_cnt]['landmark_id'] = $values['landmark_id'];
                $this->_assign->values['landmarks'][$values['landmark_id']]['landmark_type'] = $values['landmark_type'];
                $this->_assign->values['landmarks'][$values['landmark_id']]['landmark_nm']   = $values['landmark_nm'];
            }

            foreach (nvl($a_areas['values'], array()) as $n_cnt => $values) {
                $this->_assign->index['areas'][$n_cnt]['area_id'] = $values['area_id'];
                $this->_assign->values['areas'][$values['area_id']]['area_cd']   = substr($values['type'], 0, 1) . $values['area_id'];
                $this->_assign->values['areas'][$values['area_id']]['area_nm']   = $values['area_nm'];
            }
        }

        // ポイント還元キャンペーン
        $models_PointCamp = new models_PointCamp('20141201');
        if ($models_PointCamp->is_camp()) {
            $this->_assign->values['point_camp']       = $models_PointCamp->get_camp();
            $models_PointCamp->set_order($this->box->user->member->member_cd);
            $this->_assign->values['point_camp_order'] = $models_PointCamp->get_order();
            $this->_assign->values['point_camp']['entry_status'] = $models_PointCamp->is_entry(); // エントリー期間
            if (!$this->is_empty($request->input('date'))) {
                $this->_assign->values['point_camp']['stay_status'] = true; // 宿泊期間
                $o_date = new Br_Models_Date($request->input('date'));
                $s_check_in_ymd = $o_date->to_format('Y-m-d');
                $o_date->add('d', $request->input('stay'));
                $s_check_out_ymd = $o_date->to_format('Y-m-d');

                // 宿泊期間外
                if (
                    $s_check_out_ymd <= $this->_assign->values['point_camp']['stay_s_ymd']
                    or    $this->_assign->values['point_camp']['stay_e_ymd'] < $s_check_in_ymd
                ) {
                    $this->_assign->values['point_camp']['stay_status'] = false;
                }
            }
        }

        return true;
        //コントローラに値を返さなくてはいけない
        return [
            'search_condition' => $search_condition
        ];
    }


    // 施設一覧入力チェック
    private function hotelCheck()
    {
        try {
            // // 数値と認識されない場合はエラー
            // $validations = Validations::getInstance($this->box);
            // if (!$validations->is_numericality($request->input('landmark_id'), null)) {
            //     $this->box->item->error->Add('数値を指定するところに数値以外の値が設定されています。');
            //     $this->set_error_type('parameter');
            //     return false;
            // }
            //一時非表示、要修正

            return true;

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }


    // 地域パンくずリスト作成
    protected function setBreadcrumbsArea($request)
    {
        try {
            // Search モデルを生成→SearchServiceへ
            $this  = new SearchService(); //あとで移植元記載の上で当ファイル内にまとめること

            // パラメータセット（クエリストリング）
            $a_params = ['year_month', 'day', 'stay', 'rooms', 'capacity', 'senior', 'child1', 'child2', 'child3', 'child4', 'child5', 'charge_min', 'charge_max', 'hotel_category_business', 'hotel_category_inn', 'hotel_category_capsule'];
            // $s_query_p = $s_query;　//s_queryの定義なし
            $s_query_p = '';
            foreach ($a_params as $param) {
                if (!$this->is_empty($request->input($param))) { //$this->correct_paramsはrequest->inputでいいか？
                    if (!$this->is_empty($s_query_p)) {
                        $s_query_p .= '&';
                    };
                    $s_query_p .= $param . '=' . $request->input($param); //$this->correct_paramsはrequest->inputでいいか？
                }
            }

            // パンくずリストを作成
            $a_breadcrumbs = $this->toStructBreadcrumbs($request); //$this->_request→$requestでいいか？ //$this->_o_search→$this->へ変更
            $this->_assign->breadcrumbs['area']        = $a_breadcrumbs['area'];
            $this->_assign->breadcrumbs['area_detail'] = $a_breadcrumbs['area_detail'];
            if (!$this->is_empty($s_query_p)) {
                for ($n_cnt = 0; $n_cnt < count($a_breadcrumbs['area']); $n_cnt++) {
                    if (!$this->is_empty($this->_assign->breadcrumbs['area'][$n_cnt]['uri'])) {
                        if (substr($this->_assign->breadcrumbs['area'][$n_cnt]['uri'], -1) == '/') {
                            $this->_assign->breadcrumbs['area'][$n_cnt]['uri'] .= '?';
                        } else {
                            $this->_assign->breadcrumbs['area'][$n_cnt]['uri'] .= '&';
                        }
                        $this->_assign->breadcrumbs['area'][$n_cnt]['uri'] .= $s_query_p;
                    }
                }
                for ($n_cnt = 0; $n_cnt < count($a_breadcrumbs['area_detail']); $n_cnt++) {
                    if (!$this->is_empty($this->_assign->breadcrumbs['area_detail'][$n_cnt]['uri'])) {
                        if (substr($this->_assign->breadcrumbs['area_detail'][$n_cnt]['uri'], -1) == '/') {
                            $this->_assign->breadcrumbs['area_detail'][$n_cnt]['uri'] .= '?';
                        } else {
                            $this->_assign->breadcrumbs['area_detail'][$n_cnt]['uri'] .= '&';
                        }
                        $this->_assign->breadcrumbs['area_detail'][$n_cnt]['uri'] .= $s_query_p;
                    }
                }
            }

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }



    //////////////////////////////////////////////////////////////
    //ここから下SearchServiceから移植
    //////////////////////////////////////////////////////////////
    /*
    * MEMO: 移植元 public\app\_common\models\Core\Search2.php to_struct_breadcrumbs
    */
    // パンくずリストを作成
    private function toStructBreadcrumbs($ao_request, $as_url_type = null)
    {
        try {
            if (!$this->is_empty($ao_request->input('area_id'))) {
                $a_conditions['area_id'] = $ao_request->input('area_id');
            } else if (!$this->is_empty($ao_request->input('ward_id'))) {
                $a_conditions['ward_id'] = $ao_request->input('ward_id');
            } else if (!$this->is_empty($ao_request->input('city_id'))) {
                $a_conditions['city_id'] = $ao_request->input('city_id');
            } else if (!$this->is_empty($ao_request->input('pref_id'))) {
                $a_conditions['pref_id'] = $ao_request->input('pref_id');
            }

            // 都道府県市区エリア
            if (!$this->is_empty($ao_request->input('pref_id')) || !$this->is_empty($ao_request->input('city_id')) or !$this->is_empty($ao_request->input('ward_id')) or !$this->is_empty($ao_request->input('area_id'))) {
                // $core_area = new Core_Area();
                $this->createAreas($a_conditions); //参照先未修整
                $a_breadcrumbs = $this->toStructBreadcrumbsArea($as_url_type); //参照先未修整
                $a_breadcrumbs['list'] = $a_breadcrumbs['area'];
            }

            // 施設コード
            if (!$this->is_empty($ao_request->input('hotel_cd'))) {
                $core_hotel_base3 = new Core_Hotel_Base3();
                $core_hotel_base3->set_hotel_cd($ao_request->input('hotel_cd'));
                $core_hotel_base3->create_hotel(array('breadcrumbs' => true));
                $a_breadcrumbs = $core_hotel_base3->to_struct_breadcrumbs($as_url_type);
                $a_breadcrumbs['list'] = $a_breadcrumbs['area'];
                unset($a_breadcrumbs['area']);
            }

            // 複数施設コード
            if (!$this->is_empty($ao_request->input('hotel_cds'))) {
                if ($this->is_empty($ao_request->input('hotels_title'))) {
                    $s_title = 'キャンペーン中の旅館・ホテル';
                } else {
                    $s_title = $ao_request->input('hotels_title') . 'の旅館・ホテル';
                }

                $a_breadcrumbs['list'] = array(
                    array(
                        'uri'           => $ao_request->input('landing_url'),
                        'nm'            => $s_title,
                        'current_status' => true
                    )
                );
            }

            // 駅ID
            if (!$this->is_empty($ao_request->input('station_id'))) {
                $mast_stations = Mast_Stations::getInstance();
                $a_mast_stations = $mast_stations->find(array('station_id' => $ao_request->input('station_id')));
                $a_breadcrumbs['list'] = array(
                    array(
                        'uri'           => '/station/',
                        'nm'            => '駅・路線検索',
                        'current_status' => false
                    ),
                    array(
                        'uri'           => null,
                        'nm'            => $a_mast_stations['station_nm'] . '駅周辺の旅館・ホテル',
                        'current_status' => true
                    )
                );
            }

            // ランドマークID
            if (!$this->is_empty($ao_request->input('landmark_id'))) {
                $mast_landmark = Mast_Landmark::getInstance();
                $a_mast_landmark = $mast_landmark->find(array('landmark_id' => $ao_request->input('landmark_id')));
                $a_breadcrumbs['list'] = array(
                    array(
                        'uri'           => '/landmark/',
                        'nm'            => 'ランドマーク検索',
                        'current_status' => false
                    ),
                    array(
                        'uri'           => null,
                        'nm'            => $a_mast_landmark['landmark_nm'] . '周辺の旅館・ホテル',
                        'current_status' => true
                    )
                );
            }

            // キーワード
            if (!$this->is_empty($ao_request->input('keywords'))) {

                $a_breadcrumbs['list'] = array(
                    array(
                        'uri'           => null,
                        'nm'            => $ao_request->input('keywords') . 'を含む旅館・ホテル',
                        'current_status' => true
                    )
                );
            }

            // 緯度経度
            if ($this->is_empty($ao_request->input('hotel_cd'))) {
                if (
                    !$this->is_empty($ao_request->input('wgs_lng_d')) or !$this->is_empty($ao_request->input('wgs_lng')) or
                    !$this->is_empty($ao_request->input('lng'))       or !$this->is_empty($ao_request->input('lat')) or
                    !$this->is_empty($ao_request->input('ne_lng'))    or !$this->is_empty($ao_request->input('ne_lat'))
                ) {

                    $a_breadcrumbs['list'] = array(
                        array(
                            'uri'           => null,
                            'nm'            => 'ご指定のスポット周辺の旅館・ホテル',
                            'current_status' => true
                        )
                    );
                }
            }

            return $a_breadcrumbs;

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    /*
    * MEMO: 移植元 public\app\_common\models\Core\Search2.php to_strunct_search_form
    */
    // 検索フォーム整形
    // リクエストパラメータから
    private function toStrunctSearchForm($ao_request)
    {
        try {
            // 施設が固定されている場合の人数泊数等の範囲取得
            if (!$this->is_empty($ao_request->input('hotel_cd')) || !$this->is_empty($ao_request->input('hotel_cds'))) {
                $s_where_hotel_cd = '';

                // 施設コードが単体か複数かで条件式を変更
                if (!$this->is_empty($ao_request->getParam('hotel_cds'))) {
                    // 複数
                    $s_where_hotel_cd = ' 1 = 1';

                    foreach (nvl($ao_request->getParam('hotel_cds'), array()) as $n_key => $s_hotel_cd) {
                        $s_where_hotel_cd .= " ||	hotel_cd = :hotel_cd_" . $n_key . "\n";
                        $a_condition['hotel_cd_' . $n_key] = $s_hotel_cd;
                    }
                } else {
                    // 単体
                    $s_where_hotel_cd = ' hotel_cd = :hotel_cd ';
                    $a_condition['hotel_cd'] = $ao_request->getParam('hotel_cd');
                }

                // プランIDがあれば条件に追加
                if (!$this->is_empty($ao_request->getParam('plan_id'))) {
                    $a_condition['plan_id'] = $ao_request->getParam('plan_id');
                    $s_plan_id = 'and plan_id = :plan_id';
                }

                // 部屋IDがあれば条件に追加
                if (!$this->is_empty($ao_request->getParam('room_id'))) {
                    $a_condition['room_id'] = $ao_request->getParam('room_id');
                    $s_room_id = 'and room_id = :room_id';
                }

                $s_sql =
                    <<< SQL
                            select  max(nvl(plan.stay_cap, nvl(hotel_control.stay_cap, 15))) as stay_cap,
                                    min(plan.stay_limit) as stay_limit,
                                    min(charge_condition.capacity) as capacity_min,
                                    max(charge_condition.capacity) as capacity_max,
                                    max(room2.capacity_max) as room_capacity_max,
                                    max(child1_accept) as child1_accept,
                                    max(child2_accept) as child2_accept,
                                    max(child3_accept) as child3_accept,
                                    max(child4_accept) as child4_accept,
                                    max(child5_accept) as child5_accept,
                                    min(nvl(child1_person, 1)) as child1_person,
                                    min(nvl(child2_person, 1)) as child2_person,
                                    min(nvl(child3_person, 1)) as child3_person,
                                    min(nvl(child4_person, 0)) as child4_person,
                                    min(nvl(child5_person, 0)) as child5_person,
                                    min(nvl(child1_charge_include, 0)) as child1_charge_include,
                                    min(nvl(child2_charge_include, 0)) as child2_charge_include,
                                    min(nvl(child3_charge_include, 0)) as child3_charge_include,
                                    min(nvl(child4_charge_include, 0)) as child4_charge_include,
                                    min(nvl(child5_charge_include, 0)) as child5_charge_include,
                                    max(vacant_max)    as vacant
                            from	hotel_control,
                                    plan,
                                    room2,
                                    room_plan_child,
                                    charge_condition,
                                    (
                                        select	hotel_cd,
                                                plan_id,
                                                room_id
                                        from	room_plan_match
                                        where	{$s_where_hotel_cd}
                                            {$s_plan_id}
                                            {$s_room_id}
                                    ) q1
                            where	q1.hotel_cd         = hotel_control.hotel_cd
                                and	q1.hotel_cd         = plan.hotel_cd
                                and	q1.plan_id          = plan.plan_id
                                and	q1.hotel_cd         = room2.hotel_cd
                                and	q1.room_id          = room2.room_id
                                and	q1.hotel_cd         = charge_condition.hotel_cd
                                and	q1.plan_id          = charge_condition.plan_id
                                and	q1.room_id          = charge_condition.room_id
                                and	plan.display_status = 1
                                and	plan.accept_status  = 1
                                and	room2.display_status = 1
                                and	q1.hotel_cd         = room_plan_child.hotel_cd(+)
                                and	q1.plan_id          = room_plan_child.plan_id(+)
                                and	q1.room_id          = room_plan_child.room_id(+)
    SQL;

                $_oracle = _Oracle::getInstance();
                $a_control  = $_oracle->find_by_sql($s_sql, $a_condition);

                $a_control  = $a_control[0];
                $a_control['senior_min'] = $a_control['capacity_min'];
                // 子供人数設定可能な場合は、大人人数１名から設定
                if ($a_control['child1_accept'] + $a_control['child2_accept'] + $a_control['child3_accept'] + $a_control['child4_accept'] + $a_control['child5_accept'] > 0) {
                    $a_control['capacity_min'] = 1;
                }
            }

            //レスポンス配列
            $a_result = array();
            $s_date = $ao_request->getParam('date');
            if ($this->is_empty($ao_request->getParam('date')) and !$this->is_empty($ao_request->getParam('year_month')) and !$this->is_empty($ao_request->getParam('day'))) {
                $s_date = $ao_request->getParam('year_month') . '-' . $ao_request->getParam('day');
            }
            $o_date = new br_models_date($s_date);

            // 年月
            //   Vpass クラブは 2015/03/14 以前の宿泊のみ可能
            if ($this->box->user->partner->partner_cd == '2000000100') {
                $o_month = new br_models_date($s_date);
                for ($n_cnt = 1; $n_cnt <= 4; $n_cnt++) {
                    $a_result['year_month'][$n_cnt - 1]['date_ym'] = $o_month->to_format('Y-m');
                    $a_result['year_month'][$n_cnt - 1]['current_status'] =  ($o_month->to_format('Y-m') == $o_date->to_format('Y-m')) ? true : false;
                    $o_month->add('m', 1);
                }
            } else {
                $o_month = new br_models_date($s_date);
                for ($n_cnt = 1; $n_cnt <= 13; $n_cnt++) {
                    $a_result['year_month'][$n_cnt - 1]['date_ym'] = $o_month->to_format('Y-m');
                    $a_result['year_month'][$n_cnt - 1]['current_status'] =  ($o_month->to_format('Y-m') == $o_date->to_format('Y-m')) ? true : false;
                    $o_month->add('m', 1);
                }
            }

            // 日
            for ($n_cnt = 1; $n_cnt <= 31; $n_cnt++) {
                $a_result['days'][$n_cnt - 1]['date_ymd'] = $n_cnt;
                $a_result['days'][$n_cnt - 1]['current_status'] =  ($n_cnt == $o_date->to_format('j')) ? true : false;
            }
            // 泊数
            for ($n_cnt = 1; $n_cnt <= 15; $n_cnt++) {
                if (nvl($a_control['stay_cap'], 15) < $n_cnt) {
                    break;
                }
                if (nvl($a_control['stay_limit'], 1) > $n_cnt) {
                    continue;
                }
                $b_current = false;
                if (count($a_result['stay']) == 0 and $this->is_empty($ao_request->getParam('stay'))) {
                    $b_current = true;
                } else if ($n_cnt == $ao_request->getParam('stay')) {
                    $b_current = true;
                }

                $a_result['stay'][$n_cnt - 1]['days'] = $n_cnt;
                $a_result['stay'][$n_cnt - 1]['current_status'] = $b_current;
            }

            // 日付設定
            $a_result['date_status'] = $ao_request->getParam('date_status');

            // 部屋数
            for ($n_cnt = 1; $n_cnt <= 10; $n_cnt++) {
                if (nvl($a_control['vacant'], 10) < $n_cnt) {
                    break;
                }
                $b_current = false;
                if (count($a_result['rooms']) == 0 and $this->is_empty($ao_request->getParam('rooms'))) {
                    $b_current = true;
                } else if ($n_cnt == $ao_request->getParam('rooms')) {
                    $b_current = true;
                }

                $a_result['rooms'][$n_cnt - 1]['room_count'] = $n_cnt;
                $a_result['rooms'][$n_cnt - 1]['current_status'] = $b_current;
            }

            // 大人
            $n_senior = $ao_request->getParam('senior');
            if ($this->is_empty($n_senior)) {
                $n_senior = $a_control['senior_min'];
            }
            for ($n_cnt = 1; $n_cnt <= 6; $n_cnt++) {
                if (nvl($a_control['capacity_max'], 10) < $n_cnt) {
                    break;
                }
                if (nvl($a_control['capacity_min'], 1) > $n_cnt) {
                    continue;
                }
                $b_current = false;
                if ($n_cnt == $n_senior) {
                    $b_current = true;
                }

                $a_result['senior']['capacities'][$n_cnt - 1]['capacity'] = $n_cnt;
                $a_result['senior']['capacities'][$n_cnt - 1]['current_status'] =  $b_current;
            }

            // 子供
            $a_result['childs']['accept_status'] = true;
            // 施設・プラン・部屋が未指定で範囲がわからない場合は、受け入れあり
            if ($this->is_empty($a_control)) {
                $a_result['childs']['accept_status'] = true;

                #				// 部屋の最大利用人数が１名の場合は、受け入れない
                #				} else if ($a_control['capacity_max'] == 1) {
                #					$a_result['childs']['accept_status'] = false;
                #
                // 全ての子供タイプで受け入れない場合は、うけいれない
            } else {
                if ($a_control['child1_accept'] + $a_control['child2_accept'] + $a_control['child3_accept'] + $a_control['child4_accept'] + $a_control['child5_accept'] == 0) {
                    $a_result['childs']['accept_status'] = false;
                }
            }

            if ($a_result['childs']['accept_status'] and nvl($a_control['capacity_max'], 10) > 1) {
                for ($n_child = 1; $n_child <= 5; $n_child++) {
                    $s_nm = 'child' . $n_child;
                    if (nvl($a_control[$s_nm . '_accept'], 1) == 0) {
                        continue;
                    }
                    // 大人料金数えるない場合は、部屋定員最大
                    if (nvl($a_control[$s_nm . '_charge_include'], 1) == 0) {
                        $n_child_capacity_max = $a_control['room_capacity_max'];
                        // 定員数える場合は、マイナス１
                        if ($a_control[$s_nm . '_person'] == 1 || $n_child <= 3) {
                            $n_child_capacity_max = $n_child_capacity_max - 1;
                        }
                        // 大人料金数える場合は、料金登録定員最大
                    } else {
                        $n_child_capacity_max = nvl($a_control['capacity_max'], 10) - 1;
                    }

                    for ($n_cnt = 0; $n_cnt <= 5; $n_cnt++) {
                        if ($n_child_capacity_max < $n_cnt) {
                            break;
                        }
                        $b_current = false;
                        if (count($a_result['childs'][$s_nm . '_capacities']) == 0 and $this->is_empty($ao_request->getParam($s_nm))) {
                            $b_current = true;
                        } else if ($n_cnt == $ao_request->getParam($s_nm)) {
                            $b_current = true;
                        }
                        $a_result['childs'][$s_nm . '_capacities'][$n_cnt - 1]['capacity'] = $n_cnt;
                        $a_result['childs'][$s_nm . '_capacities'][$n_cnt - 1]['current_status'] =  $b_current;
                    }
                }
            }

            // 料金範囲
            $a_chage = array(
                0      => '0円',
                1000   => '1,000円',
                2000   => '2,000円',
                3000   => '3,000円',
                4000   => '4,000円',
                5000   => '5,000円',
                6000   => '6,000円',
                7000   => '7,000円',
                8000   => '8,000円',
                9000   => '9,000円',
                10000  => '10,000円',
                15000  => '15,000円',
                20000  => '20,000円',
                30000  => '30,000円',
                40000  => '40,000円',
                50000  => '50,000円',
                9999999 => '上限なし'
            );
            $n_min = nvl($this->box->user->partner->layout['charge_min'], 0);
            $n_max = nvl($this->box->user->partner->layout['charge_max'], 9999999);

            $b_min_add = ture;
            $b_max_add = ture;
            foreach ($a_chage as $key => $value) {
                // 少
                if (($n_min <= $key and $key < $n_max) || ($n_min == $n_max and $n_min == $key)) {
                    if ($key == nvl($ao_request->getParam('charge_min'), $n_min)) {
                        $b_current_status = true;
                    } else {
                        $b_current_status = false;
                    }
                    if ($key == $n_min) {
                        $b_min_add = false;
                    }
                    $a_result['charges']['min'][] = array(
                        'name'           =>  $value,
                        'charge'         =>  $key,
                        'current_status' =>  $b_current_status
                    );
                }
                // 大
                if ($n_min < $key and $key <= $n_max || ($n_min == $n_max and $n_min == $key)) {
                    if ($key == nvl($ao_request->getParam('charge_max'), $n_max)) {
                        $b_current_status = true;
                    } else {
                        $b_current_status = false;
                    }
                    if ($key == $n_max) {
                        $b_max_add = false;
                    }

                    $a_result['charges']['max'][] = array(
                        'name'           => $value,
                        'charge'         => $key,
                        'current_status' => $b_current_status
                    );
                }
            }
            // 基本料金パターン以外の場合、最少は前に追加
            if ($b_min_add) {
                if ($n_min == nvl($ao_request->getParam('charge_min'), $n_min)) {
                    $b_current_status = true;
                } else {
                    $b_current_status = false;
                }
                $a_result['charges']['min'] = array_merge(
                    array(
                        array(
                            'name'           => number_format($n_min) . '円',
                            'charge'         => $n_min,
                            'current_status' => $b_current_status
                        )
                    ),
                    nvl($a_result['charges']['min'], array())
                );
            }
            // 基本料金パターン以外の場合、最大は後ろに追加
            if ($b_max_add) {
                if ($n_max == nvl($ao_request->getParam('charge_max'), $n_max)) {
                    $b_current_status = true;
                } else {
                    $b_current_status = false;
                }
                $a_result['charges']['max'] = array_merge(
                    nvl($a_result['charges']['max'], array()),
                    array(
                        array(
                            'name'           => number_format($n_max) . '円',
                            'charge'         => $n_max,
                            'current_status' => $b_current_status
                        )
                    )
                );
            }

            $o_mast =  new models_Mast();
            $a_condition = array('not_in_by_pref_id' => array(0, 48));
            $s_order = 'pref_id';
            if (!$this->is_empty($ao_request->getParam('map_id'))) {
                $s_order = 'order_no';
                if (!$this->is_empty($ao_request->getParam('area_id'))) {
                    $a_condition['area_id'] = $ao_request->getParam('area_id');
                    $s_order = 'order_no';
                } else if (!$this->is_empty($ao_request->getParam('pref_id'))) {
                    $a_condition['pref_id'] = $ao_request->getParam('pref_id');
                }
            }
            // 中・小区分の場合、エリアから都道府県取得
            if ($this->is_empty($ao_request->getParam('place_p')) and !$this->is_empty($ao_request->getParam('area_id'))) {
                $a_prefs = $o_mast->get_mast_prefs(array('area_id' => $ao_request->getParam('area_id')), 'order_no');
                $s_pref_cd = sprintf('%02s', $a_prefs['values'][0]['pref_id']);
            } else {
                $s_pref_cd = sprintf('%02s', $ao_request->getParam('pref_id'));
            }

            $a_prefs = $o_mast->get_mast_prefs($a_condition, $s_order);
            $a_prefs = $a_prefs['values'];
            $n_pref_id = null;
            for ($n_cnt = 0; $n_cnt < count($a_prefs); $n_cnt++) {

                $s_place_cd = 'p' . sprintf('%02s', $a_prefs[$n_cnt]['pref_id']);
                $b_current = false;
                if (!$this->is_empty($ao_request->getParam('place_p'))) {
                    $b_current = ($ao_request->getParam('place_p') == $s_place_cd);
                } else if (!$this->is_empty($ao_request->getParam('map_id')) and ($ao_request->getParam('map_id') == $a_prefs[$n_cnt]['pref_id'])) {
                    $b_current = true;
                } else if (!$this->is_empty($ao_request->getParam('place')) and ('p' . $s_pref_cd == $s_place_cd)) {
                    $b_current = true;
                } else {
                    $b_current = ($s_pref_cd == $a_prefs[$n_cnt]['pref_id']);
                }
                $a_result['prefs'][] = array(
                    'place' => $s_place_cd,
                    'place_nm' => $a_prefs[$n_cnt]['pref_nm'],
                    'current_status' => $b_current
                );
                if ($b_current || $this->is_empty($n_pref_id)) {
                    $n_pref_id = $a_prefs[$n_cnt]['pref_id'];
                }
            }

            // エリア
            $a_areas = $o_mast->get_mast_place(array('pref_id' => $n_pref_id));
            $a_areas = $a_areas['values'];

            $a_result['areas'][] = array(
                'place'    => '',
                'place_nm' => '全域',
                'current_status' =>  true
            );

            $b_current = false;
            $n_m = -1;
            for ($n_cnt = 0; $n_cnt < count($a_areas); $n_cnt++) {

                if ($a_areas[$n_cnt]['place'] == $ao_request->getParam('place_ms')) {
                    $b_current = true;
                    $a_result['areas'][0]['current_status'] = false;
                } else if ($a_areas[$n_cnt]['place'] == $ao_request->getParam('map_id')) {
                    $b_current = true;
                    $a_result['areas'][0]['current_status'] = false;
                } else if ($a_areas[$n_cnt]['place'] == $ao_request->getParam('place')) {
                    $b_current = true;
                    $a_result['areas'][0]['current_status'] = false;
                } else {
                    $b_current = false;
                }

                $a_result['areas'][] = array(
                    'place'         => $a_areas[$n_cnt]['place'],
                    'place_nm'      => $a_areas[$n_cnt]['place_nm'],
                    'current_status' => $b_current
                );
                if ($b_current) {
                    $s_place = $a_areas[$n_cnt]['place'];
                    $n_m     = count($a_result['areas']);
                }
                for ($n_cnt2 = 0; $n_cnt2 < count($a_areas[$n_cnt]['type_4']); $n_cnt2++) {
                    if ($a_areas[$n_cnt]['type_4'][$n_cnt2]['place'] == $ao_request->getParam('place_ms')) {
                        $b_current = true;
                        $a_result['areas'][0]['current_status'] = false;
                    } else if ($a_areas[$n_cnt]['type_4'][$n_cnt2]['place'] == $ao_request->getParam('map_id')) {
                        $b_current = true;
                        $a_result['areas'][0]['current_status'] = false;
                    } else if ($a_areas[$n_cnt]['type_4'][$n_cnt2]['place'] == $ao_request->getParam('place')) {
                        $b_current = true;
                        $a_result['areas'][0]['current_status'] = false;
                    } else {
                        $b_current = false;
                    }

                    $a_result['areas'][] = array(
                        'place'         => $a_areas[$n_cnt]['type_4'][$n_cnt2]['place'],
                        'place_nm'      => '　' . $a_areas[$n_cnt]['type_4'][$n_cnt2]['place_nm'],
                        'current_status' => $b_current
                    );
                    if ($b_current) {
                        $s_place = $a_areas[$n_cnt]['type_4'][$n_cnt2]['place'];
                        if ($n_m >= 0) {
                            $a_result['areas'][$n_m - 1]['current_status'] = false;
                        }
                    }
                }
            }

            // 行政区分
            $a_result['cws'][] = array(
                'place'    => '',
                'place_nm' => '全域',
                'current_status' => true
            );
            if (!$this->is_empty($s_place)) {
                //					if (substr($s_place, 0, 1) == 'm') {
                //						$s_parent_area = ' or mast_area.parent_area_id = :area_id';
                //					}
                $s_sql =
                    <<< SQL
                            select *
                            from (
                                    select	distinct
                                            mast_city.city_id,
                                            q3.ward_id,
                                            mast_city.city_nm,
                                            q3.ward_nm,
                                            q3.order_no as ward_order,
                                            mast_city.order_no as city_order
                                    from	mast_city,
                                            (
                                                select	nvl(mast_ward.city_id, q2.city_id) as city_id,
                                                        mast_ward.ward_id,
                                                        nvl(mast_ward.order_no, nvl(mast_ward.ward_id, 0)) as order_no,
                                                        mast_ward.ward_nm
                                                from	mast_ward,
                                                    (
                                                        select	mast_area_match.city_id,
                                                                mast_area_match.ward_id
                                                        from	mast_area_match,
                                                            (
                                                                select	mast_area.area_id
                                                                from	mast_area
                                                                where	mast_area.area_id        = :area_id
                                                                        {$s_parent_area}
                                                            ) q1
                                                        where	mast_area_match.area_id = q1.area_id
                                                    ) q2
                                                where	q2.ward_id = mast_ward.ward_id(+)
                                            ) q3
                                    where	q3.city_id = mast_city.city_id(+)
                            )
                            order by city_order, ward_order
    SQL;
                $a_condition = array('area_id' => substr($s_place, 1));
            } else {
                $s_sql =
                    <<< SQL
                            select *
                            from (
                                    select	distinct
                                            mast_city.city_id,
                                            mast_ward.ward_id,
                                            mast_city.city_nm,
                                            mast_ward.ward_nm,
                                            nvl(mast_ward.order_no, 0) as ward_order,
                                            mast_city.order_no as city_order
                                    from	mast_city,
                                            mast_ward
                                    where	mast_city.pref_id = :pref_id
                                        and	mast_city.pref_id = mast_ward.pref_id(+)
                                        and	mast_city.city_id = mast_ward.city_id(+)
                                    union
                                    select	distinct
                                            mast_city.city_id,
                                            null as ward_id,
                                            mast_city.city_nm,
                                            null as ward_nm,
                                            0 as ward_order,
                                            mast_city.order_no as city_order
                                    from	mast_city,
                                            mast_ward
                                    where	mast_city.pref_id = :pref_id
                                        and	mast_city.pref_id = mast_ward.pref_id
                                        and	mast_city.city_id = mast_ward.city_id
                            )
                            order by city_order, ward_order
    SQL;
                $a_condition = array('pref_id' => $n_pref_id);
            }

            $_oracle = _Oracle::getInstance();
            $a_place = $_oracle->find_by_sql($s_sql, $a_condition);
            $n_c = -1;
            for ($n_cnt = 0; $n_cnt < count($a_place); $n_cnt++) {
                if ($this->is_empty($a_place[$n_cnt]['ward_id'])) {
                    $s_place_cd =  'c' .  $a_place[$n_cnt]['city_id'];
                    $s_place_nm =  $a_place[$n_cnt]['city_nm'];
                } else {
                    $s_place_cd =  'w' .  $a_place[$n_cnt]['ward_id'];
                    $s_place_nm =  '　' . $a_place[$n_cnt]['ward_nm'];
                }
                if ($ao_request->getParam('place_cw') == $s_place_cd) {
                    $b_current = true;
                    $a_result['cws'][0]['current_status'] = false;
                } else if ($ao_request->getParam('map_id') == $s_place_cd) {
                    $b_current = true;
                    $a_result['cws'][0]['current_status'] = false;
                } else if ($ao_request->getParam('place') == $s_place_cd) {
                    $b_current = true;
                    $a_result['cws'][0]['current_status'] = false;
                } else if ('c' . $ao_request->getParam('city_cd') == $s_place_cd) {
                    $b_current = true;
                    $a_result['cws'][0]['current_status'] = false;
                } else if ('w' . $ao_request->getParam('ward_cd') == $s_place_cd) {
                    $b_current = true;
                    $a_result['cws'][0]['current_status'] = false;
                } else {
                    $b_current = false;
                }
                $a_result['cws'][] = array(
                    'place'         => $s_place_cd,
                    'place_nm'      => $s_place_nm,
                    'current_status' => $b_current
                );
                if ($b_current) {
                    if ($n_c >= 0) {
                        $a_result['cws'][$n_c - 1]['current_status'] = false;
                    }
                    $n_c = count($a_result['cws']);
                }
            }
            // 施設コード
            if (!$this->is_empty($ao_request->getParam('hotel_cd'))) {
                $a_result['hotel']['hotel_cd'] = $ao_request->getParam('hotel_cd');
            } else if (!$this->is_empty($ao_request->getParam('hotel_cds'))) {
                $a_result['hotel']['hotel_cd'] = implode(',', $ao_request->getParam('hotel_cds'));
            }
            // プランID
            if (!$this->is_empty($ao_request->getParam('plan_id'))) {
                $a_result['hotel']['plan_id'] = $ao_request->getParam('plan_id');
            }
            // 部屋ID
            if (!$this->is_empty($ao_request->getParam('room_id'))) {
                $a_result['hotel']['room_id'] = $ao_request->getParam('room_id');
            }
            // 特定キャンペン文字列
            if (!$this->is_empty($ao_request->getParam('hotels_title'))) {
                $a_result['hotel']['title'] = $ao_request->getParam('hotels_title');
            }
            // ランドマークID
            if (!$this->is_empty($ao_request->getParam('landmark_id'))) {
                $a_result['landmark']['landmark_id'] = $ao_request->getParam('landmark_id');
            }
            // 駅ID
            if (!$this->is_empty($ao_request->getParam('station_id'))) {
                $a_result['station']['station_id'] = $ao_request->getParam('station_id');
            }
            // 緯度経度
            if (!$this->is_empty($ao_request->getParam('lat'))) {
                $a_result['wgs']['wgs_lat_d'] = $ao_request->getParam('lat');
                $a_result['wgs']['wgs_lng_d'] = $ao_request->getParam('lng');
                $a_result['wgs']['geo']       = $ao_request->getParam('geo');
                $a_result['wgs']['distance']  = $ao_request->getParam('distance');
            }

            // カテゴリ
            if (!$this->is_empty($ao_request->getParam('hotels_title'))) {
                // ホテルタイトル設定している場合は、施設カテゴリ設定しない
            } else {
                if ($this->is_empty($ao_request->getParam('hotel_category_business')) and $this->is_empty($ao_request->getParam('hotel_category_inn')) and $this->is_empty($ao_request->getParam('hotel_category_capsule'))) {
                    $a_result['hotel_category']['business'] = true;
                    $a_result['hotel_category']['inn']      = true;
                    $a_result['hotel_category']['capsule']  = false;
                } else {
                    if (!$this->is_empty($ao_request->getParam('hotel_category_business'))) {
                        $a_result['hotel_category']['business'] = true;
                    } else {
                        $a_result['hotel_category']['business'] = false;
                    }
                    if (!$this->is_empty($ao_request->getParam('hotel_category_inn'))) {
                        $a_result['hotel_category']['inn']      = true;
                    } else {
                        $a_result['hotel_category']['inn']      = false;
                    }
                    if (!$this->is_empty($ao_request->getParam('hotel_category_capsule'))) {
                        $a_result['hotel_category']['capsule']  = true;
                    } else {
                        $a_result['hotel_category']['capsule']  = false;
                    }
                }
            }

            // GoToキャンペーン
            if (!$this->is_empty($ao_request->getParam('goto'))) {
                if ($ao_request->getParam('goto') == 1) {
                    $a_result['goto'] = 1;
                }
            }

            // ２４時以降の予約
            $a_result['midnight']['current_status'] = false;
            $o_today = new br_models_date();
            if ($o_today->to_format('H') <= '05') {
                $a_result['midnight']['current_status'] = true;
                $o_today->add('d', -1);
            } elseif ($o_today->to_format('H') == '23') {
                $a_result['midnight']['current_status'] = true;
            }
            $a_result['midnight']['date_ymd'] = $o_today->get();


            $a_result['type'] = 'list';

            return $a_result;

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }





    /*
    * MEMO: 移植元 public\app\_common\models\Core\Area.php create_areas
    */
    //  現在のエリアから関連するエリアリストを返却
    //
    //  aa_conditions
    //    pref_id  都道府県ID
    //    city_id  市ID
    //    ward_id  区ID
    //    area_id  エリアID
    //
    //  aa_values // 施設一覧データ
    private function createAreas($aa_conditions)
    { //とりあえずうつして中身は未修整
        try {
            // $o_core_mast = new Core_Mast();
            $o_core_mast = new MastService(); //これもQueryService内にまとめる？？
            $mast_ward = new MastWard();
            $mast_city = new MastCity();
            $mast_pref = new MastPref();
            $mast_area = new MastArea();

            // 区ID
            // 全国、大エリア、都道府県、市郡と現在の区
            // 都道府県に含まれる中エリア
            // 都道府県に含まれる市郡区
            if (!$this->is_empty($aa_conditions['ward_id'])) {

                // 現在を取得
                $a_mast_ward = $mast_ward->find(array('ward_id' => $aa_conditions['ward_id']));
                $a_mast_city = $mast_city->find(array('city_id' => $a_mast_ward['city_id']));
                $a_mast_pref = $mast_pref->find(array('pref_id' => $a_mast_ward['pref_id']));

                $s_sql =
                    <<< SQL
                            select	mast_area.area_id,
                                    mast_area.area_nm
                            from	mast_area,
                                (
                                    select	distinct
                                            area_id
                                    from	mast_area_match
                                    where	null is null
                                        and	pref_id = :pref_id
                                ) q1
                            where	mast_area.area_id = q1.area_id
                                and	mast_area.area_type = 1
    SQL;
                $a_row = $_oracle->find_by_sql($s_sql, array('pref_id' => $a_mast_pref['pref_id']));

                $a_result['current'][] = array('id' => 0,                        'value' => '全国',                  'type' => 'area');

                if (!($a_mast_pref['pref_id'] == 1 or $a_mast_pref['pref_id'] == 47)) {
                    $a_result['current'][] = array('id' => 'l' . $a_row[0]['area_id'], 'value' => $a_row[0]['area_nm'],    'type' => 'area');
                }

                $a_title = array(
                    2 => $a_mast_pref['pref_nm'],
                    3 => $a_mast_city['city_nm'],
                    4 => $a_mast_ward['ward_nm']
                );

                $a_result['title']     = nvl2($a_result['title'], $a_result['title'], $a_title);
                $a_result['current'][] = array('id' => $a_mast_pref['pref_cd'],  'value' => $a_mast_pref['pref_nm'], 'type' => 'pcw');
                $a_result['current'][] = array('id' => $a_mast_city['city_cd'],  'value' => $a_mast_city['city_nm'], 'type' => 'pcw', 'pref_cd' => $a_mast_pref['pref_cd']);

                if ($a_mast_ward['city_id'] == '13100') {
                    $a_result['current_title'] = array('id' => $a_mast_ward['ward_cd'],  'value' => $a_mast_pref['pref_nm'] . $a_mast_ward['ward_nm'], 'type' => 'pcw');
                } else {
                    $a_result['current_title'] = array('id' => $a_mast_ward['ward_cd'],  'value' => $a_mast_pref['pref_nm'] . $a_mast_city['city_nm'] . $a_mast_ward['ward_nm'], 'type' => 'pcw');
                }

                // エリアを取得
                $s_sql =
                    <<< SQL
                            select	distinct
                                    q2.area_id,
                                    'm'||q2.area_id as map_id,
                                    q2.area_nm as value,
                                    q2.area_type,
                                    nvl2(mast_area.area_id, 'parent', null) as is_parent,
                                    q2.order_no
                            from	mast_area,
                                (
                                    select	mast_area.area_id,
                                            mast_area.area_nm,
                                            mast_area.area_type,
                                            mast_area.order_no
                                    from	mast_area,
                                        (
                                            select	distinct
                                                    area_id
                                            from	mast_area_match
                                            where	null is null
                                                and	pref_id = :pref_id
                                        ) q1
                                    where	mast_area.parent_area_id = q1.area_id
                                        and	mast_area.area_type = 3
                                ) q2
                            where	mast_area.parent_area_id(+) = q2.area_id
                            order  by q2.order_no
    SQL;
                $a_row = $_oracle->find_by_sql($s_sql, array('pref_id' => $a_mast_pref['pref_id']));

                $a_result['area'] = $a_row;

                // 市区郡を取得
                $a_cities = $this->get_mast_cities(array('pref_id' => $a_mast_pref['pref_id']));
                foreach ($a_cities['values'] as $n_city => $city) {
                    $a_cities['values'][$n_city]['wards'] = $this->get_mast_wards(array('city_id' => $city['city_id']));
                }

                $a_result['pcw'] = $a_cities;
            }

            // 市ID
            // 全国、大エリア、都道府県と現在の市郡
            // 都道府県に含まれる中エリア
            // 都道府県に含まれる市郡区
            if (!$this->is_empty($aa_conditions['city_id'])) {
                $a_mast_city = $mast_city->find(array('city_id' => $aa_conditions['city_id']));
                $a_mast_pref = $mast_pref->find(array('pref_id' => $a_mast_city['pref_id']));

                $s_sql =
                    <<< SQL
                            select	mast_area.area_id,
                                    mast_area.area_nm
                            from	mast_area,
                                (
                                    select	distinct
                                            area_id
                                    from	mast_area_match
                                    where	null is null
                                        and	pref_id = :pref_id
                                ) q1
                            where	mast_area.area_id = q1.area_id
                                and	mast_area.area_type = 1
    SQL;
                $a_row = $_oracle->find_by_sql($s_sql, array('pref_id' => $a_mast_pref['pref_id']));

                $a_result['current'][] = array('id' => 0,                        'value' => '全国',                  'type' => 'area');

                if (!($a_mast_pref['pref_id'] == 1 or $a_mast_pref['pref_id'] == 47)) {
                    $a_result['current'][] = array('id' => 'l' . $a_row[0]['area_id'], 'value' => $a_row[0]['area_nm'],    'type' => 'area');
                }

                $a_title = array(
                    2 => $a_mast_pref['pref_nm'],
                    3 => $a_mast_city['city_nm']
                );

                $a_result['title']     = nvl2($a_result['title'], $a_result['title'], $a_title);

                $a_result['current'][] = array('id' => $a_mast_pref['pref_cd'],  'value' => $a_mast_pref['pref_nm'], 'type' => 'pcw');

                if ($this->is_empty($a_result['current_title'])) {
                    $a_result['current_title'] = array('id' => $a_mast_city['city_cd'],  'value' => $a_mast_pref['pref_nm'] . $a_mast_city['city_nm'], 'type' => 'pcw');
                }

                // エリアを取得
                $s_sql =
                    <<< SQL
                            select	distinct
                                    q2.area_id,
                                    'm'||q2.area_id as map_id,
                                    q2.area_nm as value,
                                    q2.area_type,
                                    nvl2(mast_area.area_id, 'parent', null) as is_parent,
                                    q2.order_no
                            from	mast_area,
                                (
                                    select	mast_area.area_id,
                                            mast_area.area_nm,
                                            mast_area.area_type,
                                            mast_area.order_no
                                    from	mast_area,
                                        (
                                            select	distinct
                                                    area_id
                                            from	mast_area_match
                                            where	null is null
                                                and	pref_id = :pref_id
                                        ) q1
                                    where	mast_area.parent_area_id = q1.area_id
                                        and	mast_area.area_type = 3
                                ) q2
                            where	mast_area.parent_area_id(+) = q2.area_id
                            order  by q2.order_no
    SQL;
                $a_row = $_oracle->find_by_sql($s_sql, array('pref_id' => $a_mast_pref['pref_id']));

                $a_result['area'] = $a_row;

                // 市区郡を取得
                $a_cities = $this->get_mast_cities(array('pref_id' => $a_mast_pref['pref_id']));
                foreach ($a_cities['values'] as $n_city => $city) {
                    $a_cities['values'][$n_city]['wards'] = $this->get_mast_wards(array('city_id' => $city['city_id']));
                }

                $a_result['pcw'] = $a_cities;
            }

            // 都道府県ID
            // 全国、大エリア、現在の都道府県
            // 都道府県に含まれる中エリア
            // 都道府県に含まれる市郡区
            if (!$this->is_empty($aa_conditions['pref_id'])) {
                $a_mast_pref = $mast_pref->find(array('pref_id' => $aa_conditions['pref_id']));

                $s_sql =
                    <<< SQL
                            select	mast_area.area_id,
                                    mast_area.area_nm
                            from	mast_area,
                                (
                                    select	distinct
                                            area_id
                                    from	mast_area_match
                                    where	null is null
                                        and	pref_id = :pref_id
                                ) q1
                            where	mast_area.area_id = q1.area_id
                                and	mast_area.area_type = 1
    SQL;
                $a_row = $_oracle->find_by_sql($s_sql, array('pref_id' => $a_mast_pref['pref_id']));

                $a_result['current'][] = array('id' => 0,                        'value' => '全国',                  'type' => 'area');

                if (!($a_mast_pref['pref_id'] == 1 or $a_mast_pref['pref_id'] == 47)) {
                    $a_result['current'][] = array('id' => 'l' . $a_row[0]['area_id'], 'value' => $a_row[0]['area_nm'],    'type' => 'area');
                }

                $a_title = array(2 => $a_mast_pref['pref_nm']);
                $a_result['title']     = nvl2($a_result['title'], $a_result['title'], $a_title);

                if ($this->is_empty($a_result['current_title'])) {
                    $a_result['current_title'] = array('id' => $a_mast_pref['pref_cd'],  'value' => $a_mast_pref['pref_nm'], 'type' => 'pcw');
                }

                // エリアを取得
                $s_sql =
                    <<< SQL
                            select	distinct
                                    q2.area_id,
                                    'm'||q2.area_id as map_id,
                                    q2.area_nm as value,
                                    q2.area_type,
                                    nvl2(mast_area.area_id, 'parent', null) as is_parent,
                                    q2.order_no
                            from	mast_area,
                                (
                                    select	mast_area.area_id,
                                            mast_area.area_nm,
                                            mast_area.area_type,
                                            mast_area.order_no
                                    from	mast_area,
                                        (
                                            select	distinct
                                                    area_id
                                            from	mast_area_match
                                            where	null is null
                                                and	pref_id = :pref_id
                                        ) q1
                                    where	mast_area.parent_area_id = q1.area_id
                                        and	mast_area.area_type = 3
                                ) q2
                            where	mast_area.parent_area_id(+) = q2.area_id
                            order  by q2.order_no
    SQL;
                $a_row = $_oracle->find_by_sql($s_sql, array('pref_id' => $a_mast_pref['pref_id']));

                $a_result['area'] = $a_row;

                // 市区郡を取得
                $a_cities = $this->get_mast_cities(array('pref_id' => $a_mast_pref['pref_id']));
                foreach ($a_cities['values'] as $n_city => $city) {
                    $a_cities['values'][$n_city]['wards'] = $this->get_mast_wards(array('city_id' => $city['city_id']));
                }

                $a_result['pcw'] = $a_cities;
            }

            if (!$this->is_empty($aa_conditions['area_id'])) {

                $a_mast_area = $mast_area->find(array('area_id' => $aa_conditions['area_id']));

                // 大エリア
                // 大エリアに含まれる都道府県
                if ($a_mast_area['area_type'] == 1) {
                    $a_title = array(1 => $a_mast_area['area_nm']);
                    $a_result['title']     = nvl2($a_result['title'], $a_result['title'], $a_title);
                    $a_result['current'][] = array('id' => 0,                           'value' => '全国',                  'type' => 'area');
                    $a_result['current_title'] = array('id' => 'l' . $a_mast_area['area_id'], 'value' => $a_mast_area['area_nm'], 'type' => 'area');

                    $s_sql =
                        <<< SQL
                                select	q3.area_id,
                                        mast_pref.pref_cd as map_id,
                                        q3.area_nm as value,
                                        q3.area_type,
                                        q3.order_no,
                                        'parent' as is_parent
                                from	mast_pref,
                                    (
                                        select	mast_area.area_id,
                                                q2.pref_id,
                                                mast_area.area_nm,
                                                mast_area.area_type,
                                                mast_area.order_no
                                        from	mast_area,
                                            (
                                                select	distinct
                                                        mast_area_match.area_id,
                                                        q1.pref_id
                                                from	mast_area_match,
                                                    (
                                                        select	pref_id,
                                                                area_id
                                                        from	mast_area_match
                                                        where	null is null
                                                            and	area_id = :area_id
                                                    ) q1
                                                where	mast_area_match.pref_id = q1.pref_id
                                            ) q2
                                        where	mast_area.area_id = q2.area_id
                                            and	mast_area.area_type = 2
                                    ) q3
                                where	mast_pref.pref_id = q3.pref_id
                                order  by mast_pref.order_no, q3.order_no
    SQL;
                    $a_row = $_oracle->find_by_sql($s_sql, array('area_id' => $a_mast_area['area_id']));

                    $a_result['area'] = $a_row;
                }

                // エリアとしての都道府県（未使用）
                //					if ($a_mast_area['area_type'] == 2) {

                //					}

                // 中エリア
                // 全国、大エリア、都道府県、市郡、現在の小エリア
                // 都道府県に含まれる全ての中エリア、現在の中エリアの近隣中エリア
                if ($a_mast_area['area_type'] == 3) {

                    $s_sql =
                        <<< SQL
                                select	mast_area.area_id,
                                        mast_area.area_nm
                                from	mast_area,
                                    (
                                        select	mast_area.parent_area_id
                                        from	mast_area,
                                            (
                                                select	mast_area.parent_area_id
                                                from	mast_area
                                                where	mast_area.area_id = :area_id
                                            ) q1
                                        where	mast_area.area_id = q1.parent_area_id
                                    ) q2
                                where	mast_area.area_id = q2.parent_area_id
    SQL;
                    $a_large = $_oracle->find_by_sql($s_sql, array('area_id' => $aa_conditions['area_id']));

                    $s_sql =
                        <<< SQL
                                select	distinct
                                        pref_id
                                from	mast_area_match,
                                    (
                                        select	mast_area.parent_area_id
                                        from	mast_area
                                        where	mast_area.area_id = :area_id
                                    ) q1
                                where	mast_area_match.area_id = q1.parent_area_id
    SQL;
                    $a_pref = $_oracle->find_by_sql($s_sql, array('area_id' => $aa_conditions['area_id']));
                    $a_mast_pref = $mast_pref->find(array('pref_id' => $a_pref[0]['pref_id']));

                    $a_result['current'][] = array('id' => 0,                           'value' => '全国',                  'type' => 'area');

                    if (!($a_pref[0]['pref_id'] == 1 or $a_pref[0]['pref_id'] == 47)) {
                        $a_result['current'][] = array('id' => 'l' . $a_large[0]['area_id'],  'value' => $a_large[0]['area_nm'],  'type' => 'area');
                    }

                    $a_title = array(
                        2 => $a_mast_pref['pref_nm'],
                        3  => $a_mast_area['area_nm']
                    );
                    $a_result['title']     = nvl2($a_result['title'], $a_result['title'], $a_title);
                    $a_result['current'][] = array('id' => $a_mast_pref['pref_cd'],     'value' => $a_mast_pref['pref_nm'], 'type' => 'pcw');
                    $a_result['current_title'] = array('id' => 'm' . $a_mast_area['area_id'], 'value' => $a_mast_area['area_nm'], 'type' => 'area');

                    $s_sql =
                        <<< SQL
                                select	distinct
                                        q2.area_id,
                                        's'||q2.area_id as map_id,
                                        q2.area_nm as value,
                                        q2.order_no,
                                        q2.area_type,
                                        nvl2(mast_area.area_id, 'parent', null) as is_parent
                                from	mast_area,
                                    (
                                        select	mast_area.area_id,
                                                mast_area.area_nm,
                                                mast_area.order_no,
                                                mast_area.area_type
                                        from	mast_area,
                                            (
                                                select	distinct
                                                        mast_area.area_id
                                                from	mast_area,
                                                    (
                                                        select	distinct
                                                                area_id
                                                        from	mast_area_match
                                                        where	null is null
                                                            and	pref_id = :pref_id
                                                    ) q1
                                                where	mast_area.parent_area_id = q1.area_id
                                            ) q1
                                        where	mast_area.parent_area_id = q1.area_id
                                            and	mast_area.area_type = 4
                                    ) q2
                                where	mast_area.parent_area_id(+) = q2.area_id
                                order  by q2.order_no
    SQL;
                    $a_row = $_oracle->find_by_sql($s_sql, array('pref_id' => $a_mast_pref['pref_id']));
                    if ($this->is_empty($a_row)) {
                        // エリアを取得
                        $s_sql =
                            <<< SQL
                                select	distinct
                                        q2.area_id,
                                        'm'||q2.area_id as map_id,
                                        q2.area_nm as value,
                                        q2.area_type,
                                        nvl2(mast_area.area_id, 'parent', null) as is_parent,
                                        q2.order_no
                                from	mast_area,
                                    (
                                        select	mast_area.area_id,
                                                mast_area.area_nm,
                                                mast_area.area_type,
                                                mast_area.order_no
                                        from	mast_area,
                                            (
                                                select	distinct
                                                        area_id
                                                from	mast_area_match
                                                where	null is null
                                                    and	pref_id = :pref_id
                                            ) q1
                                        where	mast_area.parent_area_id = q1.area_id
                                            and	mast_area.area_type = 3
                                    ) q2
                                where	mast_area.parent_area_id(+) = q2.area_id
                                order  by q2.order_no
    SQL;
                        $a_row = $_oracle->find_by_sql($s_sql, array('pref_id' => $a_mast_pref['pref_id']));
                    }
                    $a_result['area'] = $a_row;

                    // 近隣エリアを取得
                    $s_sql =
                        <<< SQL
                                select	q3.area_id,
                                        'm'||q3.area_id as map_id,
                                        q3.area_nm as value
                                from
                                    (
                                        select	mast_area.area_id,
                                                mast_area.area_nm,
                                                mast_area.order_no
                                        from	mast_area,
                                            (
                                                select	nearby_area_id
                                                from	mast_area_nearby
                                                where	null is null
                                                    and	area_id = :area_id
                                            ) q1
                                        where	mast_area.area_id = q1.nearby_area_id
                                            and	mast_area.area_type = 3
                                    ) q3
                                order  by q3.order_no
    SQL;
                    $a_row = $_oracle->find_by_sql($s_sql, array('area_id' => $a_mast_area['area_id']));

                    $a_result['nearby'] = $a_row;
                }

                // 小エリア
                // 全国、大エリア、都道府県、中エリア、現在の小エリア
                // 上位中エリアに含まれる全ての小エリアと近隣中小エリア
                if ($a_mast_area['area_type'] == 4) {
                    $s_sql =
                        <<< SQL
                                select	mast_area.area_id,
                                        mast_area.area_nm
                                from	mast_area,
                                    (
                                        select	mast_area.parent_area_id
                                        from	mast_area,
                                            (
                                                select	mast_area.parent_area_id
                                                from	mast_area,
                                                    (
                                                        select	mast_area.parent_area_id
                                                        from	mast_area
                                                        where	mast_area.area_id = :area_id
                                                    ) q1
                                                where	mast_area.area_id = q1.parent_area_id
                                            ) q2
                                        where	mast_area.area_id = q2.parent_area_id
                                    ) q3
                                where	mast_area.area_id = q3.parent_area_id
    SQL;
                    $a_large = $_oracle->find_by_sql($s_sql, array('area_id' => $aa_conditions['area_id']));

                    $s_sql =
                        <<< SQL
                                select	distinct
                                        pref_id
                                from	mast_area_match,
                                    (
                                        select	mast_area.parent_area_id
                                        from	mast_area,
                                            (
                                                select	mast_area.parent_area_id
                                                from	mast_area
                                                where	mast_area.area_id = :area_id
                                            ) q1
                                        where	mast_area.area_id = q1.parent_area_id
                                    ) q2
                                where	mast_area_match.area_id = q2.parent_area_id
    SQL;
                    $a_pref = $_oracle->find_by_sql($s_sql, array('area_id' => $aa_conditions['area_id']));
                    $a_mast_pref = $mast_pref->find(array('pref_id' => $a_pref[0]['pref_id']));


                    $s_sql =
                        <<< SQL
                                select	mast_area.area_id,
                                        mast_area.area_nm
                                from	mast_area,
                                    (
                                        select	mast_area.parent_area_id
                                        from	mast_area
                                        where	mast_area.area_id = :area_id
                                    ) q1
                                where	mast_area.area_id = q1.parent_area_id
    SQL;
                    $a_middle = $_oracle->find_by_sql($s_sql, array('area_id' => $aa_conditions['area_id']));

                    $a_result['current'][] = array('id' => 0,                           'value' => '全国',                  'type' => 'area');

                    if (!($a_pref[0]['pref_id'] == 1 or $a_pref[0]['pref_id'] == 47)) {
                        $a_result['current'][] = array('id' => 'l' . $a_large[0]['area_id'],  'value' => $a_large[0]['area_nm'],  'type' => 'area');
                    }

                    $a_title = array(
                        2 => $a_mast_pref['pref_nm'],
                        3  => $a_middle[0]['area_nm'],
                        4  => $a_mast_area['area_nm']
                    );
                    $a_result['title']     = nvl2($a_result['title'], $a_result['title'], $a_title);
                    $a_result['current'][] = array('id' => $a_mast_pref['pref_id'],     'value' => $a_mast_pref['pref_nm'], 'type' => 'pcw');
                    $a_result['current'][] = array('id' => 'm' . $a_middle[0]['area_id'], 'value' => $a_middle[0]['area_nm'], 'type' => 'area');
                    $a_result['current_title'] = array('id' => 's' . $a_mast_area['area_id'], 'value' => $a_mast_area['area_nm'], 'type' => 'area');

                    // エリアを取得
                    $s_sql =
                        <<< SQL
                                select	mast_area.area_id,
                                        's'||mast_area.area_id as map_id,
                                        mast_area.area_nm as value,
                                        mast_area.order_no,
                                        mast_area.area_type
                                from	mast_area,
                                    (
                                        select	mast_area.parent_area_id
                                                from	mast_area
                                        where	mast_area.area_id = :area_id
                                    ) q1
                                where	mast_area.parent_area_id = q1.parent_area_id
                                    and	mast_area.area_type = 4
                                order  by mast_area.order_no
    SQL;
                    $a_row = $_oracle->find_by_sql($s_sql, array('area_id' => $a_mast_area['area_id']));

                    $a_result['area'] = $a_row;

                    // 近隣エリアを取得
                    $s_sql =
                        <<< SQL
                                select	q3.area_id,
                                        'm'||q3.area_id as map_id,
                                        q3.area_nm as value
                                from
                                    (
                                        select	mast_area.area_id,
                                                mast_area.area_nm,
                                                mast_area.order_no
                                        from	mast_area,
                                            (
                                                select	nearby_area_id
                                                from	mast_area_nearby
                                                where	null is null
                                                    and	area_id = :area_id
                                            ) q1
                                        where	mast_area.area_id = q1.nearby_area_id
                                            and	mast_area.area_type = 3
                                    ) q3
                                order  by q3.order_no
    SQL;
                    $a_row = $_oracle->find_by_sql($s_sql, array('area_id' => $a_mast_area['area_id']));

                    $a_result['nearby'] = $a_row;
                }
            }

            $this->area = $a_result;

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }


    /*
        * MEMO: 移植元 public\app\_common\models\Core\Area.php to_struct_breadcrumbs
        * 名称がかぶるため、末尾にAreaを追記
        */
    // パンくずの構造体
    // as_url フルパス : full 短縮版：ヌル値
    private function toStructBreadcrumbsArea($as_url = null)
    { //とりあえずうつして中身未修整
        try {

            $a_breadcrumbs = array();
            $a_area_lists = $this->area;

            // 都道府県
            if (!is_empty($a_area_lists['current'])) {
                foreach ($a_area_lists['current'] as $current) {
                    if (is_empty($a_breadcrumbs['area'])) {
                        if (is_empty($as_url)) {
                            $s_url = null;
                        } else {
                            $s_url = '/hotelarea/';
                        }
                        $a_breadcrumbs['area'][] = array(
                            'uri' => $s_url,
                            'nm'  => $current['value'],
                            'current_status' => false
                        );
                    } else {
                        if (substr($current['id'], 0, 1) == 'l') {
                            if (is_empty($as_url)) {
                                $s_url = '/area/' . $current['id'] . '/';
                            } else {
                                $s_url = '/hotelarea/map/?map_id=' . $current['id'];
                            }
                            $a_breadcrumbs['area'][] = array(
                                'uri' => $s_url,
                                'nm'  =>  $current['value'],
                                'current_status' => false
                            );
                        } else if ($current['type'] == 'pcw' and !is_empty($current['pref_cd'])) {
                            if (is_empty($as_url)) {
                                $s_url = '/list/' . $current['pref_cd'] . '/' . $current['id'] . '/';
                            } else {
                                $s_url = '/query/?pref_cd=' . $current['pref_cd'] . '&city_cd=' . $current['id'];
                            }
                            $a_breadcrumbs['area'][] = array(
                                'uri' => $s_url,
                                'nm'  =>  $current['value'],
                                'current_status' => false
                            );
                        } else {
                            if (is_empty($as_url)) {
                                $s_url =  '/list/' . $current['id'] . '/';
                            } else {
                                if (substr($current['id'], 0, 1) == 's' or substr($current['id'], 0, 1) == 'l' or substr($current['id'], 0, 1) == 'm') {
                                    $s_url = '/query/?place=' . $current['id'];
                                } else {
                                    $s_url = '/query/?place=p' . $current['id'];
                                }
                            }
                            $a_breadcrumbs['area'][] = array(
                                'uri' => $s_url,
                                'nm'  =>  $current['value'],
                                'current_status' => false
                            );
                        }
                    }
                }
                $a_breadcrumbs['area'][] = array(
                    'uri' => null,
                    'nm'  =>  $a_area_lists['current_title']['value'],
                    'current_status' => true
                );
            }
            // エリア詳細
            if (!is_empty($a_area_lists['area'])) {
                foreach ($a_area_lists['area'] as $area) {
                    if (is_empty($as_url)) {
                        $s_url =  '/list/' .  $area['map_id'] . '/';
                    } else {
                        if (substr($area['map_id'], 0, 1) == 's' or substr($area['map_id'], 0, 1) == 'l' or substr($area['map_id'], 0, 1) == 'm') {
                            $s_url = '/query/?place=' . $area['map_id'];
                        } else {
                            $s_url = '/query/?place=p' . $area['map_id'];
                        }
                    }
                    $a_breadcrumbs['area_detail'][] = array(
                        'uri' => ($a_area_lists['current_title']['id'] != $area['map_id'] ? $s_url : null),
                        'nm'  =>  $area['value'],
                        'current_status' => ($a_area_lists['current_title']['id'] == $area['map_id'] ? true : false)
                    );
                }
            }
            // 市区郡
            if (!is_empty($a_area_lists['pcw']['values'])) {
                foreach ($a_area_lists['pcw']['values'] as $pcw) {

                    // 区
                    $a_wards = array();
                    if (!is_empty($pcw['wards']['values'])) {
                        foreach ($pcw['wards']['values'] as $wards) {
                            if ($a_area_lists['current_title']['id'] != $wards['ward_id']) {
                                if (is_empty($as_url)) {
                                    $s_url =  '/list/' . sprintf('%02d', $pcw['pref_id']) . '/' . sprintf('%05d', $pcw['city_id']) . '/' . sprintf('%05d', $wards['ward_id']) . '/';
                                } else {
                                    $s_url = '/query/?pref_cd=' . sprintf('%02d', $pcw['pref_id']) . '&city_cd=' . sprintf('%05d', $pcw['city_id']) . '&ward_cd=' . sprintf('%05d', $wards['ward_id']);
                                }
                                $a_wards[] = array(
                                    'uri' => $s_url,
                                    'nm'  =>  $wards['ward_nm'],
                                    'current_status' => false
                                );
                            } else {
                                $a_wards[] = array(
                                    'uri' => null,
                                    'nm'  =>  $wards['ward_nm'],
                                    'current_status' => false
                                );
                            }
                        }
                    }

                    // 市郡
                    if ($a_area_lists['current_title']['id'] != $pcw['city_id']) {
                        if (is_empty($as_url)) {
                            $s_url =  '/list/' . sprintf('%02d', $pcw['pref_id']) . '/' . sprintf('%05d', $pcw['city_id']) . '/';
                        } else {
                            $s_url = '/query/?pref_cd=' . sprintf('%02d', $pcw['pref_id']) . '&city_cd=' . sprintf('%05d', $pcw['city_id']);
                        }
                        $a_breadcrumbs['city_ward'][] = array(
                            'uri' =>  $s_url,
                            'nm'  =>  $pcw['city_nm'],
                            'current_status' => false,
                            'wards' => $a_wards
                        );
                    } else {
                        $a_breadcrumbs['city_ward'][] = array(
                            'uri' => null,
                            'nm'  =>  $pcw['city_nm'],
                            'current_status' => true,
                            'wards' => $a_wards
                        );
                    }
                }
            }

            return $a_breadcrumbs;

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
}
