<?php

namespace App\Models;

	class Core_ChargeCondition {

		private $box          = null;    // ボックス
		public  $b_debug      = false;   // デバック

		// コンストラクタ
		//
		function __construct(){

			$o_controller = Zend_Controller_Front::getInstance();
			$this->box  = & $o_controller->getPlugin('Box')->box;
		}

		// ホテル、プラン、部屋単位で料金登録状況設定
		// aa_condition
		//   hotel_cd
		//   plan_id
		//   room_id
		//
		public function set_charge($aa_condition)
		{

if ($this->b_debug) {
	print_r(date('Y-m-d H:i:s') . ':start:' . $aa_condition['hotel_cd'] . ',' . $aa_condition['plan_id'] . ',' . $aa_condition['room_id']. "\n");
}
			try{

					$s_sql =
<<<SQL
declare
	v_hotel_cd       varchar2(32767) := '{$aa_condition['hotel_cd']}';
	v_plan_id        varchar2(32767) := '{$aa_condition['plan_id']}';
	v_room_id        varchar2(32767) := '{$aa_condition['room_id']}';
	v_action_cd      varchar2(32767) := '{$this->box->info->env->action_cd}';
	n_pref_id        number;
	n_stock_type     number;
	n_power_down     number := 0;
	n_tax            number := 8; -- 消費税率
	r_condition      charge_condition%rowtype := null;
	n_min            number := 0;
	d_start          date   := sysdate;
	d_sysdate        date   := trunc(sysdate, 'dd');
	type p_down_s   is record(
		charge       number,
		start_ymd    date,
		end_ymd      date
	);
	pr_down_s        p_down_s;
	type ptr_down_s  is table of p_down_s;
	ptrl_down_s	     ptr_down_s := ptr_down_s();
	n_down_s         number;

	function modify_condition(
		ir_charge_condition   in charge_condition%rowtype
	)return boolean
	is
		n_charge number;
	begin

		update charge_condition
			set
				sales_charge_min = ir_charge_condition.sales_charge_min,
				sales_charge_max = ir_charge_condition.sales_charge_max,
				sales_excluding_tax_charge_min = ir_charge_condition.sales_excluding_tax_charge_min,
				sales_excluding_tax_charge_max = ir_charge_condition.sales_excluding_tax_charge_max,
				vacant_min       = ir_charge_condition.vacant_min,
				vacant_max       = ir_charge_condition.vacant_max,
				rate             = ir_charge_condition.rate,
				sales_ym         = ir_charge_condition.sales_ym,
				sales_term       = ir_charge_condition.sales_term,
				date_s_ymd       = ir_charge_condition.date_s_ymd,
				date_e_ymd       = ir_charge_condition.date_e_ymd,
				accept_s_ymd     = ir_charge_condition.accept_s_ymd,
				modify_cd        = ir_charge_condition.modify_cd,
				modify_ts        = ir_charge_condition.modify_ts
		where
				hotel_cd         = ir_charge_condition.hotel_cd
			and	plan_id          = ir_charge_condition.plan_id
			and	room_id          = ir_charge_condition.room_id
			and	capacity         = ir_charge_condition.capacity
			and	login_condition  = ir_charge_condition.login_condition
		;

	if (SQL%ROWCOUNT = 0)
	then

		insert into charge_condition (
			hotel_cd,
			plan_id,
			room_id,
			capacity,
			login_condition,
			sales_charge_min,
			sales_charge_max,
			sales_excluding_tax_charge_min,
			sales_excluding_tax_charge_max,
			vacant_min,
			vacant_max,
			rate,
			sales_ym,
			sales_term,
			date_s_ymd,
			date_e_ymd,
			accept_s_ymd,
			entry_cd,
			entry_ts,
			modify_cd,
			modify_ts
		)
		values(
			ir_charge_condition.hotel_cd,
			ir_charge_condition.plan_id,
			ir_charge_condition.room_id,
			ir_charge_condition.capacity,
			ir_charge_condition.login_condition,
			ir_charge_condition.sales_charge_min,
			ir_charge_condition.sales_charge_max,
			ir_charge_condition.sales_excluding_tax_charge_min,
			ir_charge_condition.sales_excluding_tax_charge_max,
			ir_charge_condition.vacant_min,
			ir_charge_condition.vacant_max,
			ir_charge_condition.rate,
			ir_charge_condition.sales_ym,
			ir_charge_condition.sales_term,
			ir_charge_condition.date_s_ymd,
			ir_charge_condition.date_e_ymd,
			ir_charge_condition.accept_s_ymd,
			ir_charge_condition.entry_cd,
			ir_charge_condition.entry_ts,
			ir_charge_condition.modify_cd,
			ir_charge_condition.modify_ts
		);

	end if;

	return true;

	end;

begin
	dbms_output.enable (200000);

	select stock_type into n_stock_type from hotel_control where hotel_cd = v_hotel_cd;

	-- 0時から6時までの間は前日の宿泊日を対象とする。
	if (to_char(d_start, 'hh24') between '00' and '05')
	then
		d_sysdate := d_sysdate - 1;
	end if;

	-- パワーダウンチャージ取得
    if (n_stock_type = 1)
    then
		select pref_id    into n_pref_id    from hotel         where hotel_cd = v_hotel_cd;
    	for r_power in (
			select powerdown_charge
			from	(
					select	powerdown_charge
					from	hotel_powerdown
					where	hotel_cd      = v_hotel_cd
						and	target_s_ymd <= d_sysdate
					order by target_s_ymd desc
					)
			where	rownum = 1
		)
		loop
			n_power_down := r_power.powerdown_charge;
		end loop;
    end if;

	for r_rec in (
		select
					hotel_cd,
					plan_id,
					room_id,
					capacity,
					min(sales_charge)                                                                                                             as sales_charge_min,
					max(sales_charge)                                                                                                             as sales_charge_max,
					min(vacant)                                                                                                                   as vacant_min,
					max(vacant)                                                                                                                   as vacant_max,
					max(case when usual_charge > 0 and sales_charge < usual_charge then 100 - ceil(sales_charge / usual_charge * 100) else 0 end) as rate,
					to_char(trunc(d_sysdate, 'mm'), 'yyyy-mm-dd')                                                                                 as sales_ym,
					max(case when date_ymd between trunc(d_sysdate, 'mm')               and last_day(trunc(d_sysdate, 'mm'))                 then sales_condition else '0' end)  ||
					max(case when date_ymd between add_months(trunc(d_sysdate, 'mm'),  1) and last_day(add_months(trunc(d_sysdate, 'mm'),  1)) then sales_condition else '0' end)  ||
					max(case when date_ymd between add_months(trunc(d_sysdate, 'mm'),  2) and last_day(add_months(trunc(d_sysdate, 'mm'),  2)) then sales_condition else '0' end)  ||
					max(case when date_ymd between add_months(trunc(d_sysdate, 'mm'),  3) and last_day(add_months(trunc(d_sysdate, 'mm'),  3)) then sales_condition else '0' end)  ||
					max(case when date_ymd between add_months(trunc(d_sysdate, 'mm'),  4) and last_day(add_months(trunc(d_sysdate, 'mm'),  4)) then sales_condition else '0' end)  ||
					max(case when date_ymd between add_months(trunc(d_sysdate, 'mm'),  5) and last_day(add_months(trunc(d_sysdate, 'mm'),  5)) then sales_condition else '0' end)  ||
					max(case when date_ymd between add_months(trunc(d_sysdate, 'mm'),  6) and last_day(add_months(trunc(d_sysdate, 'mm'),  6)) then sales_condition else '0' end)  ||
					max(case when date_ymd between add_months(trunc(d_sysdate, 'mm'),  7) and last_day(add_months(trunc(d_sysdate, 'mm'),  7)) then sales_condition else '0' end)  ||
					max(case when date_ymd between add_months(trunc(d_sysdate, 'mm'),  8) and last_day(add_months(trunc(d_sysdate, 'mm'),  8)) then sales_condition else '0' end)  ||
					max(case when date_ymd between add_months(trunc(d_sysdate, 'mm'),  9) and last_day(add_months(trunc(d_sysdate, 'mm'),  9)) then sales_condition else '0' end)  ||
					max(case when date_ymd between add_months(trunc(d_sysdate, 'mm'), 10) and last_day(add_months(trunc(d_sysdate, 'mm'), 10)) then sales_condition else '0' end)  ||
					max(case when date_ymd between add_months(trunc(d_sysdate, 'mm'), 11) and last_day(add_months(trunc(d_sysdate, 'mm'), 11)) then sales_condition else '0' end)  ||
					max(case when date_ymd between add_months(trunc(d_sysdate, 'mm'), 12) and last_day(add_months(trunc(d_sysdate, 'mm'), 12)) then sales_condition else '0' end)  as sales_term,
					to_char(min(case when sales_condition in ('1', '2') then date_ymd else null end), 'yyyy-mm-dd') as date_s_ymd,
					to_char(max(case when sales_condition in ('1', '2') then date_ymd else null end), 'yyyy-mm-dd') as date_e_ymd,
					trunc(max(accept_s_dtm), 'dd') as accept_s_ymd
			from	(
						select
								q2.hotel_cd,
								q2.plan_id,
								q2.room_id,
								q2.capacity,
								q2.date_ymd,
								q2.usual_charge,
								case
									when q2.today_unit = 0 then	-- 率
										q2.sales_charge - floor(q2.sales_charge * (q2.today_discount_rate / 100))
									when q2.today_unit = 1 then	-- 金額
										q2.today_discount_charge
									when q2.today_unit = 2 then	-- 差額
										q2.sales_charge - q2.today_discount_charge
									when q2.early_unit = 0 then	-- 率
										trunc(q2.sales_charge - floor(q2.sales_charge * (q2.early_discount_rate / 100)), -1 * log(10, nvl(hotel_control.charge_round, 1)))
									when q2.early_unit = 1 then	-- 金額
										q2.early_discount_charge
									when q2.early_unit = 2 then	-- 差額
										q2.sales_charge - q2.early_discount_charge
									else q2.sales_charge
								end as sales_charge,
								q2.sales_condition, -- 2 在庫あり、 1 満室
								q2.vacant,
								q2.accept_s_dtm
						from
								hotel_control,
								(
									select	/*+ INDEX(charge charge_pky) */
											charge.hotel_cd,
											charge.plan_id,
											charge.room_id,
											charge.capacity,
											charge.date_ymd,
											charge.usual_charge * charge.capacity + charge.usual_charge_revise                as usual_charge,
											charge.sales_charge * charge.capacity + charge.sales_charge_revise                as sales_charge,
											q_today.unit                                                                      as today_unit,
											q_today.discount_rate                                                             as today_discount_rate,
											case when q1.charge_type = 0 then q_today.discount_charge
											     else nvl(q_today.discount_charge, 0) * charge.capacity end                   as today_discount_charge,
											charge_early.unit                                                                 as early_unit,
											charge_early.discount_rate                                                        as early_discount_rate,
											case when q1.charge_type = 0 then charge_early.discount_charge
											     else nvl(charge_early.discount_charge, 0) * charge.capacity end              as early_discount_charge,
											case when v1.vacant > 0 then '2' else '1' end as sales_condition, -- 2 在庫あり、 1 満室
											v1.vacant,
											charge.accept_s_dtm
									from
											(
												select	hotel_cd,
														room_id,
														date_ymd,
														rooms - reserve_rooms as vacant
												from	room_count2
												where	date_ymd  between add_months(d_sysdate, 0) and last_day(add_months(d_sysdate, 12))
													and	 rooms > 0
													and hotel_cd = v_hotel_cd
													and (v_room_id is null or room_id = v_room_id)
													and	accept_status = 1
											) v1,
											charge,
											charge_early,
											(
												select	room_plan_match.hotel_cd,
														room_plan_match.plan_id,
														room_plan_match.room_id,
														plan.charge_type,
														min(plan_partner_group.partner_group_id) as partner_group_id
												from	hotel_status,
														plan,
														plan_partner_group,
														room_plan_match,
														room2
												where	hotel_status.hotel_cd = plan.hotel_cd
													and	plan.hotel_cd = plan_partner_group.hotel_cd
													and	plan.plan_id  = plan_partner_group.plan_id
													and	plan.hotel_cd = room_plan_match.hotel_cd
													and	plan.plan_id  = room_plan_match.plan_id
													and	room_plan_match.hotel_cd  = room2.hotel_cd
													and	room_plan_match.room_id  = room2.room_id
													and	hotel_status.entry_status != 2
													and	plan.display_status = 1
													and	plan.accept_status = 1
													and	room2.display_status = 1
													and	room2.accept_status = 1
													and hotel_status.hotel_cd = v_hotel_cd
													and (v_room_id is null or room2.room_id = v_room_id)
													and (v_plan_id is null or plan.plan_id = v_plan_id)
												group by room_plan_match.hotel_cd,
														room_plan_match.plan_id,
														room_plan_match.room_id,
														plan.charge_type
											) q1,
											(
												select	hotel_cd,
														room_id,
														plan_id,
														partner_group_id,
														capacity,
														date_ymd,
														unit,
														discount_rate,
														discount_charge,
														modify_ts
												from	(
															select
																	charge_today.hotel_cd,
																	charge_today.room_id,
																	charge_today.plan_id,
																	charge_today.partner_group_id,
																	charge_today.capacity,
																	charge_today.date_ymd,
																	charge_today.unit,
																	charge_today.discount_rate,
																	charge_today.discount_charge,
																	rank() over(partition by
																		charge_today.hotel_cd,
																		charge_today.room_id,
																		charge_today.plan_id,
																		charge_today.partner_group_id,
																		charge_today.capacity,
																		charge_today.date_ymd
																		 order by  timetable desc) as rank,
																	charge_today.modify_ts
															from
																	charge_today
															where
																	charge_today.date_ymd   = d_sysdate
																and	charge_today.timetable <= sysdate
																and	charge_today.hotel_cd = v_hotel_cd
																and	(v_room_id is null or charge_today.room_id = v_room_id)
																and	(v_plan_id is null or charge_today.plan_id = v_plan_id)
														)
												where	rank = 1
											) q_today
									where
											q1.hotel_cd             = v1.hotel_cd
										and	q1.room_id              = v1.room_id
										and	q1.hotel_cd             = charge.hotel_cd
										and	q1.plan_id              = charge.plan_id
										and	q1.room_id              = charge.room_id
										and	q1.partner_group_id     = charge.partner_group_id
										and	v1.date_ymd             = charge.date_ymd
										and	charge.hotel_cd         = q_today.hotel_cd(+)
										and	charge.room_id          = q_today.room_id(+)
										and	charge.plan_id          = q_today.plan_id(+)
										and	charge.partner_group_id = q_today.partner_group_id(+)
										and	charge.capacity         = q_today.capacity(+)
										and	charge.date_ymd         = q_today.date_ymd(+)
										and	charge.hotel_cd         = charge_early.hotel_cd(+)
										and	charge.room_id          = charge_early.room_id(+)
										and	charge.plan_id          = charge_early.plan_id(+)
										and	charge.partner_group_id = charge_early.partner_group_id(+)
										and	charge.capacity         = charge_early.capacity(+)
										and	charge.date_ymd         = charge_early.date_ymd(+)
										and	nvl(charge_early.accept_e_ymd(+), d_sysdate) >= d_sysdate
										and	charge.accept_status = 1
										and	sysdate between charge.accept_s_dtm and charge.accept_e_dtm
										and	charge.sales_charge > 0
							) q2
						where	q2.hotel_cd = hotel_control.hotel_cd
					)
			where	sales_charge > 0
			group by hotel_cd,
					plan_id,
					room_id,
					capacity
			order by hotel_cd,
					plan_id,
					room_id,
					capacity
	)
	loop

		r_condition.hotel_cd         := r_rec.hotel_cd        ;
		r_condition.plan_id          := r_rec.plan_id         ;
		r_condition.room_id          := r_rec.room_id         ;
		r_condition.capacity         := r_rec.capacity        ;
		r_condition.sales_charge_min := r_rec.sales_charge_min;
		r_condition.sales_charge_max := r_rec.sales_charge_max;
		r_condition.vacant_min       := r_rec.vacant_min;
		r_condition.vacant_max       := r_rec.vacant_max;
		r_condition.rate             := r_rec.rate            ;
		r_condition.sales_ym         := r_rec.sales_ym        ;
		r_condition.sales_term       := r_rec.sales_term      ;
		r_condition.date_s_ymd       := r_rec.date_s_ymd      ;
		r_condition.date_e_ymd       := r_rec.date_e_ymd      ;
		r_condition.accept_s_ymd     := r_rec.accept_s_ymd    ;
		r_condition.entry_cd         := v_action_cd           ;
		r_condition.entry_ts         := sysdate               ;
		r_condition.modify_cd        := v_action_cd           ;
		r_condition.modify_ts        := sysdate               ;

		-- 税抜料金
		r_condition.sales_excluding_tax_charge_min := ceil(r_condition.sales_charge_min / (n_tax / 100 + 1));
		r_condition.sales_excluding_tax_charge_max := ceil(r_condition.sales_charge_max / (n_tax / 100 + 1));

		-- ハイランク（ログアウト時）
    	if (n_stock_type = 1)
    	then
			r_condition.login_condition := -1;
		else
			r_condition.login_condition :=  0;
		end if;

		if (not(modify_condition(r_condition)))
		then
			return;
		end if;

		-- ハイランク（ログイン時）
    	if (n_stock_type = 1)
    	then

			r_condition.login_condition := 1;

			-- 最低・最大・割引率取得
			for r_power_s in (
				select
					min(sales_charge)                                                                                                             as sales_charge_min,
					max(sales_charge)                                                                                                             as sales_charge_max,
					max(case when usual_charge > 0 and sales_charge < usual_charge then 100 - ceil(sales_charge / usual_charge * 100) else 0 end) as rate
				from	(
							select
									q2.hotel_cd,
									q2.plan_id,
									q2.room_id,
									q2.capacity,
									q2.date_ymd,
									q2.usual_charge,
									case
										when q2.today_unit = 0 then	-- 率
											q2.sales_charge - floor(q2.sales_charge * (q2.today_discount_rate / 100))
										when q2.today_unit = 1 then	-- 金額
											q2.today_discount_charge
										when q2.today_unit = 2 then	-- 差額
											q2.sales_charge - q2.today_discount_charge
										when q2.early_unit = 0 then	-- 率
											trunc(q2.sales_charge - floor(q2.sales_charge * (q2.early_discount_rate / 100)), -1 * log(10, nvl(hotel_control.charge_round, 1)))
										when q2.early_unit = 1 then	-- 金額
											q2.early_discount_charge
										when q2.early_unit = 2 then	-- 差額
											q2.sales_charge - q2.early_discount_charge
										else q2.sales_charge
									end - nvl(q_powerdown.powerdown_charge, 0) - nvl(n_power_down, 0) as sales_charge,
									q2.sales_condition, -- 2 在庫あり、 1 満室
									q2.vacant
							from
									hotel_control,
									(
										select	/*+ INDEX(charge charge_pky) */
												charge.hotel_cd,
												charge.plan_id,
												charge.room_id,
												charge.capacity,
												charge.date_ymd,
												charge.usual_charge * charge.capacity + charge.usual_charge_revise                as usual_charge,
												charge.sales_charge * charge.capacity + charge.sales_charge_revise                as sales_charge,
												q_today.unit                                                                      as today_unit,
												q_today.discount_rate                                                             as today_discount_rate,
												case when q1.charge_type = 0 then q_today.discount_charge
												     else nvl(q_today.discount_charge, 0) * charge.capacity end                   as today_discount_charge,
												charge_early.unit                                                                 as early_unit,
												charge_early.discount_rate                                                        as early_discount_rate,
												case when q1.charge_type = 0 then charge_early.discount_charge
												     else nvl(charge_early.discount_charge, 0) * charge.capacity end              as early_discount_charge,
												case when v1.vacant > 0 then '2' else '1' end as sales_condition, -- 2 在庫あり、 1 満室
												v1.vacant
										from
												(
													select	hotel_cd,
															room_id,
															date_ymd,
															rooms - reserve_rooms as vacant
													from	room_count2
													where	date_ymd  between  add_months(d_sysdate, 0) and last_day(add_months(d_sysdate, 12))
														and	 rooms > 0
														and hotel_cd = r_rec.hotel_cd
														and room_id = r_rec.room_id
														and	accept_status = 1
												) v1,
												charge,
												charge_early,
												(
													select	room_plan_match.hotel_cd,
															room_plan_match.plan_id,
															room_plan_match.room_id,
															plan.charge_type,
															min(plan_partner_group.partner_group_id) as partner_group_id
													from	hotel_status,
															plan,
															plan_partner_group,
															room_plan_match,
															room2
													where	hotel_status.hotel_cd = plan.hotel_cd
														and	plan.hotel_cd = plan_partner_group.hotel_cd
														and	plan.plan_id  = plan_partner_group.plan_id
														and	plan.hotel_cd = room_plan_match.hotel_cd
														and	plan.plan_id  = room_plan_match.plan_id
														and	room_plan_match.hotel_cd  = room2.hotel_cd
														and	room_plan_match.room_id  = room2.room_id
														and	hotel_status.entry_status != 2
														and	plan.display_status = 1
														and	plan.accept_status = 1
														and	room2.display_status = 1
														and	room2.accept_status = 1
														and hotel_status.hotel_cd = r_rec.hotel_cd
														and room2.room_id = r_rec.room_id
														and plan.plan_id = r_rec.plan_id
													group by room_plan_match.hotel_cd,
															room_plan_match.plan_id,
															room_plan_match.room_id,
															plan.charge_type
												) q1,
												(
													select	hotel_cd,
															room_id,
															plan_id,
															partner_group_id,
															capacity,
															date_ymd,
															unit,
															discount_rate,
															discount_charge,
															modify_ts
													from	(
																select
																		charge_today.hotel_cd,
																		charge_today.room_id,
																		charge_today.plan_id,
																		charge_today.partner_group_id,
																		charge_today.capacity,
																		charge_today.date_ymd,
																		charge_today.unit,
																		charge_today.discount_rate,
																		charge_today.discount_charge,
																		rank() over(partition by
																			charge_today.hotel_cd,
																			charge_today.room_id,
																			charge_today.plan_id,
																			charge_today.partner_group_id,
																			charge_today.capacity,
																			charge_today.date_ymd
																			 order by  timetable desc) as rank,
																		charge_today.modify_ts
																from
																		charge_today
																where
																		charge_today.date_ymd   = d_sysdate
																	and	charge_today.timetable <= sysdate
																	and	charge_today.hotel_cd = r_rec.hotel_cd
																	and	charge_today.room_id = r_rec.room_id
																	and	charge_today.plan_id = r_rec.plan_id
																	and	charge_today.capacity = r_rec.capacity
															)
													where	rank = 1
												) q_today
									where
												q1.hotel_cd             = v1.hotel_cd
											and	q1.room_id              = v1.room_id
											and	q1.hotel_cd             = charge.hotel_cd
											and	q1.plan_id              = charge.plan_id
											and	q1.room_id              = charge.room_id
											and	q1.partner_group_id     = charge.partner_group_id
											and	v1.date_ymd             = charge.date_ymd
											and	charge.capacity         = r_rec.capacity
											and	charge.hotel_cd         = q_today.hotel_cd(+)
											and	charge.room_id          = q_today.room_id(+)
											and	charge.plan_id          = q_today.plan_id(+)
											and	charge.partner_group_id = q_today.partner_group_id(+)
											and	charge.capacity         = q_today.capacity(+)
											and	charge.date_ymd         = q_today.date_ymd(+)
											and	charge.hotel_cd         = charge_early.hotel_cd(+)
											and	charge.room_id          = charge_early.room_id(+)
											and	charge.plan_id          = charge_early.plan_id(+)
											and	charge.partner_group_id = charge_early.partner_group_id(+)
											and	charge.capacity         = charge_early.capacity(+)
											and	charge.date_ymd         = charge_early.date_ymd(+)
											and	nvl(charge_early.accept_e_ymd(+), d_sysdate) >= d_sysdate
											and	charge.accept_status = 1
											and	sysdate between charge.accept_s_dtm and charge.accept_e_dtm
											and	charge.sales_charge > 0
								) q2,
								(
									select	hotel_powerdown_s.hotel_cd,
											mast_calendar.date_ymd,
											hotel_powerdown_s.powerdown_charge
									from	hotel_powerdown_s,
											mast_calendar
									where	hotel_cd = r_rec.hotel_cd
										and	sysdate between powerdown_s_dtm and powerdown_e_dtm
										and	mast_calendar.date_ymd between hotel_powerdown_s.target_s_ymd and hotel_powerdown_s.target_e_ymd
								) q_powerdown
							where	q2.hotel_cd = hotel_control.hotel_cd
								and	q2.hotel_cd = q_powerdown.hotel_cd(+)
								and	q2.date_ymd = q_powerdown.date_ymd(+)
						)
				where	sales_charge > 0
				group by hotel_cd,
						plan_id,
						room_id,
						capacity
				order by hotel_cd,
						plan_id,
						room_id,
						capacity
			)
			loop
				r_condition.sales_charge_min := r_power_s.sales_charge_min;
				r_condition.sales_charge_max := r_power_s.sales_charge_max;
				r_condition.rate             := r_power_s.rate;
				-- 税抜料金
				r_condition.sales_excluding_tax_charge_min := ceil(r_condition.sales_charge_min / (n_tax / 100 + 1));
				r_condition.sales_excluding_tax_charge_max := ceil(r_condition.sales_charge_max / (n_tax / 100 + 1));
			end loop;

			if (not(modify_condition(r_condition)))
			then
				return;
			end if;
		end if;
	end loop;

	-- 更新してないプランは削除
	delete	charge_condition
	where	hotel_cd = v_hotel_cd
		and (v_room_id is null or room_id = v_room_id)
		and (v_plan_id is null or plan_id = v_plan_id)
		and modify_ts < d_start;


	dbms_output.put_line('success');

exception
	when others then
		-- オラクルエラー出力
		dbms_output.put_line(SQLERRM);
end;


SQL;

				$this->box->odb->beginTransaction();
				// データ作成
				$stmt = $this->box->odb->prepare($s_sql);
				$stmt->execute();

				// 結果取得
				$stmt = $this->box->odb->prepare("begin dbms_output.GET_LINE  (:iv_line, :in_status); end;");
				$stmt->bindParam(':in_status', $in_status, PDO::PARAM_STR, 4000);
				$stmt->bindParam(':iv_line'  , $iv_line, PDO::PARAM_STR, 4000);
				$in_statu = 0;
				$b_status = false;
				while ($in_status != 1){
					$stmt->execute();
					if ($iv_line == 'success'){
						$b_status = true;
					} else {
						$this->box->item->error->add($iv_line);
					}
				}

				// 失敗
				if (!$b_status){
					$this->box->odb->rollback();
					return false;
				}

				// 正常
				$this->box->odb->commit();

if ($this->b_debug) {
print_r(date('Y-m-d H:i:s') . ':end:' . $aa_condition['hotel_cd'] . ',' . $aa_condition['plan_id'] . ',' . $aa_condition['room_id']. "\n");
}				return true;

			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
				$this->box->item->error->add($e->getMessage());
				$this->box->odb->rollback();
				return false;
			}
		}

		// 全ホテルの料金登録状況設定
		public function set_all()
		{
			try{

				// 更新がなかった場合は、販売がなくなったということで削除する。
				$s_sql =
<<<SQL
					select	hotel_control.stock_type,
							hotel.pref_id,
							hotel_status.hotel_cd,
							q10.hotel_cd as ner_hotel_cd
					from	hotel_status,
							hotel_control,
							hotel,
							( -- 直近３時間に変更があった施設
								select	hotel_cd
								from	(
									        select	distinct hotel_cd from room_plan_match             where modify_ts >= trunc(sysdate, 'hh24') - (3 / 24)
									union	select	distinct hotel_cd from plan                        where modify_ts   >= trunc(sysdate, 'hh24') - (3 / 24)
									union	select	distinct hotel_cd from room2                       where modify_ts  >=  trunc(sysdate, 'hh24') - (3 / 24)
									union	select	distinct hotel_cd from room_count2                 where modify_ts >= trunc(sysdate, 'hh24') - (3 / 24)
									union	select	distinct hotel_cd from room_count_removed          where delete_dtm >= trunc(sysdate, 'hh24') - (3 / 24)
									union	select	distinct hotel_cd from charge                      where modify_ts >= trunc(sysdate, 'hh24') - (3 / 24)
																									or	accept_s_dtm between trunc(sysdate, 'hh24') - (3 / 24) and sysdate
																									or	accept_e_dtm  between trunc(sysdate, 'hh24') - (3 / 24) and sysdate
									union	select	distinct hotel_cd from charge_today                where modify_ts >= trunc(sysdate, 'hh24') - (3 / 24)
																									or	timetable between trunc(sysdate, 'hh24') - (3 / 24) and sysdate
									union	select	distinct hotel_cd from charge_early                where modify_ts >= trunc(sysdate, 'hh24') - (3 / 24)
									union	select	distinct hotel_cd from room_charge_removed         where delete_dtm >= trunc(sysdate, 'hh24') - (3 / 24)
									union	select	distinct hotel_cd from room_plan_removed           where delete_dtm  >= trunc(sysdate, 'hh24') - (3 / 24)
									union	select	distinct hotel_cd from room_plan_match_removed     where delete_dtm >=  trunc(sysdate, 'hh24') - (3 / 24)
								)
								order by hotel_cd
							) q10
					where	hotel_status.entry_status = 0
						and	hotel_status.hotel_cd = hotel.hotel_cd
						and	hotel_status.hotel_cd = hotel_control.hotel_cd
						and	hotel_status.hotel_cd = q10.hotel_cd(+)
					order by case when hotel_control.stock_type = 1 then 0 else 1 end, -- ハイランク優先
							 case when q10.hotel_cd is null then 1 else 0 end, -- 直近３時間に変更があった施設優先
							 case when hotel.pref_id in ( 13, 23, 27, 40 ) then 0 else 1 end , -- 東京・愛知・大阪・福岡優先
							 hotel_status.hotel_cd
SQL;
				$_oracle = _Oracle::getInstance();
				$a_row = $_oracle->find_by_sql($s_sql, array());
				$this->b_debug = true;

				for ($n_cnt = 0 ; $n_cnt < count($a_row); $n_cnt++) {
					if (!$this->set_charge(array('hotel_cd' => $a_row[$n_cnt]['hotel_cd']))) {
						$this->box->item->error->clear();
					}
				}

				return true;

			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}
		}

		// 変更のあったホテルの料金登録状況設定
		public function set_diff($as_time)
		{
			try{

				// 更新がなかった場合は、販売がなくなったということで削除する。
				$s_sql =
<<<SQL
					select	hotel_control.stock_type,
							hotel.pref_id,
							hotel.hotel_cd,
							q10.hotel_cd as ner_hotel_cd
					from	hotel_control,
							hotel,
							(
								select	hotel_cd
								from	(
											        select	distinct hotel_cd from room_plan_match             where modify_ts >= to_date(:date_ymd, 'yyyymmddhh24mi')
											union	select	distinct hotel_cd from plan                        where modify_ts   >= to_date(:date_ymd, 'yyyymmddhh24mi')
											union	select	distinct hotel_cd from room2                       where modify_ts  >=  to_date(:date_ymd, 'yyyymmddhh24mi')
											union	select	distinct hotel_cd from room_count2                 where modify_ts >= to_date(:date_ymd, 'yyyymmddhh24mi')
											union	select	distinct hotel_cd from room_count_removed          where delete_dtm >= to_date(:date_ymd, 'yyyymmddhh24mi')
											union	select	distinct hotel_cd from charge                      where modify_ts >= to_date(:date_ymd, 'yyyymmddhh24mi')
																											or	accept_s_dtm between to_date(:date_ymd, 'yyyymmddhh24mi') and sysdate
																											or	accept_e_dtm  between to_date(:date_ymd, 'yyyymmddhh24mi') and sysdate
											union	select	distinct hotel_cd from charge_today                where modify_ts >= to_date(:date_ymd, 'yyyymmddhh24mi')
																											or	timetable between to_date(:date_ymd, 'yyyymmddhh24mi') and sysdate
											union	select	distinct hotel_cd from charge_early                where modify_ts >= to_date(:date_ymd, 'yyyymmddhh24mi')
											union	select	distinct hotel_cd from room_charge_removed         where delete_dtm >= to_date(:date_ymd, 'yyyymmddhh24mi')
											union	select	distinct hotel_cd from room_plan_removed           where delete_dtm  >= to_date(:date_ymd, 'yyyy-mmddhh24mi')
											union	select	distinct hotel_cd from room_plan_match_removed     where delete_dtm >=  to_date(:date_ymd, 'yyyy-mmddhh24mi')
								)
								order by hotel_cd
							) q10
					where	hotel.hotel_cd = hotel_control.hotel_cd
						and	hotel.hotel_cd = q10.hotel_cd
					order by case when hotel_control.stock_type = 1 then 0 else 1 end, -- ハイランク優先
							 case when q10.hotel_cd is null then 1 else 0 end, -- 直近３時間に変更があった施設優先
							 case when hotel.pref_id in ( 13, 23, 27, 40 ) then 0 else 1 end , -- 東京・愛知・大阪・福岡優先
							 hotel.hotel_cd
SQL;
				$_oracle = _Oracle::getInstance();
				$a_row = $_oracle->find_by_sql($s_sql, array('date_ymd' => $as_time));
				$this->b_debug = true;
				for ($n_cnt = 0 ; $n_cnt < count($a_row); $n_cnt++) {
					if (!$this->set_charge(array('hotel_cd' => $a_row[$n_cnt]['hotel_cd']))) {
						$this->box->item->error->clear();
					}
				}

				return true;

			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}
		}


	}
?>
