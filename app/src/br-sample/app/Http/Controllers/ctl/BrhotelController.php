<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

use App\Models\Hotel;
use App\Models\MastPref;
use App\Models\MastCity;
use App\Models\MastWard;
use App\Models\HotelRate;
use App\Common\Traits;
use App\Models\CustomerHotel;
use App\Models\HotelAccount;
use App\Models\HotelControl;
use App\Models\HotelMscLogin;
use App\Models\HotelNotify;
use App\Models\HotelPerson;
use App\Models\HotelStaffNote;
use App\Models\HotelStatus;
use App\Models\HotelSurvey;

class BrhotelController extends _commonController
{
	use Traits;

	/** 施設情報 画面 表示
	 * 
	 * @return 施設情報 画面
	 */
	public function index()
	{
		$mastPref = new MastPref();

		$mastPrefsData = $mastPref->getMastPrefs();

		$this->addViewData("mast_prefs", $mastPrefsData);
		// ビューを表示
		return view("ctl.brhotel.index", $this->getViewData());
	}

	// 
	/** 宿泊施設検索（HTML） 
	 * info XML版は使われていないかもしれない
	 *
	 * @return void
	 */
	public function hotelsearch()
	{

		$keywords     = Request::input("keywords");
		$pref_id      = Request::input("pref_id"); // 値があれば公開中のみ
		$entry_status = Request::input("entry_status"); // 都道府県ID
		$stock_type   = Request::input("stock_type");   // 仕入れタイプ

		// modelsのHotelオブジェクトの取得
		$o_models_hotel = new Hotel();

		// 検索条件用配列
		$a_conditions = $o_models_hotel->getConditionsForSearch($keywords, $pref_id, $entry_status, $stock_type);
		
		// 検索条件に該当するホテル一覧の取得
		$a_hotel_list = $o_models_hotel->search($errorList, $a_conditions);

		$this->addViewData("hotel_list", $a_hotel_list);
		//  現行通り、メッセージ表示しない

		// ビューを表示
		return view("ctl.brhotel.hotelsearch", $this->getViewData());

	}

	/** 詳細変更 施設各情報ハブ
	 * （ホテル情報詳細変更）
	 *
	 * @return void
	 */
	public function show(){
		$hotelStaffNoteData = Request::input('Hotel_Staff_Note');
		$hotelCd = Request::input('target_cd');
		
		// 料率の一覧データを配列で取得
		$hotelRateModel = new HotelRate();
		$hotelRateData = $hotelRateModel->selectByHotelCd($hotelCd);

		$this->getHotelInfo($hotelCd, $hotelData, $mastPrefData, $mastCityData, $mastWardData);
		$this->addViewData("hotel", $hotelData);
		$this->addViewData("mast_pref", $mastPrefData); 
		$this->addViewData("mast_city", $mastCityData);
		$this->addViewData("mast_ward", $mastWardData);

		$isRegistHotel = false;
		if(count($hotelData) !=0 ){
			$isRegistHotel = true;
		}

		// 請求先 支払先関連施設
		$customerHotelModel = new CustomerHotel();
		$customerHotelData = $customerHotelModel->getCustomer($hotelCd);

		// 登録されているかの判断
		$isRegistHotelManagement = false;
		// 施設情報取得
		$hotelAccountData = (new HotelAccount())->selectByKey($hotelCd);
		$hotelPersonData = (new HotelPerson())->selectByKey($hotelCd);
		$hotelStatusData = (new HotelStatus())->selectByKey($hotelCd);		
		if (count($hotelAccountData) != 0	||  count($hotelPersonData) != 0
				||  count($hotelStatusData) != 0
		){
			$isRegistHotelManagement = true;
		}

		// 登録されているかの判断
		$isRegistHotelState = false;
		$hotelNotifyData = (new HotelNotify())->selectByKey($hotelCd);
		$hotelControlData = (new HotelControl())->selectByKey($hotelCd);
		if (count($hotelNotifyData) != 0	|| count($hotelControlData) != 0
		){
			$isRegistHotelState = true;
		}

		// 登録されているかの判断
		$isRegistHotelSurvey = false;
		$hotelSurveyData = (new HotelSurvey())->selectByKey($hotelCd);
		if (count($hotelSurveyData) != 0
		){
			$isRegistHotelSurvey = true;
		}
		
		// 特記事項 リクエストに無ければDBから取得
		$hotelStaffNoteData = [];
		if ($this->is_empty($hotelStaffNoteData)){
			// 特記事項
			$hotelStaffNoteData = (new HotelStaffNote())->selectByKey($hotelCd);
		}

		// 
		$hotelMscInfoData = (new HotelMscLogin)->getMscUsageSituation($hotelCd);

		$this->addViewData("target_cd", $hotelCd); 
		$this->addViewData("customer_hotel", $customerHotelData['values'][0]); 

		$this->addViewData("hotel_regist", $isRegistHotel); 
		$this->addViewData("hotel_management_regist", $isRegistHotelManagement); 
		$this->addViewData("hotel_state_regist", $isRegistHotelState);
		$this->addViewData("hotel_survey_regist", $isRegistHotelSurvey); 

		$this->addViewData("hotel_staff_note", $hotelStaffNoteData); 

		$this->addViewData("hotel_msc_info", $hotelMscInfoData); 
		$this->addViewData("hotelrates", $hotelRateData); 

		return view("ctl.brhotel.show", $this->getViewData());
	}

	/** 施設情報の取得とView設定
	 * 
	 * @param [type] $hotelCd
	 * @param [type] $hotelData
	 * @param [type] $mastPrefData
	 * @param [type] $mastCityData
	 * @param [type] $mastWardData
	 * @return void
	 */
	public function getHotelInfo($hotelCd, &$hotelData,
	&$mastPrefData, &$mastCityData, &$mastWardData)
{
	//施設情報 取得
	$hotelModel = new Hotel();
	$hotelData = $hotelModel->selectByKey($hotelCd);
	
	//都道府県取得
	$prefModel = new MastPref();
	$mastPrefData = $prefModel->selectByKey($hotelData['pref_id']);

	//市取得
	$cityModel = new MastCity();
	$mastCityData = $cityModel->selectByKey($hotelData['city_id']);

	//区取得
	$wardModel = new MastWard();
	$mastWardData = $wardModel->selectByKey($hotelData['ward_id']);

}



	/** 施設情報更新 編集画面 表示
	 * 
	 *
	 * @return void
	 */
	public function edit()
	{
		$hotelData = Request::input("Hotel");
		$targetCd = Request::input("target_cd");

		if(session()->exists('hotel')){	// 変更処理からの遷移
			$hotelData = session('hotel');
			$targetCd = $hotelData['hotel_cd'];
			if(session()->exists('errorMessageArr')){
				$errorList = session('errorMessageArr');
				$this->addErrorMessageArray($errorList);
			}
			session()->forget('hotel'); // クリア
			session()->forget('errorMessageArr');

		}else if ($this->is_empty($hotelData)){ // 一覧画面から遷移
				$hotelModel = new Hotel();
				$hotelData = $hotelModel->selectByKey($targetCd);
		}

		if (!isset($hotelData)){
			//施設情報取得できない場合は処理継続できないので、index画面へ
			$errorList[] = "施設情報の更新ができませんでした。";
			return redirect()->back() // 前画面に戻る
				->with(['errorMessageArr'=>$errorList]);//sessionへ設定 
		}

		//都道府県
		$mastPref = new MastPref();
		$mastPrefList = $mastPref->getMastPrefs();

		$this->setCityDataToView($hotelData['pref_id'] ?? null);//市
		
		$this->setWardDataToView($hotelData['city_id'] ?? null);//区

		$this->addViewData("hotel", $hotelData);
		$this->addViewData("mast_prefs", $mastPrefList);
		$this->addViewData("target_cd", $targetCd);
		
		// ビューを表示
		return view("ctl.brhotel.edit", $this->getViewData());
	}


	/** 市検索 プルダウン表示
	 * 
	 * @return void
	 */
	public function searchcity()
	{
		$pref_id = Request::input("pref_id");
		$this->setCityDataToView($pref_id);

		// ビューを表示
		return view("ctl.brhotel.cityselect", $this->getViewData());
	}

	/** 市リストを画面にセット
	 * 
	 * @param [type] $city_id
	 * @return void
	 */
	private function setCityDataToView($pref_id){
		$mastCityList = [];
		if (isset($pref_id) && !$this->is_empty($pref_id)){
			//区
			$mastCity = new MastCity();
			$mastCityList = $mastCity->getMastCities( $pref_id);
		}
		$this->addViewData("mast_cities", $mastCityList);
	}


	/** 区検索 プルダウン表示
	 *
	 * @return void
	 */
	public function searchward()
	{
		$city_id = Request::input("city_id");

		$this->setWardDataToView($city_id);

		// ビューを表示
		return view("ctl.brhotel.wardselect", $this->getViewData());
	}

	/** 区リストを画面にセット
	 * 
	 * @param [type] $city_id
	 * @return void
	 */
	private function setWardDataToView($city_id){
		$mastWardList = [];
		if (isset($city_id) && !$this->is_empty($city_id)){
			//区
			$mastWard = new MastWard();
			$mastWardList = $mastWard->getMastWards( $city_id);
		}
		$this->addViewData("mast_wards", $mastWardList);
	}

	/** 画面の値をセットしてバリデーションチェック（施設更新画面）
	 * 
	 *
	 * @param [type] $hotelData
	 * @param [type] $request
	 * @param [type] $hotelModel
	 * @return array
	 */
	private function validateHotelFromScreen(&$hotelData,$request,$hotelModel){

		// 登録情報
		$hotelData = [];
		$hotelData[$hotelModel->COL_HOTEL_CD] = $request["hotel_cd"]??null;
		$hotelData[$hotelModel->COL_HOTEL_CATEGORY] = $request["hotel_category"]??null;
		$hotelData[$hotelModel->COL_HOTEL_NM] = $request["hotel_nm"]??null;
		$hotelData[$hotelModel->COL_HOTEL_KN] = $request["hotel_kn"]??null;
		$hotelData[$hotelModel->COL_HOTEL_OLD_NM] = $request["hotel_old_nm"]??null;
		$hotelData[$hotelModel->COL_POSTAL_CD] = $request["postal_cd"]??null;
		$hotelData[$hotelModel->COL_PREF_ID] = $request["pref_id"]??null;
		$hotelData[$hotelModel->COL_CITY_ID] = $request["city_id"]??null;
		$hotelData[$hotelModel->COL_WARD_ID] = $request["ward_id"]??null;
		$hotelData[$hotelModel->COL_ADDRESS] = $request["address"]??null;
		$hotelData[$hotelModel->COL_TEL] = $request["tel"]??null;
		$hotelData[$hotelModel->COL_FAX] = $request["fax"]??null;
		$hotelData[$hotelModel->COL_ROOM_COUNT] = $request["room_count"]??null;
		$hotelData[$hotelModel->COL_CHECK_IN] = $request["check_in"]??null;
		$hotelData[$hotelModel->COL_CHECK_IN_END] = $request["check_in_end"]??null;
		$hotelData[$hotelModel->COL_CHECK_IN_INFO] = $request["check_in_info"]??null;
		$hotelData[$hotelModel->COL_CHECK_OUT] = $request["check_out"]??null;
		$hotelData[$hotelModel->COL_MIDNIGHT_STATUS] = $request["midnight_status"]??null;
		//TODO 更新画面で使用しない項目
		//	$hotelData[$hotelModel->COL_ORDER_NO] = $request["order_no"]??null; //TODO 更新画面にない、現行でバリデートしない
		//$hotelData[$hotelModel->COL_ACCEPT_STATUS] = $request["accept_status"]??null;//TODO  現行でバリデートしない
		//$hotelData[$hotelModel->COL_ACCEPT_AUTO] = $request["accept_auto"]??null;//TODO  現行でバリデートしない
		//$hotelData[$hotelModel->COL_ACCEPT_DTM] = $request["accept_dtm"]??null;//TODO  現行でバリデートしない

		// バリデーション
		$errorList = $hotelModel->validation($hotelData);

		// 相関チェック
		$hotelModel->checkInTimeFromTo($errorList, $hotelData[$hotelModel->COL_CHECK_IN], $hotelData[$hotelModel->COL_CHECK_IN_END]);

		return $errorList;
	}


	/** 施設情報 更新処理
	 *
	 * @return void
	 */
	public function update()
	{
		//画面の値を取得
		$requestHotel = Request::input('Hotel');
		$hotelModel = new Hotel();

		// 画面入力を変換 
		$errorList = $this->validateHotelFromScreen($hotelData, $requestHotel , $hotelModel);

		if( count($errorList) > 0){
			$errorList[] = "施設情報の更新ができませんでした。";
			return redirect()->back() // 前画面に戻る
				->with(['hotel'=>$requestHotel])	//sessionへ設定
				->with(['errorMessageArr'=>$errorList]);
		}

		// 共通カラム値設定
		$hotelModel->setUpdateCommonColumn($hotelData, 'Htlhotel/update.');

		// コネクション
		try{
			$con = DB::connection('mysql');
			$dbErr = $con->transaction(function() use($con, $hotelModel, $hotelData) 
			{
				// DB更新
				$hotelModel->updateByKey($con, $hotelData);
			});

		}catch(Exception $e){
			$errorList[] = '施設情報の更新ができませんでした。';
		}
		// 更新エラー
		if (count($errorList) > 0 || !empty($dbErr)){
			// edit 再表示 エラー遷移
			return redirect()->back() // 前画面に戻る
				->with(['HotelInfo'=>$requestHotel])	//sessionへ設定
				->with(['errorMessageArr'=>$errorList]);
		}

		$this->addGuideMessage("施設情報の更新が完了いたしました。 ");

		/* TODO  JRセット参画施設の場合の処理→不要である想定

			// 情報のセット
			$a_find_hotel = $o_hotel->find(array('hotel_cd' => $this->_request->getParam('target_cd')));

			$o_hotel_status_jr      = Hotel_Status_Jr::getInstance();
			$a_find_hotel_status_jr = $o_hotel_status_jr->find(array('hotel_cd' => $this->_request->getParam('target_cd')));
			$b_is_rejudge           = false;
			if ( !is_empty($a_find_hotel_status_jr) ) {
				// 施設の郵便番号・住所・TEL・FAXのいずれかが更新された場合は再審査状態に変更
				if ( $a_find_hotel['hotel_nm'] != $a_hotel['hotel_nm'] ) {
					$b_is_rejudge = true;
				}

				if ( $a_find_hotel['hotel_kn'] != $a_hotel['hotel_kn'] ) {
					$b_is_rejudge = true;
				}

				if ( $a_find_hotel['postal_cd'] != $a_hotel['postal_cd'] ) {
					$b_is_rejudge = true;
				}

				if ( $a_find_hotel['address'] !== $a_hotel['address'] ) {
					$b_is_rejudge = true;
				}

				if ( $a_find_hotel['tel'] !== $a_hotel['tel'] ) {
					$b_is_rejudge = true;
				}

				if ( nvl($a_find_hotel['fax'], '') !== $a_hotel['fax'] ) {
					$b_is_rejudge = true;
				}

				// 再審査が必要な場合は状態を更新
				if ( $b_is_rejudge ) {
					// バリデート設定
					$validations->set_table(Hotel_Status_Jr::getInstance());
					$validations->set_validate(array('Hotel_Status_Jr' => 'hotel_cd'));
					$validations->set_validate(array('Hotel_Status_Jr' => 'active_status'));
					$validations->set_validate(array('Hotel_Status_Jr' => 'judge_status'));
					$validations->set_validate(array('Hotel_Status_Jr' => 'last_modify_dtm'));

					// 更新データ設定
					$a_find_hotel['last_modify_dtm'] = 'sysdate';
					$a_find_hotel['modify_cd']       = $this->box->info->env->action_cd;
					$a_find_hotel['modify_ts']       = 'sysdate';
					$o_hotel_status_jr->attributes($a_find_hotel);

					// バリデート
					$validations->valid('Hotel_Status_Jr');
					if ( !$validations->is_valid() ) {
						return $this->_forward('edit');
					}

					// 更新
					if ( !$o_hotel_status_jr->update() ) {
						// ロールバック
						$this->oracle->rollback();

						// 入力画面へ
						return $this->_forward('edit');
					}
				}

			}
		*/

		//TODO 施設情報HTML生成
		/*
			$o_hotel_html_system = new Hotel_Html_System();

			//施設情報ページHTML生成
			$a_uri = array(
				'url' => $this->box->config->system->upload->static->hotel_url,
				'method' => 'GET'
			);
			$a_params = array(
				'input'				=> "http://{$this->box->config->system->bsapp_host_name}/rsv/hotel2/index/hotel_cd/{$this->_request->getParam('target_cd')}",
				'result_path'		=> "{$this->box->config->system->upload->static->rsv->htdocw}/hotel/{$this->_request->getParam('target_cd')}/index.html",
			);

			// 送信＆レスポンスの取得
			$response = $o_hotel_html_system->request_to_inside($a_uri, $a_params);
			$o_hotel_body = simplexml_load_string($response->getBody());

			// エラーの場合
			if (preg_match('/Failure/', $o_hotel_body->detail)){

				// エラーメッセージ
				$this->box->item->error->add("情報ページHTMLの作成に失敗しました。");

				return $this->_forward('edit');
			}
		*/


		// 更新後の結果表示
		$hotelData = []; // クリア
		$hotelData = $hotelModel->selectByKey($requestHotel['hotel_cd']);
		$this->addViewData("hotel", $hotelData);

		// 住所はIDしか持っていないので、名前を取り直し
		//都道府県
		$mastPrefModel = new MastPref();
		$mastPrefData = $mastPrefModel->selectByKey($hotelData['pref_id']);
		$this->addViewData("a_mast_pref", $mastPrefData);

		if (!$this->is_empty($hotelData['city_id'])){
			//市 
			$mastCityModel = new MastCity();
			$mastCityData = $mastCityModel->selectByKey($hotelData['city_id']);
			$this->addViewData("a_mast_city", $mastCityData);
		}

		if (!$this->is_empty($hotelData['ward_id'])){
			//区
			$mastWardModel = new MastWard();
			$mastWardData = $mastWardModel->selectByKey($hotelData['ward_id']);
			$this->addViewData("a_mast_ward", $mastWardData);
		}
		$this->addViewData("target_cd", $hotelData['hotel_cd']);
		// ビューを表示
		return view("ctl.brhotel.update", $this->getViewData());
	}

}
?>