<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;


class BrBlackListService
{

    
		// ブラックリスト(予約者氏名)
		private $_sa_black_list_reservers = array(
			0 => '三坂 伸也',
			1 => '三坂 利佳',
			2 => '升本 忠宏',
			3 => '升本 知代子',
			4 => '岡崎 哲也',
			5 => '岡崎 容子',
			6 => '三島 昭吾',
			7 => '三島 恵',
		);
		
		// 宿泊者氏名
		private $_sa_black_list_stayers = array(
			 0 => 'みさか　しんや',
			 1 => 'みさか　りか',
			 2 => 'ますもと　ただひろ',
			 3 => 'ますもと　ちよこ',
			 4 => 'おかざき　てつや',
			 5 => 'おかざき　ようこ',
			 6 => 'みしま　しょうご',
			 7 => 'みしま　めぐみ',
			 8 => 'ミサカ　シンヤ',
			 9 => 'ミサカ　リカ',
			10 => 'マスモト　タダヒロ',
			11 => 'マスモト　チヨコ',
			12 => 'オカザキ　テツヤ',
			13 => 'オカザキ　ヨウコ',
			14 => 'ミシマ　ショウゴ',
			15 => 'ミシマ　メグミ',
			16 => 'みさかしんや',
			17 => 'みさかりか',
			18 => 'ますもとただひろ',
			19 => 'ますもとちよこ',
			20 => 'おかざきてつや',
			21 => 'おかざきようこ',
			22 => 'みしましょうご',
			23 => 'みしまめぐみ',
			24 => 'ミサカシンヤ',
			25 => 'ミサカリカ',
			26 => 'マスモトタダヒロ',
			27 => 'マスモトチヨコ',
			28 => 'オカザキテツヤ',
			29 => 'オカザキヨウコ',
			30 => 'ミシマショウゴ',
			31 => 'ミシマメグミ',
		);

		private   $_s_error = null;
		protected $_assign  = null;

	
		
		// 宿泊者情報テーブルから当該監視対象者の情報を検索
        /**
        * @param array
        *      ao_from_date     検索開始日時
        *		ao_to_date      検索終了日時
        * @return array
        * 		$a_result		一覧結果内容
        */
		private function _get_stay_info($ao_from_date, $ao_to_date)
		{
			try {
				
				// 初期化
				$a_result = array();
				
				// バインドパラメータ設定
				$a_conditions = array(
                    'reserve_from'    => date('Y-m-d ', $ao_from_date),
					'reserve_to'      => date('Y-m-d ', $ao_to_date),
				);

				
				$s_sql = 
<<< SQL
					select	reserve_guest.guest_nm,
							reserve_guest.check_in,
							reserve_guest.reserve_cd,
							reserve.date_ymd,
							reserve.hotel_cd,
							reserve.member_cd,
							reserve.guests,
							reserve.reserve_dtm,
							reserve.cancel_dtm,
							reserve.reserve_status,
							reserve.partner_ref,
							hotel.hotel_nm
					from	reserve_guest, reserve, hotel
					where	reserve_guest.reserve_cd in(
							select	reserve_cd
							from	reserve
							where	reserve_dtm between  :reserve_from and :reserve_to
						)
						and reserve_guest.reserve_cd = reserve.reserve_cd
						and hotel.hotel_cd = reserve.hotel_cd
SQL;

                 //クエリの発行
                $a_sql_result = DB::select($s_sql, $a_conditions);
				
				// 宿泊者名と一致するかどうかチェックして一致したものだけ返す
				foreach ($a_sql_result as $key => $value) {
					if (in_array($value->guest_nm, $this->_sa_black_list_stayers) == true) {
						$a_result[] = $a_sql_result[$key];
					}
				}
				
				return $a_result;

			} catch (Exception $e) {
				throw $e;
			}
		}
		
 		// 会員詳細情報から当該監視対象者の情報を検索
        /**
        * @param array
        *       ao_from_date 検索開始日時
        *		ao_to_date   検索終了日時
        * @return array
        * 		$a_result		一覧結果内容
        */
		private function _get_reserve_info($ao_from_date, $ao_to_date)
		{
			try {
				
				// 初期化
				$a_result = array();
			
                $a_conditions = array(
                    'reserve_from'    => date('Y-m-d ', $ao_from_date),
					'reserve_to'      => date('Y-m-d ', $ao_to_date),
				);
				
				$s_sql = 
<<< SQL
					select	reserve_guest.guest_nm,
							reserve_guest.check_in,
							reserve_guest.reserve_cd,
							reserve.date_ymd,
							reserve.hotel_cd,
							reserve.member_cd,
							reserve.guests,
							reserve.reserve_dtm,
							reserve.cancel_dtm,
							reserve.reserve_status,
							reserve.partner_ref,
							hotel.hotel_nm,
							member_detail.family_nm,
							member_detail.given_nm,
							member_detail.email
					from	reserve_guest, reserve, member_detail, hotel
					where	reserve_guest.reserve_cd in(
							select	reserve_cd
							from	reserve
							where	reserve_dtm between  :reserve_from and :reserve_to
						)
						and reserve_guest.reserve_cd = reserve.reserve_cd
						and hotel.hotel_cd = reserve.hotel_cd
						and reserve.member_cd = member_detail.member_cd
SQL;

                //クエリの発行
                $a_sql_result = DB::select($s_sql, $a_conditions);

// 				// 予約者名と一致するかどうかチェックして一致したものだけ返す
				foreach ($a_sql_result as $key => $value) {
					
					$s_temp_name = $value->family_nm . " " . $value->given_nm;
					
					if (in_array($s_temp_name, $this->_sa_black_list_reservers) == true) {
						$a_result[] = $a_sql_result[$key];
					}
					
					if (in_array($value->guest_nm, $this->_sa_black_list_stayers) == true) {
						$a_result[] = $a_sql_result[$key];
					}
				}
				
				return $a_result;

			} catch (Exception $e) {
				throw $e;
			}
		}

		
		// 監視対象者で指定期間内に予約を行った人物の関連情報を取得する
        /**
         * @param array 
         * 			$aa_form_set_params 検索用パラメータ　
         *				date_ymd		検索日時
        * @return array
        * 			$result				結果内容
        * 				guest_nm		宿泊代表者
        * 				check_in		予約コード
        * 				reserve_cd		施設コード
        * 				date_ymd		宿泊日
        * 				hotel_cd		ホテルコード
        * 				member_cd		メンバーコード
        * 				guests			宿泊人数
        * 				reserve_dtm		予約日付
        * 				cancel_dtm		キャンセル日付
        * 				reserve_status	予約状況　（0予約、1キャンセル、2電話キャンセル、4無断不泊）
        * 				partner_ref		予約詳細画面使用の値
        * 				hotel_nm		ホテル名泊
        */
		public function listMethod($aa_form_set_params)
		{
			try {
				
				// 検索期間開始日時を設定
				$s_temp_from_date = mktime( 0, 
                                            0,
                                            0,
                                            $aa_form_set_params['date_ymd']['search_mon_from'],
                                            $aa_form_set_params['date_ymd']['search_day_from'], 
                                            $aa_form_set_params['date_ymd']['search_year_from']
				);
				
				
				
				// 検索期間の終了日時を指定
				$s_temp_to_date = mktime( 23,
                                          59,
                                          59,
                                          $aa_form_set_params['date_ymd']['search_mon_to'],
                                          $aa_form_set_params['date_ymd']['search_day_to'], 
                                          $aa_form_set_params['date_ymd']['search_year_to']
				);
				
				// 宿泊者テーブルから検索
                /**
                * @param array
                *       s_temp_from_date 検索開始日時
                *		s_temp_to_date   検索終了日時
                * @return array
                * 		a_result		一覧結果内容
                */
				$a_stay_list    = $this->_get_stay_info($s_temp_from_date, $s_temp_to_date);
				
				// 予約テーブルから検索
                /**
                * @param array
                *       s_temp_from_date 検索開始日時
                *		s_temp_to_date   検索終了日時
                * @return array
                * 		a_result		一覧結果内容
                */
				$a_reserve_list = $this->_get_reserve_info($s_temp_from_date, $s_temp_to_date);
				
				$result = array_merge($a_stay_list, $a_reserve_list);
			

                return $result;
				
			} catch (Exception $e) {
				throw $e;
			}
        }

}