<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use App\Services\Htlsroomplan2Service;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class Htlsroomplan2Controller extends _commonController
	{
		/**
		 * Display a listening plan-maintenance
		 */
		public function index(Htlsroomplan2Service $service)
		{
			try{
				$request = Request::all();

				$room_list = $service->get_room_list($request['target_cd']);
				$plan_list = $service->get_plan_list($request['target_cd']);
				
				$user = array('akafu_status' => 0, 'target_cd' => $request['target_cd']);

				return view('ctl.htlsroomplan2.index', compact('user', 'room_list', 'plan_list'));
				
			} catch (Exception $e) {
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