<?php
	namespace App\Http\Controllers\ctl;

	use App\Http\Controllers\ctl\_commonController;
	use Illuminate\Support\Facades\Request;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Log;
	use App\Models\HotelSupervisor;
	use App\Models\HotelSupervisorHotel;
	use App\Models\HotelSupervisorAccount;
	use App\Models\Hotel;
	use App\Util\Models_Cipher;
	
	use Exception;

	class BrsupervisorController extends _commonController
	{
	// インデックス
		public function index()
		{
			// list アクションに転送します
			return $this->list();
		}

	// 施設統括一覧表示
		public function list()
		{
			$supervisor_cd = Request::input('supervisor_cd');
			$supervisor_nm = Request::input('supervisor_nm');

			$brsupervisormodel = new HotelSupervisor();
			$a_hotel_supervisor = $brsupervisormodel->getHotelSupervisor();

			// 取得できなかった場合
			if (count($a_hotel_supervisor['values']) == 0){
			// エラーメッセージ
			$this->addErrorMessage("グループは存在しません。");
			}

			$this->addViewData("a_hotel_supervisor",$a_hotel_supervisor);
			$this->addViewData("supervisor_cd",$supervisor_cd);
			$this->addViewData("supervisor_nm",$supervisor_nm);

			return view("ctl.brsupervisor.list",$this->getViewData());
		}
	

	// 施設統括施設一覧表示
		public function listhotel()
		{
			try {
				$id = Request::input('id');
				$supervisor_cd = Request::input('supervisor_cd');

				$brsupervisormodel = new HotelSupervisorHotel();
				$a_hotel_supervisor_hotel = $brsupervisormodel->getHotelSupervisorHotel(array('supervisor_cd'=>$supervisor_cd));

				if (count($a_hotel_supervisor_hotel['values']) == 0){
					$this->addErrorMessage("グループのホテルは存在しません。");
				}		

				$this->addViewData("id",$id);
				$this->addViewData("supervisor_cd",$supervisor_cd);
				$this->addViewData("a_hotel_supervisor_hotel",$a_hotel_supervisor_hotel);

				return view("ctl.brsupervisor.listhotel",$this->getViewData());
			
			}catch (Exception $e) {
				throw $e;
		}
	}

	// 施設統括入力（グループ登録）
		public function new()
		{
			$supervisor_cd = Request::input('supervisor_cd');  //hotel_supervisor_account
			$supervisor_nm = Request::input('supervisor_nm');  //hotel_supervisor
			$account_id = Request::input('account_id');        //hotel_supervisor_account
			$password = Request::input('password');            //hotel_supervisor_account
			$accept_status = Request::input('accept_status');  //hotel_supervisor_account

			try {
				// アサインの登録
				$this ->addViewData("supervisor_cd",$supervisor_cd);
				$this ->addViewData("supervisor_nm",$supervisor_nm);
				$this ->addViewData("account_id",$account_id);
				$this ->addViewData("accept_status",$accept_status);
				$this ->addViewData("password",$password);

				return view("ctl.brsupervisor.new",$this->getViewData());

			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
				throw $e;
			}
		}

	// 施設統括登録（グループ登録）
		public function create()
		{
			$supervisor_cd = Request::input('supervisor_cd');  //hotel_supervisor_account
			$supervisor_nm = Request::input('supervisor_nm');  //hotel_supervisor
			$account_id = Request::input('account_id');        //hotel_supervisor_account
			$accept_status = Request::input('accept_status');  //hotel_supervisor_account
			$password = Request::input('password');            //hotel_supervisor_account

			$accountCreateData = $this->getAccountCreateData($supervisor_cd,$account_id,$password,$accept_status);
			$supervisorCreateData = $this->getSupervisorCreateData($supervisor_cd,$supervisor_nm);

			// モデルの取得
			$hotelSupervisorAccountModel = new HotelSupervisorAccount();
			$hotelSupervisorModel = new HotelSupervisor();

			$accountErrorList = []; //初期化
			$supervisorErrorList = []; //初期化

			$accountErrorList = $hotelSupervisorAccountModel->validation($accountCreateData);
			$supervisorErrorList = $hotelSupervisorModel->validation($supervisorCreateData);
		
			// バリデーション	バリデーションエラーでnewへ遷移。
			if(count($accountErrorList) > 0 || count($supervisorErrorList) > 0){
				//2テーブルのバリデーションメッセージを結合
				$errorListArray = array_merge($supervisorErrorList,$accountErrorList);
				//バリデーションメッセージの重複削除
				$errorList = array_unique($errorListArray);
				return $this->viewAgainNewScreen($errorList,$supervisor_cd,$supervisor_nm,$account_id,$password,$accept_status);
			}

			// PW暗号化
			$cipher = new Models_Cipher(config('settings.cipher_key'));
			$accountCreateData['password'] = $cipher->encrypt($accountCreateData['password']);
			
			// 共通カラムをセット
			$hotelSupervisorAccountModel->SetInsertCommonColumn($accountCreateData, 'Brsupervisor/create.');
			$hotelSupervisorModel->SetInsertCommonColumn($supervisorCreateData, 'Brsupervisor/create.');
			$errorList = [];

			// 登録処理　2テーブル登録
			try{
				$con = DB::connection('mysql');
				$dbErr = $con->transaction(function()use($con,$hotelSupervisorAccountModel,$accountCreateData,$hotelSupervisorModel,$supervisorCreateData)
				{
					$hotelSupervisorAccountModel->singleInsert($con,$accountCreateData);
					$hotelSupervisorModel->singleInsert($con,$supervisorCreateData);				
				});
			}catch(Exception $e){
				Log::error($e);
				$this->addErrorMessage("登録処理でエラーが発生しました。");
				return $this->new();
			}

			//DBエラーを確認
			if(!empty($dbErr)){
				$errorList[] = $dbErr;
			}

			// 完了後画面に登録情報を表示
			// インスタンスの取得
			$o_hotel_supervisor = new HotelSupervisor();

			// 施設統括情報の取得
			$a_hotel_supervisor = $o_hotel_supervisor->selectByKey($supervisor_cd);
	
			// 完了メッセージ 正常であれば、listを表示
			$this->addGuideMessage($a_hotel_supervisor['supervisor_cd'] . "　" . $a_hotel_supervisor['supervisor_nm'] . "　の登録が完了しました 。");
			// グループホテル一覧へ
			return $this->list();
		}

	// 施設統括変更（詳細ボタン）
		public function edit()
		{
			$a_hotel_supervisor = Request::input('Hotel_Supervisor');
			$a_hotel_supervisor_account = Request::input('Hotel_Supervisor_Account');
			$supervisor_cd = Request::input('supervisor_cd');

			try {

				// リクエストに情報がなければ値を取得  ※初期表示用
				if (empty($a_hotel_supervisor) && empty($a_hotel_supervisor_account)){
					// インスタンスの取得
					$o_hotel_supervisor         = new HotelSupervisor();
					$o_hotel_supervisor_account = new HotelSupervisorAccount();

					// 施設統括情報の取得
					$a_hotel_supervisor = $o_hotel_supervisor->selectByKey($supervisor_cd);
					$a_hotel_supervisor_account = $o_hotel_supervisor_account->selectByKey($supervisor_cd);
				}

				// アサインの登録
				$this ->addViewData("a_hotel_supervisor",$a_hotel_supervisor);
				$this ->addViewData("a_hotel_supervisor_account",$a_hotel_supervisor_account);
				$this ->addViewData("supervisor_cd",$supervisor_cd);
				
				return view("ctl.brsupervisor.edit",$this->getViewData());
			
			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
				throw $e;
			}
		}

	// 施設統括更新
		public function update()
		{
			$a_hotel_supervisor = Request::input('Hotel_Supervisor');
			$a_hotel_supervisor_account = Request::input('Hotel_Supervisor_Account');
			$supervisor_cd = Request::input('supervisor_cd');

			$supervisor_nm = $a_hotel_supervisor['supervisor_nm'];	
			$account_id = $a_hotel_supervisor_account['account_id'];
			$password = $a_hotel_supervisor_account['password'];
			$accept_status = $a_hotel_supervisor_account['accept_status'];

			//施設統括登録処理
			//画面の値をセット
			$accountUpdateData = $this->getAccountUpdateData($supervisor_cd,$account_id,$accept_status);
			$supervisorUpdateData = $this->getSupervisorUpdateData($supervisor_cd,$supervisor_nm);

			// モデルの取得
			$hotelSupervisorAccountModel = new HotelSupervisorAccount();
			$hotelSupervisorModel = new HotelSupervisor();

			$accountErrorList = []; //初期化
			$accountErrorList = $hotelSupervisorAccountModel->validation($accountUpdateData);
			$supervisorErrorList = []; //初期化
			$supervisorErrorList = $hotelSupervisorModel->validation($supervisorUpdateData);
		
			// バリデーション	バリデーションエラーでeditへ遷移。
			if(count($accountErrorList) > 0 || count($supervisorErrorList) > 0){
				$errorList = array_merge($accountErrorList,$supervisorErrorList);
				return $this->viewAgainEditScreen($errorList,$supervisor_cd,$supervisor_nm,$account_id,$password,$accept_status);
			}

			//対象データ取得
			$accountOldData = 0; //初期化
			$supervisorOldData = 0; //初期化
			$accountOldData = $hotelSupervisorAccountModel->selectByKey($accountUpdateData['supervisor_cd']);
			$supervisorOldData = $hotelSupervisorModel->selectByKey($supervisorUpdateData['supervisor_cd']);
			
			// 失敗時
			if ($accountOldData == 0 || $supervisorOldData == 0){
			// editへ
			return $this->edit();
			}

			// 共通カラムをセット
			$hotelSupervisorAccountModel->setUpdateCommonColumn($accountUpdateData,'Brsupervisor/update.');
			$hotelSupervisorModel->setUpdateCommonColumn($supervisorUpdateData,'Brsupervisor/update.');

			//DB更新処理 初期化
			$errorList = [];
			$accountDB = 0;
			$supervisorDB = 0;

			//2テーブル更新。トランザクション/ロールバックはクロージャを使用
			try{
				$con = DB::connection('mysql');
				$dbErr1 = $con->transaction(function()use($con,$hotelSupervisorAccountModel,$hotelSupervisorModel,$accountUpdateData,$supervisorUpdateData,&$accountDB,&$supervisorDB)
				{
					$accountDB = $hotelSupervisorAccountModel->updateByKey($con,$accountUpdateData);
					$supervisorDB = $hotelSupervisorModel->updateByKey($con,$supervisorUpdateData);
				});
			}catch(Exception $e){
				Log::error($e);
				$errorList[] = "更新処理でエラーが発生しました。";
			}

			// 更新処理	更新0件またはアップデート処理失敗でeditへエラー遷移
			if (count($errorList) > 0 || $accountDB == 0 || $supervisorDB == 0){
				$errorList[] = "ご希望のデータを更新できませんでした。";
				return $this->viewAgainEditScreen($errorList,$supervisor_cd,$supervisor_nm,$account_id,$password,$accept_status);
			}

			// インスタンスの取得
			$o_hotel_supervisor = new HotelSupervisor();

			// 施設統括情報の取得
			$a_hotel_supervisor = $o_hotel_supervisor->selectByKey($supervisor_cd);

			// 完了メッセージ 正常であれば、Listを表示
			$this->addGuideMessage($a_hotel_supervisor['supervisor_cd'] . "　" . $a_hotel_supervisor['supervisor_nm'] . "　の更新が完了しました 。");
			
			// グループホテル一覧へ
			return $this->list();
		}

	// 施設統括ホテル入力
	public function newhotel()
		{
			$supervisor_cd = Request::input('supervisor_cd');
			$a_hotel_supervisor_hotel = Request::input('Hotel_Supervisor_Hotel');
			
			try{
				// アサインの登録
				$this ->addViewData("a_hotel_supervisor_hotel",$a_hotel_supervisor_hotel);
				$this ->addViewData("supervisor_cd",$supervisor_cd);
				
				return view("ctl.brsupervisor.newhotel",$this->getViewData());

			}catch(Exception $e) {
				throw $e;
			}
		}


	// 施設統括ホテル確認
		public function cnfhotel()
		{
			$a_hotel_supervisor_hotel = Request::input('Hotel_Supervisor_Hotel');
			$supervisor_cd = Request::input('supervisor_cd');
			$hotel_cd = $a_hotel_supervisor_hotel['hotel_cd'];

			// モデルの取得
			$hotelSupervisorHotelModel = new HotelSupervisorHotel();
			$hotelSupervisorHotelData = $this->checkHotelSupervisorHotelData($supervisor_cd,$hotel_cd);
			
			$errorList = []; //初期化
			$errorList = $hotelSupervisorHotelModel->validation($hotelSupervisorHotelData);
			//エラーリストの件数チェック、0件なら独自チェック実行
			if(count($errorList) == 0){
				$hotelSupervisorHotelModel->hotelCdValidate($errorList,$hotelSupervisorHotelData,$hotelSupervisorHotelModel->METHOD_SAVE);
			}
			//エラーリストが1件以上ならエラー表示をし処理中断
			if (count($errorList) > 0){
				$errorList[] = "";
				return $this->viewAgainNewHotelScreen($errorList,$supervisor_cd,$hotel_cd);
			}

			try {
				// インスタンスの取得
				$o_hotel = new Hotel();
				// 施設情報の取得
				$hotelData = $o_hotel->selectByKey($hotel_cd);

				// ホテルが存在しない場合はエラー
				if ($hotelData == false){
					// エラーメッセージ
					$this->addErrorMessage("グループのホテルは存在しません。");
					// newhotelへ
					return $this->newhotel();
				}

				// アサインの登録
				$this ->addViewData("hotelData",$hotelData);
				$this ->addViewData("a_hotel_supervisor_hotel",$a_hotel_supervisor_hotel);
				$this ->addViewData("supervisor_cd",$supervisor_cd);
			
				return view("ctl.brsupervisor.cnfhotel",$this->getViewData());
				
			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
				throw $e;
			}
		}

	// 施設統括ホテル登録
		public function createhotel()
		{
			// 登録用の値をセット
			$supervisor_cd = Request::input('supervisor_cd');
			$hotel_cd = Request::input('hotel_cd');

			// モデルの取得
			$hotelSupervisorHotelModel = new HotelSupervisorHotel();

			//連番idの採番
			$hotelSupervisorHotelId = $hotelSupervisorHotelModel->getSequence();
			$hotelSupervisorHotelData = $this->getHotelSupervisorHotelData($supervisor_cd,$hotel_cd,$hotelSupervisorHotelId);
			
			//発行前に事前チェック
			$errorList = $hotelSupervisorHotelModel->validation($hotelSupervisorHotelData);
			//TODO エラーリストの件数チェック、0件なら独自チェック実行
			if(count($errorList) == 0){
				$hotelSupervisorHotelModel->hotelCdValidate($errorList,$hotelSupervisorHotelData,$hotelSupervisorHotelModel->METHOD_SAVE);
			}
			//TODO エラーリストが1件以上ならエラー表示をし処理中断
			if (count($errorList) > 0){
				$errorList[] = "";
				return $this->viewAgainNewHotelScreen($errorList,$supervisor_cd,$hotel_cd);
			}

			// 施設統括施設登録処理
			// 共通カラムをセット
			$hotelSupervisorHotelModel->SetInsertCommonColumn($hotelSupervisorHotelData,'Brsupervisor/createhotel.');

			try{
				$con = DB::connection('mysql');
				$dbErr = $con->transaction(function()use($con,$hotelSupervisorHotelModel,$hotelSupervisorHotelData)
				{
					$hotelSupervisorHotelModel->singleInsert($con,$hotelSupervisorHotelData);
				});
			}catch(Exception $e){
				Log::error($e);
				$errorList[] = "グループホテル登録処理でエラーが発生しました。";
			}

			//DBエラーを確認
			if(!empty($dbErr)){
				$errorList[] = $dbErr;
			}

			if (count($errorList) > 0){
				$errorList[] = "ご希望のデータを登録できませんでした";
				return $this->viewAgainNewHotelScreen($errorList,$supervisor_cd,$hotel_cd);
			}

			$this->addGuideMessage("グループホテルの登録が完了しました。");

			return $this->listhotel();
		}

	// 施設統括ホテル削除
		public function deletehotel()
		{
			$errorList = []; //初期化

			try {
			// モデルの取得
			$hotelSupervisorHotelModel = new HotelSupervisorHotel();

			// 登録用の値をセット
			$id = Request::input('id');
			$hotel_cd = Request::input('hotel_cd');

			// 施設統括施設登録処理
			$deleteData = $hotelSupervisorHotelModel->selectByKey($hotel_cd);

			// 削除対象のデータがない場合、一覧画面へ戻る。
			if(count($deleteData) == 0){
				$errorList[] = "ご希望のデータが見つかりませんでした。";
			}
			if(count($errorList) > 0){
				$errorList[] = "削除できません。";
			}
			
				try{
					$con = DB::connection('mysql');
					$dbErr = $con->transaction(function() use($con,
								$hotelSupervisorHotelModel, $id)
					{
						$hotelSupervisorHotelModel->deleteByKey($con, $id);
					});
				}catch(Exception $e){
					Log::error($e);
					$errorList[] = "施設統括施設の削除処理でエラーが発生しました。";
				}
				//DBエラーを確認
				if(!empty($dbErr)){
					$errorList[] = $dbErr;
				}
				
				// グループホテル一覧へ
				// インスタンスの取得
				$o_hotel = new Hotel();

				// 施設情報の取得
				$a_hotel = $o_hotel->selectByKey($hotel_cd);

				// 削除完了メッセージ
				$this->addGuideMessage($a_hotel['hotel_cd'] . "　" . $a_hotel['hotel_nm'] . "　の削除が完了しました 。");

				return $this->listhotel();
		
			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
				throw $e;
			}
		}


	// Accountテーブルに登録用の値をモデルにセット
		private function getAccountCreateData($supervisor_cd,$account_id,$password,$accept_status){
			$accountCreateData = [];
			$accountCreateData['supervisor_cd'] = $supervisor_cd;
			$accountCreateData['account_id'] = $account_id;
			$accountCreateData['password'] = $password;
			$accountCreateData['accept_status'] = $accept_status;

			return $accountCreateData;
		}

	// Supervisorテーブルに登録用の値をモデルにセット
		private function getSupervisorCreateData($supervisor_cd,$supervisor_nm){
			$supervisorCreateData = [];
			$supervisorCreateData['supervisor_cd'] = $supervisor_cd;
			$supervisorCreateData['supervisor_nm'] = $supervisor_nm;

			return $supervisorCreateData;
		}

	// AccountUpdate用の値をモデルにセット
		private function getAccountUpdateData($supervisor_cd,$account_id,$accept_status){
			$accountUpdateData = [];
			$accountUpdateData['supervisor_cd'] = $supervisor_cd;
			$accountUpdateData['account_id'] = $account_id;
			$accountUpdateData['accept_status'] = $accept_status;

			return $accountUpdateData;
		}

	// SupervisorUpdate用の値をモデルにセット
		private function getSupervisorUpdateData($supervisor_cd,$supervisor_nm){
			$supervisorUpdateData = [];
			$supervisorUpdateData['supervisor_cd'] = $supervisor_cd;
			$supervisorUpdateData['supervisor_nm'] = $supervisor_nm;

			return $supervisorUpdateData;
		}
	
	// cnfhotelでバリデーションチェック用のデータ取得
		private function checkHotelSupervisorHotelData($supervisor_cd, $hotel_cd){
			$hotelSupervisorHotelData = [];
			$hotelSupervisorHotelData['supervisor_cd'] = $supervisor_cd;
			$hotelSupervisorHotelData['hotel_cd'] = $hotel_cd;

			return $hotelSupervisorHotelData;
		}

	// createhotelで画面の値をモデルにセットする
		private function getHotelSupervisorHotelData($supervisor_cd, $hotel_cd,$hotelSupervisorHotelId){
			$hotelSupervisorHotelData = [];
			$hotelSupervisorHotelData['id'] = $hotelSupervisorHotelId;
			$hotelSupervisorHotelData['supervisor_cd'] = $supervisor_cd;
			$hotelSupervisorHotelData['hotel_cd'] = $hotel_cd;

			return $hotelSupervisorHotelData;
		}

	// エラー時にnew画面を表示する 
		private function viewAgainNewScreen($errorList, $supervisor_cd,$supervisor_nm,$account_id,$password,$accept_status){
			$this->addErrorMessageArray($errorList);
			$this->addViewData("supervisor_cd", $supervisor_cd);
			$this->addViewData("supervisor_nm", $supervisor_nm);
			$this->addViewData("account_id", $account_id);
			$this->addViewData("password", $password);
			$this->addViewData("accept_status", $accept_status);

			return $this->new();
		}
	
	// エラー時にedit画面を表示する
		private function viewAgainEditScreen($errorList,$supervisor_cd,$supervisor_nm,$account_id,$password,$accept_status){
			$this->addErrorMessageArray($errorList);
			$this->addViewData('supervisor_cd', $supervisor_cd);
			$this->addViewData('supervisor_nm', $supervisor_nm);
			$this->addViewData('account_id', $account_id);
			$this->addViewData('password', $password);
			$this->addViewData('accept_status', $accept_status);

			return $this->edit();
		}

	// エラー時にnewhotel画面を表示する 
		private function viewAgainNewHotelScreen($errorList, $supervisor_cd,$hotel_cd){
			$this->addErrorMessageArray($errorList);
			$this->addViewData("supervisor_cd", $supervisor_cd);
			$this->addViewData("hotel_cd", $hotel_cd);

			return $this->newhotel();
		}
}
?>