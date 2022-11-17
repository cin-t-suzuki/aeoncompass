<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Common\Traits;
use App\Models\Advert;
use App\Models\Hotel;
use Carbon\Carbon;

class BrhotelAdvert2009000400Controller extends _commonController
{
	use Traits;

	//======================================================================
	// 一覧
	//======================================================================
	public function list(){
		
		// データを取得
		$requestAdvert = Request::all();
		$advertModel = new Advert();
		// データを取得
		//別アクションからのredirectの場合は渡されたデータを反映する
		if (session()->has('return_data')) {
			$requestAdvert = session()->pull('return_data');
			$guide = session()->pull('guide');
			$this->addGuideMessage($guide);
		} else {
		//それ以外（初期表示）
			$requestAdvert = Request::all();
		}

			// 広告掲載施設の取得
			$hotel_adverts = $advertModel->get_hotel_advert_2009000400s();
			$values = $hotel_adverts['values'];

			// 更新画面からの遷移の場合 色付け用
			if (!$this->is_empty($requestAdvert['record_id']??null)){ //null追記
				$record_id = $requestAdvert['record_id'];
				$this->addViewData("record_id", $record_id);
			}

		// データを ビューにセット
		$this->addViewData("hotel_adverts", $values);

		// ビューを表示
		return view("ctl.brhoteladvert2009000400.list", $this->getViewData());

	}
	//======================================================================
	// 新規作成画面
	//======================================================================
	public function new()
	{
		// データを取得
		$a_params = Request::all();
		$hotel_advert = $a_params['hotel_advert']??null;//null追記
		$this->addViewData("hotel_advert", $hotel_advert);

		// ビューを表示
		return view("ctl.brhoteladvert2009000400.new", $this->getViewData());
	}

	//======================================================================
	// バリデーション
	//======================================================================
	private function validateAdvertFromScreen(&$advertData,$request,$advertModel){

		// 登録情報
		$advertData = [];
		$advertData[$advertModel->COL_RECORD_ID] = $request["record_id"]??null;
		$advertData[$advertModel->COL_HOTEL_CD] = $request["hotel_cd"]??null;
		$advertData[$advertModel->COL_ADVERT_S_YMD] = $request["advert_s_ymd"]??null;
		$advertData[$advertModel->COL_ADVERT_E_YMD] = $request["advert_e_ymd"]??null;
		$advertData[$advertModel->COL_ADVERT_ORDER] = $request["advert_order"]??null;
		$advertData[$advertModel->COL_ADVERT_CHARGE] = $request["advert_charge"]??null;
		$advertData[$advertModel->COL_ADVERT_STATUS] = $request["advert_status"]??null;

		// バリデーション
		$errorList = $advertModel->validation($advertData);

		return $errorList;
	}
	//======================================================================
	// 新規追加 - 登録
	//======================================================================
	public function create()
	{
						
		$requestAdvert = Request::all(); 
		$hotelModel = new Hotel();

		// 施設存在チェック
		$hotel_advert = $requestAdvert['hotel_advert'];
		$hotel = $hotelModel->selectByKey($hotel_advert['hotel_cd']);

		if ($this->is_empty($hotel)){
			$errorList[] = "対象となる施設が見つかりませんでした。";
			$this->addViewData("hotel_advert", $hotel_advert); 
			return view("ctl.brhoteladvert2009000400.new", $this->getViewData())
				->with(['errors'=>$errorList]);
		}

		// 広告掲載 ID を取得
		$advertModel = new Advert();
		$n_record_id = $advertModel->get_record_id('Hotel_Advert_2009000400');
		$n_record_id = date('Y') . str_pad($n_record_id, 4, '0', STR_PAD_LEFT);

		// 登録するデータを作成
		$hotel_advert_2009000400['record_id']  = $n_record_id;
		$hotel_advert_2009000400['hotel_cd']  = $hotel_advert['hotel_cd'];
		$hotel_advert_2009000400['advert_s_ymd']  = $hotel_advert['advert_s_ymd'];
		$hotel_advert_2009000400['advert_e_ymd']  = $hotel_advert['advert_e_ymd'];
		$hotel_advert_2009000400['advert_order']  = $hotel_advert['advert_order'];
		$hotel_advert_2009000400['advert_charge']  = $hotel_advert['advert_charge'];
		$hotel_advert_2009000400['advert_status']  = $hotel_advert['advert_status'];


		// 画面入力を変換
		$errorList = $this->validateAdvertFromScreen($advertData, $hotel_advert_2009000400, $advertModel);

		if( count($errorList) > 0){
			$errorList[] = "広告掲載施設の登録ができませんでした。";
			$this->addViewData("hotel_advert", $hotel_advert_2009000400); 
			return view("ctl.brhoteladvert2009000400.new", $this->getViewData())
				->with(['errors'=>$errorList]);
				
		}

		// 共通カラム値設定
		$advertModel->setInsertCommonColumn($advertData);

		// コネクション
		try{
			$con = DB::connection('mysql');
			$dbErr = $con->transaction(function() use($con, $advertModel, $advertData) 
			{
				// DB更新
				$advertModel->insert($con, $advertData); 
				//insertでいいか？
			});

		}catch(Exception $e){
			$errorList[] = '広告掲載施設の登録ができませんでした。';
		}
		

		// 更新エラー
		if (count($errorList) > 0 || !empty($dbErr)){
			$errorList[] = "広告掲載施設の登録ができませんでした。";
			$this->addViewData("hotel_advert", $hotel_advert_2009000400); 
			return view("ctl.brhoteladvert2009000400.new", $this->getViewData())
				->with(['errors'=>$errorList]);
		}

		// 正常に完了、一覧へ戻る
		// 更新後の結果表示
		session()->put('guide','作成しました。');
		session()->put('record_id',$n_record_id);
		session()->put('return_data',$advertData);
		return redirect()->route('ctl.brhoteladvert2009000400.list');

	}
	
	//======================================================================
	// 編集
	//======================================================================
	public function edit()
	{
		$requestAdvert = Request::all(); 
		$hotelModel = new Hotel();
		$advertModel = new Advert();

		$hotel_advert = $requestAdvert['hotel_advert'];

		// 入力エラーの場合は入力した値が取得されます。
		$hotel_advert_2009000400 = $advertModel->selectByKey($hotel_advert['record_id']);
		$hotel = $hotelModel->selectByKey($hotel_advert_2009000400['hotel_cd']);

		$hotel_advert_2009000400['hotel_nm'] = $hotel['hotel_nm'];
		
		//DBからの取得時は日付をフォーマット
		$date = new Carbon($hotel_advert_2009000400['advert_s_ymd']);
		$hotel_advert_2009000400['advert_s_ymd'] = "$date->year"."-".sprintf('%02d',$date->month)."-".sprintf('%02d',$date->day);
		$date = new Carbon($hotel_advert_2009000400['advert_e_ymd']);
		$hotel_advert_2009000400['advert_e_ymd'] = "$date->year"."-".sprintf('%02d',$date->month)."-".sprintf('%02d',$date->day);

		// データを ビューにセット
		$this->addViewData("hotel_advert", $hotel_advert_2009000400);

		// ビューを表示
		return view("ctl.brhoteladvert2009000400.edit", $this->getViewData());
	}

	//======================================================================
	// 更新
	//======================================================================
	public function update()
	{
		$advertModel = new Advert();
		$requestAdvert = Request::all(); 
		$hotel_advert = $requestAdvert['hotel_advert'];
		$hotel_advert_2009000400 = $advertModel->selectByKey($hotel_advert['record_id']);

		// 更新するデータを作成
		$hotel_advert_2009000400['advert_s_ymd']  = $hotel_advert['advert_s_ymd'];
		$hotel_advert_2009000400['advert_e_ymd']  = $hotel_advert['advert_e_ymd'];
		$hotel_advert_2009000400['advert_order']  = $hotel_advert['advert_order'];
		$hotel_advert_2009000400['advert_charge']  = $hotel_advert['advert_charge'];
		$hotel_advert_2009000400['advert_status']  = $hotel_advert['advert_status'];

		// 画面入力を変換
		$errorList = $this->validateAdvertFromScreen($advertData, $hotel_advert_2009000400, $advertModel);

		if( count($errorList) > 0){
			$errorList[] = "広告掲載施設の登録ができませんでした。";
			$this->addViewData("hotel_advert", $hotel_advert_2009000400);
			return view("ctl.brhoteladvert2009000400.edit", $this->getViewData())
				->with(['errors'=>$errorList]);
				
		}

		// 共通カラム値設定
		$advertModel->setUpdateCommonColumn($advertData);

		// 更新件数
		$dbCount = 0;
		// コネクション
		try{
			$con = DB::connection('mysql');
			$dbErr = $con->transaction(function() use($con, $advertModel, $advertData, &$dbCount) 
			{
				// DB更新
				$dbCount = $advertModel->updateByKey($con, $advertData); 
				//TODO 更新件数0件でも1で戻る気がする,modify_tsがあるからでは？（共通カラム設定消すと想定通りになる）
			});

		}catch(Exception $e){
			$errorList[] = '広告掲載施設の登録ができませんでした。';
		}		

		// 更新エラー
		if ($dbCount == 0 || count($errorList) > 0 || !empty($dbErr)){
			$errorList[] = "広告掲載施設の登録ができませんでした。";
			$this->addViewData("hotel_advert", $hotel_advert_2009000400);
			return view("ctl.brhoteladvert2009000400.edit", $this->getViewData())
				->with(['errors'=>$errorList]);
		}

		// // 更新画面からの遷移の場合 色付け用
		// $this->request->setParam('record_id', $a_hotel_advert['record_id']);

		// 正常に完了、一覧へ戻る
		// 更新後の結果表示
		session()->put('guide','更新しました。');
		session()->put('return_data',$advertData);
		return redirect()->route('ctl.brhoteladvert2009000400.list');

	}



}
