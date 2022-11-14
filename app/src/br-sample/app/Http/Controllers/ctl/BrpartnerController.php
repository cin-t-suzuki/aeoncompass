<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;
use Illuminate\Support\Facades\Request;
use App\Models\Partner;
use App\Models\PartnerControl;
use App\Util\Models_Cipher;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;
use App\Common\Traits;

	class BrpartnerController extends _commonController
	{
		use Traits;

		// 移植後のイメージ
		public function searchlist()
		{
			// データを取得
			$a_params = Request::all();
			$a_partner = new Partner();
			$s_search_flg = Request::input("search_flg");
			if ($s_search_flg == "true"){
				$a_partner_list = $a_partner->getPartners($a_params); 
			} else {
				$a_partner_list = [];
			}
			
			// データを ビューにセット
			$this->addViewData("params", $a_params);
			$this->addViewData("partner_list", $a_partner_list);
			$this->addViewData("partner_search_flg", $s_search_flg);
			// ビューを表示
			return view("ctl.brpartner.searchlist", $this->getViewData());
		}

		public function partnerconf()
		{
				$a_params = Request::all();

				// Partner モデル の インスタンスを取得
				$partner = new Partner();

				// リターンフラグ
				$return_from_update = Request::input("return_flg");
				if ($return_from_update != "true"){
					$a_row = $partner->selectByKey($a_params["partner_cd"]);
					//DBからの取得時は日付をフォーマット
					$date = new Carbon($a_row['open_ymd']);
					$a_row['open_ymd'] = "$date->year"."-"."$date->month"."-"."$date->day";
					//初期表示時に検索結果をassign
					$this->addViewData("partner_value", $a_row);
				}else{
					//初期表示以外は取得したデータをassign　→ 一回消したけどやっぱりいりそうなので戻した
					$this->addViewData("partner_value", $a_params);
				}
				// データを ビューにセット
				$this->addViewData("partners", $a_params);
				// ビューを表示
				return view("ctl.brpartner.partnerconf", $this->getViewData());
		}


		private function validatePartnerFromScreen(&$partnerData,$request,$partnerModel){

			// 登録情報
			$partnerData = [];
			$partnerData[$partnerModel->COL_PARTNER_CD] = $request["partner_cd"]??null;
			$partnerData[$partnerModel->COL_PARTNER_NM] = $request["partner_nm"]??null;
			$partnerData[$partnerModel->COL_SYSTEM_NM] = $request["system_nm"]??null;
			$partnerData[$partnerModel->COL_PARTNER_NS] = $request["partner_ns"]??null;
			$partnerData[$partnerModel->COL_URL] = $request["url"]??null;
			$partnerData[$partnerModel->COL_POSTAL_CD] = $request["postal_cd"]??null;
			$partnerData[$partnerModel->COL_ADDRESS] = $request["address"]??null;
			$partnerData[$partnerModel->COL_TEL] = $request["tel"]??null;
			$partnerData[$partnerModel->COL_FAX] = $request["fax"]??null;
			$partnerData[$partnerModel->COL_PERSON_POST] = $request["person_post"]??null;
			$partnerData[$partnerModel->COL_PERSON_NM] = $request["person_nm"]??null;
			$partnerData[$partnerModel->COL_PERSON_KN] = $request["person_kn"]??null;
			$partnerData[$partnerModel->COL_PERSON_EMAIL] = $request["person_email"]??null;
			$partnerData[$partnerModel->COL_OPEN_YMD] = $request["open_ymd"]??null;

	
			// バリデーション
			$errorList = $partnerModel->validation($partnerData);
	
			return $errorList;
		}

		public function partnerupdate()
		{
			$requestPartner = Request::all(); 
			$partnerModel = new Partner();

			// 画面入力を変換 （TODO 入力でも使うはず）
			$errorList = $this->validatePartnerFromScreen($partnerData, $requestPartner, $partnerModel);

			if( count($errorList) > 0){
				$errorList[] = "提携先情報の更新ができませんでした。";

				//書き換え後
				$this->addViewData("partner_value", $requestPartner); //partner_valueでもたないとupdate画面に出せない（元はpartner）
				return view("ctl.brpartner.partnerupdate", $this->getViewData())
					->with(['errors'=>$errorList]);
					
			}

			// メールアドレス暗号化
			$cipher = new Models_Cipher(config('settings.cipher_key'));
			$partnerData['person_email'] = $cipher->encrypt($partnerData['person_email']);

			// 共通カラム値設定
			$partnerModel->setUpdateCommonColumn($partnerData);

			// 更新件数
			$dbCount = 0;
			// コネクション
			try{
				$con = DB::connection('mysql');
				$dbErr = $con->transaction(function() use($con, $partnerModel, $partnerData, &$dbCount) 
				{
					// DB更新
					$dbCount = $partnerModel->updateByKey($con, $partnerData); 
					//TODO 更新件数0件でも1で戻る気がする,modify_tsがあるからでは？（共通カラム設定消すと想定通りになる）
				});

			}catch(Exception $e){
				$errorList[] = '提携先情報の更新ができませんでした。';
			}
			
	
			// 更新エラー
			if ($dbCount == 0 || count($errorList) > 0 || !empty($dbErr)){
				$errorList[] = "ご希望のデータを更新できませんでした";
				$this->addViewData("partner_value", $requestPartner); 
				return view("ctl.brpartner.partnerupdate", $this->getViewData())
					->with(['errors'=>$errorList]);
			}

			$this->addGuideMessage("提携先情報の更新が完了いたしました。 ");

			// 更新後の結果表示
			$partnerData = []; // クリア
			$partnerData = $partnerModel->selectByKey($requestPartner["partner_cd"]);
			//DBからの取得時は日付をフォーマット
			$date = new Carbon($partnerData['open_ymd']);
			$partnerData['open_ymd'] = "$date->year"."-"."$date->month"."-"."$date->day";
			$this->addViewData("partner_value", $partnerData);

			// ビューを表示
			return view("ctl.brpartner.partnerupdate", $this->getViewData());

		}

		public function partnercontroledt()
		{
			$a_params = Request::all();
			$models_partner = new Partner();

			// PartnerControlモデルを取得
			$partner_control = new PartnerControl();

			// partnercontrolupdからの戻りの判定用 
			$return_from_upd = Request::input("return_flg");

			if ($return_from_upd != "true"){
				//エラーで戻ってきた場合
				if (session()->has('return_data')) {
					//エラー時の入力値データの取得、セット
					$a_row = session()->pull('return_data');
					$this->addViewData("control_value", $a_row);
					$this->addViewData("partners", $a_row); //リクエストがないのでpartnersにも同じ値を代入
					//エラーメッセージの取得、セット
					$errorList = session()->pull('errors');
					$this->addErrorMessageArray($errorList);
				} else {
					//初期表示時にsearchlistから渡されたpartner_cdで検索
					$a_row = $partner_control->selectByKey($a_params['partner_cd']);
					//データをビューにセット
					$this->addViewData("control_value", $a_row);
					$this->addViewData("partners", $a_params);
				}
			}else{
				//初期表示以外は取得したデータをassign
				$this->addViewData("control_value", $a_params);
				$this->addViewData("partners", $a_params);
			}

			// 接続形態一覧の取得
			$a_connect_cls_list = $models_partner->get_connect_cls_list();

			// 接続形態（詳細）一覧の取得
			$a_connect_type_list = $models_partner->get_connect_type_list();

			$this->addViewData("connect_cls", $a_connect_cls_list);
			$this->addViewData("connect_type", $a_connect_type_list);

			// ビューを表示
			return view("ctl.brpartner.partnercontroledt", $this->getViewData());

		}

		private function validatePartnerControlEmailFromScreen(&$partnerControlData,$value,$partnerControlModel){

			// 登録情報
			$partnerControlData = [];
			$partnerControlData[$partnerControlModel->COL_RESULT_EMAIL] = $value??null;
			// バリデーション
			$errorList = $partnerControlModel->validation($partnerControlData);
	
			return $errorList;
		}

		private function validatePartnerControlFromScreen(&$partnerControlData,$request,$partnerControlModel){

			// 登録情報
			$partnerControlData = [];
			$partnerControlData[$partnerControlModel->COL_PARTNER_CD] = $request["partner_cd"]??null;
			$partnerControlData[$partnerControlModel->COL_CONNECT_CLS] = $request["connect_cls"]??null;
			$partnerControlData[$partnerControlModel->COL_CONNECT_TYPE] = $request["connect_type"]??null;
			$partnerControlData[$partnerControlModel->COL_ENTRY_STATUS] = $request["entry_status"]??null;
			// $partnerControlData[$partnerControlModel->COL_PW_ADMIN] = $request["pw_admin"]??null; //画面にないので非表示
			// $partnerControlData[$partnerControlModel->COL_PW_OPERATOR] = $request["pw_operator"]??null;
			$partnerControlData[$partnerControlModel->COL_PW_USER] = $request["pw_user"]??null;
			$partnerControlData[$partnerControlModel->COL_CHARSET] = $request["charset"]??null;
			$partnerControlData[$partnerControlModel->COL_VOICE_STATUS] = $request["voice_status"]??null;
			$partnerControlData[$partnerControlModel->COL_PAGE_TIMELIMIT] = $request["page_timelimit"]??null;
			$partnerControlData[$partnerControlModel->COL_EXTENSION_STATE] = $request["extension_state"]??null;
			// $partnerControlData[$partnerControlModel->COL_SALES_TYPE] = $request["sales_type"]??null;
			// $partnerControlData[$partnerControlModel->COL_AUTH_TYPE] = $request["auth_type"]??null;
			$partnerControlData[$partnerControlModel->COL_RATE] = $request["rate"]??null;
			$partnerControlData[$partnerControlModel->COL_RESULT_EMAIL] = $request["result_email"]??null;
			$partnerControlData[$partnerControlModel->COL_EMAIL_FROM_NM] = $request["email_from_nm"]??null;
			$partnerControlData[$partnerControlModel->COL_RESULT_RPC_STATUS] = $request["result_rpc_status"]??null;
			$partnerControlData[$partnerControlModel->COL_RESULT_RPC_URL] = $request["result_rpc_url"]??null;

	
			// バリデーション
			$errorList = $partnerControlModel->validation($partnerControlData);
	
			return $errorList;
		}

		public function partnercontrolupd() 
		{

			$requestPartnerControl = Request::all(); 
			$partnerControlModel = new PartnerControl();
			
				// UIに適した形式から登録用の形式に整形
				if ( $requestPartnerControl['is_send_report'] == 1 ) {
					//----------------------------------------------------------
					// 配信する
					//----------------------------------------------------------
					// 空行は削除
					foreach (($requestPartnerControl['result_email_list'] ?? array()) as $key => $value ) {
						if ($this->is_empty($value) ) {
							unset($requestPartnerControl['result_email_list'][$key]);
						} else {
							// メールアドレスを単体でバリデートチェック
							// $a_msg_errors = $validations->is_mail_of($partner_control->get_column_object('result_email'), $value);
							$requestPartnerControl['result_email'] = implode(',', $requestPartnerControl['result_email_list']);//追記
							$errorList = $this->validatePartnerControlEmailFromScreen($partnerControlData, $requestPartnerControl['result_email'], $partnerControlModel);
							if ( !$this->is_empty($errorList) ) {
								session()->put('errors', $errorList);
								session()->put('return_data',$requestPartnerControl);
								return redirect()->route('ctl.brpartner.partnercontroledt');
									//できなかった ->with(['errors'=> $errorList,'return_data',$requestPartnerControl]);
							}
						}
					}
					$requestPartnerControl['result_email'] = implode(',', $requestPartnerControl['result_email_list']);

					if ($this->is_empty($requestPartnerControl['result_email'])) {
						$errorList[] ='実績レポート配信メールアドレスが未設定です';
						session()->put('errors', $errorList);
						session()->put('return_data',$requestPartnerControl);
						return redirect()->route('ctl.brpartner.partnercontroledt');
					}

				} else {
					//----------------------------------------------------------
					// 配信しない
					//----------------------------------------------------------
					$requestPartnerControl['result_email'] = null;
				}

				if ( $requestPartnerControl['result_rpc_status'] !== '0') {
					if ($this->is_empty($requestPartnerControl['result_rpc_url'])) {
						$errorList[] ='予約時実績報告レポート配信先URLが未設定です';
						session()->put('errors', $errorList);
						session()->put('return_data',$requestPartnerControl);
						return redirect()->route('ctl.brpartner.partnercontroledt');
					}
				}


			
			// 画面入力を変換 （TODO 入力でも使うはず）
			$errorList = $this->validatePartnerControlFromScreen($partnerControlData, $requestPartnerControl, $partnerControlModel);

			if( count($errorList) > 0){
				$errorList[] = "提携先管理情報の更新ができませんでした。";
				session()->put('errors', $errorList);
				session()->put('return_data',$requestPartnerControl);
				return redirect()->route('ctl.brpartner.partnercontroledt');
					
			}

			//パスワード暗号化
			$cipher = new Models_Cipher(config('settings.cipher_key'));
			$partnerControlData['pw_user'] = $cipher->encrypt($partnerControlData['pw_user']);

			// 共通カラム値設定
			$partnerControlModel->setUpdateCommonColumn($partnerControlData);

			// 更新件数
			$dbCount = 0;
			// コネクション
			try{
				$con = DB::connection('mysql');
				$dbErr = $con->transaction(function() use($con, $partnerControlModel, $partnerControlData, &$dbCount) 
				{
					// DB更新
					$dbCount = $partnerControlModel->updateByKey($con, $partnerControlData); 
					//TODO 更新件数0件でも1で戻る気がする,modify_tsがあるからでは？（共通カラム設定消すと想定通りになる）
				});

			}catch(Exception $e){
				$errorList[] = '提携先管理情報の更新ができませんでした。';
			}
		
			// 更新エラー
			if ($dbCount == 0 || count($errorList) > 0 || !empty($dbErr)){
				$errorList[] = '提携先管理情報の更新ができませんでした';
				session()->put('errors', $errorList);
				session()->put('return_data',$requestPartnerControl);
				return redirect()->route('ctl.brpartner.partnercontroledt');
			}

			$this->addGuideMessage("提携先管理情報の更新が完了いたしました。 ");

			// 更新後の結果表示
			$partnerControlData = []; // クリア
			$partnerControlData = $partnerControlModel->selectByKey($requestPartnerControl["partner_cd"]);
			$this->addViewData("partner_control_value", $partnerControlData);
			//更新後データのcdでのnmの取得（追記したがOKか）
			$partnerModel = new Partner();
			$partnerData = $partnerModel->selectByKey($requestPartnerControl["partner_cd"]);
			$this->addViewData("partners", $partnerData); //これがないとnmがとれない（bladeも追記）

			// ビューを表示
			return view("ctl.brpartner.partnercontrolupd", $this->getViewData());
		}

	}
?>