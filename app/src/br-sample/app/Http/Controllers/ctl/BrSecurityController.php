<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Services\BrSecurityService;

	class BrSecurityController extends _commonController
	{
		// // セキュリティログ一覧 
		public function search(Request $request,BrSecurityService $brSecurityService)
		{
			$errors=null;
			$a_search = $request['Search'];
			$log_securities=null;
			$search=array();

			
			try{	
				
				// 検索条件が存在すれば
				if ($this->zap_is_empty($a_search) == false){
					// 検索条件の送信日の整形
					$o_after_date  = strtotime($a_search['request_dtm_after']);
					$o_before_date =strtotime($a_search['request_dtm_before']);


					// 検索条件の設定
					$a_conditions['account_class']         = $a_search['account_class'];
					$a_conditions['account_key']           = $a_search['account_key'];
					$a_conditions['request_dtm']['after'] = date('Y-m-d 00:00:00', $o_after_date);
					$a_conditions['request_dtm']['before']  = date('Y-m-d 23:59:59', $o_before_date );

					
					// ビュー再表示の検索条件の改更
					$search=$a_search;

					// 送信日チェック
					if ($o_after_date <= $o_before_date ){
						
						// 送信電子メールキュー一覧取得
						/**
						* @param array
						*       a_conditions
						*			account_class アカウントクラス
						*			account_key   アカウント認証キー
						*			request_dtm   リクエスト日時
						* @return array
						* 		log_securities		結果内容
						*			security_cd		セキュリティログコード
						*			session_id		セッションID
						*			request_dtm		リクエスト日時
						*			account_class	アカウントクラス
						*			account_key		アカウント認証キー
						*			ip_address		IPアドレス
						*			uri				リクエストURI
						*/
						if($brSecurityService->get_log_securities($a_conditions) != null){

							$log_securities = $brSecurityService->get_log_securities($a_conditions)['values'];
						}

						if ($log_securities== null ){
							$errors=array('データが見つかりません。<br>入力された条件に該当するデータが見つかりませんでした。<br>条件を見直して、再度、検索してください。');
						}
					} else {	
						$errors=array('送信日を正しく入力してください。');
					}
				}else{
					// ビュー再表示の検索条件の改更
					$search=[
						'account_class' => '',
						'account_key' => '',
						'request_dtm_after' => '',
						'request_dtm_before' => ''
					];
				}
				
				//検索条件の有無
				$zap_is_empty=$this->zap_is_empty($a_search);



				//リクエスト日時のプルダウンの中身の生成
				$date = date('Y-m-d');
				$date_request_option=array($date);
				
				for ($i = 1; $i < 32; $i++) {
					$date =date('Y-m-d', strtotime($date . '-1 day'));
					$date_request_option[]=$date;
				}
				
				// ビューを表示、パラメータの受け渡し
				 return view("ctl.brsecurity.search",compact('errors','zap_is_empty','log_securities','search','date_request_option',));


			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
				throw $e;
			}


				
		}


		
		
		public function preDispatch()
		{
			try{
				// アクションを呼び出す際、毎回処理を行う。　管理
				parent::brDispatch();

			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
				throw $e;
			}
		}

		// インデックス
		public function indexAction()
		{
			try{
				
				$this->set_assign();

			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		
		
		// セキュリティログ内容(詳細)
		public function show(Request $request,BrSecurityService $brSecurityService)
		{
			try{

				//検索条件などパラメータの初期化
				$errors=null;
				$o_after_date =$request['request_dtm'];
				$security_cd =$request['security_cd'];
				$sql_param_month='';


				// セキュリティログのインスタンスを取得　※月毎に違う
					//　eloquent-find()メソッドで呼び出す作りにするには Log_security01~12 まで用意しなければならないので
					//  DB::ファサードのselect()メソッドを使用
				switch (date('m', strtotime($o_after_date))) {
					case '01':
						$sql_param_month='01';
						break;
					case '02':
						$sql_param_month='02';
						break;
					case '03':
						$sql_param_month='03';
						break;
					case '04':
						$sql_param_month='04';
						break;
					case '05':
						$sql_param_month='05';
						break;
					case '06':
						$sql_param_month='06';
						break;
					case '07':
						$sql_param_month='07';
						break;
					case '08':
						$sql_param_month='08';
						break;
					case '09':
						$sql_param_month='09';
						break;
					case '10':
						$sql_param_month='10';
						break;
					case '11':
						$sql_param_month='11';
						break;
					case '12':
						$sql_param_month='12';
						break;
				}


				// 検索条件の設定
				$a_conditions['sql_param_month'] = $sql_param_month;
				$a_conditions['security_cd'] = $security_cd;

				//該当データの検索
				/**
				* @param array
				*       a_conditions
				*			security_cd アカウントキー
				* @return array
				* 		log_securities		結果内容
				*			security_cd		セキュリティログコード
				*			session_id		セッションID
				*			request_dtm		リクエスト日時
				*			account_class	アカウントクラス
				*			account_key		アカウント認証キー
				*			ip_address		IPアドレス
				*			uri				リクエストURI
				*			parameter		パラメータ
				*/
				$log_securities = $brSecurityService->get_log_securities_show($a_conditions);

				// データが存在しない場合
				if ($log_securities== null ){
					$errors=array('データが見つかりません。');
				}
				
			return view('ctl.brsecurity.show',compact('log_securities','errors'));


			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
				throw $e;
			}
		}

		// 文字列が NULL または空文字または要素を持たない配列かを判断。
		function zap_is_empty($a_val)
		{
			if (is_null($a_val)) {
				return true;
			}

			if ($a_val == '') {
				return true;
			}

			if (is_array($a_val)) {
				if (count($a_val) == 0){
					return true;
				}
			}

			return false;
		}


	
	}
?>