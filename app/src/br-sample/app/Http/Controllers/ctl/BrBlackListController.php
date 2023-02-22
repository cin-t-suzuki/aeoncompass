<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Services\BrBlackListService;
	
	class BrBlackListController extends _commonController
	{

		// セレクトボックスの年の最小値
		private $_reserve_select_year = '2000-01-01';

		private $form_param=array();

		//セレクトボックスの選択肢、年、月、日
		private $selectbox_y=array();
		private $selectbox_m=array();
		private $selectbox_d=array();

		// 検索、一覧表示
		public function list(Request $request,BrBlackListService $brBlackListService)
		{

			try {
				
			// 	 年のセレクトボックスの基準年
				$s_basic_dtm = $this->_reserve_select_year;

			// 	 予約受付日セレクトボックス用配列

				for($i=substr($s_basic_dtm, 0, 4) ;$i<=date('Y');$i++){
					$selectbox_y[]= $i;
				}
				for($i=1 ;$i<=12;$i++){
					$selectbox_m[]= $i;
				}
				for($i=1 ;$i<=31;$i++){
					$selectbox_d[]= $i;
				}
				
				
			// 	現在日時と1週間前の日時を取得
				$o_today_date  = date("Y-m-d");
				$o_last_week   = date("Y-m-d", strtotime("-7 day"));

				
			// 	 検索に使用するパラメータ設定
				$form_param    = array();

			// 	 日付指定がない場合は直近1週間を表示する

			/**　@param array
			 * 
			 *		form_param->date_ymd
			 *  	 	search_year_from:　検索日付、年
			 *  	 	search_mon_from:　検索日付、月
			 *  	 	search_day_from:　検索日付、日
			 *   		search_year_to:　検索日付、年 
			 *  	 	search_mon_to:　検索日付、月 
			 *   		search_day_to:　検索日付、日 
			 */
				if (empty($request['date_ymd']) == true) {
					$form_param['date_ymd']['search_year_from'] = date('Y', strtotime($o_last_week));       // 1週間前の日時（年）
				}else{
					$form_param['date_ymd']['search_year_from'] =$request['date_ymd']['search_year_from'];
				}
				
				if (empty($request['date_ymd']) == true) {
					$form_param['date_ymd']['search_mon_from']  = date('m', strtotime($o_last_week));        // 1週間前の日時（月）
				}else{
					$form_param['date_ymd']['search_mon_from'] =$request['date_ymd']['search_mon_from'];
				}
				if (empty($request['date_ymd']) == true) {
					$form_param['date_ymd']['search_day_from']  = date('d', strtotime($o_last_week));       // 1週間前の日時（日）
				}else{
					$form_param['date_ymd']['search_day_from'] =$request['date_ymd']['search_day_from'];
				}
				
				if (empty($request['date_ymd']) == true) {
					$form_param['date_ymd']['search_year_to']   =  date('Y', strtotime($o_today_date));    // 本日の日時（年）
				}else{
					$form_param['date_ymd']['search_year_to'] =$request['date_ymd']['search_year_to'];
				}
				
				if (empty($request['date_ymd']) == true) {
					$form_param['date_ymd']['search_mon_to']    = date('m', strtotime($o_today_date));      // 本日の日時（月）
				}else{
					$form_param['date_ymd']['search_mon_to'] =$request['date_ymd']['search_mon_to'];
				}
				
				if (empty($request['date_ymd']) == true) {
					$form_param['date_ymd']['search_day_to']    = date('d', strtotime($o_today_date));        // 本日の日時（日）
				}else{
					$form_param['date_ymd']['search_day_to'] =$request['date_ymd']['search_day_to'];
				}

				
			//  予約状況一覧取得
			/**
			 * @param array 
			 * 			$form_param　　
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
			$result=$brBlackListService->listMethod($form_param);

			// ビューを表示
			return view("ctl.brblacklist.list",compact('form_param','selectbox_y','selectbox_m','selectbox_d','result'));
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		
		public function preDispatch()
		{
			try{
				
				// ログインチェック（社内）
				parent::brDispatch();

			} catch (Exception $e) { // 各メソッドで Exception が投げられた場合
				throw $e;
			}
		}

		// インデックス
		public function indexAction()
		{
			try{
				
				$this->_forward('list');
				
			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		
	}
?>