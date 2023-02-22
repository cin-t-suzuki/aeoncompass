<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
use App\Models\HotelControl;



class CoreChargeService
{

		// 返却データ
		private $_a_room_charges     = array();



		// 現在の販売料金の取得
		/**
		* @param array
		*       aa_conditions
		*			hotel_cd  施設コード
		*			room_cd   部屋コード
		*			plan_cd	プランコード
		*			partner_group_id　提携先グループID
		*			date_ymd  宿泊日
		*    	ab_only_sales
        *     		true 商品の販売状態を考慮 false 商品の販売状態を無視
		* @return bool　---
		*/
		public function charges($ab_only_sales = true,$aa_conditions = array()){
			try {

				$s_date_ymd = "								and	date_ymd         = :s_date_ymd";


				// sql1用条件を設定
				$a_conditions1['hotel_cd']         = $aa_conditions['hotel_cd'];
				$a_conditions1['room_cd']          = $aa_conditions['room_cd'];
				$a_conditions1['plan_cd']          = $aa_conditions['plan_cd'];
				$a_conditions1['partner_group_id'] = $aa_conditions['partner_group_id'];
				$a_conditions1['s_date_ymd']       = $aa_conditions['date_ymd'];
						
				// sql2用条件を設定
				$a_conditions2['hotel_cd']         = $aa_conditions['hotel_cd'];
				$a_conditions2['room_cd']          = $aa_conditions['room_cd'];
				$a_conditions2['plan_cd']          = $aa_conditions['plan_cd'];
				$a_conditions2['partner_group_id'] = $aa_conditions['partner_group_id'];
				$a_conditions2['s_date_ymd']       = $aa_conditions['date_ymd'];
				// sql2用条件,切捨て桁数の用意
				$hotel_control = HotelControl::where('hotel_cd',$aa_conditions['hotel_cd'])->get()->toArray();
				
				if (empty($hotel_control[0]['charge_round'])){
					$a_conditions2['charge_round'] = 1;
				}else{
					$a_conditions2['charge_round'] = $hotel_control[0]['charge_round'];
				}



					$s_sql1 =
<<<SQL
				
					select	q5.date_ymd,
							q5.usual_charge,
							q5.sales_charge,
							q5.accept_status,
							q5.accept_s_dtm,
							q5.accept_e_dtm,
							q5.low_price_status,
							q5.partner_group_id,
							q5.discount_type,
							q5.unit,
							q5.discount_rate,
							q5.discount_charge,
							q5.accept_e_ymd
					from
						(

								select	q4.date_ymd,
								q4.usual_charge,
								q4.sales_charge,
								q4.accept_status,
								q4.accept_s_dtm,
								q4.accept_e_dtm,
								q4.low_price_status,
								q4.partner_group_id,
								q4.discount_type,
								q4.unit,
								q4.discount_rate,
								q4.discount_charge,
								q4.accept_e_ymd,
								dense_rank() over(partition by q4.date_ymd order by q4.partner_group_id desc)  as ranking_no
								from
								(
										select	
										date_format(mast_calendar.date_ymd, '%Y-%m-%d') as date_ymd,
										ifnull(q3.usual_charge, 0)                     as usual_charge,
										ifnull(q3.sales_charge, 0)                     as sales_charge,
										ifnull(q3.accept_status, 0)                    as accept_status,
										ifnull(q3.accept_s_dtm, DATE_ADD(CURDATE(),INTERVAL -1 DAY))            as accept_s_dtm,
										ifnull(q3.accept_e_dtm, DATE_ADD(CURDATE(),INTERVAL -1 DAY))            as accept_e_dtm,
										ifnull(q3.low_price_status, 0)                 as low_price_status,
										q3.partner_group_id,
										ifnull(q3.discount_type, 0)                    as discount_type,
										q3.unit,
										q3.discount_rate,
										q3.discount_charge,
										date_format(q3.accept_e_ymd , '%Y-%m-%d') as accept_e_ymd
											
									from	 mast_calendar
									LEFT OUTER JOIN
										(	select	
													q2.date_ymd,
													q2.usual_charge,
													case
														when (room_charge_today.unit = 0 and room_charge_today.discount_rate is not null ) then	-- 率
															q2.sales_charge - floor(q2.sales_charge * (room_charge_today.discount_rate / 100))
														when ( room_charge_today.unit = 1 and room_charge_today.discount_charge is not null ) then	-- 金額
															room_charge_today.discount_charge
														when ( room_charge_today.unit = 2 and room_charge_today.discount_charge is not null ) then	-- 差額
															q2.sales_charge - room_charge_today.discount_charge
														when room_charge_today.unit is null 
																or ( room_charge_today.unit = 0  and room_charge_today.discount_rate is null )
																or ( room_charge_today.unit = 1 and room_charge_today.discount_charge is null )
																or ( room_charge_today.unit = 2 and room_charge_today.discount_charge is null )
															then
															q2.sales_charge
														end	as sales_charge,
													q2.accept_status,
													q2.accept_s_dtm, 
													q2.accept_e_dtm,
													q2.low_price_status,
													q2.partner_group_id,
													case
														when room_charge_today.unit = 1 then	-- 金額
															case
																when q2.sales_charge <= room_charge_today.discount_charge then
																0
																else
																	case
																		when room_charge_today.unit is not null then
																			2
																		else
																			0
															end
														else
																case
																		when room_charge_today.unit is not null then
																			2
																		else
																			0
																end
													end as discount_type, -- 2:当日 0:通常
													room_charge_today.unit,
													room_charge_today.discount_rate,
													room_charge_today.discount_charge,
													null as accept_e_ymd
													
											from	
												(

													select	q1.hotel_cd,
															q1.room_cd,
															q1.plan_cd,
															q1.partner_group_id,
															q1.date_ymd,
															q1.usual_charge,
															q1.sales_charge,
															q1.accept_status,
															q1.accept_s_dtm,
															q1.accept_e_dtm,
															q1.low_price_status,
															max(room_charge_today.timetable) as timetable
													from	
														(
															
															select	
															
																case
																	when CURDATE() = date_format(room_charge.date_ymd, '%Y-%m-%d') then
																		1 -- 当日予約
																	when (DATE_ADD(CURDATE(),INTERVAL -1 DAY) = date_format(room_charge.date_ymd, '%Y-%m-%d'))
																	and	time_format(convert_tz(CURRENT_TIME(), '+00:00','Asia/Tokyo'), '%H') between 0 and 5
																		
																	then
																		1 -- 当日予約
																	else
																		0
 																	end as today,
																	room_charge.hotel_cd,
																	room_charge.room_cd,
																	room_charge.plan_cd,
																	room_charge.partner_group_id,
																	room_charge.date_ymd,
																	room_charge.usual_charge,
																	room_charge.sales_charge,
																	room_charge.accept_status,
																	room_charge.accept_s_dtm,
																	room_charge.accept_e_dtm,
																	room_charge.low_price_status
															from	room_charge,
																	(
																		select	hotel_cd,
																				room_cd,
																				plan_cd,
																				partner_group_id
																		from	zap_plan_partner_group
																		where	hotel_cd         = :hotel_cd
																			and	room_cd          = :room_cd
																			and	plan_cd          = :plan_cd
																			and	partner_group_id in (0, :partner_group_id)
																	) g1
															where	room_charge.hotel_cd         = g1.hotel_cd
																and	room_charge.room_cd          = g1.room_cd
																and	room_charge.plan_cd          = g1.plan_cd
																and	room_charge.partner_group_id = g1.partner_group_id
																{$s_date_ymd}	
																)q1

																
													left OUTER JOIN room_charge_today
													ON room_charge_today.hotel_cd = q1.hotel_cd
													and	room_charge_today.room_cd    = q1.room_cd
													and	room_charge_today.plan_cd         = q1.plan_cd
													and	room_charge_today.partner_group_id = q1.partner_group_id
													and	date_format(room_charge_today.timetable , '%Y-%m-%d')      <= date_format(CURDATE() , '%Y-%m-%d')  
													and	date_format(room_charge_today.date_ymd , '%Y-%m-%d')        = date_format(q1.date_ymd , '%Y-%m-%d') 

													where
														q1.today     = 1
														

													group by q1.hotel_cd,
															q1.room_cd,
															q1.plan_cd,
															q1.partner_group_id,
															q1.date_ymd,
															q1.usual_charge,
															q1.sales_charge,
															q1.accept_status,
															q1.accept_s_dtm,
															q1.accept_e_dtm,
															q1.low_price_status		

												) q2
												left OUTER JOIN room_charge_today
												ON
													room_charge_today.hotel_cd         = q2.hotel_cd
												and	room_charge_today.room_cd          = q2.room_cd
												and	room_charge_today.plan_cd          = q2.plan_cd
												and	date_format(room_charge_today.date_ymd , '%Y-%m-%d')   = date_format(q2.date_ymd , '%Y-%m-%d') 
												and	room_charge_today.partner_group_id= q2.partner_group_id
												and	room_charge_today.timetable       = q2.timetable
												) q3
									
									ON	date_format(mast_calendar.date_ymd, '%Y-%m-%d') = date_format(q3.date_ymd , '%Y-%m-%d')
									where date_format(mast_calendar.date_ymd, '%Y-%m-%d') = date_format(q3.date_ymd , '%Y-%m-%d')
									order by mast_calendar.date_ymd
									) q4
						) q5
						where	q5.ranking_no = 1
								
													
																	
SQL;


					$s_sql2 =
<<<SQL

					select	q5.date_ymd,
							q5.usual_charge,
							q5.sales_charge,
							q5.accept_status,
							q5.accept_s_dtm,
							q5.accept_e_dtm,
							q5.low_price_status,
							q5.partner_group_id,
							q5.discount_type,
							q5.unit,
							q5.discount_rate,
							q5.discount_charge,
							q5.accept_e_ymd
					from
						(

								select	q4.date_ymd,
								q4.usual_charge,
								q4.sales_charge,
								q4.accept_status,
								q4.accept_s_dtm,
								q4.accept_e_dtm,
								q4.low_price_status,
								q4.partner_group_id,
								q4.discount_type,
								q4.unit,
								q4.discount_rate,
								q4.discount_charge,
								q4.accept_e_ymd,
								dense_rank() over(partition by q4.date_ymd order by q4.partner_group_id desc)  as ranking_no
								from
								(
									select	
										date_format(mast_calendar.date_ymd, '%Y-%m-%d') as date_ymd,
										ifnull(q3.usual_charge, 0)                     as usual_charge,
										ifnull(q3.sales_charge, 0)                     as sales_charge,
										ifnull(q3.accept_status, 0)                    as accept_status,
										ifnull(q3.accept_s_dtm, DATE_ADD(CURDATE(),INTERVAL -1 DAY))            as accept_s_dtm,
										ifnull(q3.accept_e_dtm, DATE_ADD(CURDATE(),INTERVAL -1 DAY))            as accept_e_dtm,
										ifnull(q3.low_price_status, 0)                 as low_price_status,
										q3.partner_group_id,
										ifnull(q3.discount_type, 0)                    as discount_type,
										q3.unit,
										q3.discount_rate,
										q3.discount_charge,
										date_format(q3.accept_e_ymd , '%Y-%m-%d') as accept_e_ymd
											
									from	 mast_calendar
									LEFT OUTER JOIN
										(

											
											select	q1.date_ymd,
													q1.usual_charge,
													case
 														when (room_charge_early.unit = 0 and room_charge_early.discount_rate is not null ) then	-- 率
														 TRUNCATE(q1.sales_charge - floor(q1.sales_charge * (room_charge_early.discount_rate / 100)), -1 * log(10, :charge_round))
 														when ( room_charge_early.unit = 1 and room_charge_early.discount_charge is not null ) then	-- 金額
 															room_charge_early.discount_charge
 														when ( room_charge_early.unit = 2 and room_charge_early.discount_charge is not null ) then	-- 差額
 															q1.sales_charge - room_charge_early.discount_charge
														when room_charge_early.unit is null 
 																or ( room_charge_early.unit = 0  and room_charge_early.discount_rate is null )
 																or ( room_charge_early.unit = 1 and room_charge_early.discount_charge is null )
 																or ( room_charge_early.unit = 2 and room_charge_early.discount_charge is null )
 															then
 															q1.sales_charge
 														end	as sales_charge,
 													q1.accept_status,
 													q1.accept_s_dtm, 
 													q1.accept_e_dtm,
 													q1.low_price_status,
 													q1.partner_group_id,
													case
														when room_charge_early.unit = 1 then	-- 金額
 															case
																when q1.sales_charge <= room_charge_early.discount_charge then
																	0
																else
																	case
																			when room_charge_early.unit is not null then
																				1
																			else
																				0
																	end
 															end
														else
																case
																		when room_charge_early.unit is not null then
																			1
																		else
																			0
																end
 													end as discount_type, -- 1:当日 0:通常

													 when room_charge_today.unit = 1 then	-- 金額
															case
																when q2.sales_charge <= room_charge_today.discount_charge then
																0
																else
																	case
																		when room_charge_today.unit is not null then
																			2
																		else
																			0
															end
														else
																case
																		when room_charge_early.unit is not null then
																			1
																		else
																			0
																end




													room_charge_early.unit,
													room_charge_early.discount_rate,
													room_charge_early.discount_charge,
													room_charge_early.accept_e_ymd
											from	
												(
														select	case

															when CURDATE() = date_format(room_charge.date_ymd, '%Y-%m-%d') then
																1 -- 当日予約
															when (DATE_ADD(CURDATE(),INTERVAL -1 DAY) = date_format(room_charge.date_ymd, '%Y-%m-%d'))
															and	time_format(convert_tz(CURRENT_TIME(), '+00:00','Asia/Tokyo'), '%H') between 0 and 5
																
															then
																1 -- 当日予約
															else
																0
															end as today,
															room_charge.hotel_cd,
															room_charge.room_cd,
															room_charge.plan_cd,
															room_charge.partner_group_id,
															room_charge.date_ymd,
															room_charge.usual_charge,
															room_charge.sales_charge,
															room_charge.accept_status,
															room_charge.accept_s_dtm,
															room_charge.accept_e_dtm,
															room_charge.low_price_status
													from	room_charge,
															(
																select	hotel_cd,
																		room_cd,
																		plan_cd,
																		partner_group_id
																from	zap_plan_partner_group
																where	hotel_cd         = :hotel_cd
																	and	room_cd          = :room_cd
																	and	plan_cd          = :plan_cd
																	and	partner_group_id in (0, :partner_group_id)
															) g1
													where	room_charge.hotel_cd         = g1.hotel_cd
														and	room_charge.room_cd          = g1.room_cd
														and	room_charge.plan_cd          = g1.plan_cd
														and	room_charge.partner_group_id = g1.partner_group_id
														{$s_date_ymd}
												) q1
												
											
											LEFT OUTER JOIN room_charge_early
 													ON room_charge_early.hotel_cd = q1.hotel_cd
 													and	room_charge_early.room_cd    = q1.room_cd
 													and	room_charge_early.plan_cd         = q1.plan_cd
 													and	room_charge_early.partner_group_id = q1.partner_group_id
													and	date_format(room_charge_early.accept_e_ymd , '%Y-%m-%d')      >=  CURDATE() 
													and	date_format(room_charge_early.date_ymd , '%Y-%m-%d')        = date_format(q1.date_ymd , '%Y-%m-%d') 

												where
													q1.today     = 0
													) q3
									
									ON	date_format(mast_calendar.date_ymd, '%Y-%m-%d') = date_format(q3.date_ymd , '%Y-%m-%d')
									where date_format(mast_calendar.date_ymd, '%Y-%m-%d') = date_format(q3.date_ymd , '%Y-%m-%d')
									order by mast_calendar.date_ymd
								) q4
						) q5
						where	q5.ranking_no = 1
						
											
										
SQL;


				//クエリの発行
				$a_room_charge1 = DB::select($s_sql1,$a_conditions1);
				$a_room_charge2 = DB::select($s_sql2,$a_conditions2);

				 $a_room_charges=array_merge($a_room_charge1,$a_room_charge2);

				// 販売停止中
				if (count($a_room_charges) == 0){
					$this->_a_room_charges = array();
					return false;
				}
				// 取得データを保持
				$this->_a_room_charges = $a_room_charges;

				return true;

			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}

		}

		// 消費税を取得
		/**
		* @param array
		*		accept_s_ymd 開始日 YYYY-MM-DD
		* @return bool
		*			tax_rate     消費税率
		*/
		public function get_tax_rate($as_accept_s_ymd = null){
			try {

				// 開始日
				if (empty($as_accept_s_ymd)== false){
					$s_accept_s_ymd = "	and	accept_s_ymd <= :accept_s_ymd";
					$a_conditions['accept_s_ymd'] = $as_accept_s_ymd;
				} else {
					$s_accept_s_ymd = "	and	accept_s_ymd <= CURDATE()";
					$a_conditions = array();
				}

				$s_sql =
<<<SQL
					select	tax
					from	mast_tax
					where	accept_s_ymd = (
												select	max(accept_s_ymd)
												from	mast_tax
												where	null is null
													{$s_accept_s_ymd}
											)
SQL;
				//変数の用意
				$tax_rate = '';

                //クエリの発行
                $a_row = DB::select($s_sql, $a_conditions);

				if(empty($a_row[0]->tax )== false){
					$tax_rate = $a_row[0]->tax ;
				}else{
					$tax_rate = 0;
				}


				return array(
								'values'     => $tax_rate ,
							);

			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}

		}

		// ある期間の料金の取得
		/**
		* @param array
		*		ab_only_sales
		*		 	true 料金の販売状態を考慮 false 料金の販売状態を無視
		*       a_room_charges
		*			usual_charge     通常料金
		*			sales_charge     実売料金
		*			accept_status    予約受付状態 (0:停止 1:受付)
		*			accept_s_dtm     開始日時
		*			accept_e_dtm     終了日時
		*			low_price_status 最安値宣言ステータス (0:最安値でない 1:最安値)
		*			discount_type   0:通常 1:早割り 2:当日
		*			discount_charge 割引料金
		*			accept_e_ymd　	終了年
		*			tax_rate		消費税率

		* @return bool
		* 		 a_attributes  
		*			usual_charge     通常料金
		*			sales_charge     実売料金
		*			accept_status    予約受付状態 (0:停止 1:受付)
		*			accept_s_dtm     開始日時
		*			accept_e_dtm     終了日時
		*			low_price_status 最安値宣言ステータス (0:最安値でない 1:最安値)
		*			discount_type   0:通常 1:早割り 2:当日
		*			discount_charge 割引料金
		*			accept_e_ymd　	終了年
		*			tax_rate		消費税率
		*/
		public function get_charges($ab_only_sales = true){

			//返却する配列の初期化
			$a_attributes=array();

			try {

				// 販売期間を無視する場合
				if (!($ab_only_sales)){
					for ($n_cnt = 0; $n_cnt < count($this->_a_room_charges); $n_cnt++){

						$o_date_ymd=$this->_a_room_charges[$n_cnt]->date_ymd;
						$o_accept_s_dtm=$this->_a_room_charges[$n_cnt]->accept_s_dtm;
						$o_accept_e_dtm=$this->_a_room_charges[$n_cnt]->accept_e_dtm;
						$o_accept_e_ymd=$this->_a_room_charges[$n_cnt]->accept_e_ymd;

						// 消費税を取得
						/**
						* @param array
						*		accept_s_ymd 開始日 YYYY-MM-DD
						* @return bool
						*			tax_rate     消費税率
						*/
						$n_tax_rate     = $this->get_tax_rate($o_date_ymd);

						$a_attributes[$o_date_ymd]['usual_charge']     = $this->_a_room_charges[$n_cnt]->usual_charge;
						$a_attributes[$o_date_ymd]['sales_charge']     = $this->_a_room_charges[$n_cnt]->sales_charge;
						$a_attributes[$o_date_ymd]['accept_status']            = $this->_a_room_charges[$n_cnt]->accept_status;
						$a_attributes[$o_date_ymd]['accept_s_dtm']             = $o_accept_s_dtm;
						$a_attributes[$o_date_ymd]['accept_e_dtm']             = $o_accept_e_dtm;
						$a_attributes[$o_date_ymd]['partner_group_id']         = $this->_a_room_charges[$n_cnt]->partner_group_id;
						$a_attributes[$o_date_ymd]['low_price_status']         = $this->_a_room_charges[$n_cnt]->low_price_status;
						$a_attributes[$o_date_ymd]['discount_type']            = $this->_a_room_charges[$n_cnt]->discount_type;
						$a_attributes[$o_date_ymd]['unit']                     = $this->_a_room_charges[$n_cnt]->unit;
						$a_attributes[$o_date_ymd]['discount_rate']            = $this->_a_room_charges[$n_cnt]->discount_rate;
						$a_attributes[$o_date_ymd]['discount_charge']          = $this->_a_room_charges[$n_cnt]->discount_charge;
						$a_attributes[$o_date_ymd]['accept_e_ymd']             = $o_accept_e_ymd;
						$a_attributes[$o_date_ymd]['tax_rate']                 = $n_tax_rate['values'];

					}

					return array(
								'values'     => $a_attributes,
								);
				}


			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}

		}
		
}

?>