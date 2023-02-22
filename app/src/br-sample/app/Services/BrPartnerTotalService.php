<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;


class BrPartnerTotalService
{

		// 提携先専用料金設定を行っているプラン情報、選択肢の生成
		/**
		* @param array
		*       aa_conditions
		*			partner_group_id 提携先コード
		* @return array
		* 		a_result		結果内容
		*			partner_group_id		提携先コード
		*			partner_group_nm		提携先名
		*			
		*/
		public function get_material_partner_group(){
			try {

				$s_sql =
<<<SQL
					select	distinct
							partner_group.partner_group_id,
							partner_group.partner_group_nm
					from	partner_group,
						(
							select	partner_group_id
							from	partner_group_join,
								(
									select	partner_cd
									from	partner_control
									where	entry_status = 0
								) q1
							where	partner_group_join.partner_cd = q1.partner_cd
						) q2
					where	partner_group.partner_group_id = q2.partner_group_id
						-- ベストリザーブを除外
						and	partner_group.partner_group_id not in ('232', '0', '1')
					group by	partner_group.partner_group_id,
								partner_group.partner_group_nm
					order by	partner_group.partner_group_nm
SQL;


                $a_result = DB::select($s_sql);


				// データの取得
				return array(
								'values'    => $a_result,
								
							);
			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}
		}


		// 提携先専用料金設定を行っているプラン情報の集計、リスト表示
		/**
		* @param array
		*       aa_conditions
		*			partner_group_id 提携先コード
		* @return array
		* 		a_row		結果内容
		*			hotel_cd		施設コード
		*			hotel_nm		施設名
		*			pref_nm		都道府県
		*			address		住所
		*			tel		ホテルTEL
		*			fax		ホテルFAX
		*			room_nm		部屋名称
		*			room_id		部屋コード
		*			plan_nm		プラン名称
		*			plan_id		プランコード
		*			room_charge_hotel_cd		ベストリザーブ料金設定有無
		*			extend_status		自動延長対象有無
		*			
		*/
		public function get_total_material($aa_conditions){
			try {


                // すべて表示かそうでない場合か
				if ($aa_conditions['partner_group_id'] != 'all') {
					$s_partner_group_id = 'and	charge.partner_group_id = :partner_group_id';
					$a_conditions = $aa_conditions;
				} else {
					$s_partner_group_id = "and	charge.partner_group_id not in ('232', '0', '1')";
					$a_conditions = array();
				}


				$s_sql =


<<<SQL

					select	distinct
							q9.hotel_cd,
							q9.hotel_nm,
							q9.pref_nm,
							q9.address,
							q9.tel,
							q9.fax,
							q9.room_nm,
							q9.room_id,
							q9.plan_nm,
							q9.plan_id,
							case
								when charge.hotel_cd is not null then
								'有'	
								else
								'無'
								end as room_charge_hotel_cd,
						
							CASE q9.extend_status
								WHEN 1 THEN '有'
								ELSE '無' end as extend_status 
					from	
						(

							select	q8.hotel_cd,
									q8.hotel_nm,
									q8.pref_nm,
									q8.address,
									q8.tel,
									q8.fax,
									q8.room_id,
									q8.room_nm,
									q8.plan_id,
									q8.plan_nm,
									q8.date_ymd,
									case
										when charge_initial.hotel_cd is not null then
											q8.extend_status	
										else
											0
										end as extend_status
									
							from	
							(

									select	q7.hotel_cd,
											q7.hotel_nm,
											q7.pref_nm,
											q7.address,
											q7.tel,
											q7.fax,
											q7.room_id,
											q7.room_nm,
											q7.plan_id,
											q7.plan_nm,
											q7.extend_status,
											charge.date_ymd
									from	charge,
										(
											select	q6.hotel_cd,
													q6.hotel_nm,
													q6.pref_nm,
													q6.address,
													q6.tel,
													q6.fax,
													q6.room_id,
													q6.room_nm,
													q6.plan_id,
													q6.plan_nm,
													CASE q6.extend_status
														WHEN 1 THEN ifnull(extend_switch_plan2.extend_status, 0)
														ELSE 0 end as extend_status 
											from	
												(
												select	plan.hotel_cd,
															q5.hotel_nm,
															q5.pref_nm,
															q5.address,
															q5.tel,
															q5.fax,
															q5.room_id,
															q5.room_nm,
															plan.plan_id,
															plan.plan_nm,
															q5.extend_status
													from	plan,
														(
															select	q5.hotel_cd,
																	q5.hotel_nm,
																	q5.pref_nm,
																	q5.address,
																	q5.tel,
																	q5.fax,
																	q5.room_id,
																	room_plan_match.plan_id,
																	q5.room_nm,
																	q5.extend_status
															from room_plan_match,
																(
																	select	room2.hotel_cd,
																			q4.hotel_nm,
																			q4.pref_nm,
																			q4.address,
																			q4.tel,
																			q4.fax,
																			room2.room_id,
																			room2.room_nl as room_nm,
																			q4.extend_status
																	from	room2,
 																		(

 																			select	q3.hotel_cd,
 																					q3.hotel_nm,
 																					q3.pref_nm,
 																					q3.address,
 																					q3.tel,
 																					q3.fax,
 																					ifnull(extend_switch.extend_status, 0) as extend_status
 																			from	
 																				(

																					select	q2.hotel_cd,
																							q2.hotel_nm,
																							mast_pref.pref_nm,
																							q2.address,
																							q2.tel,
																							q2.fax
																					from	mast_pref,
																						(


																							select	hotel.hotel_cd,
																									hotel.hotel_nm,
																									hotel.pref_id,
																									hotel.address,
																									hotel.tel,
																									hotel.fax
																							from	hotel,
																								(
																									select	hotel_cd
																									from	hotel_status
																									where	entry_status = 0
																								) q1
																							where	hotel.hotel_cd = q1.hotel_cd


																						) q2
																					where	mast_pref.pref_id = q2.pref_id
																					) q3

																			left outer join		extend_switch
																			on	extend_switch.hotel_cd= q3.hotel_cd
																			) q4
																	where	room2.hotel_cd = q4.hotel_cd
																		and	room2.display_status = 1
																) q5
															where	room_plan_match.hotel_cd = q5.hotel_cd
																and	room_plan_match.room_id  = q5.room_id
														) q5
													where	plan.hotel_cd = q5.hotel_cd
														and	plan.plan_id  = q5.plan_id
														and	plan.display_status = 1
													) q6
											left outer join extend_switch_plan2
											on	extend_switch_plan2.hotel_cd = q6.hotel_cd
												and	extend_switch_plan2.room_id  = q6.room_id
												and	extend_switch_plan2.plan_id = q6.plan_id
											) q7
									where	charge.hotel_cd     = q7.hotel_cd
										and	charge.room_id      = q7.room_id
										and	charge.plan_id      = q7.plan_id
										{$s_partner_group_id}
										and	charge.date_ymd    >= CURDATE()
										) q8
							left outer join	charge_initial
							on	charge_initial.hotel_cd = q8.hotel_cd
							and	charge_initial.room_id = q8.room_id
							and	charge_initial.plan_id = q8.plan_id
							
							
							where	(ifnull(charge_initial.sales_charge_sun, 0) +
									 ifnull(charge_initial.sales_charge_mon, 0) +
									 ifnull(charge_initial.sales_charge_tue, 0) +
									 ifnull(charge_initial.sales_charge_wed, 0) +
									 ifnull(charge_initial.sales_charge_thu, 0) +
									 ifnull(charge_initial.sales_charge_fri, 0) +
									 ifnull(charge_initial.sales_charge_sat, 0) +
									 ifnull(charge_initial.sales_charge_hol, 0) +
									 ifnull(charge_initial.sales_charge_bfo, 0)) > 0
						) q9

						left outer join charge
						on charge.hotel_cd         = q9.hotel_cd
						and	charge.room_id          = q9.room_id
						and	charge.plan_id          = q9.plan_id
						and	charge.partner_group_id = 0
						and	charge.date_ymd        >= q9.date_ymd
					order by q9.hotel_cd, q9.room_id, q9.plan_id

SQL;

                //クエリの発行
                $a_row = DB::select($s_sql, $a_conditions);

				// データの取得
				return $a_row;
					
					

			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}
		}        



}