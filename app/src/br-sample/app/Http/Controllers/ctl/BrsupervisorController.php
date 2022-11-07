<?php
	namespace App\Http\Controllers\ctl;

	use App\Http\Controllers\ctl\_commonController;
	use Illuminate\Support\Facades\Request;
	use App\Models\HotelSupervisor;
	use App\Models\HotelSupervisorHotel;
	use App\Models\HotelSupervisorAccount;
	use App\Models\Hotel;
	use App\Models\CommonDBModel;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Log;
	
	use Exception;

	use App\Common\Traits;

	class BrsupervisorController extends _commonController
	{

	// 施設統括一覧表示 hotelsupervisor
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
	
	
	// 施設統括施設一覧表示 hotelsupervisorhotel
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

				$this->addViewData("a_hotel_supervisor_hotel",$a_hotel_supervisor_hotel);
				$this->addViewData("id",$id);
				$this->addViewData("supervisor_cd",$supervisor_cd);

			return view("ctl.brsupervisor.listhotel",$this->getViewData());
			
			}catch (Exception $e) {
			throw $e;
		}
	}


		// 11/2追記　TODO施設統括入力
		public function new()
		{
			$a_hotel_supervisor = Request::input('Hotel_Supervisor');
			$a_hotel_supervisor_account = Request::input('Hotel_Supervisor_Account');
			$supervisor_cd = Request::input('supervisor_cd');//hotel_supervisor_accountに登録
			$supervisor_nm = Request::input('supervisor_nm');//hotel_supervisorに登録
			$account_id = Request::input('account_id');//hotel_supervisor_accountに登録
			$accept_status = Request::input('accept_status');//hotel_supervisor_accountに登録
			$password = Request::input('password');//hotel_supervisor_accountに登録

			try {
				// アサインの登録
				$this ->addViewData("a_hotel_supervisor",$a_hotel_supervisor);
				$this ->addViewData("a_hotel_supervisor_account",$a_hotel_supervisor_account);
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





		// 11/4追記　施設統括登録
		public function create(Request $request)
		{
			// $a_hotel_supervisor         = $this->params('Hotel_Supervisor');
			// $a_hotel_supervisor_account = $this->params('Hotel_Supervisor_Account');
			$supervisor_cd = Request::input('supervisor_cd');//hotel_supervisor_accountに登録
			$supervisor_nm = Request::input('supervisor_nm');//hotel_supervisorに登録
			$account_id = Request::input('account_id');//hotel_supervisor_accountに登録
			$accept_status = Request::input('accept_status');//hotel_supervisor_accountに登録
			$password = Request::input('password');//hotel_supervisor_accountに登録

			$accountCreateData = $this->getAccountCreateData($supervisor_cd,$account_id,$password,$accept_status);
			$supervisorCreateData = $this->getSupervisorCreateData($supervisor_cd,$supervisor_nm);

			// モデルの取得
			$hotelSupervisorAccountModel = new HotelSupervisorAccount();
			$hotelSupervisorModel = new HotelSupervisor();

			$accountErrorList = []; //初期化
			$accountErrorList = $hotelSupervisorAccountModel->validation($accountCreateData);
			$supervisorErrorList = []; //初期化
			$supervisorErrorList = $hotelSupervisorModel->validation($supervisorCreateData);

			//TODO 独自チェック書き換え
			
			// バリデーション	バリデーションエラーでlistへ遷移。
			if(count($accountErrorList) > 0 || count($supervisorErrorList) > 0){
				$errorList = array_merge($accountErrorList,$supervisorErrorList);
				return $this->viewAgainNewScreen($errorList, $supervisor_cd, $supervisor_nm,$account_id,$password,$accept_status);
			}

			// 共通カラムをセット
			$hotelSupervisorAccountModel->SetInsertCommonColumn($accountCreateData, 'Brsupervisor/create.');
			$hotelSupervisorModel->SetInsertCommonColumn($supervisorCreateData, 'Brsupervisor/create.');
			$accountErrorList = [];
			$supervisorErrorList = [];

			try{
				$con = DB::connection('mysql');
				$dbErr1 = $con->transaction(function()use($con, $hotelSupervisorAccountModel, $accountCreateData)
				{
					$hotelSupervisorAccountModel->singleInsert($con, $accountCreateData);
					
					//exception確認用
					// if($accountDB == 1 ){
					// throw new Exception;
					// }
				});
			}catch(Exception $e){
				Log::error($e);
				$accountErrorList[] = "accountの登録処理でエラーが発生しました。";
			}
			try{
				$con = DB::connection('mysql');
				$dbErr2 = $con->transaction(function() use($con, $hotelSupervisorModel, $supervisorCreateData)
				{
					$hotelSupervisorModel->singleInsert($con, $supervisorCreateData);
					//exception確認用
					// if($supervisorDB == 1 ){
					// 	throw new Exception;
					// }
					
				});
			}catch(Exception $e){
				Log::error($e);
				$supervisorErrorList[] = "supervisorの更新処理でエラーが発生しました。";

			}
			//DBエラーを確認
			if(!empty($dbErr1)){
				$errorList[] = $dbErr1;
			}
			if(!empty($dbErr2)){
				$errorList[] = $dbErr2;
			}

				// インスタンスの取得
				$o_hotel_supervisor = new HotelSupervisor();

				// 施設統括情報の取得
				$a_hotel_supervisor = $o_hotel_supervisor->selectByKey($supervisor_cd);
		
				// 完了メッセージ 正常であれば、Listを表示
				$this->addGuideMessage($a_hotel_supervisor['supervisor_cd'] . "　" . $a_hotel_supervisor['supervisor_nm'] . "の登録が完了しました 。");
				$this ->addViewData("supervisor_cd",$supervisor_cd);
				// グループホテル一覧へ
				return $this->list();
		}

		// 施設統括変更
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

			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
				throw $e;
			}

			return view("ctl.brsupervisor.edit",$this->getViewData());

		}






		// 施設統括更新
		public function update()
		{
			// $a_hotel_supervisor = Request::input('Hotel_Supervisor');
			$supervisor_cd = Request::input('supervisor_cd');//hotel_supervisor_accountで更新
			$supervisor_nm = Request::input('supervisor_nm');//hotel_supervisorで更新
			$account_id = Request::input('account_id');//hotel_supervisor_accountで更新
			$accept_status = Request::input('accept_status');//hotel_supervisor_accountで更新


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

				//TODO 独自チェック書き換え
				
				// バリデーション	バリデーションエラーでlistへ遷移。
				if(count($accountErrorList) > 0 || count($supervisorErrorList) > 0){
					$errorList = array_merge($accountErrorList,$supervisorErrorList);
					return $this->viewAgainEditScreen($errorList, $supervisor_cd, $supervisor_nm,$account_id,$accept_status);
				}
		
				//対象データ取得
				$accountOldData = $hotelSupervisorAccountModel->selectByKey($accountUpdateData['supervisor_cd']);
				$supervisorOldData = $hotelSupervisorModel->selectByKey($supervisorUpdateData['supervisor_cd']);
				
				// 失敗時
				if (count($accountOldData) == 0 || count($supervisorOldData) == 0){
				// editへ
				return $this->edit();

				}

				// 共通カラムをセット
				$hotelSupervisorAccountModel->setUpdateCommonColumn($accountUpdateData, 'Brsupervisor/update.');
				$hotelSupervisorModel->setUpdateCommonColumn($supervisorUpdateData, 'Brsupervisor/update.');
				$accountErrorList = [];
				$supervisorErrorList = [];
				$accountDB = 0;
				$supervisorDB = 0;

				//TODO トランザクション　ロールバック
					try{
						$con = DB::connection('mysql');
						$dbErr1 = $con->transaction(function()use($con, $hotelSupervisorAccountModel, $accountUpdateData, &$accountDB)
						{
							$accountDB = $hotelSupervisorAccountModel->updateByKey($con, $accountUpdateData);
							
							//exception確認用
							// if($accountDB == 1 ){
							// throw new Exception;
							// }
						});
					}catch(Exception $e){
						Log::error($e);
						$accountErrorList[] = "accountの更新処理でエラーが発生しました。";
					}
		
					try{
						$con = DB::connection('mysql');
						$dbErr2 = $con->transaction(function() use($con, $hotelSupervisorModel, $supervisorUpdateData, &$supervisorDB)
						{
							$supervisorDB = $hotelSupervisorModel->updateByKey($con, $supervisorUpdateData);
							//exception確認用
							// if($supervisorDB == 1 ){
							// 	throw new Exception;
							// }
							
						});
					}catch(Exception $e){
						Log::error($e);
						$supervisorErrorList[] = "supervisorの更新処理でエラーが発生しました。";

					}
				
					//DBエラーを確認
					// if($accountDB == !1 || $supervisorDB == !1){
					// 	$this->rollBack();
					// }elseif($accountDB == 1 && $supervisorDB == 1){
					// 	$this->commit();
					// }


				$errorList = array_merge($accountErrorList,$supervisorErrorList);

				// 更新処理	更新が0件でeditへエラー遷移、アップデート処理失敗でeditへエラー遷移
				if (count($errorList) > 0 || $accountDB == 0 || $supervisorDB == 0){
					$errorList[] = "ご希望のデータを更新できませんでした。";
					return $this->viewAgainEditScreen($errorList, $supervisor_cd, $supervisor_nm, $account_id,$accept_status);
				}

				// インスタンスの取得
				$o_hotel_supervisor = new HotelSupervisor();

				// 施設統括情報の取得
				$a_hotel_supervisor = $o_hotel_supervisor->selectByKey($supervisor_cd);
		
				// 完了メッセージ 正常であれば、Listを表示
				$this->addGuideMessage($a_hotel_supervisor['supervisor_cd'] . "　" . $a_hotel_supervisor['supervisor_nm'] . "の更新が完了しました 。");
				
				// グループホテル一覧へ
				return $this->list();

		}
		

	


	
	// 施設統括ホテル入力
	public function newhotel()
	{
		$supervisor_cd = Request::input('supervisor_cd');
		$a_hotel_supervisor_hotel = Request::input('Hotel_Supervisor_Hotel');
		
		// アサインの登録
		$this ->addViewData("a_hotel_supervisor_hotel",$a_hotel_supervisor_hotel);
		$this ->addViewData("supervisor_cd",$supervisor_cd);
		

	return view("ctl.brsupervisor.newhotel",$this->getViewData());
	}


	// 施設統括ホテル確認
	public function cnfhotel()
	{
		$a_hotel_supervisor_hotel = Request::input('Hotel_Supervisor_Hotel');
		$supervisor_cd = Request::input('supervisor_cd');
		$hotel_cd = Request::input('hotel_cd');

		$errorList = []; //初期化

		// モデルの取得
		$hotelSupervisorHotelModel = new HotelSupervisorHotel();
		$hotelSupervisorHotelData = $this->checkHotelSupervisorHotelData($supervisor_cd,$hotel_cd);
		
		$errorList = $hotelSupervisorHotelModel->validation($hotelSupervisorHotelData);
		//TODO エラーリストの件数チェック、0件なら独自チェック実行
		if(count($errorList) == 0){
			$hotelSupervisorHotelModel->hotelCdValidate($errorList, $hotelSupervisorHotelData, $hotelSupervisorHotelModel->METHOD_SAVE);
		}
		//TODO エラーリストが1件以上ならエラー表示をし処理中断
		if (count($errorList) > 0){
			$errorList[] = "";
			return $this->viewAgainNewHotelScreen($errorList, $hotelSupervisorHotelData);
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

		// 各メソッドで Exception が投げられた場合
		} catch (Exception $e) {
			throw $e;
		}

	return view("ctl.brsupervisor.cnfhotel",$this->getViewData());
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
			$hotelSupervisorHotelModel->hotelCdValidate($errorList, $hotelSupervisorHotelData, $hotelSupervisorHotelModel->METHOD_SAVE);
		}
		//TODO エラーリストが1件以上ならエラー表示をし処理中断
		if (count($errorList) > 0){
			$errorList[] = "";
			return $this->viewAgainNewHotelScreen($errorList, $hotelSupervisorHotelData);
		}

		// 施設統括施設登録処理
		// 共通カラムをセット
		$hotelSupervisorHotelModel->SetInsertCommonColumn($hotelSupervisorHotelData, 'Brsupervisor/createhotel.');

		try{
			$con = DB::connection('mysql');
			$dbErr = $con->transaction(function()use($con,$hotelSupervisorHotelModel,$hotelSupervisorHotelData)
			{
				$hotelSupervisorHotelModel->singleInsert($con, $hotelSupervisorHotelData);
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
			return $this->viewAgainNewHotelScreen($errorList, $hotelSupervisorHotelData);
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
		$hotelSupervisorHotelData = $this->getHotelSupervisorHotelId($id,$hotel_cd);
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
							$hotelSupervisorHotelModel, $hotel_cd)
				{
					$hotelSupervisorHotelModel->deleteByKey($con, $hotel_cd);
				});
			}catch(Exception $e){
				Log::error($e);
				$errorList[] = "施設統括施設の削除処理でエラーが発生しました。";
			}
			//DBエラーを確認
			if( !empty($dbErr)){
				$errorList[] = $dbErr;
			}
			
			// グループホテル一覧へ
			$this->addGuideMessage("グループホテルの削除が完了しました。");

			return $this->listhotel();
	
		// 各メソッドで Exception が投げられた場合
		} catch (Exception $e) {
			throw $e;
		}
	}













	// Accountテーブルに登録用の値をモデルにセットする
	private function getAccountCreateData($supervisor_cd,$account_id,$password,$accept_status){
		$accountCreateData = [];
		$accountCreateData['supervisor_cd'] = $supervisor_cd;
		$accountCreateData['account_id'] = $account_id;
		$accountCreateData['password'] = $password;
		$accountCreateData['accept_status'] = $accept_status;

		return $accountCreateData;
	}
	// Supervisorテーブルに登録用の値をモデルにセットする
	private function getSupervisorCreateData($supervisor_cd,$supervisor_nm){
		$supervisorCreateData = [];
		$supervisorCreateData['supervisor_cd'] = $supervisor_cd;
		$supervisorCreateData['supervisor_nm'] = $supervisor_nm;

		return $supervisorCreateData;
	}

	// AccountUpdate用の値をモデルにセットする
	private function getAccountUpdateData($supervisor_cd,$account_id,$accept_status){
		$accountUpdateData = [];
		$accountUpdateData['supervisor_cd'] = $supervisor_cd;
		$accountUpdateData['account_id'] = $account_id;
		$accountUpdateData['accept_status'] = $accept_status;

		return $accountUpdateData;
	}
	// SupervisorUpdate用の値をモデルにセットする
	private function getSupervisorUpdateData($supervisor_cd,$supervisor_nm){
		$supervisorUpdateData = [];
		$supervisorUpdateData['supervisor_cd'] = $supervisor_cd;
		$supervisorUpdateData['supervisor_nm'] = $supervisor_nm;

		return $supervisorUpdateData;
	}
	
	// createhotelに行く前にnewhotelでバリデーションチェック
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
	// 画面の値をモデルにセットする
	private function getHotelSupervisorHotelId($id,$hotel_cd){
		$hotelSupervisorHotelId = [];
		$hotelSupervisorHotelId['id'] = $id;
		$hotelSupervisorHotelId['hotel_cd'] = $hotel_cd;

		return $hotelSupervisorHotelId;
	}
	
	
	/** エラー時にlist画面を表示する */
	private function viewAgainListHotelScreen($errorList, $hotelSupervisorHotelData){
		$this->addErrorMessageArray($errorList);
		$this->addViewData("hotel_cd", $hotelSupervisorHotelData);
		// ビューを表示
		return $this->listhotel();
	}
	private function viewAgainEditScreen($errorList,$supervisor_cd,$supervisor_nm,$account_id,$accept_status){
		$this->addErrorMessageArray($errorList);
		$this->addViewData('supervisor_cd', $supervisor_cd);
		$this->addViewData('supervisor_nm', $supervisor_nm);
		$this->addViewData('account_id', $account_id);
		$this->addViewData('accept_status', $accept_status);

		// ビューを表示
		return $this->edit();
	}

	/** エラー時にnew画面を表示する 
	 * 
	 * @param [type] $errorList
	 * @param [type] $hotelStatusData
	 * @return view
	 */
	private function viewAgainNewHotelScreen($errorList, $hotelSupervisorHotelData){
		$this->addErrorMessageArray($errorList);
		$this->addViewData("hotel_cd", $hotelSupervisorHotelData);
		// ビューを表示
		return $this->newhotel();
	}



	private function viewAgainNewScreen($errorList, $supervisor_cd,$supervisor_nm,$account_id,$password,$accept_status){
		$this->addErrorMessageArray($errorList);
		$this->addViewData("supervisor_cd", $supervisor_cd);
		$this->addViewData("supervisor_nm", $supervisor_nm);
		$this->addViewData("account_id", $account_id);
		$this->addViewData("password", $password);
		$this->addViewData("accept_status", $accept_status);

		// 入力値を持ってビューを表示
		return view("ctl.brsupervisor.new",$this->getViewData());

	}












}
?>