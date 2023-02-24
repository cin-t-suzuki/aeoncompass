<?php

namespace App\Services\Rsv;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Common\DateUtil;

class TopService
{
    /**
     * 「キーワードから探す」の「人気のおすすめキーワード」を取得
     *
     * MEMO: 移植元 public\app\rsv\lib\Controllers\Action2.php set_partner() L.730 あたり
     *
     * @param string $partnerCd
     * @return stdClass[]
     */
    public function getKeywords($partnerCd): array
    {
			// 1: keyword, 2: station, 3: landmark
			for ($n_type = 0; $n_type < 3; $n_type++) {
				$s_sql =
				<<<SQL
					select
						case
							when layout_type = 0 then 'keyword'
							when layout_type = 1 then 'station'
							when layout_type = 2 then 'landmark'
						end as layout_type_nm,
						word,
						value
					from
						partner_keyword_example
					where
						partner_cd = :partner_cd
						and	layout_type = :layout_type
						and	display_status = 1
					order by
						layout_type,
						order_no
				SQL;
				// データの取得
				$_oracle = _Oracle::getInstance();
				$a_row = $_oracle->find_by_sql($s_sql, array('partner_cd' => $this->_request->getParam('partner_cd'), 'layout_type' => $n_type));

				if (is_empty($a_row)) {
					$a_row = $_oracle->find_by_sql($s_sql, array('partner_cd' => '0000000000', 'layout_type' => $n_type));
				}
				for ($n_cnt = 0; $n_cnt < count($a_row); $n_cnt++) {
					$this->box->user->partner->keyword_example[$a_row[$n_cnt]['layout_type_nm']][] = array(
						'word' => $a_row[$n_cnt]['word'],
						'value' => $a_row[$n_cnt]['value']
					);
				}
			}
    }

    public function getSearchCondition($request): array
    {
        // $searchCondition = $this->dummySearchCondition();

        $searchCondition['form'] = $this->toStructSearchForm($request);
        unset($searchCondition['form']['type']);
        unset($searchCondition['form']['cws']);

        return $searchCondition;
    }

    // 検索フォーム整形
    // リクエストパラメータから
    public function to_strunct_search_form($ao_request)
    {
        try {

            // 施設が固定されている場合の人数泊数等の範囲取得
            if (!is_empty($ao_request->getParam('hotel_cd')) or !is_empty($ao_request->getParam('hotel_cds'))) {

                $s_where_hotel_cd = '';

                // 施設コードが単体か複数かで条件式を変更
                if (!is_empty($ao_request->getParam('hotel_cds'))) {
                    // 複数
                    $s_where_hotel_cd = ' 1 = 1';

                    foreach (nvl($ao_request->getParam('hotel_cds'), array()) as $n_key => $s_hotel_cd) {
                        $s_where_hotel_cd .= " or    hotel_cd = :hotel_cd_" . $n_key . "\n";
                        $a_condition['hotel_cd_' . $n_key] = $s_hotel_cd;
                    }
                } else {
                    // 単体
                    $s_where_hotel_cd = ' hotel_cd = :hotel_cd ';
                    $a_condition['hotel_cd'] = $ao_request->getParam('hotel_cd');
                }

                // プランIDがあれば条件に追加
                if (!is_empty($ao_request->getParam('plan_id'))) {
                    $a_condition['plan_id'] = $ao_request->getParam('plan_id');
                    $s_plan_id = 'and plan_id = :plan_id';
                }

                // 部屋IDがあれば条件に追加
                if (!is_empty($ao_request->getParam('room_id'))) {
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
                        from    hotel_control,
                                plan,
                                room2,
                                room_plan_child,
                                charge_condition,
                                (
                                    select    hotel_cd,
                                            plan_id,
                                            room_id
                                    from    room_plan_match
                                    where    {$s_where_hotel_cd}
                                        {$s_plan_id}
                                        {$s_room_id}
                                ) q1
                        where    q1.hotel_cd         = hotel_control.hotel_cd
                            and    q1.hotel_cd         = plan.hotel_cd
                            and    q1.plan_id          = plan.plan_id
                            and    q1.hotel_cd         = room2.hotel_cd
                            and    q1.room_id          = room2.room_id
                            and    q1.hotel_cd         = charge_condition.hotel_cd
                            and    q1.plan_id          = charge_condition.plan_id
                            and    q1.room_id          = charge_condition.room_id
                            and    plan.display_status = 1
                            and    plan.accept_status  = 1
                            and    room2.display_status = 1
                            and    q1.hotel_cd         = room_plan_child.hotel_cd(+)
                            and    q1.plan_id          = room_plan_child.plan_id(+)
                            and    q1.room_id          = room_plan_child.room_id(+)
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
            if (is_empty($ao_request->getParam('date')) and !is_empty($ao_request->getParam('year_month')) and !is_empty($ao_request->getParam('day'))) {
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
                if (count($a_result['stay']) == 0 and is_empty($ao_request->getParam('stay'))) {
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
                if (count($a_result['rooms']) == 0 and is_empty($ao_request->getParam('rooms'))) {
                    $b_current = true;
                } else if ($n_cnt == $ao_request->getParam('rooms')) {
                    $b_current = true;
                }

                $a_result['rooms'][$n_cnt - 1]['room_count'] = $n_cnt;
                $a_result['rooms'][$n_cnt - 1]['current_status'] = $b_current;
            }

            // 大人
            $n_senior = $ao_request->getParam('senior');
            if (is_empty($n_senior)) {
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
            if (is_empty($a_control)) {
                $a_result['childs']['accept_status'] = true;

                #                // 部屋の最大利用人数が１名の場合は、受け入れない
                #                } else if ($a_control['capacity_max'] == 1) {
                #                    $a_result['childs']['accept_status'] = false;
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
                        if ($a_control[$s_nm . '_person'] == 1 or $n_child <= 3) {
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
                        if (count($a_result['childs'][$s_nm . '_capacities']) == 0 and is_empty($ao_request->getParam($s_nm))) {
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
                if (($n_min <= $key and $key < $n_max) or ($n_min == $n_max and $n_min == $key)) {
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
                if ($n_min < $key and $key <= $n_max or ($n_min == $n_max and $n_min == $key)) {
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
            if (!is_empty($ao_request->getParam('map_id'))) {
                $s_order = 'order_no';
                if (!is_empty($ao_request->getParam('area_id'))) {
                    $a_condition['area_id'] = $ao_request->getParam('area_id');
                    $s_order = 'order_no';
                } else if (!is_empty($ao_request->getParam('pref_id'))) {
                    $a_condition['pref_id'] = $ao_request->getParam('pref_id');
                }
            }
            // 中・小区分の場合、エリアから都道府県取得
            if (is_empty($ao_request->getParam('place_p')) and !is_empty($ao_request->getParam('area_id'))) {
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
                if (!is_empty($ao_request->getParam('place_p'))) {
                    $b_current = ($ao_request->getParam('place_p') == $s_place_cd);
                } else if (!is_empty($ao_request->getParam('map_id')) and ($ao_request->getParam('map_id') == $a_prefs[$n_cnt]['pref_id'])) {
                    $b_current = true;
                } else if (!is_empty($ao_request->getParam('place')) and ('p' . $s_pref_cd == $s_place_cd)) {
                    $b_current = true;
                } else {
                    $b_current = ($s_pref_cd == $a_prefs[$n_cnt]['pref_id']);
                }
                $a_result['prefs'][] = array(
                    'place' => $s_place_cd,
                    'place_nm' => $a_prefs[$n_cnt]['pref_nm'],
                    'current_status' => $b_current
                );
                if ($b_current or is_empty($n_pref_id)) {
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
            if (!is_empty($s_place)) {
                //                    if (substr($s_place, 0, 1) == 'm') {
                //                        $s_parent_area = ' or mast_area.parent_area_id = :area_id';
                //                    }
                $s_sql =
                    <<< SQL
                        select *
                        from (
                                select    distinct
                                        mast_city.city_id,
                                        q3.ward_id,
                                        mast_city.city_nm,
                                        q3.ward_nm,
                                        q3.order_no as ward_order,
                                        mast_city.order_no as city_order
                                from    mast_city,
                                        (
                                            select    nvl(mast_ward.city_id, q2.city_id) as city_id,
                                                    mast_ward.ward_id,
                                                    nvl(mast_ward.order_no, nvl(mast_ward.ward_id, 0)) as order_no,
                                                    mast_ward.ward_nm
                                            from    mast_ward,
                                                (
                                                    select    mast_area_match.city_id,
                                                            mast_area_match.ward_id
                                                    from    mast_area_match,
                                                        (
                                                            select    mast_area.area_id
                                                            from    mast_area
                                                            where    mast_area.area_id        = :area_id
                                                                    {$s_parent_area}
                                                        ) q1
                                                    where    mast_area_match.area_id = q1.area_id
                                                ) q2
                                            where    q2.ward_id = mast_ward.ward_id(+)
                                        ) q3
                                where    q3.city_id = mast_city.city_id(+)
                        )
                        order by city_order, ward_order
        SQL;
                $a_condition = array('area_id' => substr($s_place, 1));
            } else {
                $s_sql =
                    <<< SQL
                        select *
                        from (
                                select    distinct
                                        mast_city.city_id,
                                        mast_ward.ward_id,
                                        mast_city.city_nm,
                                        mast_ward.ward_nm,
                                        nvl(mast_ward.order_no, 0) as ward_order,
                                        mast_city.order_no as city_order
                                from    mast_city,
                                        mast_ward
                                where    mast_city.pref_id = :pref_id
                                    and    mast_city.pref_id = mast_ward.pref_id(+)
                                    and    mast_city.city_id = mast_ward.city_id(+)
                                union
                                select    distinct
                                        mast_city.city_id,
                                        null as ward_id,
                                        mast_city.city_nm,
                                        null as ward_nm,
                                        0 as ward_order,
                                        mast_city.order_no as city_order
                                from    mast_city,
                                        mast_ward
                                where    mast_city.pref_id = :pref_id
                                    and    mast_city.pref_id = mast_ward.pref_id
                                    and    mast_city.city_id = mast_ward.city_id
                        )
                        order by city_order, ward_order
        SQL;
                $a_condition = array('pref_id' => $n_pref_id);
            }

            $_oracle = _Oracle::getInstance();
            $a_place = $_oracle->find_by_sql($s_sql, $a_condition);
            $n_c = -1;
            for ($n_cnt = 0; $n_cnt < count($a_place); $n_cnt++) {
                if (is_empty($a_place[$n_cnt]['ward_id'])) {
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
            if (!is_empty($ao_request->getParam('hotel_cd'))) {
                $a_result['hotel']['hotel_cd'] = $ao_request->getParam('hotel_cd');
            } else if (!is_empty($ao_request->getParam('hotel_cds'))) {
                $a_result['hotel']['hotel_cd'] = implode(',', $ao_request->getParam('hotel_cds'));
            }
            // プランID
            if (!is_empty($ao_request->getParam('plan_id'))) {
                $a_result['hotel']['plan_id'] = $ao_request->getParam('plan_id');
            }
            // 部屋ID
            if (!is_empty($ao_request->getParam('room_id'))) {
                $a_result['hotel']['room_id'] = $ao_request->getParam('room_id');
            }
            // 特定キャンペン文字列
            if (!is_empty($ao_request->getParam('hotels_title'))) {
                $a_result['hotel']['title'] = $ao_request->getParam('hotels_title');
            }
            // ランドマークID
            if (!is_empty($ao_request->getParam('landmark_id'))) {
                $a_result['landmark']['landmark_id'] = $ao_request->getParam('landmark_id');
            }
            // 駅ID
            if (!is_empty($ao_request->getParam('station_id'))) {
                $a_result['station']['station_id'] = $ao_request->getParam('station_id');
            }
            // 緯度経度
            if (!is_empty($ao_request->getParam('lat'))) {
                $a_result['wgs']['wgs_lat_d'] = $ao_request->getParam('lat');
                $a_result['wgs']['wgs_lng_d'] = $ao_request->getParam('lng');
                $a_result['wgs']['geo']       = $ao_request->getParam('geo');
                $a_result['wgs']['distance']  = $ao_request->getParam('distance');
            }

            // カテゴリ
            if (!is_empty($ao_request->getParam('hotels_title'))) {
                // ホテルタイトル設定している場合は、施設カテゴリ設定しない
            } else {
                if (is_empty($ao_request->getParam('hotel_category_business')) and is_empty($ao_request->getParam('hotel_category_inn')) and is_empty($ao_request->getParam('hotel_category_capsule'))) {
                    $a_result['hotel_category']['business'] = true;
                    $a_result['hotel_category']['inn']      = true;
                    $a_result['hotel_category']['capsule']  = false;
                } else {
                    if (!is_empty($ao_request->getParam('hotel_category_business'))) {
                        $a_result['hotel_category']['business'] = true;
                    } else {
                        $a_result['hotel_category']['business'] = false;
                    }
                    if (!is_empty($ao_request->getParam('hotel_category_inn'))) {
                        $a_result['hotel_category']['inn']      = true;
                    } else {
                        $a_result['hotel_category']['inn']      = false;
                    }
                    if (!is_empty($ao_request->getParam('hotel_category_capsule'))) {
                        $a_result['hotel_category']['capsule']  = true;
                    } else {
                        $a_result['hotel_category']['capsule']  = false;
                    }
                }
            }

            // GoToキャンペーン
            if (!is_empty($ao_request->getParam('goto'))) {
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

    // TODO: to be deleted
    private function dummySearchCondition(): array
    {
        return [
            'form' => [
                'stay' => [],
                'areas' => [
                    [
                        'place' => 'place',
                        'current_status' => false,
                        'place_nm' => '地名',
                    ],
                    [
                        'place' => 'place',
                        'current_status' => false,
                        'place_nm' => '地名',
                    ],
                    [
                        'place' => 'place',
                        'current_status' => false,
                        'place_nm' => '地名',
                    ],
                ],
                'cws' => [
                    [
                        'place' => 'place',
                        'current_status' => false,
                        'place_nm' => '地名',
                    ],
                    [
                        'place' => 'place',
                        'current_status' => false,
                        'place_nm' => '地名',
                    ],
                    [
                        'place' => 'place',
                        'current_status' => false,
                        'place_nm' => '地名',
                    ],
                ],
                'prefs' => [
                    [
                        'place' => 'place',
                        'current_status' => false,
                        'place_nm' => '地名',
                    ],
                    [
                        'place' => 'place',
                        'current_status' => false,
                        'place_nm' => '地名',
                    ],
                    [
                        'place' => 'place',
                        'current_status' => false,
                        'place_nm' => '地名',
                    ],
                ],
                'hotel' => [
                    'room_id' => null,
                    'hotel_cd' => null,
                    'title' => null,
                    'plan_id' => null,
                ],
                'type' => null,
                'goto' => 0,
                'charges' => [
                    'min' => [],
                    'max' => [],
                ],
                'senior' => [
                    'capacities' => [],
                ],
                'childs' => [
                    'accept_status' => true,
                    'child1_capacities' => [
                        ['capacity' => 1, 'current_status' => false,],
                        ['capacity' => 2, 'current_status' => false,],
                        ['capacity' => 3, 'current_status' => false,],
                    ],
                    'child2_capacities' => [
                        ['capacity' => 1, 'current_status' => false,],
                        ['capacity' => 2, 'current_status' => false,],
                        ['capacity' => 3, 'current_status' => false,],
                    ],
                    'child3_capacities' => [
                        ['capacity' => 1, 'current_status' => false,],
                        ['capacity' => 2, 'current_status' => false,],
                        ['capacity' => 3, 'current_status' => false,],
                    ],
                    'child4_capacities' => [
                        ['capacity' => 1, 'current_status' => false,],
                        ['capacity' => 2, 'current_status' => false,],
                        ['capacity' => 3, 'current_status' => false,],
                    ],
                    'child5_capacities' => [
                        ['capacity' => 1, 'current_status' => false,],
                        ['capacity' => 2, 'current_status' => false,],
                        ['capacity' => 3, 'current_status' => false,],
                    ],
                ],
                'date_status' => '',
                'midnight' => [
                    'current_status' => false,
                    'date_ymd' => strtotime(date('Y-m-d')),
                ],
                'rooms' => [
                    [
                        'room_count' => 100,
                        'current_status' => true,
                    ],
                ],
                'year_month' => [
                    [
                        'date_ym' => '2023-06',
                        'current_status' => true,
                    ],
                ],
                'days' => [
                    [
                        'date_ymd' => '2023-06-12',
                        'current_status' => true,
                    ],
                ],
                'stay' => [
                    [
                        'days' => 'days value',
                        'current_status' => true,
                    ],
                ],
            ],
        ];
    }

    // 都道府県マスタを取得
	//
	// aa_conditions
	//   region_id         地方ID
	//   area_id           エリアID
	//   pref_id           都道府県ID
	//   not_in_by_pref_id 取り除く都道府県IDを設定
	// as_order            ソートキー (pref_id, order_no)
	//
	// example
	//     get_mast_pref(array('not_in_by_pref_id', array('1', '2')))
	public function get_mast_prefs($aa_conditions = array(), $as_order = 'pref_id')
	{
		try {

			// 地方ID
			if (!is_empty($aa_conditions['region_id'])) {
				$s_region_id = '	and	mast_pref.region_id = :region_id';
			}

			// エリアID
			if (!is_empty($aa_conditions['area_id'])) {
				$s_area_id =
					<<<SQL
						and	mast_pref.pref_id in (
							select	nvl(nvl(mast_ward.pref_id, mast_city.pref_id), mast_area_match.pref_id) as pref_id
							from	mast_area_match,
									mast_city,
									mast_ward,
									(
										select	area_id
										from	mast_area
										where	area_id = :area_id
											or	parent_area_id = :area_id
									) q1
							where	mast_area_match.area_id = q1.area_id
								and	mast_area_match.city_id = mast_city.city_id(+)
								and	mast_area_match.ward_id = mast_ward.ward_id(+)
							)
			SQL;
			}
			// 都道府県
			if (!is_empty($aa_conditions['pref_id'])) {
				$s_pref_id = '	and	mast_pref.pref_id = :pref_id';
			}

			// 取り除く都道府県ID
			if (!is_empty($aa_conditions['not_in_by_pref_id'])) {
				$s_not_in_by_pref_id = '	and	mast_pref.pref_id not in(';

				for ($n_cnt = 0; $n_cnt < count($aa_conditions['not_in_by_pref_id']); $n_cnt++) {
					$s_not_in_by_pref_id .= ':pref_id' . $n_cnt . ', ';
					$aa_conditions['pref_id' . $n_cnt] = $aa_conditions['not_in_by_pref_id'][$n_cnt];
				}

				$s_not_in_by_pref_id = substr($s_not_in_by_pref_id, 0, -2);
				$s_not_in_by_pref_id .= ')';
				unset($aa_conditions['not_in_by_pref_id']);
			}

			$s_sql =
				<<<SQL
					select	mast_pref.pref_id,
							mast_pref.region_id,
							mast_pref.pref_nm,
							mast_pref.pref_ns,
							mast_pref.order_no,
							mast_pref.pref_cd,
							to_char(mast_pref.delete_ymd, 'YYYY-MM-DD') as delete_ymd
					from	mast_pref
					where	null is null
						{$s_region_id}
						{$s_area_id}
						{$s_pref_id}
						{$s_not_in_by_pref_id}
					order by mast_pref.{$as_order}
            SQL;

			// データの取得
			$_oracle = _Oracle::getInstance();
			return array(
				'values'     => $_oracle->find_by_sql($s_sql, $aa_conditions),
				'reference' => $this->set_reference('都道府県マスタを取得', __METHOD__)
			);

			// 各メソッドで Exception が投げられた場合
		} catch (Exception $e) {
			throw $e;
		}
	}

	// 都道府県して、地域一覧を取得します。
	//   aa_conditions
	//     pref_id   都道府県ID
	public function get_mast_place($aa_conditions)
	{
		try {

			// 都道府県ID
			if (!is_empty($aa_conditions['pref_id'])) {
				$s_pref_id = '	and	mast_area_match.pref_id = :pref_id';
			}

			// データの取得
			$_oracle = _Oracle::getInstance();

			// 3:小エリア
			$s_sql =
				<<<SQL
					select	mast_area.area_id,
							mast_area.parent_area_id,
							mast_area.area_nm,
							mast_area.area_type
					from	mast_area,
						(
							select	area_id
							from	mast_area_match
							where	null is null
								{$s_pref_id}
						) q1
					where	mast_area.parent_area_id = q1.area_id
						and	mast_area.area_type = 3
					order by mast_area.order_no
            SQL;
			$a_place3 = $_oracle->find_by_sql($s_sql, $aa_conditions);
			for ($n_type3 = 0; $n_type3 < count($a_place3); $n_type3++) {
				// 4:細エリア
				$a_places4 = array();
				$s_sql =
					<<<SQL
						select	mast_area.area_id,
								mast_area.parent_area_id,
								mast_area.area_nm,
								mast_area.area_type
						from	mast_area
						where	mast_area.parent_area_id = :parent_area_id
							and	mast_area.area_type = 4
						order by mast_area.order_no
            SQL;
				$a_place4 = $_oracle->find_by_sql($s_sql, array('parent_area_id' => $a_place3[$n_type3]['area_id']));
				for ($n_type4 = 0; $n_type4 < count($a_place4); $n_type4++) {
					$a_places4[] = array(
						'place' => 's' . $a_place4[$n_type4]['area_id'],
						'place_nm' => $a_place4[$n_type4]['area_nm']
					);
				}

				// エリア追加
				$a_places[] = array(
					'place'    => 'm' . $a_place3[$n_type3]['area_id'],
					'place_nm' => $a_place3[$n_type3]['area_nm'],
					'type_4'   => $a_places4
				);
			}

			return array(
				'values'     => $a_places,
				'reference' => $this->set_reference('都道府県市区が属している地域の3:小・4:細を階層で取得します。', __METHOD__)
			);

			// 各メソッドで Exception が投げられた場合
		} catch (Exception $e) {
			throw $e;
		}
	}
}
