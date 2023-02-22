<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
use App\Services\CoreChargeService;


class BrmsdService
{
    public function _get_msd_plan()
		{
			try {



				$s_sql1 = 
<<<SQL
					select	q6.pref_nm,
							q6.hotel_cd,
							q6.hotel_nm,
							room_plan.plan_cd,
							room_plan.plan_nm,
							q6.room_cd,
							q6.room_nm,
							room_plan.capacity,
							q6.partner_group_id
					from	room_plan,
						(
							select	q5.pref_nm,
									q5.pref_cd,
									q5.hotel_cd,
									q5.hotel_nm,
									room.room_cd,
									room.room_nl as room_nm,
									q5.plan_cd,
									q5.partner_group_id
							from	room,
								(
									select	CONCAT( mast_pref.pref_cd, " ", mast_pref.pref_nm) as pref_nm,
											mast_pref.pref_cd,
											q4.hotel_cd,
											q4.hotel_nm,
											q4.room_cd,
											q4.plan_cd,
											q4.partner_group_id
									from	mast_pref,
										(
											select	hotel.pref_id,
													hotel.hotel_cd,
													hotel.hotel_nm,
													q3.room_cd,
													q3.plan_cd,
													q3.partner_group_id
											from	hotel,
												(
													select	zap_room_plan_charge.hotel_cd,
															zap_room_plan_charge.room_cd,
															zap_room_plan_charge.plan_cd,
															q2.partner_group_id
													from	zap_room_plan_charge,
														(
															select	room_plan_match.hotel_cd,
																	room_plan_match.room_id,
																	room_plan_match.plan_id,
																	q1.partner_group_id
															from	room_plan_match,
																(
																	select	hotel_cd,
																			plan_id,
																			partner_group_id
																	from	plan_partner_group
																	where	partner_group_id in (select partner_group_id from partner_group_join where partner_cd = :partner_cd)
																) q1
															where	room_plan_match.hotel_cd = q1.hotel_cd
																and	room_plan_match.plan_id  = q1.plan_id
														) q2
													where	zap_room_plan_charge.parent_hotel_cd = q2.hotel_cd
														and	zap_room_plan_charge.parent_room_id = q2.room_id
														and	zap_room_plan_charge.parent_plan_id = q2.plan_id
												) q3
											where	hotel.hotel_cd = q3.hotel_cd
										) q4
									where	mast_pref.pref_id = q4.pref_id
								) q5
							where	room.hotel_cd = q5.hotel_cd
								and	room.room_cd  = q5.room_cd
								and	room.active_status = 1
								and	room.display_status = 1
								and	room.accept_status = 1
						) q6
					where	room_plan.hotel_cd = q6.hotel_cd
						and	room_plan.room_cd = q6.room_cd
						and	room_plan.plan_cd = q6.plan_cd
						and	room_plan.display_status = 1
						and	room_plan.accept_status = 1
					order by q6.pref_cd,
							q6.hotel_cd,
							room_plan.capacity,
							room_plan.plan_cd,
							q6.room_cd
SQL;


                $s_sql2 = 
<<<SQL
                    select	q6.pref_nm,
							q6.hotel_cd,
							q6.hotel_nm,
							room_plan.plan_cd,
							room_plan.plan_nm,
							q6.room_cd,
							q6.room_nm,
							room_plan.capacity,
							q6.partner_group_id
					from	room_plan,
						(
                            select	q5.pref_nm,
									q5.pref_cd,
									q5.hotel_cd,
									q5.hotel_nm,
									room.room_cd,
									room.room_nl as room_nm,
									q5.plan_cd,
									q5.partner_group_id
							from	room,
								(
								

                                    select	CONCAT( mast_pref.pref_cd, " ", mast_pref.pref_nm) as pref_nm,
											mast_pref.pref_cd,
											q4.hotel_cd,
											q4.hotel_nm,
											q4.room_cd,
											q4.plan_cd,
											q4.partner_group_id
									from	mast_pref,
										(
                                            select	hotel.pref_id,
													hotel.hotel_cd,
													hotel.hotel_nm,
													q3.room_cd,
													q3.plan_cd,
													q3.partner_group_id
											from	hotel,
												(
													select	zap_room_plan.hotel_cd,
															zap_room_plan.room_cd,
															zap_room_plan.plan_cd,
															q2.partner_group_id
													from	zap_room_plan,
														(
															select	room_plan_match.hotel_cd,
																	room_plan_match.room_id,
																	room_plan_match.plan_id,
																	q1.partner_group_id
															from	room_plan_match,
																(
																	select	hotel_cd,
																			plan_id,
																			partner_group_id
																	from	plan_partner_group
																	where	partner_group_id in (select partner_group_id from partner_group_join where partner_cd = :partner_cd)
																) q1
															where	room_plan_match.hotel_cd = q1.hotel_cd
																and	room_plan_match.plan_id  = q1.plan_id
														) q2
													where	zap_room_plan.hotel_cd = q2.hotel_cd
														and	zap_room_plan.room_id = q2.room_id
														and	zap_room_plan.plan_id = q2.plan_id
                                                    

												) q3
											where	hotel.hotel_cd = q3.hotel_cd
                                            
										) q4
									where	mast_pref.pref_id = q4.pref_id
                                    ) q5
							where	room.hotel_cd = q5.hotel_cd
								and	room.room_cd  = q5.room_cd
								and	room.active_status = 1
								and	room.display_status = 1
								and	room.accept_status = 1
                                ) q6
					where	room_plan.hotel_cd = q6.hotel_cd
						and	room_plan.room_cd = q6.room_cd
						and	room_plan.plan_cd = q6.plan_cd
						and	room_plan.display_status = 1
						and	room_plan.accept_status = 1
                    order by q6.pref_cd,
                        q6.hotel_cd,
                        room_plan.capacity,
                        room_plan.plan_cd,
                        q6.room_cd
SQL;

                //クエリの発行
                $a_row1 = DB::select($s_sql1, array('partner_cd' => '2000005100'));
                $a_row2 = DB::select($s_sql2, array('partner_cd' => '2000005100'));

                //配列内容の統合、重複削除
                $a_row=array_merge($a_row1,$a_row2);
                $a_row=array_unique($a_row, SORT_REGULAR);


				//プランの在庫、現在販売可否のチェック
				for ($n_cnt = 0; $n_cnt < count($a_row); $n_cnt++){

                    $a_conditions=array();
                    $a_conditions['hotel_cd'] = $a_row[$n_cnt]->hotel_cd;
                    $a_conditions['room_cd'] = $a_row[$n_cnt]->room_cd;
                    $a_conditions['plan_cd'] = $a_row[$n_cnt]->plan_cd;
                    $a_conditions['partner_group_id'] = $a_row[$n_cnt]->partner_group_id;
                    $a_conditions['date_ymd']['after'] = date('Y-m-d');
                

					// ある月に在庫数が存在しているかチェックします。
					/**
					* @param array
					*       a_conditions
					*			hotel_cd  施設コード
					*			room_cd   部屋コード
					*			date_ymd->after  日付 > 日付以降宿泊の予約 YYYY-MM-DD
					* @return bool
					* 		>> true  : room_count が存在
					*       >> false : room_count が存在しない
					*/
					if ($this->has_room_count($a_conditions)) {
						$b_vacant = true;
					} else {
						$b_vacant = false;
					}

					// ある期間において料金が存在しているかチェックします。
					/**
					* @param array
					*       a_conditions
					*			hotel_cd  施設コード
					*			room_cd   部屋コード
					*			date_ymd ->after  日付 > 日付以降宿泊 YYYY-MM-DD
					* @return bool
					* 		>> true  : room_charge が存在
					*       >> false : room_charge が存在しない
					*/
					if ($this->has_room_charge($a_conditions)) {
						$b_charge = true;
					} else {
						$b_charge = false;
					}

					//画面表示要素の付与
					if       ( $b_vacant and  $b_charge) {	$a_row[$n_cnt]->capacity_value = '○○';
					} elseif (!$b_vacant and  $b_charge) {	$a_row[$n_cnt]->capacity_value = '×○';
					} elseif ( $b_vacant and !$b_charge) {	$a_row[$n_cnt]->capacity_value = '○×';
					} elseif (!$b_vacant and !$b_charge) {	$a_row[$n_cnt]->capacity_value = '××';
					}
				}

                
				return $a_row;

			} catch (Exception $e) {
				throw $e;
			}
		}


		// MSD プラン一覧	
        /**
        * @param ---
        *      
        * @return array
        * 		plan_list		    結果内容
        *			pref_nm			都道府県
        *			hotel_cd		ホテルコード
        *			hotel_nm		ホテル名
        *			plan_cd			プランコード
        *			plan_nm			プラン名
        *			room_cd			部屋コード
        *			room_nm			部屋名
        *			capacity		部屋キャパシティ
        *			partner_group_id	提携先ID
        *			capacity_value		キャパシティ内容 -> ○○、×× etc
        *
        */
		public function planlistMethod()
		{

			try {
				$plan_list   = $this->_get_msd_plan();

				return $plan_list ;

			} catch (Exception $e) {
				throw $e;
			}
		}
      	
		// ある月に在庫数が存在しているかチェックします。
		/**
		* @param array
		*       aa_conditions
		*			hotel_cd  施設コード
		*			room_cd   部屋コード
		*			date_ymd->after  日付 > 日付以降宿泊の予約 YYYY-MM-DD
		* @return bool
		* 		>> true  : room_count が存在
        *       >> false : room_count が存在しない
		*/
		public function has_room_count($aa_conditions = array()){
			try {
				$s_sql =
<<<SQL
					select	room_count.hotel_cd
					from	room_count
					where	room_count.hotel_cd = :hotel_cd
						and	room_count.room_cd  = :room_cd
						and	room_count.date_ymd >= :after_date_ymd		
SQL;

				$a_conditions['hotel_cd'] = $aa_conditions['hotel_cd'];
				$a_conditions['room_cd']  = $aa_conditions['room_cd'];
                $a_conditions['after_date_ymd'] = $aa_conditions['date_ymd']['after'];

                 //クエリの発行
                $a_room_count = DB::select($s_sql, $a_conditions);

                return isset($a_room_count[0]->hotel_cd);

			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}
		}

		// ある期間において料金が存在しているかチェックします。
        /**
		* @param array
		*       aa_conditions
		*			hotel_cd  施設コード
		*			room_cd   部屋コード
		*			date_ymd ->after  日付 > 日付以降宿泊 YYYY-MM-DD
		* @return bool
		* 		>> true  : room_charge が存在
        *       >> false : room_charge が存在しない
		*/
		public function has_room_charge($aa_conditions = array()){
			try {
				$s_sql =
<<<SQL
					select	room_charge.hotel_cd,
							room_charge.room_cd,
							room_charge.plan_cd,
							room_charge.partner_group_id,
                            room_charge.date_ymd as date_ymd
							
					from	room_charge
					where	room_charge.hotel_cd = :hotel_cd
						and	room_charge.room_cd  = :room_cd
						and	accept_status = 1
						and	room_charge.date_ymd >= :after_date_ymd
SQL;

                $a_conditions['hotel_cd'] = $aa_conditions['hotel_cd'];
				$a_conditions['room_cd']  = $aa_conditions['room_cd'];
                $a_conditions['after_date_ymd'] = $aa_conditions['date_ymd']['after'];

                
                 //クエリの発行
                $a_room_charge = DB::select($s_sql, $a_conditions);

				if (count($a_room_charge) == 0){
					return false;
				}

				// 販売終了期間の判断用フラグ
				$b_accept_e_flg = false;
				// 料金の判断用フラグ
				$b_sales_charge_flg = false;
				
				
				for ($n_cnt = 0; $n_cnt < count($a_room_charge); $n_cnt++){
                        
                    $param=array();
                    $param['hotel_cd']=$a_room_charge[$n_cnt]->hotel_cd;
                    $param['room_cd']=$a_room_charge[$n_cnt]->room_cd;
                    $param['plan_cd']=$a_room_charge[$n_cnt]->plan_cd;
                    $param['date_ymd']=$a_room_charge[$n_cnt]->date_ymd;
                    $param['partner_group_id']=$a_room_charge[$n_cnt]->partner_group_id;
                  
                    // 施設の販売状態を無視して取得
                    $coreChargeService = new CoreChargeService; 

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
                    $coreChargeService->charges(false,$param); 
					
					// ある期間の料金の取得
					/**
					* @param array
					*		ab_only_sales
					*		 	true 料金の販売状態を考慮 false 料金の販売状態を無視
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
                    $a_charge = $coreChargeService->get_charges(false);

					$o_date=date('Y-m-d', strtotime($a_room_charge[$n_cnt]->date_ymd));

					// 料金の判断　※１プランでも販売していればtrue　0円以下は販売していない状態
					if (((empty($a_charge['values'][$o_date]['sales_charge'] )) == false) && $a_charge['values'][$o_date]['sales_charge']  >= 1){
						$b_sales_charge_flg = true;
					}

					// 販売終了期間の判断　※１プランでも販売していればtrue
					if(empty($a_charge['values'][$o_date]['accept_e_dtm']) == false ){
					$o_accept_e_dtm=$a_charge['values'][$o_date]['accept_e_dtm'];
					}

					if (((empty($a_charge['values'][$o_date]['sales_charge'] )) == false )&& date('YmdHis', strtotime($o_accept_e_dtm)) >= date('YmdHis')) {
						$b_accept_e_flg = true;
					}
				}
				
				// 全てのプランで販売期間又は料金がなければfalse
				if ($b_accept_e_flg == false || $b_sales_charge_flg == false){
					return false;
				}
				return true;

			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}
		}


	}

?>