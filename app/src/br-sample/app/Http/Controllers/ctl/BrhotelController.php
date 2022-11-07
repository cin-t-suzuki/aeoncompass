<?php
namespace App\Http\Controllers\ctl;

use App\Common\Traits;
use App\Common\DateUtil;
use App\Http\Controllers\ctl\_commonController;
use App\Models\CustomerHotel;
use App\Models\Hotel;
use App\Models\HotelAccount;
use App\Models\HotelControl;
use App\Models\HotelMscLogin;
use App\Models\HotelNotify;
use App\Models\HotelPerson;
use App\Models\HotelRate;
use App\Models\HotelStaffNote;
use App\Models\HotelStatus;
use App\Models\HotelSurvey;
use App\Models\MastPref;
use App\Models\MastCity;
use App\Models\MastWard;
use App\Util\Models_Cipher;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

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
	 * info 別の関数のXML版は使われていないかもしれない
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
		
		// 特記事項 リクエストに無ければDBから取得（ある場合は特記事項 関連処理の戻り）
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

	/** 施設情報の取得とView設定（参照渡し）
	 * 
	 * @param [type] $hotelCd
	 * @param [type] $hotelData
	 * @param [type] $mastPrefData
	 * @param [type] $mastCityData
	 * @param [type] $mastWardData
	 * @return void
	 */
	public function getHotelInfo($hotelCd, &$hotelData, &$mastPrefData, &$mastCityData, &$mastWardData)
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

	/** 施設管理 特記事項登録
	 *
	 * @return void
	 */
	public function createNote(){
		// リクエスト取得
		$targetCd = Request::input('target_cd');

		$hotelStaffNoteModel = new HotelStaffNote();

		// バリデート
		$errorList = [];
		$errorList = $this->setScreenDataForStaffNote($hotelStaffNoteModel, $hotelStaffNoteData, $targetCd );

		if(count($errorList) > 0){
			// show アクションへ
			return $this->viewAgainShowScreen($errorList, $targetCd);
		}
		$hotelStaffNoteModel->setInsertCommonColumn($hotelStaffNoteData);
		// 新規登録
		try{
			$con = DB::connection('mysql');
			$dbErr = $con->transaction(function() use($con,
						$hotelStaffNoteModel, $hotelStaffNoteData)
			{
				$hotelStaffNoteModel->singleInsert($con, $hotelStaffNoteData);
			});
		}catch(Exception $e){
			Log::error($e);
			$errorList[] = "特記事項の登録処理でエラーが発生しました。";
		}

			//チェックエラー時は全てshowへ
		if (count($errorList) > 0){
			$errorList[] = "ご希望のデータを登録できませんでした";
			return $this->viewAgainShowScreen($errorList, $targetCd);
		}
	
		// 正常処理
		$this->addGuideMessage("特記事項の登録が完了いたしました。");

		$this->addViewData("target_cd", $targetCd);
		return $this->show();
	}

	/** 画面の値を取得し、単項目チェックを行う（参照渡し）
	 * 
	 * @param [type] $hotelStaffNoteModel
	 * @param [type] $hotelStaffNoteData
	 * @param [type] $targetCd
	 * @return array
	 */
	private function setScreenDataForStaffNote($hotelStaffNoteModel, &$hotelStaffNoteData, $targetCd){

		$hotelStaffNoteData = [];
		$hotelStaffNoteData[$hotelStaffNoteModel->COL_HOTEL_CD]=$targetCd;
		$hotelStaffNoteArray=Request::input('Hotel_Staff_Note');
		$hotelStaffNoteData[$hotelStaffNoteModel->COL_STAFF_NOTE]=$hotelStaffNoteArray['staff_note'];
		$errorList = $hotelStaffNoteModel->validation($hotelStaffNoteData);

		return $errorList;
	}

	/** 施設管理 特記事項更新
	 *
	 * @return void
	 */
	public function updateNote(){
		// リクエスト取得
		$targetCd = Request::input('target_cd');

		$hotelStaffNoteModel = new HotelStaffNote();

		// バリデート
		$errorList = [];
		$errorList = $this->setScreenDataForStaffNote($hotelStaffNoteModel, $hotelStaffNoteData, $targetCd );

		if(count($errorList) > 0){
			// show アクションへ 
			return $this->viewAgainShowScreen($errorList, $targetCd);
		}

		$hotelStaffNoteModel->setUpdateCommonColumn($hotelStaffNoteData);
		// 更新件数
		$dbCount = 0;
		// 更新
		try{
			$con = DB::connection('mysql');
			$dbErr = $con->transaction(function() use($con,
						$hotelStaffNoteModel, $hotelStaffNoteData, &$dbCount)
			{
				$dbCount = $hotelStaffNoteModel->updateByKey($con, $hotelStaffNoteData);
			});
		}catch(Exception $e){
			Log::error($e);
			$errorList[] = "特記事項の更新処理でエラーが発生しました。";
		}

		//チェックエラー時は全てshowへ
		if ($dbCount == 0 || count($errorList) > 0){
			$errorList[] = "ご希望のデータを更新できませんでした";
			return $this->viewAgainShowScreen($errorList, $targetCd);
		}

		// 正常処理
		$this->addGuideMessage("特記事項の更新が完了いたしました。");

		$this->addViewData("target_cd", $targetCd);
		return $this->show();
	}

	// 特記事項の処理エラーで 詳細変更画面を再表示する
	private function viewAgainShowScreen($errorList, $targetCd){
		$this->addErrorMessageArray($errorList);
		$this->addViewData("target_cd", $targetCd);
		return $this->show();
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

	/** 画面の値をセットしてバリデーションチェック（施設更新画面）（参照渡し）
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
		$hotelModel->setUpdateCommonColumn($hotelData);

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

    /**
     * 施設管理情報更新
     *
     * @return \Illuminate\Http\Response
     */
    public function editManagement()
    {
        $errors       = Session::pull('errors');
        $hotelAccount = Session::pull('Hotel_Account');
        $hotelPerson  = Session::pull('Hotel_Person');
        $hotelStatus  = Session::pull('Hotel_Status');

        $targetCd = Request::input('target_cd');

        $display = 'edit';

        // 登録情報の取得
        if (is_null($hotelAccount)) {
            $hotelAccount = HotelAccount::find($targetCd);
        } else {
            $hotelAccount = (object)$hotelAccount;
        }
        if (is_null($hotelPerson)) {
            $hotelPerson = HotelPerson::find($targetCd);
        } else {
            $hotelPerson = (object)$hotelPerson;
        }

        $existingHotelStatus = HotelStatus::find($targetCd);

        if (is_null($hotelStatus)) {
            $hotelStatus = $existingHotelStatus;
            if (!$this->is_empty($existingHotelStatus->contract_ymd)) {
                $hotelStatus->contract_ymd = date('Y/m/d', strtotime($existingHotelStatus->contract_ymd));
            }
            if (!$this->is_empty($existingHotelStatus->open_ymd)) {
                $hotelStatus->open_ymd = date('Y/m/d', strtotime($existingHotelStatus->open_ymd));
            }
            if (!$this->is_empty($existingHotelStatus->close_dtm)) {
                $hotelStatus->close_dtm = date('Y/m/d H:i:s', strtotime($existingHotelStatus->close_dtm));
            }
        } else {
            if (!$this->is_empty($existingHotelStatus->close_dtm)) {
                $hotelStatus['close_dtm'] = date('Y/m/d H:i:s', strtotime($existingHotelStatus->close_dtm));
            } else {
                $hotelStatus['close_dtm'] = null;
            }
            $hotelStatus = (object)$hotelStatus;
        }

        $hotelControl = HotelControl::find($targetCd);
        // 買取販売以外は料率のチェックを行う。
        $rateChk = true;
        if ($hotelControl->stock_type != HotelControl::STOCK_TYPE_PURCHASE_SALE) {
            $hotelRates = HotelRate::where('hotel_cd', $targetCd)->get();
            if ($hotelRates->isEmpty()) {
                $rateChk = false;
            }
        }

        // 表示用データの取得

        // 施設情報取得
        // 都道府県取得
        $this->getHotelInfo($targetCd, $hotelData, $mastPrefData, $mastCityData, $mastWardData);

        // 施設担当者変更履歴
        $sql = <<<SQL
            select
                p.branch_no,
                p.person_post,
                p.person_nm,
                p.person_tel,
                p.person_fax,
                p.person_email,
                date_format(p.entry_ts, '%Y/%m/%d %H:%i:%s') as entry_ts,
                date_format(p.modify_ts, '%Y/%m/%d %H:%i:%s') as modify_ts
            from
                log_hotel_person p
            where
                p.hotel_cd = :hotel_cd
            order by
                p.modify_ts desc
        SQL;

        // ホテル担当者の変更履歴
        $logHotelPersons = DB::select($sql, ['hotel_cd' => $targetCd]);

        // メール復号
        $cipher = new Models_Cipher(config('settings.cipher_key'));
        $hotelPerson->person_email = $cipher->decrypt($hotelPerson->person_email);
        foreach ($logHotelPersons as $key => $value) {
            if (!$this->is_empty($logHotelPersons[$key]->person_email)) {
                try {
                    $logHotelPersons[$key]->person_email = $cipher->decrypt($logHotelPersons[$key]->person_email);
                } catch (Exception $ignored) {
                    // 復号できないものが混じっている可能性があるため、例外を握りつぶす
                }
            }
        }

        return view('ctl.brhotel.edit-management', [
            'errors'            => $errors,

            'hotel'             => $hotelData,
            'mast_pref'         => $mastPrefData,
            'mast_city'         => $mastCityData,
            'mast_ward'         => $mastWardData,

            'target_cd'         => $targetCd,

            'disp'              => $display,
            'hotel_account'     => $hotelAccount,
            'hotel_person'      => $hotelPerson,
            'hotel_status'      => $hotelStatus,
            'log_hotel_person'  => $logHotelPersons,
            'rate_chk'          => $rateChk,

            // MEMO: 移植元では、登録の場合のみ設定されている値。
            // 未定義だと動作しないため、干渉しない値であろうを設定している。
            'status'            => null,
            'new_flg'           => 0,
            'target_stock_type' => null,
        ]);
    }

    /**
     * 施設管理情報更新処理・結果画面
     *
     * @return \Illuminate\Http\Response
     */
    public function updateManagement()
    {
        $errorList = [];

        $targetCd          = Request::input('target_cd');
        $inputHotelAccount = Request::input('Hotel_Account');
        $inputHotelPerson  = Request::input('Hotel_Person');
        $inputHotelStatus  = Request::input('Hotel_Status');

        // 日付のフォーマットをチェック
        // MEMO: 移植元のソースでは日付のフォーマットをチェックしているが、 validation() メソッドと処理が重複しているため削除した。

        // MEMO: 移植元ソースに倣っている
        $display = 'edit';

        // トランザクション開始
        DB::beginTransaction();

        // 情報のセット
        $hotel = Hotel::find($targetCd);

        // 選択された登録状態が公開中でなく、ホテルの受付状態が停止中でない場合、停止中へ
        if ($inputHotelStatus['entry_status'] != HotelStatus::ENTRY_STATUS_PUBLIC && $hotel->accept_status != Hotel::ACCEPT_STATUS_STOPPING) {
            $hotel->accept_status = Hotel::ACCEPT_STATUS_STOPPING;
        }

        if ($inputHotelStatus['entry_status'] == HotelStatus::ENTRY_STATUS_PUBLIC) {
            $hotelControl = HotelControl::find($targetCd);
            // 買取販売以外は料率のチェックを行う。
            if ($hotelControl->stock_type != HotelControl::STOCK_TYPE_PURCHASE_SALE) {
                $hotelRates = HotelRate::where('hotel_cd', $targetCd)->get();
                if ($hotelRates->isEmpty()) {
                    $errorList[] = '施設の料率情報が存在していない為、登録状態:公開中で更新できません。';
                    DB::rollBack();
                    // 編集画面へ
                    return redirect()->route('ctl.br_hotel.edit_management', ['target_cd' => $targetCd])
                        ->with([
                            'errors'        => $errorList,
                            'Hotel_Account' => $inputHotelAccount,
                            'Hotel_Person'  => $inputHotelPerson,
                            'Hotel_Status'  => $inputHotelStatus,
                        ]);
                }
            }
        }

        $hotelAccountModel = new HotelAccount();
        $hotelPersonModel = new HotelPerson();
        $hotelStatusModel = new HotelStatus();

        // validation
        $errorList = array_merge($errorList, $hotelAccountModel->validation($inputHotelAccount));
        $errorList = array_merge($errorList, $hotelPersonModel->validation($inputHotelPerson));
        $errorList = array_merge($errorList, $hotelStatusModel->validation($inputHotelStatus));
        if (count($errorList) > 0) {
            DB::rollback();
            // 編集画面へ
            return redirect()->route('ctl.br_hotel.edit_management', ['target_cd' => $targetCd])
                ->with([
                    'errors'        => $errorList,
                    'Hotel_Account' => $inputHotelAccount,
                    'Hotel_Person'  => $inputHotelPerson,
                    'Hotel_Status'  => $inputHotelStatus,
                ]);
        }

        // メール暗号化
        $cipher = new Models_Cipher(config('settings.cipher_key'));
        $inputHotelPerson['person_email'] = $cipher->encrypt($inputHotelPerson['person_email']);

        // 共通カラム設定
        $hotelAccountModel->setUpdateCommonColumn($inputHotelAccount);
        $hotelStatusModel->setUpdateCommonColumn($inputHotelStatus);
        $hotelPersonModel->setUpdateCommonColumn($inputHotelPerson);

        // 変更対象インスタンス取得
        $hotelAccount = HotelAccount::find($targetCd);
        $hotelPerson  = HotelPerson::find($targetCd);
        $hotelStatus  = HotelStatus::find($targetCd);

        // 値をセット
        $hotelAccount->fill([
            'accept_status'    => $inputHotelAccount['accept_status'],
            'account_id_begin' => $inputHotelAccount['account_id_begin'],
            'account_id'       => strtoupper($inputHotelAccount['account_id_begin']),
            'modify_cd'        => $inputHotelAccount['modify_cd'],
            'modify_ts'        => $inputHotelAccount['modify_ts'],
        ]);
        $hotelPerson->fill($inputHotelPerson);
        $hotelStatus->fill($inputHotelStatus);
        if ($inputHotelStatus['entry_status'] == HotelStatus::ENTRY_STATUS_CANCELLED) {
            $hotelStatus->close_dtm = date('Y/m/d');
        }

        // 更新実行
        if (!$hotelAccount->save() || !$hotelPerson->save() || !$hotelStatus->save() || !$hotel->save()) {
            DB::rollBack();
            $errorList[] = '施設管理情報の更新ができませんでした。';
            // 編集画面へ
            return redirect()->route('ctl.br_hotel.edit_management', ['target_cd' => $targetCd])
                ->with([
                    'errors'        => $errorList,
                    'Hotel_Account' => $inputHotelAccount,
                    'Hotel_Person'  => $inputHotelPerson,
                    'Hotel_Status'  => $inputHotelStatus,
                ]);
        }

        // コミット
        DB::commit();

        // 完了メッセージ
        $guides[] = '施設情報の更新が完了いたしました。';

        // 登録情報の取得
        $hotelAccount = HotelAccount::find($targetCd);
        $hotelPerson  = HotelPerson::find($targetCd);
        $hotelStatus  = HotelStatus::find($targetCd);

        // メール復号
        $hotelPerson->person_email = $cipher->decrypt($hotelPerson->person_email);

        // 日付の整形
        if (!$this->is_empty($hotelStatus->contract_ymd)) {
            $hotelStatus->contract_ymd = date('Y/m/d', strtotime($hotelStatus->contract_ymd));
        }
        if (!$this->is_empty($hotelStatus->open_ymd)) {
            $hotelStatus->open_ymd = date('Y/m/d', strtotime($hotelStatus->open_ymd));
        }
        if (!$this->is_empty($hotelStatus->close_dtm)) {
            $hotelStatus->close_dtm = date('Y/m/d H:i:s', strtotime($hotelStatus->close_dtm));
        }

        // 表示用データの取得
        $this->getHotelInfo($targetCd, $hotelData, $mastPrefData, $mastCityData, $mastWardData);

        return view('ctl.brhotel.update-management', [
            'guides'        => $guides,

            'hotel'         => $hotelData,
            'mast_pref'     => $mastPrefData,
            'mast_city'     => $mastCityData,
            'mast_ward'     => $mastWardData,

            'target_cd'     => $targetCd,

            'disp'          => $display,
            'hotel_account' => $hotelAccount,
            'hotel_person'  => $hotelPerson,
            'hotel_status'  => $hotelStatus,

            // MEMO: 移植元では、登録の場合のみ設定されている値。
            // 未定義だと動作しないため、干渉しない値であろうを設定している。
            // 'status' => null,
            // 'new_flg' => 0,
            'target_stock_type' => null,
        ]);
    }
}
