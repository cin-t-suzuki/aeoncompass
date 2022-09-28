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
//TODO 削除予定
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
			$hotelCd = $$a_request_hotelrate['hotel_cd'];
		}

		//料率の一覧データ取得
		//TODO get_hotel_ratesのSQLで行っている accept_s_ymd 日付変換が必要かどうか。画面確認値→「2016-11-24 00:00:00」
		$hotelRateModel = new HotelRate();
		$a_hotelrate = $hotelRateModel->selectByHotelCd($hotelCd);

		//施設情報 取得
		$hotelModel = new Hotel();
		$a_hotel = $hotelModel->selectByKey($hotelCd);
		
		//都道府県取得
		$prefModel = new MastPref();
		$a_mast_pref = $prefModel->selectByKey($a_hotel['pref_id']);

		//市取得
		$cityModel = new MastCity();
		$a_mast_city = $cityModel->selectByKey($a_hotel['city_id']);

		//区取得
		$wardModel = new MastWard();
		$a_mast_ward = $wardModel->selectByKey($a_hotel['ward_id']);

		$this->addViewData("hotel", $a_hotel);
		$this->addViewData("mast_pref", $a_mast_pref); 
		$this->addViewData("mast_city", $a_mast_city);
		$this->addViewData("mast_ward", $a_mast_ward);

		$this->addViewData("hotelrates", $a_hotelrate); 
		$this->addViewData("hotelrate", $a_request_hotelrate);
		$this->addViewData("target_cd", $targetCd);
		$this->addViewData("hotel_cd", "");//TODO 初期表示ででは使わないが、update、delete で使用するらしい

		// ビューを表示
		return view("ctl.brhotelRate.list", $this->getViewData());

/*	TODO  参考
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
*/


	}



	//TODO 参考
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
		return view("ctl.brhotelRate.edit", $this->getViewData());
	}
	
	/**  更新処理
	 * 
	 * @return view
	 */
	public function update()
	{
		// リクエストの取得
		$requestHotelStatus    =  Request::input('hotel_status');     // 施設情報
		$targetCd = Request::input('target_cd');  
		$requestHotelStatus['hotel_cd'] = $targetCd;



		/*
				if (count($errorList) > 0 || $dbCount == 0){
					$errorList[] = "？？の更新ができませんでした。";
					return $this->viewAgainEditScreen($errorList, $requestHotelStatus);
				}
		*/
		// 更新エラーなし 正常処理
		$this->addGuideMessage("？？の更新が完了しました。");
		// ビューedit 変更画面を表示
		$this->addViewData("target_cd", $targetCd);		// targetCd リクエスト渡し
		return $this->index(); 	//return で別メソッドを呼ばないと表示できない

	}


}
?>