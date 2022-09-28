<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

use App\Models\HotelInfo;

class HtlhotelInfoController extends _commonController
{

	/** 施設情報 画面 表示
	 * 
	 * @return 施設情報 画面
	 */
	public function show()
	{
		$request = Request::all();
		
		if(session()->exists('target_cd')){
			$hotelCd = session("target_cd");
			if(session()->exists('guideMessage')){
				$this->addGuideMessage(session('guideMessage'));
			}
			session()->forget('target_cd'); // クリア
			session()->forget('guideMessage');
			
		}else if(isset($request["target_cd"])){
			$hotelCd = $request["target_cd"];
		}else{
			//TODO 取れない場合は新規登録へ 呼び元機能未確認
		}

		// 施設データ取得
		$this->setHotelDbData($hotelInfoData, $hotelCd);

		$this->addViewData("hotelInfo", $hotelInfoData);

		// ビューを表示
		return view("ctl.htlhotelInfo.show", $this->getViewData());
	}

	/** 施設データをDBから取得
	 *
	 * @return void
	 */
	private function setHotelDbData(&$hotelInfoData, $hotelCd)
	{
		$hotel = new HotelInfo();
		$hotelInfoData = $hotel->selectByKey($hotelCd);
	}

	/** 施設情報 更新画面表示
	 *
	 * @return void
	 */
	public function edit()
	{
		// 施設情報マスタのリクエストパラメータの取得
		$request = Request::all();

		if(session()->exists('HotelInfo')){	// 変更画面からの遷移
			$hotelInfoData = session('HotelInfo');
			$hotelCd = $hotelInfoData['hotel_cd'];
			if(session()->exists('errorMessageArr')){
				$errorList = session('errorMessageArr');
				$this->addErrorMessageArray($errorList);
			}
		}else if(isset($request)){	//照会画面からの遷移
			$requestHotelInfo = $request['HotelInfo'];
			$hotelCd = $requestHotelInfo['hotel_cd'];
			// 施設データ取得
			$this->setHotelDbData($hotelInfoData, $hotelCd);
		}

		$this->addViewData("hotelInfo", $hotelInfoData);
		$this->addViewData("target_cd", $hotelCd);

		// ビューを表示
		return view("ctl.htlhotelInfo.edit", $this->getViewData());

	}

	// 施設情報の画面の値を取得、変換、バリデーションを行う
	private function validateHotelInfoFromScreen(&$hotelInfoData, $input, $hotelInfo)
	{
		$hotelInfoData['hotel_cd'] = $input['hotel_cd'];
		$hotelInfoData['parking_info'] = str_replace(">","＞",str_replace("<","＜",$input['parking_info']));
		$hotelInfoData['card_info'] = str_replace(">","＞",str_replace("<","＜",$input['card_info']));
		$hotelInfoData['info'] = str_replace(">","＞", str_replace("<","＜",$input['info']));

		// バリデーションチェック
		return $hotelInfo->validation($hotelInfoData);
	}


	/** 施設情報 更新処理
	 *
	 * @return void
	 */
	public function update()
	{
		//画面の値を取得
		$request = Request::all();
		$hotelInfo = new HotelInfo();

		$requestHotelInfo = $request['HotelInfo'];
		// 画面入力を変換 入力でも使う
		$errorList = $this->validateHotelInfoFromScreen($hotelInfoData, $requestHotelInfo , $hotelInfo);

		if( count($errorList) > 0){
			$errorList[] = "ご希望の施設情報データを更新できませんでした。";
			return redirect()->back() // 前画面に戻る
				->with(['HotelInfo'=>$requestHotelInfo])	//sessionへ設定
				->with(['errorMessageArr'=>$errorList]);
		}
		// 共通カラム値設定
		$hotelInfo->setUpdateCommonColumn($hotelInfoData, 'HtlhotelInfo/update.');
		// コネクション

		try{
			$con = DB::connection('mysql');
			$dbErr = $con->transaction(function() use($con, $hotelInfo, $hotelInfoData) 
			{
				// DB更新
				$hotelInfo->updateByKey($con, $hotelInfoData);
			});

		}catch(Exception $e){
			$errorList[] = 'ご希望の施設情報データを更新できませんでした。';
		}
		// 更新エラー
		if (count($errorList) > 0 || !empty($dbErr)){
			// edit 再表示 エラー遷移
			return redirect()->back() // 前画面に戻る
				->with(['HotelInfo'=>$requestHotelInfo])	//sessionへ設定
				->with(['errorMessageArr'=>$errorList]);
		}

		$this->addErrorMessageArray($errorList);

		return redirect()
			->route('ctl.htlhotelInfo.show')
			->with(['target_cd'=>$hotelInfoData['hotel_cd']]) // session
			->with(['guideMessage'=>'施設情報データを更新しました。']);

	}





}
?>