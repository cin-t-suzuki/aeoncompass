<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Services\BrSecurityService;


// use App\Models\Br_Models_Date.php;

// use App\Models\models_System.php;
	
	class BrSecurityController extends _commonController
	{

		

		// // セキュリティログ一覧 
		public function search(Request $request,BrSecurityService $brSecurityService)
		{
			$errors=null;
			$a_search = $request['Search'];
			$log_securities=null;

				// dd($a_search);
			try{	
				// Member モデルを生成
				// $o_models_system = new models_System();
				
				// 検索条件が存在すれば
				if ($this->zap_is_empty($a_search) == false){
						// dd('detayo');
					// 検索条件の送信日の整形
					// $o_after_date  = new Br_Models_Date($a_search['request_dtm_after']);
					// $o_before_date = new Br_Models_Date($a_search['request_dtm_before']);
					$o_after_date  = strtotime($a_search['request_dtm_after']);
					$o_before_date =strtotime($a_search['request_dtm_before']);

					// dd($o_before_date);
					// dd($a_search['request_dtm_before']);
					// 23:59:59の設定
					// $o_before_date->add('h', 23);
					// $o_before_date->add('i', 59);
					// $o_before_date->add('s', 59);

					// 検索条件の設定
					$a_conditions['account_class']         = $a_search['account_class'];
					$a_conditions['account_key']           = $a_search['account_key'];
					// $a_conditions['request_dtm']['before'] = $o_before_date->to_format('Y-m-d H:i:s');
					// $a_conditions['request_dtm']['after']  = $o_after_date->to_format('Y-m-d H:i:s');
					$a_conditions['request_dtm']['before'] = date('Y-m-d 23:59:59', $o_after_date);
					$a_conditions['request_dtm']['after']  = date('Y-m-d 23:59:59', $o_before_date );

					// dd($a_conditions['request_dtm']['before']  );

						// $log_securities=array(	1=>['security_cd' => "hotel",
						// 							'session_id' => "hotel",
						// 							'request_dtm' => "hotel",
						// 							'account_class' => "hotel",
						// 							'account_key' => "hotel",
						// 							'ip_address' => "hotel",
						// 							'uri' => "hotel"],
						// 						2=>['security_cd' => "hotel",
						// 							'session_id' => "hotel",
						// 							'request_dtm' => "hotel",
						// 							'account_class' => "hotel",
						// 							'account_key' => "hotel",
						// 							'ip_address' => "hotel",
						// 							'uri' => "hotel"],);

					// 送信日チェック
					// if ($o_before_date->get() > $o_after_date->get()){
					if ($o_after_date <= $o_before_date ){
						
						// 送信電子メールキュー一覧取得
						// $a_log_securities = $o_models_system->get_log_securities($a_conditions);
						$a_log_securities = $brSecurityService->get_log_securities($a_conditions);

						// データが存在しない場合
						// if (count($log_securities['values']) == 0){
						if (!isset($log_securities) ){
							$errors=array('データが見つかりません。<br>入力された条件に該当するデータが見つかりませんでした。<br>条件を見直して、再度、検索してください。');
						}
					} else {
						// dd('detayo');
						$errors=array('送信日を正しく入力してください。');
						// $this->box->item->error->add('送信日を正しく入力してください。');
					}
				}
				
				// アサイン登録
				// $this->box->item->assign->log_securities = $a_log_securities;
				// $this->box->item->assign->search         = $a_search;
				
				// $this->set_assign();
				$search=array('account_class' => "partner",'account_key' => "hotel");
				$zap_is_empty=$this->zap_is_empty($search['account_class']);



				//リクエスト日時のプルダウンの中身
				$date = date('Y-m-d');
				$date_request_option=array($date);
				
				for ($i = 1; $i < 32; $i++) {
					$date =date('Y-m-d', strtotime($date . '-1 day'));
					$date_request_option[]=$date;
				}
				

				 // ビューを表示
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
		public function showAction()
		{
			try{
				
				// 検索条件の送信日の設定
				$o_after_date = new Br_Models_Date($this->_request->getParam('request_dtm'));

				// セキュリティログのインスタンスを取得　※月毎に違う
				switch ($o_after_date->to_format('m')) {
					case '01':
						$o_log_security = log_security_01::getInstance();
						break;
					case '02':
						$o_log_security = log_security_02::getInstance();
						break;
					case '03':
						$o_log_security = log_security_03::getInstance();
						break;
					case '04':
						$o_log_security = log_security_04::getInstance();
						break;
					case '05':
						$o_log_security = log_security_05::getInstance();
						break;
					case '06':
						$o_log_security = log_security_06::getInstance();
						break;
					case '07':
						$o_log_security = log_security_07::getInstance();
						break;
					case '08':
						$o_log_security = log_security_08::getInstance();
						break;
					case '09':
						$o_log_security = log_security_09::getInstance();
						break;
					case '10':
						$o_log_security = log_security_10::getInstance();
						break;
					case '11':
						$o_log_security = log_security_11::getInstance();
						break;
					case '12':
						$o_log_security = log_security_12::getInstance();
						break;
				}
				
				$a_log_security = $o_log_security->find(array(
															'security_cd'  => $this->_request->getParam('security_cd'),
				));

				// データが存在しない場合
				if (count($a_log_security) == 0){
					$this->box->item->error->add('データが見つかりません。');
				}
				
				// アサイン登録
				$this->box->item->assign->log_security = $a_log_security;
				
				$this->set_assign();

			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
				throw $e;
			}
		}

		// 文字列が NULL または空文字または要素を持たない配列かを判断します。
	// 0 は true と判断します。
	// ※この関数は今後利用してはいけません 2009/05/12
	//
	// example
	//   ''       -> true
	//   null     -> true
	//   array    -> true
	//   0        -> true
	//   'a'      -> false
	//   array(0) -> false
	function zap_is_empty($a_val)
	{

		// $o_controller = Zend_Controller_Front::getInstance();
		// $box  = & $o_controller->getPlugin('Box')->box;

		// if ($box->config->environment->status == 'development'){
		// 	$x = debug_backtrace();
		// 	print('<div style="border:1px solid #f00; margin:1px">新しい【is_empty】を利用しなさい。<br>'.$x[1]['file'] .'(' .$x[1]['line'].')</div>');
		// }

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