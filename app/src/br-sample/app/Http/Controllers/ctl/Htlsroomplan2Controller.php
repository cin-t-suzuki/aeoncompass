<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use App\Http\Models\HtlsRoomPlan2Model;

use Illuminate\Support\Facades\Request;

	//==========================================================================
	// Htlsroomplan2 : Htlsroomplan2
	//==========================================================================
	// require_once '../models/HtlsRoomPlan2Model.php';
	// require_once '_common/models/Core/ChargeCondition.php';
	
	class Htlsroomplan2Controller extends _commonController
	{
		//======================================================================
		// 事前処理
		//======================================================================
		public function preDispatch()
		{
			try{
				// ログインチェック
				parent::htlDispatch(); // ホテル向け認証
				
				//--------------------------------
				// バージョンチェック
				//--------------------------------
				// 旧画面利用施設の場合は管理画面TOPへ飛ばす
				$o_hotel_system_version = Hotel_System_Version::getInstance();
				$a_hotel_system_version = $o_hotel_system_version->find(array('hotel_cd' => $this->params('target_cd'), 'system_type' => 'plan'));
				
				if ( $a_hotel_system_version['version'] === '1' ) {
					return $this->_helper->redirector('index', 'htltop', 'ctl');
				}
				
			} catch (Exception $e) {
				// 各メソッドで Exception が投げられた場合
				throw $e;
			}
			
		}
		
		//======================================================================
		// インデックス
		//======================================================================
		public function index()
		{
			try{
				// ※別画面のテンプレートを表示する場合
				// return $this->_forward('list');
				$request = Request::all();

				$user = array('akafu_status' => 0, 'target_cd' => $request['target_cd']);
				$this->addViewData('user', $user);

				return view('ctl.htlsroomplan2.index', $this->getViewData());
				
			} catch (Exception $e) {
				throw $e;
			}
			
		}
		
		//======================================================================
		// 一覧
		//======================================================================
		public function listAction()
		{
			try{
				// アクションの処理
				$this->listMethod();
				
				// ※当該画面のtplを表示
				$this->box->item->assign = $this->_assign;
				$this->set_assign();
				
			} catch (Exception $e) {
				// 各メソッドで Exception が投げられた場合
				throw $e;
			}
			
		}
		
		//======================================================================
		// 部屋休止状態更新
		//======================================================================
		public function updateroomacceptAction()
		{
			try {
				// トランザクション開始
				$this->oracle->beginTransaction();
				
				// アクションの処理
				if ( $this->updateroomacceptMethod() ) {
					// 成功時
					$this->oracle->commit();
				} else {
					// 失敗時
					$this->oracle->rollback(); // ロールバック
				}
				
				return $this->_forward('list');
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 部屋削除(論理削除)
		//======================================================================
		public function hiddenroomAction()
		{
			try {
				// トランザクション開始
				$this->oracle->beginTransaction();
				
				// アクションの処理
				if ( $this->hiddenroomMethod() ) {
					// 成功時
					$this->oracle->commit();
				} else {
					// 失敗時
					$this->oracle->rollback(); // ロールバック
				}
				
				return $this->_forward('list');
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// プラン休止状態更新
		//======================================================================
		public function updateplanacceptAction()
		{
			try {
				// トランザクション開始
				$this->oracle->beginTransaction();
				
				// アクションの処理
				if ( $this->updateplanacceptMethod() ) {
					// 成功時
					$this->oracle->commit();
				} else {
					// 失敗時
					$this->oracle->rollback(); // ロールバック
				}
				
				return $this->_forward('list');
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// プラン削除(論理削除)
		//======================================================================
		public function hiddenplanAction()
		{
			try {
				// トランザクション開始
				$this->oracle->beginTransaction();
				
				// アクションの処理
				if ( $this->hiddenplanMethod() ) {
					// 成功時
					$this->oracle->commit();
				} else {
					// 失敗時
					$this->oracle->rollback(); // ロールバック
				}
				
				return $this->_forward('list');
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// BR販売ページへの遷移
		// ・プレビューリンクが踏まれたときに最安値更新プログラムを実行する為
		//======================================================================
		public function previewAction()
		{
			try {
				
				//------------------------------------------------------------
				// RedirectControllerの処理を使用している為利用していませんが、
				// プレビュー仕様変更の可能性がある為残しています。
				//------------------------------------------------------------
				
				
				// 初期化
				$o_models_charge_conditions = new Core_ChargeCondition();
				
				// リクエストの取得
				$a_form_params = $this->params();
				
				$a_conditions = array();
				$a_conditions['hotel_cd'] = $a_form_params['target_cd'];
				$a_conditions['plan_id']  = $a_form_params['plan_id'];
				
				$s_uri = '';
				
				// 最安値更新プログラム実行
				$o_models_charge_conditions->set_charge($a_conditions);
				
				// 販売ページへリダイレクト
				$s_uri = 'http://' . $this->box->config->system->rsv_host_name . $this->box->info->env->source_path . 'plan/' . $a_form_params['target_cd'] . '/' . $a_form_params['plan_id'] . '/' . $a_form_params['room_id'] . '/?view=preview';
				
				return $this->_redirect($s_uri);
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
	} //--->
	
?>