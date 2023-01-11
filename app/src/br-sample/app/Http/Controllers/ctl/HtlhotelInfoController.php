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

	// インデックス
	public function index()
	{
		// Hotel_Rate モデル の インスタンスを取得
		$Hotel_Info = new HotelInfo();
		$targetCd = Request::input('target_cd');
		$a_hotelinfo = $Hotel_Info->find(['hotel_cd' => $targetCd])->first();

		if (is_null($a_hotelinfo)) {
			return $this->new();
		} else {
			//show アクションに転送します
			return $this->show();
		}
	}


	// 登録処理入力
	public function new()
	{
		// 施設情報マスタのリクエストパラメータの取得
		$Hotel_Info = new HotelInfo();
		$targetCd = Request::input('target_cd');
		$input_data = Request::input('HotelInfo');
		$a_request_hotelinfo = $Hotel_Info->find(array('hotel_cd' => $targetCd))->first();

		if (!is_null($a_request_hotelinfo)) {
			$tmp_parking_info = str_replace("<", "＜", $a_request_hotelinfo['parking_info']);
			$a_request_hotelinfo['parking_info'] = str_replace(">", "＞", $tmp_parking_info);
			$tmp_card_info = str_replace("<", "＜", $a_request_hotelinfo['card_info']);
			$a_request_hotelinfo['card_info'] = str_replace(">", "＞", $tmp_card_info);
			$tmp_info = str_replace("<", "＜", $a_request_hotelinfo['info']);
			$a_request_hotelinfo['info'] = str_replace(">", "＞", $tmp_info);
		}

		try {
			if ($targetCd != "") {
				$a_request_hotelinfo['hotel_cd'] = $targetCd;
			}

			// アサインの登録
			$this->addViewData("hotelinfos", $a_request_hotelinfo);
			$this->addViewData("target_cd", $targetCd);
			$this->addViewData("input_data", $input_data);

			// ビューを表示
			return view("ctl.htlhotelInfo.new", $this->getViewData());

			// 各メソッドで Exception が投げられた場合
		} catch (Exception $e) {
			throw $e;
		}
	}

	// 登録
	public function create()
	{
		// 施設情報マスタのリクエストパラメータの取得
		$Hotel_Info = new HotelInfo();
		$targetCd = Request::input('target_cd');
		$input_data = Request::input('HotelInfo');

		$a_request_hotelinfo = $Hotel_Info->find(array('hotel_cd' => $targetCd))->first();
		if (!is_null($a_request_hotelinfo)) {
			$tmp_parking_info = str_replace("<", "＜", $a_request_hotelinfo['parking_info']);
			$a_request_hotelinfo['parking_info'] = str_replace(">", "＞", $tmp_parking_info);
			$tmp_card_info = str_replace("<", "＜", $a_request_hotelinfo['card_info']);
			$a_request_hotelinfo['card_info'] = str_replace(">", "＞", $tmp_card_info);
			$tmp_info = str_replace("<", "＜", $a_request_hotelinfo['info']);
			$a_request_hotelinfo['info'] = str_replace(">", "＞", $tmp_info);
		} else {
			$tmp_parking_info = str_replace("<", "＜", $input_data['parking_info']);
			$a_request_hotelinfo['parking_info'] = str_replace(">", "＞", $tmp_parking_info);
			$tmp_card_info = str_replace("<", "＜", $input_data['card_info']);
			$a_request_hotelinfo['card_info'] = str_replace(">", "＞", $tmp_card_info);
			$tmp_info = str_replace("<", "＜", $input_data['info']);
			$a_request_hotelinfo['info'] = str_replace(">", "＞", $tmp_info);
			if (empty($a_request_hotelinfo['info'])) {
				$a_request_hotelinfo['info'] = null;
			}
		}

		try {
			// トランザクション開始
			DB::beginTransaction();

			// キーに紐付くデータ取得
			$a_hotelinfo = $Hotel_Info->find(['hotel_cd' => $targetCd]);

			// 更新対象のテーブルがない場合、新規登録実行する
			if (count($a_hotelinfo) == 0) {

				$a_attributes = [];
				$a_attributes['hotel_cd'] = $targetCd;
				$a_attributes['parking_info'] = $a_request_hotelinfo['parking_info'];
				$a_attributes['card_info'] = $a_request_hotelinfo['card_info'];
				$a_attributes['info'] = $a_request_hotelinfo['info'];

				// バリデート結果を判断
				$errorList = [];
				$errorList = $Hotel_Info->validation($a_attributes);

				if (count($errorList) > 0) {
					$this->addErrorMessageArray($errorList);
					DB::rollback();
					return $this->new();
				}

				$Hotel_Info->create([
					'hotel_cd' => $targetCd,
					'parking_info' => $a_attributes['parking_info'],
					'card_info' => $a_attributes['card_info'],
					'info' => $a_attributes['info'],
					'entry_cd' => 'action_cd', // TODO $this->box->info->env->action_cd
					'entry_ts' => now(),
					'modify_cd' => 'modify_cd', // TODO $this->box->info->env->action_cd
					'modify_ts' => now(),
				]);

				// コミット
				DB::commit();


				//アサイン登録
				$this->addViewData("hotelrate", $a_request_hotelinfo);
				$this->addViewData("target_cd", $targetCd);
				$this->addGuideMessage("下記内容で新規登録しました");

				// show アクションに転送します
				return $this->show();
			} else {
				$this->addErrorMessage('既にデータが存在します。');
				$this->addViewData("hotelrate", $a_request_hotelinfo);

				// edit アクションに転送します
				return $this->show();
			}
			// アサインをテンプレートエンジンへ渡す
			return view("ctl.htlhotelInfo.create", $this->getViewData());

			// 各メソッドで Exception が投げられた場合
		} catch (Exception $e) {
			throw $e;
		}
	}




	/** 施設情報 画面 表示
	 * 
	 * @return 施設情報 画面
	 */
	public function show()
	{
		$request = Request::all();

		if (session()->exists('target_cd')) {
			$hotelCd = session("target_cd");
			if (session()->exists('guideMessage')) {
				$this->addGuideMessage(session('guideMessage'));
			}
			session()->forget('target_cd'); // クリア
			session()->forget('guideMessage');
		} else if (isset($request["target_cd"])) {
			$hotelCd = $request["target_cd"];
		} else {
			return $this->new();
		}

		// 施設データ取得
		$this->setHotelDbData($hotelInfoData, $hotelCd);

		$this->addViewData("hotelInfo", $hotelInfoData);
		$this->addViewData("target_cd", $hotelCd);

		// ビューを表示
		return view("ctl.htlhotelInfo.show", $this->getViewData());
	}

	/** 施設データをDBから取得（参照渡し）
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

		if (session()->exists('HotelInfo')) {	// 変更画面からの遷移
			$hotelInfoData = session('HotelInfo');
			$hotelCd = $hotelInfoData['hotel_cd'];
			if (session()->exists('errorMessageArr')) {
				$errorList = session('errorMessageArr');
				$this->addErrorMessageArray($errorList);
			}
		} else if (isset($request)) {	//照会画面からの遷移
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

	/** 施設情報の画面の値を取得、変換、バリデーションを行う（参照渡し）
	 *
	 * @param [type] $hotelInfoData
	 * @param [type] $input
	 * @param [type] $hotelInfo
	 * @return array
	 */
	private function validateHotelInfoFromScreen(&$hotelInfoData, $input, $hotelInfo)
	{
		$hotelInfoData['hotel_cd'] = $input['hotel_cd'];
		$hotelInfoData['parking_info'] = str_replace(">", "＞", str_replace("<", "＜", $input['parking_info']));
		$hotelInfoData['card_info'] = str_replace(">", "＞", str_replace("<", "＜", $input['card_info']));
		$hotelInfoData['info'] = str_replace(">", "＞", str_replace("<", "＜", $input['info']));
		if (empty($hotelInfoData['info'])) {
			$hotelInfoData['info'] = null;
		}

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
		$errorList = $this->validateHotelInfoFromScreen($hotelInfoData, $requestHotelInfo, $hotelInfo);

		if (count($errorList) > 0) {
			return redirect()->back() // 前画面に戻る
				->with(['HotelInfo' => $requestHotelInfo])	//sessionへ設定
				->with(['errorMessageArr' => $errorList]);
		}
		// 共通カラム値設定
		$hotelInfo->setUpdateCommonColumn($hotelInfoData);
		// コネクション

		try {
			$con = DB::connection('mysql');
			$dbErr = $con->transaction(function () use ($con, $hotelInfo, $hotelInfoData) {
				// DB更新
				$hotelInfo->updateByKey($con, $hotelInfoData);
			});
		} catch (Exception $e) {
			$errorList[] = 'ご希望の施設情報データを更新できませんでした。';
		}
		// 更新エラー
		if (count($errorList) > 0 || !empty($dbErr)) {
			// edit 再表示 エラー遷移
			return redirect()->back() // 前画面に戻る
				->with(['HotelInfo' => $requestHotelInfo])	//sessionへ設定
				->with(['errorMessageArr' => $errorList]);
		}

		$this->addErrorMessageArray($errorList);

		return redirect()
			->route('ctl.htlhotelInfo.show')
			->with(['target_cd' => $hotelInfoData['hotel_cd']]) // session
			->with(['guideMessage' => '施設情報データを更新しました。']);
	}
}
