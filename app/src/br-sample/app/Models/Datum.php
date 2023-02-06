<?php

namespace App\Models;
namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 *
 */
class Datum extends CommonDBModel
{
    use Traits;

    /**
     * コンストラクタ
     */
    public function __construct() //function→public functionでいいか？(phpcs赤エラー)
    {
        // // カラム情報の設定
    }

    // 施設ごとの部屋料金登録情報を取得
    //
    public function getRoomDemand($as_relation_cd)
    {
        try {
            $s_sql =
            <<<SQL
select	q20.hotel_cd,
		q20.open_ymd as open_ymd, -- to_char削除でいいか？'yyyy/mm/dd'
		q20.hotel_nm,
		q20.pref_cd || ' ' || q20.pref_nm as pref_nm,
		q20.city_nm,
		q20.area_nm,
		q20.person_post,
		q20.person_nm,
		q20.person_tel,
		q20.person_fax,
		case
			when q20.hotel_category = 'a' then 'カプセルホテル'
			when q20.hotel_category = 'b' then 'ビジネスホテル'
			when q20.hotel_category = 'c' then 'シティホテル'
			when q20.hotel_category = 'j' then '旅館'
		end hotel_category,
		q20.management_status,
		case when q20.version = 1 then '旧' else '新' end  ||  -- 1:Ver1 2:Ver2
		case when q20.complete_status = 1 then '[マイグレーション済]' else '[マイグレーション未]' end as migration_status,
		q20.system_rate,
		q20.system_rate_out,
		case
			when q20.judge_status = 0 then '未契約'
			when q20.judge_status = 1 then '契約OK'
			when q20.judge_status = 2 then '未契約'
			when q20.judge_status = 3 then '解約'
			when q20.judge_status = 4 then '未契約'
			else '不明'
		end as judge_status,
		case when q20.jetstar_status = 1 then '掲載可' else '否' end as jetstar_status,
		case when q20.msd_status = 1 then '掲載可' else '否' end as msd_status,
		case when q20.extend_status = 1 then '延長ON' else '延長OFF' end as extend_status,
		q20.after_months,
		q21.valid_room_on_cnt,
		q21.valid_charge_on_cnt,
		q21.invalid_room_off_cnt,
		q21.invalid_charge_off_cnt,
		case when q20.accept_status = 0 then '停止中' else '受付中' end as accept_status, -- 0:停止中 1:受付中
		case when q20.accept_auto = 0 then 'ON' else 'OFF' end as accept_auto, -- 0:自動更新 1:手動更新
		q20.accept_dtm as accept_dtm, -- to_char削除でいいか？'yyyy/mm/dd hh24:mi'
		ifNull(q22.month_01, '×') as month_01,
		ifNull(q22.month_02, '×') as month_02,
		ifNull(q22.month_03, '×') as month_03,
		ifNull(q22.month_04, '×') as month_04,
		ifNull(q22.month_05, '×') as month_05,
		ifNull(q22.month_06, '×') as month_06,
		ifNull(q22.month_07, '×') as month_07,
		ifNull(q22.month_08, '×') as month_08,
		ifNull(q22.month_09, '×') as month_09,
		ifNull(q22.month_10, '×') as month_10,
		ifNull(q22.month_11, '×') as month_11,
		ifNull(q22.month_12, '×') as month_12,
		ifNull(q22.month_13, '×') as month_13,
		c.customer_id,
		c.customer_nm
from
	(
		select	q10.hotel_cd,
				q10.open_ymd,
				q10.hotel_nm,
				q10.pref_cd,
				q10.pref_nm,
				q10.city_cd,
				q10.city_nm,
				q10.person_post,
				q10.person_nm,
				q10.person_tel,
				q10.person_fax,
				q10.management_status,
				mast_area.area_id,
				mast_area.area_nm,
				migration.complete_status, -- 0:未完 1:完了
				hotel_system_version.version, -- 1:Ver1 2:Ver2
				q13.system_rate,
				q13.system_rate_out,
				hotel_status_jr.judge_status,
				case when q11.hotel_cd is null then 1 else 0 end as jetstar_status,
				case when q12.hotel_cd is null then 1 else 0 end as msd_status,
				q10.accept_status, -- 0:停止中 1:受付中
				q10.accept_auto, -- 0:自動更新 1:手動更新
				q10.accept_dtm,
				q10.hotel_category,
				ifNull(extend_switch.extend_status, 0) as extend_status,
				extend_setting.after_months
		from	-- mast_area,
		-- 		migration,
				hotel_system_version,
		-- 		hotel_status_jr,
		-- 		extend_switch,
		--      extend_setting,
			(
				select	q3.hotel_cd,
						q3.open_ymd,
						q3.hotel_nm,
						mast_pref.pref_cd,
						mast_pref.pref_nm,
						mast_city.city_cd,
						mast_city.city_nm,
						hotel_person.person_post,
						hotel_person.person_nm,
						hotel_person.person_tel,
						hotel_person.person_fax,
						hotel_control.management_status, -- 1:ファックス管理 2:インターネット管理 3:ファックス管理＋インターネット管理
						q3.accept_status, -- 0:停止中 1:受付中
						q3.accept_auto, -- 0:自動更新 1:手動更新
						q3.accept_dtm,
						q3.hotel_category,
						max(m1.area_id) as area_id
				from	hotel_person,
						hotel_control,
						mast_pref,
						mast_city,
						hotel_area m1
                        right outer join
					(
						select	q2.hotel_cd,
								q2.open_ymd,
								q2.hotel_nm,
								q2.hotel_category,
								q2.accept_status, -- 0:停止中 1:受付中
								q2.accept_auto, -- 0:自動更新 1:手動更新
								q2.accept_dtm,
								q2.pref_id,
								q2.city_id,
								min(hotel_area.entry_no) as entry_no
						from	hotel_area
                        right outer join
							(
								select	hotel.hotel_cd,
										q1.open_ymd,
										hotel.hotel_nm,
										hotel.hotel_category,
										hotel.accept_status, -- 0:停止中 1:受付中
										hotel.accept_auto, -- 0:自動更新 1:手動更新
										hotel.accept_dtm,
										hotel.pref_id,
										hotel.city_id
								from	hotel,
									(
										select	hotel_cd,
												open_ymd
										from	hotel_status
										where	entry_status = 0
											and hotel_cd > '2000050000'
									) q1
								where	hotel.hotel_cd = q1.hotel_cd
							) q2
						on hotel_area.hotel_cd  = q2.hotel_cd
							and	hotel_area.area_type = 2
							and	hotel_area.area_id   = (
                        -- where	hotel_area.hotel_cd(+)  = q2.hotel_cd
						-- 	and	hotel_area.area_type(+) = 2
						-- 	and	hotel_area.area_id(+)   = (
								case 
									when q2.pref_id in (16,17,18) then	q2.pref_id + 14
									when  q2.pref_id in (19, 20) then	q2.pref_id + 9
									else								q2.pref_id + 12
								end)
						group by q2.hotel_cd,
								q2.open_ymd,
								q2.hotel_nm,
								q2.hotel_category,
								q2.accept_status,
								q2.accept_auto,
								q2.accept_dtm,
								q2.pref_id,
								q2.city_id
					) q3
                    on m1.hotel_cd = q3.hotel_cd
                     and m1.entry_no = q3.entry_no
				where	hotel_person.hotel_cd = q3.hotel_cd
					and	hotel_control.hotel_cd = q3.hotel_cd
					and	hotel_control.stock_type = 0
					and	mast_pref.pref_id = q3.pref_id
					and	mast_city.pref_id = q3.pref_id
					and	mast_city.city_id = q3.city_id
					-- and	m1.hotel_cd(+) = q3.hotel_cd
					-- and	m1.entry_no(+) = q3.entry_no
				group by q3.hotel_cd,
						q3.open_ymd,
						q3.hotel_nm,
						mast_pref.pref_cd,
						mast_pref.pref_nm,
						mast_city.city_cd,
						mast_city.city_nm,
						hotel_person.person_post,
						hotel_person.person_nm,
						hotel_person.person_tel,
						hotel_person.person_fax,
						hotel_control.management_status,
						q3.accept_status,
						q3.accept_auto,
						q3.accept_dtm,
						q3.hotel_category
			) q10
            left outer join mast_area
                on	q10.area_id = mast_area.area_id
            left outer join migration
                on q10.hotel_cd = migration.hotel_cd
            left outer join hotel_status_jr
                on q10.hotel_cd = hotel_status_jr.hotel_cd
            left outer join extend_switch
                on q10.hotel_cd = extend_switch.hotel_cd
            left outer join extend_setting
                on q10.hotel_cd = extend_setting.hotel_cd               
			left outer join
			( -- JetStar
				select	distinct
						hotel_cd
				from	deny_list
				where	partner_cd = '3016007888'
			) q11
            on	q10.hotel_cd = q11.hotel_cd
            left outer join
			( -- MSD
				select	distinct
						hotel_cd
				from	deny_list
				where	partner_cd = '2000005100'
			) q12
            on	q10.hotel_cd = q12.hotel_cd
            left outer join
			( -- Rate
				select	hotel_rate.hotel_cd,
						hotel_rate.system_rate,
						hotel_rate.system_rate_out
				from	hotel_rate,
						(
							select	hotel_cd,
									max(accept_s_ymd) as accept_s_ymd
							from	hotel_rate
							group by hotel_cd
						) q1
				where	hotel_rate.hotel_cd= q1.hotel_cd
					and	hotel_rate.accept_s_ymd = q1.accept_s_ymd
			) q13
            on	q10.hotel_cd = q13.hotel_cd
			left outer join
			( -- 春秋航空
				select	distinct
						hotel_cd
				from	deny_list
				where	partner_cd = '3018009900'
			) q14
            on	q10.hotel_cd = q14.hotel_cd
        where q10.hotel_cd = hotel_system_version.hotel_cd
		-- where	q10.area_id = mast_area.area_id(+)
		-- 	and	q10.hotel_cd = hotel_system_version.hotel_cd
		-- 	and	q10.hotel_cd = migration.hotel_cd(+)
		-- 	and	q10.hotel_cd = hotel_status_jr.hotel_cd(+)
		-- 	and	q10.hotel_cd = q11.hotel_cd(+)
		-- 	and	q10.hotel_cd = q12.hotel_cd(+)
		-- 	and	q10.hotel_cd = q13.hotel_cd(+)
		-- 	and	q10.hotel_cd = q14.hotel_cd(+)
		-- 	and	q10.hotel_cd = extend_switch.hotel_cd(+)
		-- 	and	q10.hotel_cd = extend_setting.hotel_cd(+)
	) q20
    left outer join -- 追記場所ここでいいか
	(
		select	hotel_cd,
				count(distinct rooms_on )   as valid_room_on_cnt,
				count(distinct charge_on )  as valid_charge_on_cnt,
				count(distinct rooms_off )  as invalid_room_off_cnt,
				count(distinct charge_off ) as invalid_charge_off_cnt
		from	(
					select	q0.hotel_cd,
							q0.plan_id,
							q0.room_id,
							case when q0.extend_status = 1
								and	(
										rooms_sun is not null
								 	or	rooms_mon is not null
								 	or	rooms_tue is not null
								 	or	rooms_wed is not null
								 	or	rooms_thu is not null
								 	or	rooms_fri is not null
								 	or	rooms_sat is not null
								 	or	rooms_hol is not null
								 	or	rooms_bfo is not null
								 ) then q0.room_id
								 else null
							end as rooms_on,
							case when q0.extend_status = 0 then q0.room_id
								 when
									(
										rooms_sun is not null
								 	or	rooms_mon is not null
								 	or	rooms_tue is not null
								 	or	rooms_wed is not null
								 	or	rooms_thu is not null
								 	or	rooms_fri is not null
								 	or	rooms_sat is not null
								 	or	rooms_hol is not null
								 	or	rooms_bfo is not null
								 ) then null
								 else q0.room_id
							end as rooms_off,
							case when q0.extend_status = 1
								and	(
										sales_charge_sun is not null
									or	sales_charge_mon is not null
									or	sales_charge_tue is not null
									or	sales_charge_wed is not null
									or	sales_charge_thu is not null
									or	sales_charge_fri is not null
									or	sales_charge_sat is not null
									or	sales_charge_hol is not null
									or	sales_charge_bfo is not null
								) then q0.plan_id
								else null
							end as charge_on,
							case when q0.extend_status = 0 then q0.plan_id
								 when
									(
										sales_charge_sun is not null
									or	sales_charge_mon is not null
									or	sales_charge_tue is not null
									or	sales_charge_wed is not null
									or	sales_charge_thu is not null
									or	sales_charge_fri is not null
									or	sales_charge_sat is not null
									or	sales_charge_hol is not null
									or	sales_charge_bfo is not null
									) then null
								else q0.plan_id end as charge_off
					from	(
								select	room_plan_match.hotel_cd,
										room_plan_match.room_id,
										room_plan_match.plan_id,
										ifNull(extend_switch_plan2.extend_status, 0) as extend_status
								from	room2,
										plan,
										plan_partner_group,
                                        room_plan_match
                                left outer join extend_switch_plan2
                                on 	room_plan_match.hotel_cd  = extend_switch_plan2.hotel_cd
									and	room_plan_match.room_id   = extend_switch_plan2.room_id
									and	room_plan_match.plan_id   = extend_switch_plan2.plan_id
								where	room_plan_match.hotel_cd  = room2.hotel_cd
									and	room_plan_match.room_id   = room2.room_id
									and	room_plan_match.hotel_cd  = plan.hotel_cd
									and	room_plan_match.plan_id   = plan.plan_id
									and	room_plan_match.hotel_cd  = plan_partner_group.hotel_cd
									and	room_plan_match.plan_id   = plan_partner_group.plan_id
									and	plan_partner_group.partner_group_id = 0
									and	room2.display_status      = 1
									and	room2.active_status       = 1
									and	plan.display_status       = 1
									and	plan.active_status        = 1
									-- and	room_plan_match.hotel_cd  = extend_switch_plan2.hotel_cd(+)
									-- and	room_plan_match.room_id   = extend_switch_plan2.room_id(+)
									-- and	room_plan_match.plan_id   = extend_switch_plan2.plan_id(+)
							) q0
                            left outer join room_count_initial2
                                on q0.hotel_cd    = room_count_initial2.hotel_cd
                                and	q0.room_id     = room_count_initial2.room_id
                            left outer join charge_initial
                                on q0.hotel_cd    = charge_initial.hotel_cd
                                and q0.room_id     = charge_initial.room_id
                                and q0.plan_id     = charge_initial.plan_id
					-- where	q0.hotel_cd    = room_count_initial2.hotel_cd(+)
					-- 	and	q0.room_id     = room_count_initial2.room_id(+)
					-- 	and	q0.hotel_cd    = charge_initial.hotel_cd(+)
					-- 	and	q0.room_id     = charge_initial.room_id(+)
					-- 	and	q0.plan_id     = charge_initial.plan_id(+)
				) as b -- 追記,Every derived table must have its own alias対応
		group by hotel_cd
	) q21
    on	q20.hotel_cd = q21.hotel_cd
    left outer join
	(
		select	hotel_cd,
				case when sum(ifNull(month_01, 0)) = 0 then '×'
					 when count(month_01) = sum(ifNull(month_01, 0)) then '△'
					 else '○'
				end as month_01,
				case when sum(ifNull(month_02, 0)) = 0 then '×'
					 when count(month_02) = sum(ifNull(month_02, 0)) then '△'
					 else '○'
				end as month_02,
				case when sum(ifNull(month_03, 0)) = 0 then '×'
					 when count(month_03) = sum(ifNull(month_03, 0)) then '△'
					 else '○'
				end as month_03,
				case when sum(ifNull(month_04, 0)) = 0 then '×'
					 when count(month_04) = sum(ifNull(month_04, 0)) then '△'
					 else '○'
				end as month_04,
				case when sum(ifNull(month_05, 0)) = 0 then '×'
					 when count(month_05) = sum(ifNull(month_05, 0)) then '△'
					 else '○'
				end as month_05,
				case when sum(ifNull(month_06, 0)) = 0 then '×'
					 when count(month_06) = sum(ifNull(month_06, 0)) then '△'
					 else '○'
				end as month_06,
				case when sum(ifNull(month_07, 0)) = 0 then '×'
					 when count(month_07) = sum(ifNull(month_07, 0)) then '△'
					 else '○'
				end as month_07,
				case when sum(ifNull(month_08, 0)) = 0 then '×'
					 when count(month_08) = sum(ifNull(month_08, 0)) then '△'
					 else '○'
				end as month_08,
				case when sum(ifNull(month_09, 0)) = 0 then '×'
					 when count(month_09) = sum(ifNull(month_09, 0)) then '△'
					 else '○'
				end as month_09,
				case when sum(ifNull(month_10, 0)) = 0 then '×'
					 when count(month_10) = sum(ifNull(month_10, 0)) then '△'
					 else '○'
				end as month_10,
				case when sum(ifNull(month_11, 0)) = 0 then '×'
					 when count(month_11) = sum(ifNull(month_11, 0)) then '△'
					 else '○'
				end as month_11,
				case when sum(ifNull(month_12, 0)) = 0 then '×'
					 when count(month_12) = sum(ifNull(month_12, 0)) then '△'
					 else '○'
				end as month_12,
				case when sum(ifNull(month_13, 0)) = 0 then '×'
					 when count(month_13) = sum(ifNull(month_13, 0)) then '△'
					 else '○'
				end as month_13
		from	(
					select	charge_condition.hotel_cd,
                            -- 元ソース to_number(replace(substr(sales_term, case when sales_ym = trunc(sysdate, 'mm') then  1 else  2 end, 1), '0', null)) as month_01,
                            -- to_number削除,trunc(now(), 'mm')→date_format(now(),'%Y-%m-01')
                            -- replaceの第3引数がnullだとnullしか返さないため、''でいいか？
							replace(substr(sales_term, case when sales_ym = date_format(now(), '%Y-%m-01') then  1 else  2 end, 1), '0', '') as month_01,
							replace(substr(sales_term, case when sales_ym = date_format(now(), '%Y-%m-01') then  2 else  3 end, 1), '0', '') as month_02,
							replace(substr(sales_term, case when sales_ym = date_format(now(), '%Y-%m-01') then  3 else  4 end, 1), '0', '') as month_03,
							replace(substr(sales_term, case when sales_ym = date_format(now(), '%Y-%m-01') then  4 else  5 end, 1), '0', '') as month_04,
							replace(substr(sales_term, case when sales_ym = date_format(now(), '%Y-%m-01') then  5 else  6 end, 1), '0', '') as month_05,
							replace(substr(sales_term, case when sales_ym = date_format(now(), '%Y-%m-01') then  6 else  7 end, 1), '0', '') as month_06,
							replace(substr(sales_term, case when sales_ym = date_format(now(), '%Y-%m-01') then  7 else  8 end, 1), '0', '') as month_07,
							replace(substr(sales_term, case when sales_ym = date_format(now(), '%Y-%m-01') then  8 else  9 end, 1), '0', '') as month_08,
							replace(substr(sales_term, case when sales_ym = date_format(now(), '%Y-%m-01') then  9 else 10 end, 1), '0', '') as month_09,
							replace(substr(sales_term, case when sales_ym = date_format(now(), '%Y-%m-01') then 10 else 11 end, 1), '0', '') as month_10,
							replace(substr(sales_term, case when sales_ym = date_format(now(), '%Y-%m-01') then 11 else 12 end, 1), '0', '') as month_11,
							replace(substr(sales_term, case when sales_ym = date_format(now(), '%Y-%m-01') then 12 else 13 end, 1), '0', '') as month_12,
							replace(substr(sales_term, case when sales_ym = date_format(now(), '%Y-%m-01') then 13 else 14 end, 1), '0', '') as month_13
					from	charge_condition,
							plan_partner_group
					where	charge_condition.hotel_cd = plan_partner_group.hotel_cd
						and	charge_condition.plan_id  = plan_partner_group.plan_id
						and	plan_partner_group.partner_group_id = 0
				) as b -- 追記,Every derived table must have its own alias対応
		group by hotel_cd
	) q22
    on q20.hotel_cd = q22.hotel_cd
    left outer join 
    (
        customer_hotel ch
        left outer join customer c
         on ch.customer_id = c.customer_id
    )
    on q20.hotel_cd = ch.hotel_cd
    
-- customer_hotel ch,
-- customer c
-- where	q20.hotel_cd = q21.hotel_cd(+)
-- 	and	q20.hotel_cd = q22.hotel_cd(+)
--     and q20.hotel_cd = ch.hotel_cd(+)
-- 	and ch.customer_id = c.customer_id(+)
order by q20.hotel_cd
SQL;

            return [
                'values'     => DB::select($s_sql, [])
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }


    //======================================================================
    // CSVヘッダー設定 ※モデルへの記述でいい？
    //======================================================================
    public function setCsvHeader()
    {
        $header = [
            "施設コード", "施設名", "公開日", "都道府県", "市", "エリア",
            "管理方法", "部署・役職", "担当者", "担当者電話番号", "担当者ファックス番号",
            "カテゴリ", "管理画面", "BRサイト料率", "他サイト料率", "JRC契約審査", "JETStar契約", "MSDの可否",
            "自動延長", "自動延長タイミング", "自動延長可能部屋数", "自動延長可能プラン数", "自動延長不可部屋数", "自動延長不可プラン数",
            "受付状態", "受付変更日時", "受付夜間解除",
            date('Y/m'),
            date('Y/m', strtotime('+1 month')),
            date('Y/m', strtotime('+2 month')),
            date('Y/m', strtotime('+3 month')),
            date('Y/m', strtotime('+4 month')),
            date('Y/m', strtotime('+5 month')),
            date('Y/m', strtotime('+6 month')),
            date('Y/m', strtotime('+7 month')),
            date('Y/m', strtotime('+8 month')),
            date('Y/m', strtotime('+9 month')),
            date('Y/m', strtotime('+10 month')),
            date('Y/m', strtotime('+11 month')),
            date('Y/m', strtotime('+12 month')),
            "請求連番", "精算先名称"
        ];

        return $header;
    }

    //======================================================================
    // CSVデータ設定 ※モデルへの記述でいい？
    //======================================================================
    public function setCsvData($room_demand_list)
    {
        $data = [];

        foreach ($room_demand_list as $value) {
            $string = [
                /* 施設コード */$value->hotel_cd,
                /* 施設名 */ $value->hotel_nm,
                /* 公開日 */ $value->open_ymd,
                /*都道府県 */ $value->pref_nm,
                /*市 */ $value->city_nm
            ];


            /*エリア  */
            if ($this->is_empty($value->area_nm)) {
                array_push($string, "設定なし");
            } else {
                array_push($string, $value->area_nm);
            }
            /*管理方法    */
            if ($value->management_status == '1') {
                array_push($string, "ファックス管理");
            } elseif ($value->management_status == '2') {
                array_push($string, "インターネット管理");
            } elseif ($value->management_status == '3') {
                array_push($string, "ファックス管理＋インターネット管理");
            }
            $string = array_merge($string, [
                /* 部署・役職 */ $value->person_post,
                /* 担当者 */ $value->person_nm,
                /* 担当者電話番号 */ $value->person_tel,
                /* 担当者ファックス番号 */ $value->person_fax,
                /* カテゴリ */ $value->hotel_category,
                /* 管理画面 */ $value->migration_status,
                /* BRサイト料率 */ $value->system_rate,
                /* 他サイト料率 */ $value->system_rate_out,
                /* JRC契約審査 */ $value->judge_status,
                /* JETStar契約*/ $value->jetstar_status,
                /* MSDの可否 */ $value->msd_status,
                /* 自動延長 */ $value->extend_status,
                /* 自動延長タイミング */ $value->after_months,
                /* 自動延長可能部屋数 */ $value->valid_room_on_cnt,
                /* 自動延長可能プラン数 */ $value->valid_charge_on_cnt,
                /* 自動延長不可部屋数 */ $value->invalid_room_off_cnt,
                /* 自動延長不可プラン数 */ $value->invalid_charge_off_cnt,
                /* 受付状態 */ $value->accept_status,
                /* 受付変更日時 */ $value->accept_dtm,
                /* 受付夜間解除 */ $value->accept_auto,
                /* ym1 */ $value->month_01,
                /* ym2 */ $value->month_02,
                /* ym3 */ $value->month_03,
                /* ym4 */ $value->month_04,
                /* ym5 */ $value->month_05,
                /* ym6 */ $value->month_06,
                /* ym7 */ $value->month_07,
                /* ym8 */ $value->month_08,
                /* ym9 */ $value->month_09,
                /* ym10*/ $value->month_10,
                /* ym11*/ $value->month_11,
                /* ym12*/ $value->month_12,
                /* ym13*/ $value->month_13,
                /* 請求連番   */ $value->customer_id,
                /* 精算先名称  */ $value->customer_nm
            ]);

            $data[] = $string;
        }

        return $data;
    }
}
