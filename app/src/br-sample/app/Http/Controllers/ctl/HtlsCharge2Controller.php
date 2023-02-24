<?php

namespace App\Http\Controllers\ctl;

	use App\Services\HtlsCharge2Service as Service;
	use App\Models\Core_ChargeCondition;
	use App\Http\Controllers\Controller;
	use App\Models\Room3;
	use App\Models\Plan3;
	use Illuminate\Http\Request;

	class HtlsCharge2Controller extends Controller
	{
		// 特殊な扱いになる提携先コードの定義
		const PTN_CD_BR   = '0000000000'; // ベストリザーブ
		const PTN_CD_JRC  = '3015008801'; // JRコレクション
		const PTN_CD_RELO = '3015008796'; // リロクラブ
		
		// プランスペックの定義
		const PLAN_SPEC_MEAL = 4; // 食事
		
		//==========================================================================================
		// 事前処理
		//==========================================================================================
		public function preDispatch()
		{
			try{
				// ログインチェック（ホテル）
				parent::htlDispatch();
				
			} catch (Exception $e) {
				// 各メソッドで Exception が投げられた場合
				throw $e;
			}
		}
		

		//==========================================================================================
		// 1室設定（確認）
		//==========================================================================================
		public function singleAction()
		{
			try {
				// アクションの実行
				$this->singleMethod();
				
				// リロプランの場合、NTAログインでなければ編集不可画面を表示
				if ( $this->o_models_plan3->is_relo() and !$this->box->user->operator->is_nta()) {
					return $this->_forward('preview');
				}
				
				// 結果のアサイン
				$this->box->item->assign = $this->_assign;
				$this->set_assign();
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//==========================================================================================
		// 1室指定（編集不可・確認のみ）
		//==========================================================================================
		public function previewAction()
		{
			try {
				// アクションの実行
				$this->singleMethod();
				
				// 結果のアサイン
				$this->box->item->assign = $this->_assign;
				$this->set_assign();
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//==========================================================================================
		// 編集（確認 & 入力）
		//==========================================================================================
		public function editAction()
		{
			try {
				// アクションの実行
				if ( !$this->editMethod() ) {
					if ( $this->a_request_params['pre_action'] === 'single' ) {
						return $this->_forward('single');
					} else {
						return $this->_forward('lump');
					}
				}
				
				// リロプランの場合、NTAログインでなければプランメンテ画面へ戻す
				if ( $this->o_models_plan3->is_relo() and !$this->box->user->operator->is_nta()) {
					return $this->_forward('list', 'htlsroomplan2');
				}
				
				// 結果のアサイン
				$this->box->item->assign = $this->_assign;
				$this->set_assign();
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//==========================================================================================
		// 更新（登録 & 更新）
		//==========================================================================================
		public function updateAction()
		{
			try {
				// トランザクション開始
				$this->oracle->beginTransaction();
				
				// アクションの実行
				if ( !$this->updateMethod() ) {
					$this->oracle->rollback();
					return $this->_forward('edit');
				}
				
				// リロプランの場合、NTAログインでなければプランメンテ画面へ戻す
				if ( $this->o_models_plan3->is_relo() and !$this->box->user->operator->is_nta()) {
					$this->oracle->rollback();
					return $this->_forward('list', 'htlsroomplan2');
				}
				
				// コミット
				$this->oracle->commit();
				
				$this->box->item->guide->add('料金の設定が完了しました。');
				
				// 結果のアサイン
				$this->box->item->assign = $this->_assign;
				$this->set_assign();
				
				//--------------------------------------------------------------
				// 料金のユーザー画面への反映
				//   ※モデル内でトランザクションが実装されているのを可能ならば
				//     コントローラに任せる仕様に変更したい
				//--------------------------------------------------------------
				$o_models_charge_conditions = new Core_ChargeCondition();
				$o_models_charge_conditions->set_charge(array('hotel_cd' => $this->params('target_cd'), 'plan_id' => $this->params('plan_id')));
				
			} catch (Exception $e) {
				throw $e;
			}
		}

		/**
		 * 画面の振り分け
		 */
        public function index()
		{
			return $this->_forward('lump');
		}

		/**
		 * 一括設定画面
		 */
		public function lump(Request $request ,Service $service)
		{
			$targetCd = $request->input('target_cd');
			$planId = $request->input('plan_id');
			$pre_action = $request->input('pre_action');
			$return_path = $request->input('return_path');
			$current_ymd = $request->input('current_ymd');

			//viewへ渡すrequest_paramsを設定する。(viewで使用する必要最低限の値をセット)後々削除する
			$request_params = [
				'target_cd' => $targetCd,
				'plan_id' => $planId,
				'pre_action' => $pre_action,
				'return_path' => $return_path,
				'current_ymd' => $current_ymd
			];

			//プラン情報を取得
			$plan_detail = $service->_make_plan_detail($targetCd, $planId);

			//日付を取得
			$accept_ymd = $service->_make_plan_accept_ymd($plan_detail[0]['accept_s_ymd'], $plan_detail[0]['accept_e_ymd'], $request);

			//部屋情報を取得
			$a_detail_room = $service->_make_plan_has_rooms_detail($request);

			//設定可能な部屋の一覧リストを指定する
			$a_operation_status_rooms = $service->_make_operation_status_rooms($a_detail_room, $request);			

			// リロプランの場合、NTAログインでなければプランメンテ画面へ戻す
			if ( $service->is_relo($targetCd, $planId) and !$this->box->user->operator->is_nta()){
				return $this->_forward('list', 'htlsroomplan2');
			}
			// if ( $this->o_models_plan3->is_relo() and !$this->box->user->operator->is_nta()) {
			// 	return $this->_forward('list', 'htlsroomplan2');
			// }

			//ログイン関連は考慮しなくてもいいという話だった？,is_ntaを設定しておかないとエラーになるため定義している。
			//ntaログインを考慮しなくよいのであれば、viewでのif分で使わないようすれば、エラーは出ない。認証系はミドルウェアで済む？
			// 'is_nta' => $this->controller->is_nta()こちらも後々削除する
			$is_nta = false;

			return view('ctl.htlsCharge2.lump',[
				'opration_status_rooms' => $a_operation_status_rooms,
				'plan_has_rooms_detail' => $a_detail_room,
				'request_params' => $request_params,
				'plan_detail' => $plan_detail,
				'plan_accept_ymd_selecter' => $accept_ymd,
				"is_nta" => $is_nta
			]);
		}
	}
	
?>