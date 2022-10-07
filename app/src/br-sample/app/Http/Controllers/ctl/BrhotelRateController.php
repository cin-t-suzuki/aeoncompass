<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

use App\Models\MastPref;
use App\Models\MastCity;
use App\Models\HotelRate;
use App\Models\Hotel;
use App\Models\MastWard;

use App\Common\Traits;

class BrhotelRateController extends _commonController
{
	use Traits;

	/**   画面 表示
	 * 
	 * @return 表示画面
	 */
	public function index()
	{
			// 料率のリクエストパラメータの取得
			$a_request_hotelrate = Request::input("HotelRate");
			$targetCd     = Request::input("target_cd");

		if($targetCd != ""){
			$hotelCd = $targetCd;
		}else{
			$hotelCd = $a_request_hotelrate['hotel_cd'];
		}

		//料率の一覧データ取得
		$hotelRateModel = new HotelRate();
		$hotelrateData = $hotelRateModel->selectByHotelCd($hotelCd);

		$this->setViewHotelInfo($hotelCd);

		$this->addViewData("hotelrates", $hotelrateData); 
		$this->addViewData("target_cd", $targetCd);
		$this->addViewData("hotel_cd", "");//TODO 料率一覧（削除）/新規登録/更新画面では使用していない

		// ビューを表示
		return view("ctl.brhotelRate.list", $this->getViewData());
	}

	/** 新規登録画面 表示
	 * 
	 *
	 * @return void
	 */
	public function new()
	{
		// リクエストの取得
		$targetCd = Request::input('target_cd');
		$requestHotelRateData    =  Request::input('HotelRate');
		if ($this->is_empty($requestHotelRateData)){
			$requestHotelRateData['accept_s_ymd']     = date('Y/m/d');
			$requestHotelRateData['system_rate']      = 8;
			$requestHotelRateData['system_rate_out']  = 8;
		}
		// 施設情報詳細のデータ取得とViewセット
		$this->setViewHotelInfo($targetCd);
	
		$hotelRateData = $requestHotelRateData;
		// ビュー情報を設定
		$this->addViewData("hotelrate", $hotelRateData);
		$this->addViewData("target_cd", $targetCd);

		return view("ctl.brhotelRate.new", $this->getViewData());
	}

	/** 施設情報の取得とView設定
	 * 
	 *
	 * @return void
	 */
	public function setViewHotelInfo($hotelCd)
	{
		//施設情報 取得
		$hotelModel = new Hotel();
		$hoteldata = $hotelModel->selectByKey($hotelCd);
		
		//都道府県取得
		$prefModel = new MastPref();
		$mastPrefData = $prefModel->selectByKey($hoteldata['pref_id']);

		//市取得
		$cityModel = new MastCity();
		$mastCityData = $cityModel->selectByKey($hoteldata['city_id']);

		//区取得
		$wardModel = new MastWard();
		$mastWardData = $wardModel->selectByKey($hoteldata['ward_id']);

		$this->addViewData("hotel", $hoteldata);
		$this->addViewData("mast_pref", $mastPrefData); 
		$this->addViewData("mast_city", $mastCityData);
		$this->addViewData("mast_ward", $mastWardData);
	}

	/** 新規登録 処理
	 * 
	 */
	public function create(){
		$requestHotelRate    =  Request::input('HotelRate');

		// リクエストに設定？
		$requestHotelRate['hotel_cd'] = Request::input('target_cd');

		//登録値をセット、枝番は一時ダミー
		//$hotelRateData['branch_no'] = 99;//事前チェックするためにダミーの番号セット
		$hotelRateData = $this->getHotelRateData(Request::input('target_cd'), 99, $requestHotelRate);

		$hotelRateModel = new HotelRate();
		//発行前に事前チェック
		$errorList = $hotelRateModel->validation($hotelRateData);

		// 枝番発行後にチェック
		if(count($errorList) == 0){
			//TODO 枝番取得	$new_branch_no = $o_hotel->increment_counter('Hotel_Rate','branch_no');
			$new_branch_no = 98;//TODO
			$hotelRateData['branch_no'] = $new_branch_no;
			//枝番のチェックと日付の独自チェック
			$errorList = $hotelRateModel->validation($hotelRateData);
		}
		if(count($errorList) == 0){
			// 開始日の独自チェック
			$hotelRateModel->checkAcceptSYmd($errorList, $hotelRateData, $hotelRateModel->METHOD_SAVE);
		}
			//	バリデート失敗で、リクエストと一緒にnewに遷移
		if(count($errorList) > 0){
			return $this->viewAgainNewScreen($errorList, $requestHotelRate);
		}
		// 共通カラムをセット
		$hotelRateModel->SetInsertCommonColumn($hotelRateData, 'BrhotelRate/create.');

		try{
			$con = DB::connection('mysql');
			$dbErr = $con->transaction(function() use($con,
						$hotelRateModel, $hotelRateData)
			{
				$hotelRateModel->singleInsert($con, $hotelRateData);
			});
		}catch(Exception $e){
			Log::error($e);
			$errorList[] = "料率データ登録処理でエラーが発生しました。";
		}

		//DBエラーを確認
		if( !empty($dbErr)){
			$errorList[] = $dbErr;
		}

		if (count($errorList) > 0){
			$errorList[] = "ご希望のデータを登録できませんでした";
			return $this->viewAgainNewScreen($errorList, $requestHotelRate);
		}
		$this->addGuideMessage("料率データの登録が完了しました。");

		$this->addViewData("target_cd", Request::input('target_cd'));
		return $this->index();
	}

	// 画面の値を料率モデルにセットする
	private function getHotelRateData($hotelCd, $branchNo, $requestHotelRate){
		$hotelRateData = [];
		$hotelRateData['hotel_cd'] = $hotelCd;
		$hotelRateData['branch_no'] = $branchNo;
		$hotelRateData['accept_s_ymd'] = $requestHotelRate['accept_s_ymd'];
		$hotelRateData['system_rate'] = $requestHotelRate['system_rate'];
		$hotelRateData['system_rate_out'] = $requestHotelRate['system_rate_out'];

		return $hotelRateData;
	}

	/** エラー時にnew画面を表示する 
	 * 
	 * @param [type] $errorList
	 * @param [type] $hotelStatusData
	 * @return view
	 */
	private function viewAgainNewScreen($errorList, $hotelRateData){
		$this->addErrorMessageArray($errorList);
		$targetCd =  $hotelRateData['hotel_cd'];
		$this->addViewData("target_cd", $targetCd);
		// ビューを表示
		$this->addViewData("HotelRate", $hotelRateData);
		return $this->new();
	}

	/** エラー時にedit画面を表示する 
	 * 
	 * @param [type] $errorList
	 * @param [type] $hotelStatusData
	 * @return view
	 */
	private function viewAgainEditScreen($errorList, $hotelRateData, $hotelCd, $branchNo){
		$this->addErrorMessageArray($errorList);
		$this->addViewData("target_cd", $hotelCd);
		$this->addViewData("hotel_cd", $hotelCd);
		$this->addViewData("branch_no", $branchNo);
		// ビューを表示
		$this->addViewData("HotelRate", $hotelRateData);
		return $this->edit();
	}

	/** エラー時にlist画面を表示する 
	 * 
	 * @param [type] $errorList
	 * @param [type] $hotelStatusData
	 * @return view
	 */
	private function viewAgainListScreen($errorList, $targetCd){
		$this->addErrorMessageArray($errorList);
		$this->addViewData("target_cd", $targetCd);	
		// list アクションに転送
		return $this->index();
	}

	/** 料率 更新画面 表示
	 * 
	 *
	 * @return view
	 */
	public function edit(){

		// リクエストの取得
		$requestHotelRateData    =  Request::input('HotelRate');

		// 料率情報を取得
		$hotelRateModel = new HotelRate();
		$hotelCd = Request::input('hotel_cd');
		$branchNo = Request::input('branch_no');
		$targetCd = Request::input('target_cd');

		$hotelRateData = $hotelRateModel->selectByKey($hotelCd, $branchNo);
		$errorList = []; //初期化
		// 対象のデータがない場合、一覧画面へ戻る。
		if(count($hotelRateData) == 0){
			$errorList[] = "ご希望の料率データが見つかりませんでした。下記一覧から選んでください。";
			$this->addErrorMessageArray($errorList);
			// list アクションに転送
			$this->addViewData("target_cd", $targetCd);	
			return $this->index();
		}

		// 呼び元からのデータがあれば、取得データに上書き
		if (!$this->is_empty($requestHotelRateData)){
			// 編集画面の更新処理でDBエラー時に呼ばれる
			$hotelRateData['accept_s_ymd'] = $requestHotelRateData['accept_s_ymd'];
			$hotelRateData['system_rate']  = $requestHotelRateData['system_rate'];
		} else {
			$hotelRateData['accept_s_ymd'] = date('Y/m/d', strtotime($hotelRateData['accept_s_ymd']));
		}

		// 施設情報詳細のデータ取得とViewセット
		$this->setViewHotelInfo($targetCd);

		// ビュー情報を設定
		$this->addViewData("hotelrate", $hotelRateData);
		$this->addViewData("target_cd", $targetCd);

		// ビューを表示
		return view("ctl.brhotelRate.edit", $this->getViewData());
	}
	
	/**	更新処理
	 * 
	 * @return view
	 */
	public function update()
	{
		// リクエストの取得
		$requestHotelRate    =  Request::input('HotelRate');     // 施設情報
		$targetCd = Request::input('target_cd');
		$hotelCd=Request::input('target_cd');
		$branchNo=Request::input('branch_no');

		//画面の値をセット
		$hotelRateData = $this->getHotelRateData(
				$hotelCd, $branchNo, $requestHotelRate);

		$hotelRateModel = new HotelRate();
		$errorList = []; //初期化
		//チェック
		$errorList = $hotelRateModel->validation($hotelRateData);
		if(count($errorList) == 0){
			// 開始日の独自チェック
			$hotelRateModel->checkAcceptSYmd($errorList, $hotelRateData, $hotelRateModel->METHOD_UPDATE);
		}
		// バリデーション	バリデーションエラーでeditへ遷移。
		if(count($errorList) > 0){
			return $this->viewAgainEditScreen($errorList, $requestHotelRate, $hotelCd, $branchNo);
		}

		// 料率データ取得処理
		$hotelRateOldData = $hotelRateModel->selectByKey(
				$hotelRateData['hotel_cd'], $hotelRateData['branch_no']);

		// 更新対象のテーブルがない場合、一覧画面へ戻る。
		if(count($hotelRateOldData) == 0){
			$errorList[] = "ご希望の料率データが見つかりませんでした。下記一覧から選んでください。";
			return $this->viewAgainListScreen($errorList, $targetCd);
		}

		// 共通カラムをセット
		$hotelRateModel->SetUpdateCommonColumn($hotelRateData, 'BrhotelRate/update.');
		$dbCount = 0; //初期化
		try{
			$con = DB::connection('mysql');
			$dbErr = $con->transaction(function() use($con, $hotelRateModel, $hotelRateData, &$dbCount)
			{
				$dbCount = $hotelRateModel->updateByKey($con, $hotelRateData);
			});
		}catch(Exception $e){
			Log::error($e);
			$errorList[] = "料率データ更新処理でエラーが発生しました。";
		}

		// 更新処理	更新が0件でeditへエラー遷移、アップでーと処理失敗でeditへエラー遷移
		if (count($errorList) > 0 || $dbCount == 0){
			$errorList[] = "ご希望の料率データを更新できませんでした。";
			return $this->viewAgainEditScreen($errorList, $requestHotelRate, $hotelCd, $branchNo);
		}

		// 更新エラーなし 正常処理
		$this->addGuideMessage("料率データの更新が完了しました。");
		// ビューedit 変更画面を表示
		$this->addViewData("hotelrate", $requestHotelRate);
		$this->addViewData("target_cd", $targetCd);

		return $this->edit(); 	
	}

	/** 料率 削除機能
	* 
	* @return view
	*/
	public function destroy()
	{
		$errorList = []; //初期化

		// 料率マスタのリクエストパラメータの取得
		$targetCd    =  Request::input('target_cd');
		// 料率情報を取得
		$hotelRateModel = new HotelRate();
		$hotelCd = Request::input('hotel_cd');
		$branchNo = Request::input('branch_no');

		$hotelRateData = $hotelRateModel->selectByKey($hotelCd, $branchNo);
		// 削除対象のデータがない場合、一覧画面へ戻る。
		if(count($hotelRateData) == 0){
			$errorList[] = "ご希望の料率データが見つかりませんでした。下記一覧から選んでください。";
		}
		if(count($errorList) == 0){
				// 過去データは削除できない
				if (strtotime($hotelRateData['accept_s_ymd']) < strtotime(date('Y-m-d'))){
					$errorList[] = "料率適用開始日が過去日の料率データは削除できません。";
				}
		}
		if (count($errorList) > 0){
			// list アクションに転送
			return $this->viewAgainListScreen($errorList, $targetCd);
		}

		try{
			$con = DB::connection('mysql');
			$dbErr = $con->transaction(function() use($con,
						$hotelRateModel, $hotelCd, $branchNo)
			{
				$hotelRateModel->deleteByKey($con, $hotelCd, $branchNo);
			});
		}catch(Exception $e){
			Log::error($e);
			$errorList[] = "料率データ削除処理でエラーが発生しました。";
		}

		//DBエラーを確認
		if( !empty($dbErr)){
			$errorList[] = $dbErr;
		}

		if (count($errorList) > 0){
			$errorList[] = "ご希望の料率データを削除できませんでした。";
			$this->addErrorMessageArray($errorList);
			//edit アクションに転送
			return $this->edit();
		}
		//正常であれば、Listを表示
		$this->addGuideMessage("料率データの削除が完了しました。");

		return $this->index();
	}
	
}
?>