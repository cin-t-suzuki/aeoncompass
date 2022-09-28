<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

use App\Models\HotelStatus;
use App\Models\HotelControl;
use App\Models\HotelRate;
use App\Models\Hotel;
use App\Models\HotelSurvey;

use App\Common\Traits;

class BrhotelStatusController extends _commonController
{
	use Traits;

	/**  施設情報変更(登録状態変更) 画面 表示
	 * 
	 * @return 表示画面
	 */
	public function index()
	{
		$targetCd     = Request::input("target_cd");

		if(session()->exists('hotel_status')){	// 変更画面からの遷移
			$hotelStatusData = session('hotel_status');
			$targetCd = $hotelStatusData['hotel_cd'];
			if(session()->exists('errorMessageArr')){
				$errorList = session('errorMessageArr');
				$this->addErrorMessageArray($errorList);//（後続でもう一回単体add しても問題ない）
			}
			session()->forget('hotel_status'); // クリア
			session()->forget('errorMessageArr');
			// 入力をそのまま表示する（特に日付）

		}else{
			$hotelStatusModel = new HotelStatus();
			$hotelStatusData = $hotelStatusModel->selectByKey($targetCd);
			// 画面用の書式に合わせる
			if (!$this->is_empty($hotelStatusData['contract_ymd'])){
				$hotelStatusData['contract_ymd'] = date('Y/m/d', strtotime($hotelStatusData['contract_ymd']));
			}
			if (!$this->is_empty($hotelStatusData['open_ymd'])){
				$hotelStatusData['open_ymd']     = date('Y/m/d', strtotime($hotelStatusData['open_ymd']));
			}
		}

		$rateChk = $this->getRateCheck($targetCd);

		$this->addViewData("target_cd", $targetCd);
		$this->addViewData("hotel_status", $hotelStatusData);// ホテルステータス情報
		$this->addViewData("rate_chk", $rateChk);// 料率チェック情報(true = OK)
		// ビューを表示
		return view("ctl.brhotelStatus.edit", $this->getViewData());

	}

	/** 料率のチェック要否を取得する
	 * 
	 * @param [type] $targetCd
	 * @return boolean
	 */
	private function getRateCheck($targetCd){
		// 買取販売以外は料率のチェックを行う。
		$rateChk = true;
		if(isset($targetCd)){
			$hotelControlModel = new HotelControl();
			$hotelControlData = $hotelControlModel->selectByKey($targetCd);	

			if (isset($hotelControlData) && $hotelControlData['stock_type'] != 1) {
				$hotelRateModel = new HotelRate();
				$hotelRateData = $hotelRateModel->selectByHotelCd($targetCd);
				if($this->is_empty($hotelRateData)){
					$rateChk = false;
				}
			}
		}
		return $rateChk;
	}

	/** エラー時にedit画面を表示する
	 * 
	 * @param [type] $errorList
	 * @param [type] $hotelStatusData
	 * @return view
	 */
	private function viewAgainEditScreen($errorList, $hotelStatusData){
		$this->addErrorMessageArray($errorList);
		$targetCd =  $hotelStatusData['hotel_cd'];
		$this->addViewData("target_cd", $targetCd);
		$this->addViewData("hotel_status", $hotelStatusData);// ホテルステータス情報
		$rateChk = $this->getRateCheck($targetCd);
		$this->addViewData("rate_chk", $rateChk);// 料率チェック情報(true = OK)
		// ビューを表示
		return view("ctl.brhotelStatus.edit", $this->getViewData());
	}
	
	/** 施設情報変更(登録状態変更) 更新処理
	 * 
	 * @return view
	 */
	public function update()
	{
		// リクエストの取得
		$requestHotelStatus    =  Request::input('hotel_status');     // 施設情報
		$targetCd = Request::input('target_cd');  
		$requestHotelStatus['hotel_cd'] = $targetCd;

		//Hotel、HotelStatusをデータ保存
		$hotelModel = new Hotel();
		$hotelStatusModel = new HotelStatus();

		// ホテル情報を取得
		$a_hotel        = $hotelModel->selectByKey($targetCd);
		$a_hotel_status = $hotelStatusModel->selectByKey($targetCd);

		// 画面値を設定
		$hotelStatusData[$hotelStatusModel->COL_HOTEL_CD] = $targetCd;
		$hotelStatusData[$hotelStatusModel->COL_ENTRY_STATUS] = $requestHotelStatus['entry_status'];
		$hotelStatusData[$hotelStatusModel->COL_CONTRACT_YMD] = $requestHotelStatus['contract_ymd'];
		$hotelStatusData[$hotelStatusModel->COL_OPEN_YMD] = $requestHotelStatus['open_ymd'];

		$hotelStatusData[$hotelStatusModel->COL_CLOSE_DTM] = $requestHotelStatus['close_dtm']??null;

		// 解約が選択されていれば解約日時を自動登録。既に設定されていても現行通り上書きする
		if ($requestHotelStatus[$hotelStatusModel->COL_ENTRY_STATUS] == 2){
			$hotelStatusData[$hotelStatusModel->COL_CLOSE_DTM] = date("Y-m-d");
		}

		//単項目チェック
		$errorList = $hotelStatusModel->validation($hotelStatusData);

		// 更新対象のテーブルがない場合insert
		$insertFlg = false;
		if ( !isset($a_hotel_status) || count($a_hotel_status) == 0){
			$insertFlg = true;
			$requestHotelStatus['entry_ts'] =  date("Y-m-d H:i:d");//登録日
		}else{
			// 登録日はバリデーション対象外なのでここで追加
			$requestHotelStatus['entry_ts'] =  $a_hotel_status[$hotelStatusModel->COL_ENTRY_TS];
		}

		if(count($errorList) > 0){
			// editに転送します
			return $this->viewAgainEditScreen($errorList, $requestHotelStatus);
		}

		// 「登録作業中」→「公開中」に更新時、緯度経度情報が存在するかチェック
		if ( ($insertFlg || $a_hotel_status['entry_status'] == 1) && $requestHotelStatus['entry_status'] == 0) {
			$a_find_hotel_survey = array();
			$hotelSurveyModel      = new HotelSurvey();
			$a_find_hotel_survey = $hotelSurveyModel->selectByKey($targetCd);
			
			// 緯度経度情報が存在しないとき
			if ( $this->is_empty($a_find_hotel_survey) ) {
				// エラー表示
				$errorList[] = ("施設の緯度経度情報が存在していない為、更新できません。");
				
				// editに転送します
				return $this->viewAgainEditScreen($errorList, $requestHotelStatus);
			}
		}

		if($requestHotelStatus['entry_status'] == 0){
			$hotelControlModel = new HotelControl();
			$a_hotel_control = $hotelControlModel->selectByKey($targetCd);

			// 買取販売以外は料率のチェックを行う。
			if ( !isset($a_hotel_control) || $a_hotel_control['stock_type'] != 1) {
				$hotelRateModel = new HotelRate();
				$a_hotel_rate = $hotelRateModel->selectByHotelCd($targetCd);
				if($this->is_empty($a_hotel_rate)){
					$errorList[] = ('施設の料率情報が存在していない為、登録状態:公開中で更新できません。');

					// editに転送します
					return $this->viewAgainEditScreen($errorList, $requestHotelStatus);
				}	
			}
		}
	
		// 登録状態が公開中でなくホテルが受付状態が受付中で無い場合、停止中へ
		// entry_status		0:公開中 1:登録作業中 2:解約
		// accept_status	0:停止中 1:受付中
		// ホテルのDBデータがなければ、更新はしない
		$hotelData = [];
		if ($a_hotel['accept_status'] != 0 && $requestHotelStatus['entry_status'] != 0){
			// 停止中へ変更準備
			$hotelData['hotel_cd'] = $targetCd;
			$hotelData['accept_status'] = 0;
		}
		
		$dbCount = 0;
		try{
			$con = DB::connection('mysql');
			$dbErr = $con->transaction(function() use($con,
						$hotelModel, $hotelData, $insertFlg, $hotelStatusModel, $hotelStatusData, &$dbCount) 
			{
				if(isset($hotelData) && array_key_exists('hotelCd', $hotelData)){
					//ホテル update （渡す項目自体なければ、そのフィールドは更新されない）
					$hotelModel->updateByKey($con, $hotelData);
				}
				//施設状態 updateかinsert を行う
				// insertの場合
				if ($insertFlg) {
					$hotelStatusModel->SetInsertCommonColumn($hotelStatusData, 'BrhotelStatus/create.');
					$hotelStatusModel->singleInsert($con, $hotelStatusData);
					$dbCount = 1;
				}else{
					$hotelStatusModel->SetUpdateCommonColumn($hotelStatusData, 'BrhotelStatus/update.');
					$dbCount = $hotelStatusModel->updateByKey($con, $hotelStatusData);
				}
			});
		}catch(Exception $e){
			Log::error($e);
			$errorList[] = "施設情報の更新処理でエラーが発生しました。";
		}

		//チェックエラーがあったか、更新件数が0件ならば、editを表示
		if( !empty($dbErr)){
			$errorList[] = $dbErr;
		}

		if (count($errorList) > 0 || $dbCount == 0){
			$errorList[] = "施設情報の更新ができませんでした。";
			return $this->viewAgainEditScreen($errorList, $requestHotelStatus);
		}

		// 更新エラーなし 正常処理
		$this->addGuideMessage("施設情報の更新が完了しました。");
		// ビューedit 変更画面を表示
		$this->addViewData("target_cd", $targetCd);		// targetCd リクエスト渡し
		return $this->index(); 	//return で別メソッドを呼ばないと表示できない

	}


}
?>