<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;
use App\Models\BroadcastMessage;
use App\Models\BroadcastMessagesHotel;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Exception;
use Illuminate\Support\Facades\Log; 
use App\Common\DateUtil;
use App\Common\Traits;

use Illuminate\Support\Facades\Cookie;

/**
 * 管理画面_施設管理TOPお知らせ情報管理
 */
class BrbroadcastMessageController extends _commonController
{
	use Traits;

	// cookie 関連
	protected $pointPlusCreateCookieName  = 'CBRBCM1709';
	private   $pointPlusCreateCookieValue = '1';
	private   $pointPlusCreateCookiePath  = '/ctl/brbroadcastMessage/';	

		/** 検索画面 index=list
		 */
		public function index()
		{
			// 検索処理
			$broadcastMessage = new BroadcastMessage();
			$broadcastMessageList = $broadcastMessage->getList();

			// ビュー情報を設定
			$this->addViewData("broadcastMessages", $broadcastMessageList);
			// ビューを表示
			return view("ctl.brbroadcastMessage.list", $this->getViewData());
		}

		/** 詳細画面
		 */
		public function detail() 
		{
			$request = Request::all();
			if(count($request) > 0){
				$brbroadcast_id = isset($request["brbroadcast_id"]) ? trim($request["brbroadcast_id"]) : "";
			}
			$this->getDetailDbData($broadcastMessageDetailData, $brbroadcast_id);

			// ビュー情報を設定
			$this->addViewData("broadcastMessageDetail", $broadcastMessageDetailData);

			// ビューを表示
			return view("ctl.brbroadcastMessage.detail", $this->getViewData());
		}

		/** 詳細データをDBから取得する
		 */
		private function getDetailDbData(&$broadcastMessageDetailData, $brbroadcast_id)
		{
			if(isset($brbroadcast_id)) {
				// DB検索
				$broadcastMessage = new BroadcastMessage();
				$broadcastMessageDetailData = $broadcastMessage->getDetail($brbroadcast_id);

				//      管理画面お知らせ施設 の情報を 管理画面お知らせ にセットする
				$broadcastMessagesHotel = new BroadcastMessagesHotel();
				$broadcastMessagesHotelList = $broadcastMessagesHotel->selectByBroadcastMessagesId($brbroadcast_id);
				$targetHotelList = array();
				foreach ($broadcastMessagesHotelList as $broadcast_messages_hotel) {
					$targetHotelList[] = $broadcast_messages_hotel->hotel_cd;
				}
				if(count($targetHotelList) > 0) {
					$broadcastMessageDetailData['target_hotels'] = implode("\n", $targetHotelList); 
				} else {
				$broadcastMessageDetailData['target_hotels'] = '';
				}
			}
		}

		/** 登録画面表示
		 */
		public function new()
		{
			// cookieクリア
			$this->revokePointPlusCookie();

			$this->setViewFromDetailData();
			
			// 画面項目準備
			$this->setViewDataSelecter();
			// ビューを表示
			return view("ctl.brbroadcastMessage.new", $this->getViewData());
		}

		/**登録用プルダウンデータを作成し、画面設定
		 */
		private function setViewDataSelecter()
		{
			$acceptHeaderYmdSelecter  = $this->makeYmdSelecter();
			$acceptYmdSelecter       = $this->makeYmdSelecter();
			$acceptHeaderTimeSelecter = $this->makeTimeSelecter();
			$acceptTimeSelecter = $this->makeTimeSelecter();

			// ビュー情報を設定
			$this->addViewData("accept_header_ymd_selecter", $acceptHeaderYmdSelecter);
			$this->addViewData("accept_ymd_selecter", $acceptYmdSelecter); 
			$this->addViewData("accept_header_time_selecter", $acceptHeaderTimeSelecter); //2種類データ
			$this->addViewData("accept_time_selecter", $acceptTimeSelecter);
		}

		/** 詳細表示のデータを取得しViewにセット
		 * 
		 */
		private function setViewFromDetailData($existBroadcastMessageData = null)
		{
			if(isset($existBroadcastMessageData)) {
					//  データある場合
					$form_params           = $existBroadcastMessageData;
			}else{

				// 新規
				$form_params['header_message']           = '';
				
				$form_params['title']               = '';
				$form_params['description']         = '';
				$form_params['target_hotels']   = '';
				
				// 新規表示用項目設定のみ
				$form_params['accept_header_s_time'] = '';
				$form_params['accept_header_e_time'] = '';
				$form_params['accept_s_time'] = '';
				$form_params['accept_e_time'] = '';

				$form_params['accept_header_s_year']  = date('Y');
				$form_params['accept_header_s_month'] = date('n');
				$form_params['accept_header_s_day']   = date('j');
				
				$form_params['accept_header_e_year']  = date('Y');
				$form_params['accept_header_e_month'] = date('n');
				$form_params['accept_header_e_day']   = date('j');

				$form_params['accept_s_year']  = date('Y');
				$form_params['accept_s_month'] = date('n');
				$form_params['accept_s_day']   = date('j');
				
				$form_params['accept_e_year']  = date('Y');
				$form_params['accept_e_month'] = date('n');
				$form_params['accept_e_day']   = date('j');
			}
			
			// ビュー情報を設定
			$this->addViewData("form_params", $form_params);
		}

		/** 変更画面表示
		 */
		public function edit()
		{
			// cookieクリア
			$this->revokePointPlusCookie();

			// 画面項目準備
			$this->setViewDataSelecter();

			$request = Request::all();
			if(count($request) > 0){
				$brbroadcast_id = isset($request["brbroadcast_id"]) ? trim($request["brbroadcast_id"]) : "";
			}

			$this->getDetailDbData($broadcastMessageDetailData, $brbroadcast_id);

			// ビュー情報を設定
			$this->addViewData("form_params", $broadcastMessageDetailData);

			// ビューを表示
			return view("ctl.brbroadcastMessage.edit", $this->getViewData());
		}


		/** 登録画面表示
		 */
		public function create()
		{
			
			// 完了画面でリロードした時の対策
			if($this->existsMedialistViewCookie()) {
				$this->addErrorMessage("お知らせ情報は既に登録済みです。");
				return $this->index();
			}
			
			// cookie登録
			$this->issuePointPlusCookie();
			
			$request = Request::all();
			$form_params = $request;

			if(!isset($request)){
				$this->addErrorMessage("登録パラメータが存在しません");
				return $this->new(); 
			}

			// モデル
			$broadcastMessage = new BroadcastMessage();
			//------brbroadcast_idの発番-------//
			$nextBroadcastMessageId = $broadcastMessage->getNextId();
			
			// 画面入力情報 の編集と準備
			$this->setBroadcastMessageFromScreen($broadcastMessageData, $hotelList, $form_params, $nextBroadcastMessageId);

			// 単項目チェック
			$errorArr = $broadcastMessage->validation($broadcastMessageData);

			// 相関チェック
			if( count($errorArr) > 0 || ! $this->checkRelation($broadcastMessageData))
			{       //単項目か相関チェックでエラーがあれば
				$this->addErrorMessageArray($errorArr);
				$this->setViewFromDetailData($request);
				return view("ctl.brbroadcastMessage.new", $this->getViewData());
			}
			
			// 登録処理 broadcast_message、 broadcast_message_hotel
			try{
				$con = DB::connection('mysql');
				$dbErr = $con->transaction(function() use($con, $broadcastMessage, $broadcastMessageData, $hotelList) 
				{
					$broadcastMessage->setInsertCommonColumn($broadcastMessageData, 'BrbroadcastMessage/create.');
					$broadcastMessage->singleInsert($con, $broadcastMessageData);

					// 不要となった
						//$errorArr = $this->setBroadcastMessagesHotelData($con, $broadcastMessageData['id'], $hotelList);
						//if( count($errorArr) > 0)
						//{
						//	throw new Exception("入力値チェックにエラーがあります。");
						//}
					//↑施設コード処理 end

				});

			} catch (Exception $e) {
				Log::error($e);
				$this->addErrorMessage("登録中に例外が発生しました");
				$this->setViewFromDetailData($request);
				return view("ctl.brbroadcastMessage.new", $this->getViewData());
			}

			// 登録時エラー
			if (!empty($dbErr)){
				$this->addErrorMessage($dbErr);
				$this->setViewFromDetailData($request);
				return view("ctl.brbroadcastMessage.new", $this->getViewData());
			}
			// 画面再表示データ取得
			$this->getDetailDbData($broadcastMessageDetailData, $nextBroadcastMessageId);

			// ビュー情報を設定
			$this->addViewData("broadcastMessageDetail", $broadcastMessageDetailData);
			$this->addGuideMessage("施設管理TOPお知らせ情報を作成しました。");
			// ビューを表示
			return view("ctl.brbroadcastMessage.detail", $this->getViewData());

		}

		//↓不要
		/* （未使用）施設登録
		*   →不要となった
		*/
		/*private function setBroadcastMessagesHotelData($con, 
		 $broadcastMessageId, $hotelList)
		{
			// 施設コード処理 
			$broadcastMessagesHotel = new BroadcastMessagesHotel();
			$hotel = new Hotel();
			$errorArr = [];
			foreach ($hotelList as $idx => $hotelCd) {
				
				$hotelCd = trim($hotelCd, "\t\n\r\0\x0B　");
				if($hotelCd =="") {
						continue;
				}
				$broadcastMessagesHotelData = []; //クリア
				$errorArr = $this->getBroadcastmessageHotelFromScreen(
				$broadcastMessagesHotelData, $hotelCd, $broadcastMessagesHotel, $broadcastMessageId, $idx, $hotel);

				if( count($errorArr) <= 0 ){
					// INSERT  broadcast_message_hotel
					$broadcastMessagesHotel->singleInsert($con, $broadcastMessagesHotelData);			
				}
			}
			return $errorArr;
		}*/

		/** （未使用）登録/変更画面の入力値 施設コードを取得する。
		 * ・hotelCdで存在チェック
		 *  →不用となった
		 */
		/*private function getBroadcastmessageHotelFromScreen(
			&$broadcastMessagesHotelData,
			$hotelCd, $broadcastMessagesHotel, $broadcastMessageId, $idx, $hotel)
		{
			$a_next_broadcast_msg_hotel_id = $broadcastMessagesHotel->getNextId();
			//画面に表示されてしまうので不要 			var_dump($a_next_broadcast_msg_hotel_id);

			$broadcastMessagesHotelData['broadcast_messages_hotel_id']     = $a_next_broadcast_msg_hotel_id;
			$broadcastMessagesHotelData['broadcast_messages_id']           = $broadcastMessageId;
			$broadcastMessagesHotelData['hotel_cd']                       = $hotelCd;
			$broadcastMessagesHotelData['order_number']                   = $idx+1;

			//Broadcast_Messages_Hotelバリデーションチェック
			$errorArr = $broadcastMessagesHotel->validation($broadcastMessagesHotelData);
			if( count($errorArr) > 0 ){
				return $errorArr;
			}
			// hotelCdで存在チェック
			$a_cnt_data = $hotel->selectByKey($hotelCd);

			if( !isset($a_cnt_data) || count($a_cnt_data) <= 0){
				$errorArr[] = "施設CD[". "{$hotelCd}" ."]の施設は存在しません。";
				return $errorArr;
			}
			
			$broadcastMessagesHotel->setInsertCommonColumn($broadcastMessagesHotelData ,'BrbroadcastMessageHotel/update.');
			return $errorArr;
		}*/
		//↑不要

		/** 登録/変更画面の入力値 お知らせを取得する。
		 */
		private function setBroadcastMessageFromScreen(&$broadcastMessageData, &$hotelList, $form_params, $broadcast_message_id)
		{
			if($this->is_empty($form_params['header_message'])) {
				$form_params['accept_header_s_year'] = null;
				$form_params['accept_header_s_month'] = null;
				$form_params['accept_header_s_day'] = null;
				$form_params['accept_header_s_time'] = null;
				$form_params['accept_header_e_year'] = null;
				$form_params['accept_header_e_month'] = null;
				$form_params['accept_header_e_day'] = null;
				$form_params['accept_header_e_time'] = null;
			}

			$broadcastMessageData['id']                   = $broadcast_message_id;
			$broadcastMessageData['header_message']       = $form_params['header_message'];
			$broadcastMessageData['title']                = $form_params['title'];
			$broadcastMessageData['description']          = $form_params['description'];

			if(!$this->is_empty($form_params['header_message'])) {
				$broadcastMessageData['accept_header_s_dtm']    =$this->getFormatedDate(
						$form_params['accept_header_s_year']
						, $form_params['accept_header_s_month']
						, $form_params['accept_header_s_day']
						, $form_params['accept_header_s_time']);
				$broadcastMessageData['accept_header_e_dtm']    = $this->getFormatedDate(
						$form_params['accept_header_e_year']
						, $form_params['accept_header_e_month']
						, $form_params['accept_header_e_day']
						, $form_params['accept_header_e_time']);
			} else {
				$broadcastMessageData['accept_header_s_dtm']    = null;
				$broadcastMessageData['accept_header_e_dtm']    = null;
			}
			if(!$this->is_empty($form_params['accept_s_year']) && !$this->is_empty($form_params['accept_s_month'])  
				&& !$this->is_empty($form_params['accept_s_day']) && !$this->is_empty($form_params['accept_s_time']))
			{
				$broadcastMessageData['accept_s_dtm']         = $this->getFormatedDate(
						$form_params['accept_s_year']
						, $form_params['accept_s_month']
						, $form_params['accept_s_day']
						, $form_params['accept_s_time'] ); 
			}else{
					$broadcastMessageData['accept_s_dtm'] = null;
			}
			if(!$this->is_empty($form_params['accept_e_year']) && !$this->is_empty($form_params['accept_e_month']) 
					&& !$this->is_empty($form_params['accept_e_day']) && !$this->is_empty($form_params['accept_e_time'])){
					$broadcastMessageData['accept_e_dtm']         = $this->getFormatedDate(
							$form_params['accept_e_year']
							, $form_params['accept_e_month']
							, $form_params['accept_e_day']
							, $form_params['accept_e_time']);
			}else{
					$broadcastMessageData['accept_e_dtm'] = null;
			}

			// 特定施設指定を取得
			$hotelList = explode("\n", $form_params['target_hotels']);

		}

		/** 相関チェック
		 */ 
		private function checkRelation($form_params) 
		{
			$result = true;
			//期間の整合性チェック(宿泊日－開始日と宿泊日－終了日との比較)
			if(isset($form_params['n_accep_e_dtm']) && isset($form_params['$n_accep_s_dtm'])) {
				if ($form_params['n_accep_e_dtm'] < $form_params['n_accep_s_dtm']) {
						$this->addErrorMessage('お知らせ欄表示期間(終了日時)はお知らせ欄表示期間(開始日時)より先日付時間で設定してください。');
						$result = false;
				}
			}
				//          header_message が設定されていれば必須
			if(!$this->is_empty($form_params['header_message'])) {
				if($this->is_empty($form_params['accept_header_s_dtm']) ) {
					$this->addErrorMessage('ページ上部表示期間(開始日時)は必須です。');
					$result = false;
				}

				if($this->is_empty($form_params['accept_header_e_dtm']) ) {
					$this->addErrorMessage('ページ上部表示期間(終了日時)は必須です。');
					$result = false;
				}
			} 
			return $result;
		}

		/** 更新処理
		 */
		public function update()
		{
			///	cookie チェック	//完了画面でリロードした時の対策
			if($this->existsMedialistViewCookie()) {
				$this->addErrorMessage("お知らせ情報は既に変更済みです。");
				return $this->index(); // 一覧に戻る
			} 

			// cookie 登録
			$this->issuePointPlusCookie();

			$request = Request::all();
			$form_params = $request;
			
			//------brbroadcast_idの取得-------//
			$broadcast_message_id = $request['brbroadcast_id'];

			$broadcastMessage = new BroadcastMessage();
			
			// 画面入力情報 の編集と準備
			$this->setBroadcastMessageFromScreen($broadcastMessageData, $hotelList, $form_params, $broadcast_message_id);

			// 単項目チェック
			$errorArr = $broadcastMessage->validation($broadcastMessageData);

			// 相関チェック
			if( count($errorArr) > 0 || ! $this->checkRelation($broadcastMessageData))
			{       //単項目か相関チェックでエラーがあれば
				$this->addErrorMessageArray($errorArr);
				$this->setViewFromDetailData($request);
				return view("ctl.brbroadcastMessage.edit", $this->getViewData());
			}

			// 登録処理 broadcast_message
			try{
				$con = DB::connection('mysql');
				$dbErr = $con->transaction(function() use($con, $broadcastMessage, $broadcastMessageData, $hotelList) 
				{
					$broadcastMessage->setUpdateCommonColumn($broadcastMessageData, 'BrbroadcastMessage/update.');
					// 変更処理 
					$broadcastMessage->singleUpdate($con, $broadcastMessageData);

					// 施設コード処理  BroadcastMessagesHotel の登録キー取得
					$broadcastMessagesHotel = new BroadcastMessagesHotel();
					
					// delete key 取得し削除
					$broadcastMessagesHotelList = $broadcastMessagesHotel->selectByBroadcastMessagesId($broadcastMessageData['id']);
					foreach ($broadcastMessagesHotelList as $data) {
						// key でdelete 
						$broadcastMessagesHotel->deleteByKey($con, $data->broadcast_messages_hotel_id);
					}

					// 不用となった BroadcastMessagesHotel 登録
						//$errorArr = $this->setBroadcastMessagesHotelData($con,$broadcastMessageData['id'], $hotelList);
						//if( count($errorArr) > 0)
						//{
						//	$this->addErrorMessageArray($errorArr);
						//	throw new Exception("入力値チェックにエラーがあります。");
						//}
					//↑施設コード処理 end

				});

			} catch (Exception $e) {
				Log::error($e);
				$this->addErrorMessage("登録中にエラーが発生しました");
				$this->setViewDataSelecter();
				$this->setViewFromDetailData($request);
				return view("ctl.brbroadcastMessage.edit", $this->getViewData());
			}

			// 登録時エラー
			if (!empty($dbErr)){
				$this->addErrorMessage($dbErr);
				$this->setViewDataSelecter();
				$this->setViewFromDetailData($request);
				return view("ctl.brbroadcastMessage.edit", $this->getViewData());
			}
			// 画面再表示
			$this->getDetailDbData($broadcastMessageDetailData, $broadcast_message_id);

			// ビュー情報を設定
			$this->addViewData("broadcastMessageDetail", $broadcastMessageDetailData);
			$this->addGuideMessage("施設管理TOPお知らせ情報を変更しました。");
			// ビューを表示
			return view("ctl.brbroadcastMessage.detail", $this->getViewData());
		}
		
		/** 論理削除処理
		 * ・各終了日付を昨日にして更新する
		 * （お知らせとしては非表示状態だが、一覧では一番上に表示される）
		 *
		 * @return void
		 */
		public function destroy()
		{
			
			//cookie 登録
			$this->issuePointPlusCookie();
			
			$brbroadcast_id     = Request::input("brbroadcast_id");
			
			// 選択データ論理削除？
			$bMModel = new BroadcastMessage();

			//end  日付のみセットする
			$endDateTime = $this->getFormatedDate(date('Y'),date('n'),date('j')-1,date('H:i:s'));
			$broadcastMessageData[$bMModel->COL_BROADCAST_MESSAGE_ID] = $brbroadcast_id;
			$broadcastMessageData[$bMModel->COL_ACCEPT_HEADER_E_DTM]    = $endDateTime;
			$broadcastMessageData[$bMModel->COL_ACCEPT_E_DTM]         = $endDateTime;	

			try{
				$con = DB::connection('mysql');
				$dbErr = $con->transaction(function() use($con, $bMModel, $broadcastMessageData) 
				{
					$bMModel->setUpdateCommonColumn($broadcastMessageData, 'BrbroadcastMessage/destroy.');
					// 変更処理
					$bMModel->singleUpdate($con, $broadcastMessageData);

					$this->addGuideMessage("施設管理TOPお知らせ情報を削除しました。");
				});

			} catch (Exception $e) {
				Log::error($e);
				$this->addErrorMessage("削除中にエラーが発生しました");
			}

			// 一覧を再表示
			// 検索処理
			$broadcastMessageList = $bMModel->getList();

			// ビュー情報を設定
			$this->addViewData("broadcastMessages", $broadcastMessageList);
			// ビューを表示
			return view("ctl.brbroadcastMessage.list", $this->getViewData());
		}

		/** cookie クリア
		 * 画面表示用 DB select で使用
		 */
		private function revokePointPlusCookie()
		{
			Cookie::expire($this->pointPlusCreateCookieName
			,$this->pointPlusCreateCookiePath
			,env('COOKIE_DOMAIN'));
		}

		/** cookieにあるかチェック
		 * 登録、更新ボタン押下時に使用
		 *
		 * @return void
		 */
		private function existsMedialistViewCookie() 
		{
			$result = Cookie::get($this->pointPlusCreateCookieName);
			return isset($result);
		}

		/** チェック用cookie 
		 * DB登録 更新 論理削除 処理で使用
		 *
		 * @return void
		 */
		private function issuePointPlusCookie()
		{
			$arr= Cookie::make(
				$this->pointPlusCreateCookieName
			   ,$this->pointPlusCreateCookieValue
			   ,60 // 分指定で1時間
			   ,$this->pointPlusCreateCookiePath 
			   ,env('COOKIE_DOMAIN') //TODO ドメイン定義
			   ,env('SESSION_SECURE_COOKIE') //TODO 定義
			);

			Cookie::queue($arr);
		}

		/** 販売日時：selectのoption
		 * 
		 * @return void
		 */
		private function makeYmdSelecter()
		{
			// 初期化
			$o_models_date = new DateUtil();
			$n_start_year  = 2017;
			$n_now_year    = (int)$o_models_date->to_format('Y');
			$n_end_year    = $n_now_year + 2;
			$a_result      = array();

			// 選択可能項目の作成：年
			$a_result['year'] = array();
			for ($ii = $n_start_year; $ii <= $n_end_year; $ii++) {
					$a_result['year'][] = $ii;
			}

			// 選択可能項目の作成：月
			$a_result['month'] = array();
			for ($ii = 1; $ii <= 12; $ii++) {
					$a_result['month'][] = $ii;
			}

			// 選択可能項目の作成：日
			$a_result['day'] = array();
			for ($ii = 1; $ii <= 31; $ii++) {
					$a_result['day'][] = $ii;
			}

			return $a_result;
		}

		/** 時間のプルダウンデータ作成
		 *
		 * @return void
		 */
		private function makeTimeSelecter()
		{
			// 初期化
			$a_result['time'] = array();
			$a_result['month'] = array();
			for ($i = 0; $i <= 23; $i++) {
					$s_hour = ($i < 10) ? '0' . $i : $i;
					for ($j = 0; $j <= 59; $j++) {
							$s_sec = ($j < 10) ? '0' . $j : $j;
							$a_result['time'][] = $s_hour .':'. $s_sec; 
					}
			}
			return $a_result;
		}

		/** 画面から入力された日付を登録用の数値に変換
		 *
		 * @param [type] $s_year
		 * @param [type] $s_month
		 * @param [type] $s_day
		 * @param [type] $s_time
		 * @return mixed
		 */
		private function convertTime($s_year, $s_month, $s_day, $s_time)
		{
			// 初期化
			$dateUtil = new DateUtil();
			$a_tmp_ymd     = array();

			// 日付整形
			$a_tmp_ymd['year']  = sprintf('%04d', $s_year);
			$a_tmp_ymd['month'] = sprintf('%02d', $s_month);
			$a_tmp_ymd['day']   = sprintf('%02d', $s_day);
			$dateUtil->set($a_tmp_ymd['year'] . '-' . $a_tmp_ymd['month'] . '-' . $a_tmp_ymd['day'] .' '.$s_time);
			$n_time = $dateUtil->get();

			return $n_time;
		}

		/** 日付のシリアル値をこの画面の日付フォーマットで取得
		 */
		private function getFormatedDate($s_year, $s_month, $s_day, $s_time)
		{
			if(isset($s_year) && isset($s_month) && isset($s_day) && isset($s_time))
			{
				$dateSerial = $this->convertTime($s_year, $s_month, $s_day, $s_time);
				return date("Y-m-d H:i",$dateSerial);
			}
		}

}
