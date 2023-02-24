<?php
namespace App\Models;

	class Models_Calendar
	{
		const SATDAY_NUM = 6; // 土曜日
		
		// メンバ変数定義
		protected $s_from_ymd;
		protected $s_to_ymd;
		protected $o_oracle;
		protected $a_charge_calendar;
		protected $a_define_day_of_week;
		
		//======================================================================
		// コンストラクタ
		//======================================================================
		public function __construct()
		{
			try {
				// 初期化
				$this->s_from_ymd        = null;
				$this->s_to_ymd          = null;
				$this->a_charge_calendar = array();
				$this->a_define_day_of_week = array('日', '月', '火', '水', '木', '金', '土');
				
				$this->o_oracle = _Oracle::getInstance();
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// Setter：対象期間の開始日
		//======================================================================
		public function set_from_ymd($as_from_ymd)
		{
			try {
				// エラーチェック
				if ( !is_numeric($as_from_ymd) and !is_string($as_from_ymd) ) {
					// エラーとする
					throw new Exception('開始日付に' . gettype($as_from_ymd) . 'が指定されています。数値または文字列(Y-m-d形式)で指定してください。');
				}
				
				// 文字列の場合
				if ( is_string($as_from_ymd) ) {
					$as_from_ymd = strtotime($as_from_ymd);
				}
				
				// 入力された日付が日付として正しくない場合はエラー
				if ( !checkdate(date('m', $as_from_ymd), date('d', $as_from_ymd), date('Y', $as_from_ymd)) ) {
					throw new Exception('開始日付が日付として正しくありません。');
				}
				
				// 指定された日付を設定
				$this->s_from_ymd = date('Y-m-d', $as_from_ymd);
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// Setter：対象期間の終了日
		//======================================================================
		public function set_to_ymd($as_to_ymd)
		{
			try {
				// エラーチェック
				if ( !is_numeric($as_to_ymd) and !is_string($as_to_ymd) ) {
					// エラーとする
					throw new Exception('開始日付に' . gettype($as_to_ymd) . 'が指定されています。数値または文字列(Y-m-d形式)で指定してください。');
				}
				
				// 文字列の場合
				if ( is_string($as_to_ymd) ) {
					$as_to_ymd = strtotime($as_to_ymd);
				}
				
				// 入力された日付が日付として正しくない場合はエラー
				if ( !checkdate(date('m', $as_to_ymd), date('d', $as_to_ymd), date('Y', $as_to_ymd)) ) {
					throw new Exception('終了日付が日付として正しくありません。');
				}
				
				// 指定された日付を設定
				$this->s_to_ymd = date('Y-m-d', $as_to_ymd);
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// Getter：指定の表示期間開始日の週の初日日付を取得
		//
		// @return String 設定された表示期間開始日
		//======================================================================
		public function get_from_ymd_week_first()
		{
			// 指定の開始日の週の日曜日を取得(※カレンダーは日曜日から表示)
			$n_from_ymd     = strtotime($this->s_from_ymd);
			$n_from_weekday = (int)date('w', $n_from_ymd);
			$s_from_day_sun = date('Y-m-d', strtotime('-' . $n_from_weekday .' day', $n_from_ymd));
			
			return $s_from_day_sun;
		}
		
		//======================================================================
		// Getter：指定の表示期間終了日の週の週末日付を取得
		//
		// @return String 設定された表示期間終了日
		//======================================================================
		public function get_to_ymd_week_last()
		{
			// 指定の終了日の週の土曜日を取得(※カレンダーは土曜日まで表示)
			$n_to_ymd     = strtotime($this->s_to_ymd);
			$n_to_weekday = (self::SATDAY_NUM) - (int)date('w', $n_to_ymd);
			$s_to_day_sat = date('Y-m-d', strtotime('+' . $n_to_weekday .' day', $n_to_ymd));
				
			return $s_to_day_sat;
		}
		
		//======================================================================
		// Getter：カレンダー
		//
		// @return Array 指定期間のカレンダー表示用データ
		//======================================================================
		public function get_calendar()
		{
			return $this->a_calendar;
		}
		
		
		//======================================================================
		// 指定期間のカレンダーを作成（表示用）
		// ※対象期間の開始日の週初めから終了日の週終わりまでのデータを生成
		//
		//======================================================================
		public function make_calendar()
		{
			try {
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$this->a_calendar = array();
				$a_tmp_calendar   = array();
				$a_conditions     = array();
				$s_sql            = '';
				$n_week_idx = 1;
				$n_row_idx  = 1;
				$n_key_ym   = '';
				$n_from_ymd = strtotime($this->s_from_ymd);
				$n_to_ymd   = strtotime($this->s_to_ymd);
				
				//--------------------------------------------------------------
				// データ取得
				// ※最終日が休前日の場合、判定できない為1日多く取得する
				//--------------------------------------------------------------
				$a_conditions = array(
					'from_ymd' => $this->get_from_ymd_week_first(),
					'to_ymd'   => date('Y-m-d', strtotime('+1 day', strtotime($this->get_to_ymd_week_last())))
				);
				
				$s_sql =
<<< SQL
					select	to_char(mc.date_ymd, 'YYYY-MM-DD') as date_ymd,
							mc.ymd,
							mc.holiday_nm
					from	mast_calendar mc
					where	mc.date_ymd between to_date(:from_ymd, 'YYYY-MM-DD') and to_date(:to_ymd, 'YYYY-MM-DD')
					order by	mc.date_ymd
SQL;
				$a_rows = $this->o_oracle->find_by_sql($s_sql, $a_conditions);
				
				//--------------------------------------------------------------
				// 必要な情報を追加
				//--------------------------------------------------------------
				foreach ( nvl($a_rows, array()) as $n_idx => $a_row ) {
					$a_tmp_calendar[$n_idx]            = array();
					$a_tmp_calendar[$n_idx]['ymd']     = $a_row['date_ymd'];
					$a_tmp_calendar[$n_idx]['ymd_num'] = strtotime($a_row['date_ymd']);
					$a_tmp_calendar[$n_idx]['ymd_str'] = date('Y', $a_tmp_calendar[$n_idx]['ymd_num']) . '年' . ltrim(date('m', $a_tmp_calendar[$n_idx]['ymd_num']), '0') . '月' . ltrim(date('d', $a_tmp_calendar[$n_idx]['ymd_num']), '0') . '日';
					$a_tmp_calendar[$n_idx]['md_str']  = mb_substr($a_tmp_calendar[$n_idx]['ymd_str'], 5);
					$a_tmp_calendar[$n_idx]['dow_num'] = (int)date('w', $a_tmp_calendar[$n_idx]['ymd_num']);
					$a_tmp_calendar[$n_idx]['dow_str'] = $this->a_define_day_of_week[$a_tmp_calendar[$n_idx]['dow_num']];
					
					// 対象日が編集範囲外
					if ( !($n_from_ymd <= $a_tmp_calendar[$n_idx]['ymd_num'] and $a_tmp_calendar[$n_idx]['ymd_num'] <= $n_to_ymd) ) {
						// 編集不可フラグを設定
						$a_tmp_calendar[$n_idx]['is_not_edit'] = true;
					}
					
					// 対象日が祝日の場合
					if ( !is_empty($a_row['holiday_nm']) ) {
						// 祝日フラグを設定
						$a_tmp_calendar[$n_idx]['is_hol'] = true;
						
						// 対象の前日に休前日フラグを設定
						if ( $n_idx > 0 ) {
							$a_tmp_calendar[$n_idx - 1]['is_bfo'] = true;
						}
					}
					
					unset($a_rows[$n_idx]);
				}
				
				// 1日多く取得した日を削除
				unset($a_tmp_calendar[$n_idx]);
				
				unset($a_rows);
				
				//--------------------------------------------------------------
				// 整形
				//--------------------------------------------------------------
				foreach ( nvl($a_tmp_calendar, array()) as $n_idx => $a_row ) {
					
					$n_key_ym = date('Ym', $a_row['ymd_num']);
					
					// 月ヘッダー表示用のデータを設定
					$this->a_calendar[$n_week_idx]['header_month'][$n_key_ym]['col_count'] = nvl($this->a_calendar[$n_week_idx]['header_month'][$n_key_ym]['col_count'], 0) + 1;
					
					if ( is_empty($this->a_calendar[$n_week_idx]['header_month'][$n_key_ym]['col_value']) ) {
						$this->a_calendar[$n_week_idx]['header_month'][$n_key_ym]['col_value'] = date('Y', $a_row['ymd_num']) . '年' . ltrim(date('m', $a_row['ymd_num']), '0') . '月';
					}
					
					// 各日のデータ設定
					$this->a_calendar[$n_week_idx]['values'][] = $a_row;
					
					if ( $n_row_idx % 7 == 0 ) {
					
						$n_week_idx++;
					}
					
					unset($a_tmp_calendar[$n_idx]);
					
					$n_row_idx++;
				}
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 料金登録用の期間データを作成
		// ※対象期間のデータを生成
		//
		//======================================================================
		public function make_update_charge_calendar()
		{
			try {
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$this->a_calendar = array();
				$a_conditions     = array();
				$s_sql            = '';
				$n_week_idx = 1;
				$n_row_idx  = 1;
				
				// 指定の開始日の週の日曜日を取得(※カレンダーは日曜日から表示)
				$n_from_ymd     = strtotime($this->s_from_ymd);
				$n_from_weekday = (int)date('w', $n_from_ymd);
				$s_from_day_sun = date('Y-m-d', strtotime('-' . $n_from_weekday .' day', $n_from_ymd));
				
				// 指定の終了日の週の土曜日を取得(※カレンダーは土曜日まで表示)
				$n_to_ymd     = strtotime($this->s_to_ymd);
				$n_to_weekday = (self::SATDAY_NUM) - (int)date('w', $n_to_ymd);
				$s_to_day_sat = date('Y-m-d', strtotime('+' . $n_to_weekday .' day', $n_to_ymd));
				
				//--------------------------------------------------------------
				// データ取得
				// ※最終日が休前日の場合、判定できない為1日多く取得する
				//--------------------------------------------------------------
				$a_conditions = array(
					'from_ymd' => $s_from_day_sun,
					'to_ymd'   => date('Y-m-d', strtotime('+1 day', strtotime($s_to_day_sat)))
				);
				
				$s_sql =
<<< SQL
					select	to_char(mc.date_ymd, 'YYYY-MM-DD') as date_ymd,
							mc.holiday_nm
					from	mast_calendar mc
					where	mc.date_ymd between to_date(:from_ymd, 'YYYY-MM-DD') and to_date(:to_ymd, 'YYYY-MM-DD')
					order by	mc.date_ymd
SQL;
				$a_rows = $this->o_oracle->find_by_sql($s_sql, $a_conditions);
				
				//--------------------------------------------------------------
				// 必要な情報を追加
				//--------------------------------------------------------------
				foreach ( nvl($a_rows, array()) as $n_idx => $a_row ) {
					$this->a_calendar[$n_idx]['ymd']     = $a_row['date_ymd'];
					$this->a_calendar[$n_idx]['ymd_num'] = strtotime($a_row['date_ymd']);
					$this->a_calendar[$n_idx]['ymd_str'] = date('Y', $this->a_calendar[$n_idx]['ymd_num']) . '年' . ltrim(date('m', $this->a_calendar[$n_idx]['ymd_num']), '0') . '月' . ltrim(date('d', $this->a_calendar[$n_idx]['ymd_num']), '0') . '日';
					$this->a_calendar[$n_idx]['md_str']  = mb_substr($this->a_calendar[$n_idx]['ymd_str'], 5);
					$this->a_calendar[$n_idx]['dow_num'] = (int)date('w', $this->a_calendar[$n_idx]['ymd_num']);
					$this->a_calendar[$n_idx]['dow_str'] = $this->a_define_day_of_week[$this->a_calendar[$n_idx]['dow_num']];
					
					// 対象日が編集範囲外
					if ( !($n_from_ymd <= $this->a_calendar[$n_idx]['ymd_num'] and $this->a_calendar[$n_idx]['ymd_num'] <= $n_to_ymd) ) {
						// 編集不可フラグを設定
						$this->a_calendar[$n_idx]['is_not_edit'] = true;
					}
					
					// 対象日が祝日の場合
					if ( !is_empty($a_row['holiday_nm']) ) {
						// 祝日フラグを設定
						$this->a_calendar[$n_idx]['is_hol'] = true;
						
						// 対象の前日に休前日フラグを設定
						if ( $n_idx > 0 ) {
							$this->a_calendar[$n_idx - 1]['is_bfo'] = true;
						}
					}
					
					unset($a_rows[$n_idx]);
				}
				
				// 1日多く取得した日を削除
				unset($this->a_calendar[$n_idx]);
				
				return $this->a_calendar;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 料金登録用の期間データを作成
		// ※対象期間のデータを生成（第1週、第2週..のインデックスを持たない形）
		//
		//======================================================================
		public function make_line_calendar()
		{
			try {
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$this->a_calendar = array();
				$a_conditions     = array();
				$o_date           = new DateTime();
				$s_sql            = '';
				$n_week_idx = 1;
				$n_row_idx  = 1;
				
				// 指定の開始日の週の日曜日を取得(※カレンダーは日曜日から表示)
				$n_from_ymd     = strtotime($this->s_from_ymd);
				
				// 指定の終了日の週の土曜日を取得(※カレンダーは土曜日まで表示)
				$n_to_ymd     = strtotime($this->s_to_ymd);
				
				//--------------------------------------------------------------
				// データ取得
				// ※最終日が休前日の場合、判定できない為1日多く取得する
				//--------------------------------------------------------------
				$a_conditions = array(
					'from_ymd' => $this->s_from_ymd,
					'to_ymd'   => date('Y-m-d', strtotime('+1 day', strtotime($this->s_to_ymd)))
				);
				
				$s_sql =
<<< SQL
					select	to_char(mc.date_ymd, 'YYYY-MM-DD') as date_ymd,
							mc.holiday_nm
					from	mast_calendar mc
					where	mc.date_ymd between to_date(:from_ymd, 'YYYY-MM-DD') and to_date(:to_ymd, 'YYYY-MM-DD')
					order by	mc.date_ymd
SQL;
				$a_rows = $this->o_oracle->find_by_sql($s_sql, $a_conditions);
				
				//--------------------------------------------------------------
				// 必要な情報を追加
				//--------------------------------------------------------------
				foreach ( nvl($a_rows, array()) as $n_idx => $a_row ) {
					$this->a_calendar[$n_idx]['ymd']        = $a_row['date_ymd'];
					$this->a_calendar[$n_idx]['ymd_num']    = strtotime($a_row['date_ymd']);
					$this->a_calendar[$n_idx]['ymd_str']    = date('Y', $this->a_calendar[$n_idx]['ymd_num']) . '年' . ltrim(date('m', $this->a_calendar[$n_idx]['ymd_num']), '0') . '月' . ltrim(date('d', $this->a_calendar[$n_idx]['ymd_num']), '0') . '日';
					$this->a_calendar[$n_idx]['md_str']     = mb_substr($this->a_calendar[$n_idx]['ymd_str'], 5);
					$this->a_calendar[$n_idx]['dow_num']    = (int)date('w', $this->a_calendar[$n_idx]['ymd_num']);
					$this->a_calendar[$n_idx]['dow_str']    = $this->a_define_day_of_week[$this->a_calendar[$n_idx]['dow_num']];
					$this->a_calendar[$n_idx]['ymd_mn_num'] = strtotime('+30 hour', strtotime($this->a_calendar[$n_idx]['ymd']));
					
					// 対象日が編集範囲外
					if ( !($n_from_ymd <= $this->a_calendar[$n_idx]['ymd_num'] and $this->a_calendar[$n_idx]['ymd_num'] <= $n_to_ymd) ) {
						// 編集不可フラグを設定
						$this->a_calendar[$n_idx]['is_not_edit'] = true;
					}
					
					// 対象日が祝日の場合
					if ( !is_empty($a_row['holiday_nm']) ) {
						// 祝日フラグを設定
						$this->a_calendar[$n_idx]['is_hol'] = true;
						
						// 対象の前日に休前日フラグを設定
						if ( $n_idx > 0 ) {
							$this->a_calendar[$n_idx - 1]['is_bfo'] = true;
						}
					}
					
					unset($a_rows[$n_idx]);
				}
				
				// 1日多く取得した日を削除
				unset($this->a_calendar[$n_idx]);
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
	} //-->
?>