<?php

namespace App\Services\Rsv;

use Illuminate\Support\Facades\DB;
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
        $n_type = 0;
        $s_sql = <<<SQL
            select
                word,
                value
            from
                partner_keyword_example
            where
                partner_cd = :partner_cd
                and layout_type = :layout_type
                and display_status = 1
            order by
                layout_type,
                order_no
        SQL;
        // データの取得
        $keywords = DB::select($s_sql, [
            'partner_cd' => $partnerCd,
            'layout_type' => $n_type,
        ]);

        if (count($keywords) === 0) {
            $keywords = DB::select($s_sql, [
                'partner_cd' => '0000000000',
                'layout_type' => $n_type,
            ]);
        }
        return $keywords;
    }

    /**
     * 検索フォーム設定
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function getSearchCondition($request): array
    {
        $searchCondition['form'] = $this->toStructSearchForm($request);

        // MEMO: HACK: 工数次第で対応
        //     何かしらの歴史的経緯があるものと思われる。
        //     不要であれば、取得処理を削除できるか。
        unset($searchCondition['form']['type']);
        unset($searchCondition['form']['cws']);

        return $searchCondition;
    }

    /**
     * 検索フォーム整形 リクエストパラメータから
     *
     * MEMO: 移植元 public\app\_common\models\Core\Search2.php to_strunct_search_form()
     * HACK: 長大なメソッド
     *      不要な処理が含まれている可能性が高い（対応は工数次第）
     *
     * @return array
     */
    private function toStructSearchForm($ao_request): array
    {
        $a_control = null;
        // 施設が固定されている場合の人数泊数等の範囲取得
        if ($ao_request->has('hotel_cd') || $ao_request->has('hotel_cds')) {
            $s_where_hotel_cd = '';

            // 施設コードが単体か複数かで条件式を変更
            if ($ao_request->has('hotel_cds')) {
                // 複数
                $s_where_hotel_cd = ' 1 = 1';

                foreach ($ao_request->input('hotel_cds', []) as $n_key => $s_hotel_cd) {
                    $s_where_hotel_cd .= ' or hotel_cd = :hotel_cd_' . $n_key . "\n";
                    $a_condition['hotel_cd_' . $n_key] = $s_hotel_cd;
                }
            } else {
                // 単体
                $s_where_hotel_cd = ' hotel_cd = :hotel_cd ';
                $a_condition['hotel_cd'] = $ao_request->input('hotel_cd');
            }

            // プランIDがあれば条件に追加
            $s_plan_id = '';
            if ($ao_request->has('plan_id')) {
                $a_condition['plan_id'] = $ao_request->input('plan_id');
                $s_plan_id = 'and plan_id = :plan_id';
            }

            // 部屋IDがあれば条件に追加
            $s_room_id = '';
            if ($ao_request->has('room_id')) {
                $a_condition['room_id'] = $ao_request->input('room_id');
                $s_room_id = 'and room_id = :room_id';
            }

            $s_sql = <<<SQL
                select
                    max(ifnull(plan.stay_cap, ifnull(hotel_control.stay_cap, 15))) as stay_cap,
                    min(plan.stay_limit) as stay_limit,
                    min(charge_condition.capacity) as capacity_min,
                    max(charge_condition.capacity) as capacity_max,
                    max(room2.capacity_max) as room_capacity_max,
                    max(child1_accept) as child1_accept,
                    max(child2_accept) as child2_accept,
                    max(child3_accept) as child3_accept,
                    max(child4_accept) as child4_accept,
                    max(child5_accept) as child5_accept,
                    min(ifnull(child1_person, 1)) as child1_person,
                    min(ifnull(child2_person, 1)) as child2_person,
                    min(ifnull(child3_person, 1)) as child3_person,
                    min(ifnull(child4_person, 0)) as child4_person,
                    min(ifnull(child5_person, 0)) as child5_person,
                    min(ifnull(child1_charge_include, 0)) as child1_charge_include,
                    min(ifnull(child2_charge_include, 0)) as child2_charge_include,
                    min(ifnull(child3_charge_include, 0)) as child3_charge_include,
                    min(ifnull(child4_charge_include, 0)) as child4_charge_include,
                    min(ifnull(child5_charge_include, 0)) as child5_charge_include,
                    max(vacant_max) as vacant
                from
                    (
                        select
                            hotel_cd,
                            plan_id,
                            room_id
                        from
                            room_plan_match
                        where
                            {$s_where_hotel_cd}
                            {$s_plan_id}
                            {$s_room_id}
                    ) as q1
                    inner join hotel_control
                        on q1.hotel_cd = hotel_control.hotel_cd
                    inner join plan
                        on q1.hotel_cd = plan.hotel_cd
                        and q1.plan_id = plan.plan_id
                    inner join room2
                        on q1.hotel_cd = room2.hotel_cd
                        and q1.room_id = room2.room_id
                    left outer join room_plan_child
                        on q1.hotel_cd = room_plan_child.hotel_cd
                        and q1.plan_id = room_plan_child.plan_id
                        and q1.room_id = room_plan_child.room_id
                    inner join charge_condition
                        on q1.hotel_cd = charge_condition.hotel_cd
                        and q1.plan_id = charge_condition.plan_id
                        and q1.room_id = charge_condition.room_id
                where 1 = 1
                    and plan.display_status = 1
                    and plan.accept_status = 1
                    and room2.display_status = 1
            SQL;

            $a_controls = DB::select($s_sql, $a_condition);

            /** @var stdClass $a_control */
            $a_control = $a_controls[0];
            $a_control->senior_min = $a_control->capacity_min;
            // 子供人数設定可能な場合は、大人人数１名から設定
            if (
                $a_control->child1_accept + $a_control->child2_accept + $a_control->child3_accept
                + $a_control->child4_accept + $a_control->child5_accept > 0
            ) {
                $a_control->capacity_min = 1;
            }
        }

        //レスポンス配列
        $a_result = [];
        $s_date = $ao_request->input('date');
        if ($ao_request->missing('date') && $ao_request->has('year_month') && $ao_request->has('day')) {
            $s_date = $ao_request->input('year_month') . '-' . $ao_request->input('day');
        }

        $o_date = new DateUtil($s_date);

        // 年月
        $o_month = new DateUtil($s_date);
        for ($n_cnt = 1; $n_cnt <= 13; $n_cnt++) {
            $a_result['year_month'][$n_cnt - 1]['date_ym'] = $o_month->to_format('Y-m');
            $a_result['year_month'][$n_cnt - 1]['current_status']
                = $o_month->to_format('Y-m') == $o_date->to_format('Y-m');
            $o_month->add('m', 1);
        }

        // 日
        for ($n_cnt = 1; $n_cnt <= 31; $n_cnt++) {
            $a_result['days'][$n_cnt - 1]['date_ymd'] = $n_cnt;
            $a_result['days'][$n_cnt - 1]['current_status'] = ($n_cnt == $o_date->to_format('j'));
        }
        // 泊数
        for ($n_cnt = 1; $n_cnt <= 15; $n_cnt++) {
            if ((!is_null($a_control) && !is_null($a_control->stay_cap) ? $a_control->stay_cap : 15) < $n_cnt) {
                break;
            }
            if ((!is_null($a_control) && !is_null($a_control->stay_limit) ? $a_control->stay_limit : 1) > $n_cnt) {
                continue;
            }
            $b_current = false;
            if (!array_key_exists('stay', $a_result) && $ao_request->missing('stay')) {
                $b_current = true;
            } elseif ($n_cnt == $ao_request->input('stay')) {
                $b_current = true;
            }

            $a_result['stay'][$n_cnt - 1]['days'] = $n_cnt;
            $a_result['stay'][$n_cnt - 1]['current_status'] = $b_current;
        }

        // 日付設定
        $a_result['date_status'] = $ao_request->input('date_status');

        // 部屋数
        for ($n_cnt = 1; $n_cnt <= 10; $n_cnt++) {
            if ((!is_null($a_control) && !is_null($a_control->vacant) ? $a_control->vacant : 10) < $n_cnt) {
                break;
            }
            $b_current = false;
            if (!array_key_exists('rooms', $a_result) && $ao_request->missing('rooms')) {
                $b_current = true;
            } elseif ($n_cnt == $ao_request->input('rooms')) {
                $b_current = true;
            }

            $a_result['rooms'][$n_cnt - 1]['room_count'] = $n_cnt;
            $a_result['rooms'][$n_cnt - 1]['current_status'] = $b_current;
        }

        // 大人
        $n_senior = $ao_request->input('senior', !is_null($a_control) ? $a_control->senior_min : null);
        for ($n_cnt = 1; $n_cnt <= 6; $n_cnt++) {
            if ((!is_null($a_control) && !is_null($a_control->capacity_max) ? $a_control->capacity_max : 10) < $n_cnt) {
                break;
            }
            if ((!is_null($a_control) && !is_null($a_control->capacity_min) ? $a_control->capacity_min : 1) > $n_cnt) {
                continue;
            }
            $b_current = false;
            if ($n_cnt == $n_senior) {
                $b_current = true;
            }

            $a_result['senior']['capacities'][$n_cnt - 1]['capacity'] = $n_cnt;
            $a_result['senior']['capacities'][$n_cnt - 1]['current_status'] = $b_current;
        }

        // 子供
        $a_result['children']['accept_status'] = true;
        if (is_null($a_control)) {
            // 施設・プラン・部屋が未指定で範囲がわからない場合は、受け入れあり
            $a_result['children']['accept_status'] = true;
        } else {
            // 全ての子供タイプで受け入れない場合は、うけいれない
            if (
                $a_control->child1_accept + $a_control->child2_accept + $a_control->child3_accept
                + $a_control->child4_accept + $a_control->child5_accept == 0
            ) {
                $a_result['children']['accept_status'] = false;
            }
        }


        if (
            $a_result['children']['accept_status']
            && (!is_null($a_control) && !is_null($a_control->capacity_max) ? $a_control->capacity_max : 10) > 1
        ) {
            for ($n_child = 1; $n_child <= 5; $n_child++) {
                $s_nm = 'child' . $n_child;
                $child_n_accept = $s_nm . '_accept';
                $child_n_charge_include = $s_nm . '_charge_include';
                $child_n_person = $s_nm . '_person';
                $child_n_capacities = $s_nm . '_capacities';
                if (
                    (!is_null($a_control) && !is_null($a_control->$child_n_accept) ? $a_control->$child_n_accept : 1)
                    == 0
                ) {
                    continue;
                }
                // 大人料金数えない場合は、部屋定員最大
                if (
                    (!is_null($a_control) && !is_null($a_control->$child_n_charge_include)
                        ? $a_control->$child_n_charge_include : 1) == 0
                ) {
                    $n_child_capacity_max = $a_control->room_capacity_max;
                    // 定員数える場合は、マイナス１
                    if ($a_control->$child_n_person == 1 || $n_child <= 3) {
                        $n_child_capacity_max = $n_child_capacity_max - 1;
                    }
                } else {
                    // 大人料金数える場合は、料金登録定員最大
                    $n_child_capacity_max =
                        (!is_null($a_control) && !is_null($a_control->capacity_max)
                            ? $a_control->capacity_max : 10) - 1;
                }

                for ($n_cnt = 0; $n_cnt <= 5; $n_cnt++) {
                    if ($n_child_capacity_max < $n_cnt) {
                        break;
                    }
                    $b_current = false;
                    if (!array_key_exists($child_n_capacities, $a_result['children']) && $ao_request->missing($s_nm)) {
                        $b_current = true;
                    } elseif ($n_cnt == $ao_request->input($s_nm)) {
                        $b_current = true;
                    }
                    $a_result['children'][$child_n_capacities][$n_cnt - 1]['capacity'] = $n_cnt;
                    $a_result['children'][$child_n_capacities][$n_cnt - 1]['current_status'] = $b_current;
                }
            }
        }

        // 料金範囲
        $charges = [
            0       => '0円',
            1000    => '1,000円',
            2000    => '2,000円',
            3000    => '3,000円',
            4000    => '4,000円',
            5000    => '5,000円',
            6000    => '6,000円',
            7000    => '7,000円',
            8000    => '8,000円',
            9000    => '9,000円',
            10000   => '10,000円',
            15000   => '15,000円',
            20000   => '20,000円',
            30000   => '30,000円',
            40000   => '40,000円',
            50000   => '50,000円',
            9999999 => '上限なし',
        ];
        // MEMO: public\app\rsv\lib\Controllers\Action2.php L.700 辺りに実装あり
        //      $this->box->user->partner->layout['charge_min']
        //      $this->box->user->partner->layout['charge_max']
        $n_min =  0;
        $n_max =  9999999;

        $b_min_add = true;
        $b_max_add = true;
        foreach ($charges as $key => $value) {
            // 少
            if (($n_min <= $key && $key < $n_max) || ($n_min == $n_max && $n_min == $key)) {
                if ($key == $ao_request->input('charge_min', $n_min)) {
                    $b_current_status = true;
                } else {
                    $b_current_status = false;
                }
                if ($key == $n_min) {
                    $b_min_add = false;
                }
                $a_result['charges']['min'][] = [
                    'name' => $value,
                    'charge' => $key,
                    'current_status' => $b_current_status,
                ];
            }
            // 大
            if ($n_min < $key && $key <= $n_max || ($n_min == $n_max && $n_min == $key)) {
                if ($key == $ao_request->input('charge_max', $n_max)) {
                    $b_current_status = true;
                } else {
                    $b_current_status = false;
                }
                if ($key == $n_max) {
                    $b_max_add = false;
                }

                $a_result['charges']['max'][] = [
                    'name' => $value,
                    'charge' => $key,
                    'current_status' => $b_current_status,
                ];
            }
        }
        // 基本料金パターン以外の場合、最少は前に追加
        if ($b_min_add) {
            if ($n_min == $ao_request->input('charge_min', $n_min)) {
                $b_current_status = true;
            } else {
                $b_current_status = false;
            }
            $a_result['charges']['min'] = array_merge(
                [
                    [
                        'name' => number_format($n_min) . '円',
                        'charge' => $n_min,
                        'current_status' => $b_current_status,
                    ],
                ],
                (!is_null($a_result['charges']['min']) ? $a_result['charges']['min'] : [])
            );
        }
        // 基本料金パターン以外の場合、最大は後ろに追加
        if ($b_max_add) {
            if ($n_max == $ao_request->input('charge_max', $n_max)) {
                $b_current_status = true;
            } else {
                $b_current_status = false;
            }
            $a_result['charges']['max'] = array_merge(
                (!is_null($a_result['charges']['max']) ? $a_result['charges']['max'] : []),
                [
                    [
                        'name' => number_format($n_max) . '円',
                        'charge' => $n_max,
                        'current_status' => $b_current_status,
                    ],
                ]
            );
        }

        $a_condition = ['not_in_by_pref_id' => [0, 48]];
        $s_order = 'pref_id';
        if ($ao_request->has('map_id')) {
            $s_order = 'order_no';
            if ($ao_request->has('area_id')) {
                $a_condition['area_id'] = $ao_request->input('area_id');
                $s_order = 'order_no';
            } elseif ($ao_request->has('pref_id')) {
                $a_condition['pref_id'] = $ao_request->input('pref_id');
            }
        }
        // 中・小区分の場合、エリアから都道府県取得
        if ($ao_request->missing('place_p') && $ao_request->has('area_id')) {
            $a_prefs = $this->getMastPrefs(['area_id' => $ao_request->input('area_id')], 'order_no');
            $s_pref_cd = sprintf('%02s', $a_prefs[0]['pref_id']);
        } else {
            $s_pref_cd = sprintf('%02s', $ao_request->input('pref_id'));
        }

        $a_prefs = $this->getMastPrefs($a_condition, $s_order);
        $n_pref_id = null;
        // HACK: foreach のほうがシンプルに思われる。(工数次第)
        for ($n_cnt = 0; $n_cnt < count($a_prefs); $n_cnt++) {
            $s_place_cd = 'p' . sprintf('%02s', $a_prefs[$n_cnt]->pref_id);
            $b_current = false;
            if ($ao_request->has('place_p')) {
                $b_current = ($ao_request->input('place_p') == $s_place_cd);
            } elseif ($ao_request->has('map_id') && ($ao_request->input('map_id') == $a_prefs[$n_cnt]->pref_id)) {
                $b_current = true;
            } elseif ($ao_request->has('place') && ('p' . $s_pref_cd == $s_place_cd)) {
                $b_current = true;
            } else {
                $b_current = ($s_pref_cd == $a_prefs[$n_cnt]->pref_id);
            }
            $a_result['prefs'][] = [
                'place' => $s_place_cd,
                'place_nm' => $a_prefs[$n_cnt]->pref_nm,
                'current_status' => $b_current,
            ];
            if ($b_current || is_null($n_pref_id)) {
                $n_pref_id = $a_prefs[$n_cnt]->pref_id;
            }
        }

        // エリア
        $a_areas = $this->getMastPlace(['pref_id' => $n_pref_id]);

        $a_result['areas'][] = [
            'place' => '',
            'place_nm' => '全域',
            'current_status' => true,
        ];

        $b_current = false;
        $n_m = -1;
        $s_place = null;
        // HACK: foreach のほうがシンプルに思われる。(工数次第)
        for ($n_cnt = 0; $n_cnt < count($a_areas); $n_cnt++) {
            if ($a_areas[$n_cnt]['place'] == $ao_request->input('place_ms')) {
                $b_current = true;
                $a_result['areas'][0]['current_status'] = false;
            } elseif ($a_areas[$n_cnt]['place'] == $ao_request->input('map_id')) {
                $b_current = true;
                $a_result['areas'][0]['current_status'] = false;
            } elseif ($a_areas[$n_cnt]['place'] == $ao_request->input('place')) {
                $b_current = true;
                $a_result['areas'][0]['current_status'] = false;
            } else {
                $b_current = false;
            }

            $a_result['areas'][] = [
                'place' => $a_areas[$n_cnt]['place'],
                'place_nm' => $a_areas[$n_cnt]['place_nm'],
                'current_status' => $b_current,
            ];
            if ($b_current) {
                $s_place = $a_areas[$n_cnt]['place'];
                $n_m = count($a_result['areas']);
            }
            // HACK: foreach のほうがシンプルに思われる。(工数次第)
            for ($n_cnt2 = 0; $n_cnt2 < count($a_areas[$n_cnt]['type_4']); $n_cnt2++) {
                if ($a_areas[$n_cnt]['type_4'][$n_cnt2]['place'] == $ao_request->input('place_ms')) {
                    $b_current = true;
                    $a_result['areas'][0]['current_status'] = false;
                } elseif ($a_areas[$n_cnt]['type_4'][$n_cnt2]['place'] == $ao_request->input('map_id')) {
                    $b_current = true;
                    $a_result['areas'][0]['current_status'] = false;
                } elseif ($a_areas[$n_cnt]['type_4'][$n_cnt2]['place'] == $ao_request->input('place')) {
                    $b_current = true;
                    $a_result['areas'][0]['current_status'] = false;
                } else {
                    $b_current = false;
                }

                $a_result['areas'][] = [
                    'place' => $a_areas[$n_cnt]['type_4'][$n_cnt2]['place'],
                    'place_nm' => '　' . $a_areas[$n_cnt]['type_4'][$n_cnt2]['place_nm'],
                    'current_status' => $b_current,
                ];
                if ($b_current) {
                    $s_place = $a_areas[$n_cnt]['type_4'][$n_cnt2]['place'];
                    if ($n_m >= 0) {
                        $a_result['areas'][$n_m - 1]['current_status'] = false;
                    }
                }
            }
        }

        // 行政区分
        $a_result['cws'][] = [
            'place' => '',
            'place_nm' => '全域',
            'current_status' => true,
        ];

        if (!is_null($s_place)) {
            $s_parent_area = '';
            $s_sql = <<<SQL
                select *
                from
                    (
                        select distinct
                            mast_city.city_id,
                            q3.ward_id,
                            mast_city.city_nm,
                            q3.ward_nm,
                            q3.order_no as ward_order,
                            mast_city.order_no as city_order
                        from
                            (
                                select
                                    ifnull(mast_ward.city_id, q2.city_id) as city_id,
                                    mast_ward.ward_id,
                                    ifnull(mast_ward.order_no, ifnull(mast_ward.ward_id, 0)) as order_no,
                                    mast_ward.ward_nm
                                from
                                    (
                                        select
                                            mast_area_match.city_id,
                                            mast_area_match.ward_id
                                        from
                                            mast_area_match
                                            inner join (
                                                select
                                                    mast_area.area_id
                                                from
                                                    mast_area
                                                where
                                                    mast_area.area_id = :area_id
                                                    {$s_parent_area}
                                            ) q1
                                                on mast_area_match.area_id = q1.area_id
                                    ) q2
                                    left outer join mast_ward
                                        on q2.ward_id = mast_ward.ward_id
                            ) q3
                            left outer join mast_city
                                on q3.city_id = mast_city.city_id
                        )
                order by
                    city_order,
                    ward_order
            SQL;
            $a_condition = [
                'area_id' => substr($s_place, 1),
            ];
        } else {
            $s_sql = <<<SQL
                select *
                from
                    (
                        select distinct
                            mast_city.city_id,
                            mast_ward.ward_id,
                            mast_city.city_nm,
                            mast_ward.ward_nm,
                            ifnull(mast_ward.order_no, 0) as ward_order,
                            mast_city.order_no as city_order
                        from
                            mast_city
                            left outer join mast_ward
                                on mast_city.pref_id = mast_ward.pref_id
                                and mast_city.city_id = mast_ward.city_id
                        where
                            mast_city.pref_id = :pref_id_1
                        union
                        select distinct
                            mast_city.city_id,
                            null as ward_id,
                            mast_city.city_nm,
                            null as ward_nm,
                            0 as ward_order,
                            mast_city.order_no as city_order
                        from
                            mast_city
                            inner join mast_ward
                                on mast_city.pref_id = mast_ward.pref_id
                                and mast_city.city_id = mast_ward.city_id
                        where
                            mast_city.pref_id = :pref_id_2
                    ) as q
                order by
                    city_order,
                    ward_order
            SQL;
            $a_condition = [
                'pref_id_1' => $n_pref_id,
                'pref_id_2' => $n_pref_id,
            ];
        }

        $a_place = DB::select($s_sql, $a_condition);
        $n_c = -1;
        // HACK: foreach のほうがシンプルに思われる。(工数次第)
        for ($n_cnt = 0; $n_cnt < count($a_place); $n_cnt++) {
            if (is_null($a_place[$n_cnt]->ward_id)) {
                $s_place_cd = 'c' . $a_place[$n_cnt]->city_id;
                $s_place_nm = $a_place[$n_cnt]->city_nm;
            } else {
                $s_place_cd = 'w' . $a_place[$n_cnt]->ward_id;
                $s_place_nm = '　' . $a_place[$n_cnt]->ward_nm;
            }
            if ($ao_request->input('place_cw') == $s_place_cd) {
                $b_current = true;
                $a_result['cws'][0]['current_status'] = false;
            } elseif ($ao_request->input('map_id') == $s_place_cd) {
                $b_current = true;
                $a_result['cws'][0]['current_status'] = false;
            } elseif ($ao_request->input('place') == $s_place_cd) {
                $b_current = true;
                $a_result['cws'][0]['current_status'] = false;
            } elseif ('c' . $ao_request->input('city_cd') == $s_place_cd) {
                $b_current = true;
                $a_result['cws'][0]['current_status'] = false;
            } elseif ('w' . $ao_request->input('ward_cd') == $s_place_cd) {
                $b_current = true;
                $a_result['cws'][0]['current_status'] = false;
            } else {
                $b_current = false;
            }
            $a_result['cws'][] = [
                'place' => $s_place_cd,
                'place_nm' => $s_place_nm,
                'current_status' => $b_current,
            ];
            if ($b_current) {
                if ($n_c >= 0) {
                    $a_result['cws'][$n_c - 1]['current_status'] = false;
                }
                $n_c = count($a_result['cws']);
            }
        }
        // 施設コード
        if ($ao_request->has('hotel_cd')) {
            $a_result['hotel']['hotel_cd'] = $ao_request->input('hotel_cd');
        } elseif ($ao_request->has('hotel_cds')) {
            $a_result['hotel']['hotel_cd'] = implode(',', $ao_request->input('hotel_cds'));
        }
        // プランID
        if ($ao_request->has('plan_id')) {
            $a_result['hotel']['plan_id'] = $ao_request->input('plan_id');
        }
        // 部屋ID
        if ($ao_request->has('room_id')) {
            $a_result['hotel']['room_id'] = $ao_request->input('room_id');
        }
        // 特定キャンペン文字列
        if ($ao_request->has('hotels_title')) {
            $a_result['hotel']['title'] = $ao_request->input('hotels_title');
        }
        // ランドマークID
        if ($ao_request->has('landmark_id')) {
            $a_result['landmark']['landmark_id'] = $ao_request->input('landmark_id');
        }
        // 駅ID
        if ($ao_request->has('station_id')) {
            $a_result['station']['station_id'] = $ao_request->input('station_id');
        }
        // 緯度経度
        if ($ao_request->has('lat')) {
            $a_result['wgs']['wgs_lat_d'] = $ao_request->input('lat');
            $a_result['wgs']['wgs_lng_d'] = $ao_request->input('lng');
            $a_result['wgs']['geo'] = $ao_request->input('geo');
            $a_result['wgs']['distance'] = $ao_request->input('distance');
        }

        // カテゴリ
        if ($ao_request->has('hotels_title')) {
            // ホテルタイトル設定している場合は、施設カテゴリ設定しない
        } else {
            if (
                $ao_request->missing('hotel_category_business')
                && $ao_request->missing('hotel_category_inn')
                && $ao_request->missing('hotel_category_capsule')
            ) {
                $a_result['hotel_category']['business'] = true;
                $a_result['hotel_category']['inn'] = true;
                $a_result['hotel_category']['capsule'] = false;
            } else {
                if ($ao_request->has('hotel_category_business')) {
                    $a_result['hotel_category']['business'] = true;
                } else {
                    $a_result['hotel_category']['business'] = false;
                }
                if ($ao_request->has('hotel_category_inn')) {
                    $a_result['hotel_category']['inn'] = true;
                } else {
                    $a_result['hotel_category']['inn'] = false;
                }
                if ($ao_request->has('hotel_category_capsule')) {
                    $a_result['hotel_category']['capsule'] = true;
                } else {
                    $a_result['hotel_category']['capsule'] = false;
                }
            }
        }

        // GoToキャンペーン
        if ($ao_request->has('goto')) {
            if ($ao_request->input('goto') == 1) {
                $a_result['goto'] = 1;
            }
        }

        // ２４時以降の予約
        $a_result['midnight']['current_status'] = false;
        $o_today = new DateUtil();
        if ($o_today->to_format('H') <= '05') {
            $a_result['midnight']['current_status'] = true;
            $o_today->add('d', -1);
        } elseif ($o_today->to_format('H') == '23') {
            $a_result['midnight']['current_status'] = true;
        }
        $a_result['midnight']['date_ymd'] = $o_today->get();


        $a_result['type'] = 'list';

        return $a_result;
    }

    /**
     * 都道府県マスタを取得
     *
     * MEMO: 移植元 public\app\_common\models\Core\Mast.php get_mast_prefs
     *
     *
     * aa_conditions
     *   region_id         地方ID
     *   area_id           エリアID
     *   pref_id           都道府県ID
     *   not_in_by_pref_id 取り除く都道府県IDを設定
     * as_order            ソートキー (pref_id, order_no)
     *
     * example
     *     get_mast_pref(array('not_in_by_pref_id', array('1', '2')))
     *
     * @param array $aa_conditions
     * @param string $as_order
     * @return stdClass[]
     */
    private function getMastPrefs($aa_conditions = [], $as_order = 'pref_id'): array
    {
        // 地方ID
        $s_region_id = '';
        if (array_key_exists('region_id', $aa_conditions)) {
            $s_region_id = ' and mast_pref.region_id = :region_id';
        }

        // エリアID
        $s_area_id = '';
        if (array_key_exists('area_id', $aa_conditions)) {
            $s_area_id = <<<SQL
                and mast_pref.pref_id in (
                    select
                        ifnull(ifnull(mast_ward.pref_id, mast_city.pref_id), mast_area_match.pref_id) as pref_id
                    from
                        mast_area_match
                        left outer join mast_city
                            on mast_area_match.city_id = mast_city.city_id
                        left outer join mast_ward
                            on mast_area_match.ward_id = mast_ward.ward_id
                        inner join (
                            select
                                area_id
                            from
                                mast_area
                            where
                                area_id = :area_id
                                or parent_area_id = :area_id
                        ) q1
                            on mast_area_match.area_id = q1.area_id
                )
            SQL;
        }

        // 都道府県
        $s_pref_id = '';
        if (array_key_exists('pref_id', $aa_conditions)) {
            $s_pref_id = ' and mast_pref.pref_id = :pref_id';
        }

        // 取り除く都道府県ID
        if (array_key_exists('not_in_by_pref_id', $aa_conditions)) {
            $s_not_in_by_pref_id = ' and mast_pref.pref_id not in(';

            for ($n_cnt = 0; $n_cnt < count($aa_conditions['not_in_by_pref_id']); $n_cnt++) {
                $s_not_in_by_pref_id .= ':pref_id' . $n_cnt . ', ';
                $aa_conditions['pref_id' . $n_cnt] = $aa_conditions['not_in_by_pref_id'][$n_cnt];
            }

            $s_not_in_by_pref_id = substr($s_not_in_by_pref_id, 0, -2);
            $s_not_in_by_pref_id .= ')';
            unset($aa_conditions['not_in_by_pref_id']);
        }

        $s_sql = <<<SQL
            select
                mast_pref.pref_id,
                mast_pref.region_id,
                mast_pref.pref_nm,
                mast_pref.pref_ns,
                mast_pref.order_no,
                mast_pref.pref_cd,
                date_format(mast_pref.delete_ymd, '%Y-%m-%d') as delete_ymd
            from
                mast_pref
            where 1 = 1
                {$s_region_id}
                {$s_area_id}
                {$s_pref_id}
                {$s_not_in_by_pref_id}
            order by
                mast_pref.{$as_order}
        SQL;

        // データの取得
        return DB::select($s_sql, $aa_conditions);
    }
    /**
     * 都道府県して、地域一覧を取得します。
     *
     * MEMO: 移植元 public\app\_common\models\Core\Mast.php get_mast_place
     *
     *   aa_conditions
     *     pref_id   都道府県ID
     *
     * @param [type] $aa_conditions
     * @return stdClass[]
     */
    public function getMastPlace($aa_conditions): array
    {
        // HACK: magic number?

        // 都道府県ID
        $s_pref_id = '';
        if (array_key_exists('pref_id', $aa_conditions)) {
            $s_pref_id = '  and mast_area_match.pref_id = :pref_id';
        }

        // データの取得
        // 3:小エリア
        $s_sql =
            <<<SQL
                select
                    mast_area.area_id,
                        mast_area.parent_area_id,
                        mast_area.area_nm,
                        mast_area.area_type
                from
                    mast_area
                    inner join (
                        select
                            area_id
                        from
                            mast_area_match
                        where
                            null is null
                            {$s_pref_id}
                    ) q1
                    on mast_area.parent_area_id = q1.area_id
                where
                    mast_area.area_type = 3
                order by
                    mast_area.order_no
            SQL;
        $a_place3 = DB::select($s_sql, $aa_conditions);

        $a_places = [];
        // HACK: foreach のほうがシンプルに思われる。(工数次第)
        for ($n_type3 = 0; $n_type3 < count($a_place3); $n_type3++) {
            // 4:細エリア
            $a_places4 = [];
            $s_sql = <<<SQL
                select
                    mast_area.area_id,
                    mast_area.parent_area_id,
                    mast_area.area_nm,
                    mast_area.area_type
                from
                    mast_area
                where
                    mast_area.parent_area_id = :parent_area_id
                    and mast_area.area_type = 4
                order by
                    mast_area.order_no
            SQL;

            $a_place4 = DB::select($s_sql, ['parent_area_id' => $a_place3[$n_type3]['area_id']]);
            // HACK: foreach のほうがシンプルに思われる。(工数次第)
            for ($n_type4 = 0; $n_type4 < count($a_place4); $n_type4++) {
                $a_places4[] = [
                    'place' => 's' . $a_place4[$n_type4]['area_id'],
                    'place_nm' => $a_place4[$n_type4]['area_nm'],
                ];
            }

            // エリア追加
            $a_places[] = [
                'place'    => 'm' . $a_place3[$n_type3]['area_id'],
                'place_nm' => $a_place3[$n_type3]['area_nm'],
                'type_4'   => $a_places4,
            ];
        }

        return $a_places;
    }

    /**
     * 注目文言取得
     *
     * MEMO: 移植元 public\app\rsv\models\TopModel.php set_attention()
     *
     * @return array
     */
    public function getAttention(): array
    {
        $s_sql = <<<SQL
            select
                attention_id,
                display_status
            from (
                select
                    attention_id,
                    start_date,
                    display_flag,
                    display_status
                from
                    top_attention
                where
                    display_flag = 1
                    and start_date <= now()
                order by
                    start_date desc
            ) as q
            limit 1
        SQL;
        $a_attention = DB::select($s_sql, []);

        $attention_id = $a_attention[0]->attention_id;
        $display_status = $a_attention[0]->display_status;
        $send_param = [
            'attention_id' => $attention_id,
            'display_status' => $display_status,
        ];

        $s_sql = <<<SQL
            select
                attention_detail_id,
                order_no,
                word,
                url,
                jwest_word,
                jwest_url
            from
                top_attention_detail
            where
                attention_id = :attention_id
                and order_no <= :display_status
            order by
                order_no asc
        SQL;
        $a_attention_detail = DB::select($s_sql, $send_param);

        return [
            'display_attention' => $a_attention_detail,
            'display_status' => $display_status,
        ];
    }
}
