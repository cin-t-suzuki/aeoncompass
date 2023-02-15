<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\HotelInfo;
use App\Models\HotelSearchWord;
use App\Models\KeywordsHotel;

class HtlhotelInfoController extends _commonController
{

	/**
	 * インデックス
	 * 
	 * @return view
	 */
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

	/**
	 * 登録情報入力
	 * 
	 * @return view
	 */
	public function new()
	{
		// 施設情報マスタのリクエストパラメータの取得
		$Hotel_Info = new HotelInfo();
		$targetCd = Request::input('target_cd');
		$input_data = Request::input('HotelInfo');
		$a_request_hotelinfo = $Hotel_Info->where(['hotel_cd' => $targetCd])->first();

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

	/** 
	 * 施設情報 画面 表示
	 * 
	 * @return view
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

	/**
	 * 登録
	 * 
	 * @return view
	 */
	public function create()
	{
		// 施設情報マスタのリクエストパラメータの取得
		$Hotel_Info = new HotelInfo();
		$targetCd = Request::input('target_cd');
		$input_data = Request::input('HotelInfo');
		$actionCd = $this->getActionCd();

		$a_request_hotelinfo = $Hotel_Info->where(['hotel_cd' => $targetCd])->first();

		if (!is_null($a_request_hotelinfo)) {
			$tmp_parking_info = str_replace("<", "＜", $a_request_hotelinfo['parking_info']);
			$a_request_hotelinfo['parking_info'] = str_replace(">", "＞", $tmp_parking_info);
			$tmp_card_info = str_replace("<", "＜", $a_request_hotelinfo['card_info']);
			$a_request_hotelinfo['card_info'] = str_replace(">", "＞", $tmp_card_info);
			$tmp_info = str_replace("<", "＜", $a_request_hotelinfo['info']);
			$a_request_hotelinfo['info'] = str_replace(">", "＞", $tmp_info);
		} else {
			if (empty($input_data['parking_info'])) {
				$a_request_hotelinfo['parking_info'] = null;
			} else {
				$tmp_parking_info = str_replace("<", "＜", $input_data['parking_info']);
				$a_request_hotelinfo['parking_info'] = str_replace(">", "＞", $tmp_parking_info);
			}

			if (empty($input_data['card_info'])) {
				$a_request_hotelinfo['card_info'] = null;
			} else {
				$tmp_card_info = str_replace("<", "＜", $input_data['card_info']);
				$a_request_hotelinfo['card_info'] = str_replace(">", "＞", $tmp_card_info);
			}

			if (empty($input_data['info'])) {
				$a_request_hotelinfo['info'] = null;
			} else {
				$tmp_info = str_replace("<", "＜", $input_data['info']);
				$a_request_hotelinfo['info'] = str_replace(">", "＞", $tmp_info);
			}
		}

		// キーに紐付くデータ取得
		$a_hotelinfo = $Hotel_Info->find(['hotel_cd' => $targetCd]);

		try {
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
					return $this->new();
				}

				$a_attributes['entry_cd'] = $actionCd;
				$a_attributes['entry_ts'] = now();
				$a_attributes['modify_cd'] = $actionCd;
				$a_attributes['modify_ts'] = now();

				// トランザクション開始
				DB::beginTransaction();

				$Hotel_Info->create([
					'hotel_cd' => $targetCd,
					'parking_info' => $a_attributes['parking_info'],
					'card_info' => $a_attributes['card_info'],
					'info' => $a_attributes['info'],
					'entry_cd' => $a_attributes['entry_cd'],
					'entry_ts' => $a_attributes['entry_ts'],
					'modify_cd' => $a_attributes['modify_cd'],
					'modify_ts' => $a_attributes['modify_ts'],
				]);

				// 施設情報ページの更新依頼 hotel_modify
				$hotelInfo = new HotelInfo();
				$hotelInfo->hotel_modify($a_attributes);

				// 施設検索情報テーブルへの登録
				$this->search_words($targetCd);

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

				// show アクションに転送します
				return $this->show();
			}
			// アサインをテンプレートエンジンへ渡す
			return view("ctl.htlhotelInfo.create", $this->getViewData());

			// 各メソッドで Exception が投げられた場合
		} catch (Exception $e) {
			DB::rollback();
			$this->addErrorMessage('登録に失敗しました。');
			$this->addViewData("hotelrate", $a_request_hotelinfo);
			return $this->show();
		}
	}

	/** 施設情報 更新画面表示
	 *
	 * @return view
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

	/** 施設情報 更新処理
	 *
	 * @return view
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

		// 施設情報ページの更新依頼 hotel_modify
		// hotel_modifyにinsertする場合のためにentry_cd,entry_tsを取得
		$hotelInfo->setInsertCommonColumn($hotelInfoData);
		$hotelInfo->hotel_modify($hotelInfoData);

		// 施設検索情報テーブルへの登録
		$this->search_words($hotelInfoData['hotel_cd']);

		$this->addErrorMessageArray($errorList);

		return redirect()
			->route('ctl.htlhotelInfo.show')
			->with(['target_cd' => $hotelInfoData['hotel_cd']]) // session
			->with(['guideMessage' => '施設情報データを更新しました。']);
	}

	/**
	 * 施設データをDBから取得
	 * 
	 * @return void
	 */
	private function setHotelDbData(&$hotelInfoData, $hotelCd)
	{
		$hotel = new HotelInfo();
		$hotelInfoData = $hotel->selectByKey($hotelCd);
	}

	/**
	 * 施設情報の画面の値を取得、変換、バリデーションを行う
	 * 
	 * @param [type] $hotelInfoData
	 * @param [type] $input
	 * @param [type] $hotelInfo
	 * @return array
	 */
	private function validateHotelInfoFromScreen(&$hotelInfoData, $input, $hotelInfo)
	{
		$hotelInfoData['hotel_cd'] = $input['hotel_cd'];

		if (!is_null($input['parking_info'])) {
			$hotelInfoData['parking_info'] = str_replace(">", "＞", str_replace("<", "＜", $input['parking_info']));
		} else {
			$hotelInfoData['parking_info'] = null;
		}
		if (!is_null($input['card_info'])) {
			$hotelInfoData['card_info'] = str_replace(">", "＞", str_replace("<", "＜", $input['card_info']));
		} else {
			$hotelInfoData['card_info'] = null;
		}
		$hotelInfoData['info'] = str_replace(">", "＞", str_replace("<", "＜", $input['info']));
		if (empty($hotelInfoData['info'])) {
			$hotelInfoData['info'] = null;
		}

		// バリデーションチェック
		return $hotelInfo->validation($hotelInfoData);
	}

	/**
	 * 施設キーワードの登録・更新
	 * 
	 * @return void|bool
	 */
	public function search_words($targetCd)
	{
		// 施設検索情報テーブルへの登録
		// 初期化
		$o_hotel_search_words = new HotelSearchWord();
		$a_conditions             = [];
		$a_hotel_search_words      = [];
		$a_find_hotel_search_words = [];
		$b_is_create_hsd          = false;
		$actionCd = $this->getActionCd();

		// 施設検索情報テーブルへの登録			
		// 施設検索データが存在するかチェック
		$a_find_hotel_search_words = $o_hotel_search_words->find($targetCd);

		// レコードが存在しなければ作成
		if (empty($a_find_hotel_search_words)) {
			$b_is_create_hsd = true;
			$a_find_hotel_search_words['entry_cd']  = $actionCd;
			$a_find_hotel_search_words['entry_ts']  = now();
		}

		// 登録データの整形
		$a_conditions['hotel_cd'] = $targetCd;

		$s_translate_hotel_nm     = $this->get_sql_translate_keyword('q3.hotel_nm');
		$s_translate_hotel_kn     = $this->get_sql_translate_keyword('q3.hotel_kn');
		$s_translate_hotel_old_nm = $this->get_sql_translate_keyword('q3.hotel_old_nm');
		$s_translate_info         = $this->get_sql_translate_keyword('q3.info');
		$s_translate_pref_nm      = $this->get_sql_translate_keyword('q3.pref_nm');
		$s_translate_address      = $this->get_sql_translate_keyword('q3.address');

		$s_sql =
			<<< SQL
				select	q3.hotel_cd,
						{$s_translate_hotel_nm} as hotel_nm,
						{$s_translate_hotel_kn} as hotel_kn,
						{$s_translate_hotel_old_nm} as hotel_old_nm,
						{$s_translate_info} as info,
						concat({$s_translate_pref_nm},{$s_translate_address}) as address,
						replace(q3.tel, '-', '') as tel
				from	(
							select	q2.hotel_cd,
									q2.hotel_nm,
									q2.hotel_kn,
									q2.hotel_old_nm,
									q2.tel,
									mp.pref_nm,
									q2.address,
									q2.info
							from	mast_pref mp,
									(
										select	q1.hotel_cd,
												q1.hotel_nm,
												q1.hotel_kn,
												q1.hotel_old_nm,
												q1.tel,
												q1.pref_id,
												q1.address,
												hi.info
										from	hotel_info hi,
												(
													select	hotel_cd,
															hotel_nm,
															hotel_kn,
															hotel_old_nm,
															tel,
															pref_id,
															address
													from	hotel
													where	hotel_cd = :hotel_cd
												) q1
										where	q1.hotel_cd = hi.hotel_cd
									) q2
							where	q2.pref_id  = mp.pref_id
						) q3
SQL;
		$a_hotel_search_words = DB::select($s_sql, $a_conditions);

		// 登録データの取得
		$a_find_hotel_search_words['modify_cd'] = $actionCd;
		$a_find_hotel_search_words['modify_ts'] = now();

		$a_attributes['hotel_cd']     = $targetCd;
		$a_attributes['hotel_nm']     = $a_hotel_search_words[0]->hotel_nm;
		$a_attributes['hotel_kn']     = $a_hotel_search_words[0]->hotel_kn;
		$a_attributes['hotel_old_nm']     = $a_hotel_search_words[0]->hotel_old_nm;
		$a_attributes['info']     = $a_hotel_search_words[0]->info;
		$a_attributes['address']     = $a_hotel_search_words[0]->address;
		$a_attributes['tel']     = $a_hotel_search_words[0]->tel;

		// バリデート結果を判断
		$errorList = [];
		$errorList = $o_hotel_search_words->validation($a_attributes);

		if (count($errorList) > 0) {
			$this->addErrorMessageArray($errorList);
			return false;
		}

		try {
			DB::beginTransaction();

			// レコードの登録・更新
			if ($b_is_create_hsd) {
				$o_hotel_search_words->create([
					'hotel_cd' 		=> $a_hotel_search_words[0]->hotel_cd,
					'hotel_nm' 		=> $a_hotel_search_words[0]->hotel_nm,
					'hotel_kn'		=> $a_hotel_search_words[0]->hotel_kn,
					'hotel_old_nm'	=> $a_hotel_search_words[0]->hotel_old_nm,
					'info'			=> $a_hotel_search_words[0]->info,
					'address'		=> $a_hotel_search_words[0]->address,
					'tel'			=> $a_hotel_search_words[0]->tel,
					'entry_cd'		=> $a_find_hotel_search_words['entry_cd'],
					'entry_ts'		=> $a_find_hotel_search_words['entry_ts'],
					'modify_cd'		=> $a_find_hotel_search_words['modify_cd'],
					'modify_ts'		=> $a_find_hotel_search_words['modify_ts'],
				]);
			} else {
				$o_hotel_search_words->where([
					'hotel_cd' 		=> $a_hotel_search_words[0]->hotel_cd
				])->update([
					'hotel_nm' 		=> $a_hotel_search_words[0]->hotel_nm,
					'hotel_kn'		=> $a_hotel_search_words[0]->hotel_kn,
					'hotel_old_nm'	=> $a_hotel_search_words[0]->hotel_old_nm,
					'info'			=> $a_hotel_search_words[0]->info,
					'address'		=> $a_hotel_search_words[0]->address,
					'tel'			=> $a_hotel_search_words[0]->tel,
					'modify_cd'		=> $a_find_hotel_search_words['modify_cd'],
					'modify_ts'		=> $a_find_hotel_search_words['modify_ts'],
				]);
			}

			//------------------------------------------------------------------
			// 施設キーワードの登録(Keywords_Hotel)
			//------------------------------------------------------------------
			// インスタンス生成
			$o_keywords_hotel = new KeywordsHotel();

			// キーワード項目数だけループ
			foreach ($a_hotel_search_words[0] ?? [] as $s_item_nm => $s_keyword) {
				// 施設コード以外を登録
				if ($s_item_nm !== 'hotel_cd') {
					// 初期化
					$a_keywords_hotel_work = [];
					$a_keywords_hotel_find = [];

					// 対象レコードが存在するかチェック
					$a_keywords_hotel_work['hotel_cd'] = $a_hotel_search_words[0]->hotel_cd;
					$a_keywords_hotel_work['field_nm'] = $s_item_nm;

					$a_keywords_hotel_find             = $o_keywords_hotel->where([
						'hotel_cd' => $a_keywords_hotel_work['hotel_cd'],
						'field_nm' => $a_keywords_hotel_work['field_nm']
					])->first();


					// 電話番号（TEL）のみ半角→全角へ変換されていないので対応
					if ($s_item_nm === 'tel') {
						$s_keyword = mb_convert_kana($s_keyword, 'ASK', 'UTF-8');
					}

					// 登録・更新
					// レコード無：登録
					// レコード有：更新
					if (empty($a_keywords_hotel_find)) {
						// 登録用データ作成
						$a_keywords_hotel_work = [];
						$a_keywords_hotel_work['hotel_cd']  = $a_hotel_search_words[0]->hotel_cd;
						$a_keywords_hotel_work['field_nm']  = $s_item_nm;
						$a_keywords_hotel_work['keyword']   = $s_keyword;

						// バリデート実行
						$errorList = [];
						$errorList = $o_keywords_hotel->validation($a_keywords_hotel_work);

						if (count($errorList) > 0) {
							$this->addErrorMessageArray($errorList);
							DB::rollback();
							return false;
						}
						// 登録
						$o_keywords_hotel->create([
							'hotel_cd'  => $a_hotel_search_words[0]->hotel_cd,
							'field_nm'  => $s_item_nm,
							'keyword'   => $s_keyword,
							'entry_cd'  => $actionCd,
							'entry_ts'  => now(),
							'modify_cd' => $actionCd,
							'modify_ts' => now()
						]);
					} else {
						// 更新用データ作成
						$a_keywords_hotel_work = [];
						$a_keywords_hotel_work['keyword']   = $s_keyword;

						// バリデート実行
						$errorList = [];
						$errorList = $o_keywords_hotel->validation($a_keywords_hotel_work);

						if (count($errorList) > 0) {
							$this->addErrorMessageArray($errorList);
							DB::rollback();
							return false;
						}
						// 更新
						$o_keywords_hotel->where([
							'hotel_cd'  => $a_hotel_search_words[0]->hotel_cd,
							'field_nm'  => $s_item_nm,
						])->update([
							'keyword'   => $s_keyword,
							'modify_cd' => $actionCd,
							'modify_ts' => now()
						]);
					}
				}
			}
			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return false;
		}
	}

	/**
	 * キーワードの変換を行うSQL文を取得
	 * 
	 * @return string
	 */
	public function get_sql_translate_keyword($as_str, $ab_is_trans_kana = true)
	{
		try {
			$as_str = "upper(" . $as_str . ")";                   // 半角英小文字を半角英大文字に変換
			// $as_str = "to_multi_byte(" . $as_str . ")";        // 全角文字に変換  TODO mysqlにto_multi_byteに該当するものはない？
			$as_str = "replace(" . $as_str . ", '　', '')";       // 全角空白を除去
			$as_str = "replace(" . $as_str . ", '・', '')";       // 全角「・」を除去

			if (!$ab_is_trans_kana) {
				return $as_str;
			}

			// 「ヴ」の変換は最後でないといけない
			$as_str = "replace(" . $as_str . ", 'ヴァ',  'バ')";
			$as_str = "replace(" . $as_str . ", 'ヴぁ',  'バ')";
			$as_str = "replace(" . $as_str . ", 'ｳﾞｧ',   'バ')";
			$as_str = "replace(" . $as_str . ", 'ヴィ',  'ビ')";
			$as_str = "replace(" . $as_str . ", 'ヴぃ', 'ビ')";
			$as_str = "replace(" . $as_str . ", 'ｳﾞｨ',   'ビ')";
			$as_str = "replace(" . $as_str . ", 'ヴェ',  'ベ')";
			$as_str = "replace(" . $as_str . ", 'ヴぇ',  'ベ')";
			$as_str = "replace(" . $as_str . ", 'ｳﾞｪ',   'ベ')";
			$as_str = "replace(" . $as_str . ", 'ヴォ',  'ボ')";
			$as_str = "replace(" . $as_str . ", 'ヴぉ',  'ボ')";
			$as_str = "replace(" . $as_str . ", 'ｳﾞｫ',   'ボ')";
			$as_str = "replace(" . $as_str . ", 'ヴ',    'ブ')";
			$as_str = "replace(" . $as_str . ", 'う゛',  'ブ')";
			$as_str = "replace(" . $as_str . ", 'ｳﾞ',    'ブ')";

			$as_str = "replace(" . $as_str . ", 'あいうえおかきくけこさしすせそたちつてとなにぬねのはひふへほまみむめもやゆよらりるれろわをんがぎぐげござじずぜぞだぢづでどばびぶべぼぱぴぷぺぽぁぃぅぇぉゃゅょっァィゥェォャュョッ', 'アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲンガギグゲゴザジズゼゾダヂヅデドバビブベボパピプペポアイウエオヤユヨツアイウエオヤユヨツ')";

			return $as_str;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * コントローラ名とアクション名を取得して、ユーザーIDと連結
	 * ユーザーID取得は暫定の為、書き換え替えが必要です。
	 *
	 * MEMO: app/Models/common/CommonDBModel.php から移植したもの
	 * HACK: 適切に共通化したいか。
	 * @return string
	 */
	private function getActionCd()
	{
		$path = explode("@", \Illuminate\Support\Facades\Route::currentRouteAction());
		$pathList = explode('\\', $path[0]);
		$controllerName = str_replace("Controller", "", end($pathList)); // コントローラ名
		$actionName = $path[1]; // アクション名
		$userId = \Illuminate\Support\Facades\Session::get("user_id");   // TODO: ユーザー情報取得のキーは仮です
		$actionCd = $controllerName . "/" . $actionName . "." . $userId;

		return $actionCd;
	}
}
