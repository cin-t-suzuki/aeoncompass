<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use App\Services\Htlsroomplan2Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class Htlsroomplan2Controller extends _commonController
{
		/**
		 * Display a listening plan-maintenance
		 */
		public function index(Htlsroomplan2Service $service, Request $request)
		{
			try{
				if(! isset($request->search_sale_status)){
					$search_sale_status = 9;
				}else{
					$search_sale_status = $request->search_sale_status;
				}
				
				$room_list = $service->get_room_list($request->target_cd);
				$plan_list = $service->get_plan_list($request->target_cd, $search_sale_status);
				
				$user = array('akafu_status' => 0, 'target_cd' => $request['target_cd']);

				return view('ctl.htlsroomplan2.index', compact('user', 'room_list', 'plan_list', 'search_sale_status'));
				
			} catch (Exception $e) {
				throw $e;
			}
			
		}

		/**
		 * update room's accept_status
		 */
		public function update_room_accept_status(Request $request)
		{
			try{
				DB::table('room2')
					->where('hotel_cd', '=', $request->target_cd)
					->where('room_id', '=', $request->room_id)
					->update(['accept_status' => $request->accept_status,
							  'modify_cd' => 'admin',
							  'modify_ts' => date('Y/m/d H:i;s')]);

				return Redirect::route('ctl.htlsroomplan2.index')->withInput($request);
			}catch(Exception $e){
				throw $e;
			}		
		}
		
		/**
		 * change room's display_status
		 */
		public function change_room_display_status(Request $request)
		{
			try{
				DB::table('room2')
					->where('hotel_cd', '=', $request->target_cd)
					->where('room_id', '=', $request->room_id)
					->update(['display_status' => 0,
							  'modify_cd' => 'admin',
							  'modify_ts' => date('Y/m/d H:i;s')]);

				return Redirect::route('ctl.htlsroomplan2.index')->withInput($request);
			}catch(Exception $e){
				throw $e;
			}		
		}
		
		/**
		 * update plan's accept_status
		 */
		public function update_plan_accept_status(Request $request)
		{
			try{
				DB::table('plan')
					->where('hotel_cd', '=', $request->target_cd)
					->where('plan_id', '=', $request->plan_id)
					->update(['accept_status' => $request->accept_status,
							  'modify_cd' => 'admin',
							  'modify_ts' => date('Y/m/d H:i;s')]);

				return Redirect::route('ctl.htlsroomplan2.index')->withInput($request);
			}catch(Exception $e){
				throw $e;
			}		
		}
		
		/**
		 * change room's display_status
		 */
		public function change_plan_display_status(Request $request)
		{
			try{
				DB::table('plan')
					->where('hotel_cd', '=', $request->target_cd)
					->where('plan_id', '=', $request->plan_id)
					->update(['display_status' => 0,
							  'modify_cd' => 'admin',
							  'modify_ts' => date('Y/m/d H:i;s')]);

				return Redirect::route('ctl.htlsroomplan2.index')->withInput($request);
			}catch(Exception $e){
				throw $e;
			}		
		}
		
		//======================================================================
		// BR販売ページへの遷移
		// ・プレビューリンクが踏まれたときに最安値更新プログラムを実行する為
		//======================================================================
		// public function previewAction()
		// {
		// 	try {
				
				//------------------------------------------------------------
				// RedirectControllerの処理を使用している為利用していませんが、
				// プレビュー仕様変更の可能性がある為残しています。
				//------------------------------------------------------------
				
				
				// 初期化
				// $o_models_charge_conditions = new Core_ChargeCondition();
				
				// リクエストの取得
				// $a_form_params = $this->params();
				
				// $a_conditions = array();
				// $a_conditions['hotel_cd'] = $a_form_params['target_cd'];
				// $a_conditions['plan_id']  = $a_form_params['plan_id'];
				
				// $s_uri = '';
				
				// 最安値更新プログラム実行
				// $o_models_charge_conditions->set_charge($a_conditions);
				
				// 販売ページへリダイレクト
				// $s_uri = 'http://' . $this->box->config->system->rsv_host_name . $this->box->info->env->source_path . 'plan/' . $a_form_params['target_cd'] . '/' . $a_form_params['plan_id'] . '/' . $a_form_params['room_id'] . '/?view=preview';
				
				// return $this->_redirect($s_uri);
				
		// 	} catch (Exception $e) {
		// 		throw $e;
		// 	}
		// }
		
	} //--->
	
?>