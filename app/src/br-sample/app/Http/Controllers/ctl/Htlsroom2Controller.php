<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;

use App\Services\Htlsroom2Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Exception;


class HtlsRoom2Controller extends _commonController
{
	/**
	 * display a new-register room
	 */
	public function new(Request $request, Htlsroom2Service $service)
	{
		$hotel_cd = $request->target_cd;
		$service->check_hotel_status($hotel_cd);

		$plan_elements = DB::table('mast_plan_element')
                           ->where('element_type', '=', 'room')
                           ->get();
        $plan_element_values = DB::table('mast_plan_element_value')
                                 ->get();

		foreach($plan_elements as $element){
			$element->element_value = [];
			foreach($plan_element_values as $element_value){
				if($element->element_id == $element_value->element_id){
					array_push($element->element_value, $element_value);
				}
			}
		}

		return view('ctl.htlsroom2.new', compact('hotel_cd', 'plan_elements'));
	}

	/**
	 * register new hotel room
	 */
	public function create(Request $request, Htlsroom2Service $service)
	{
		$service->check_hotel_status($request->target_cd);

		if(! $service->Validate($request)){
			$errorMsg = Session::pull('validate-error');
			return Redirect::route('ctl.htlsroom2.new')->withErrors($errorMsg);
		}

		$room = $service->create($request);

		if(! is_array($room)){
			return view('ctl.htlsroom2.create', compact('room'));
		}else{
			return Redirect::route('ctl.htlsroom2.new')->withErrors($errorMsg);
		}

		
	}

	//========================================
	// 部屋メンテナンス
	//========================================
	public function indexAction()
	{
		try {
			// アクションの実行
			$this->indexMethod();
			
			// 結果のアサイン
			$this->box->item->assign = $this->_assign;
			$this->set_assign();
		} catch (Exception $e) { // 各メソッドで Exception が投げられた場合
			throw $e;
		}
		
	}
	
//********************************************************************
//	物理削除から論理削除に変わったため不要になった
//********************************************************************
//
//		//========================================
//		// 部屋削除
//		//========================================
//		public function deleteAction()
//		{
//			try {
//				
//				// トランザクション開始
//				$this->oracle->beginTransaction();
//				
//				// 登録アクションが成功
//				if ( $this->deleteMethod() ) {
//					
//					// コミット
//					$this->oracle->commit();
//					
//				} else {
//					
//					// ロールバック
//					$this->oracle->rollback();
//					
//					// new アクションに転送します
//					return $this->_forward('list', 'htls_room_plan');
//					
//				}
//				
//				// 結果のアサイン
//				$this->box->item->assign = $this->_assign;
//				$this->set_assign();
//				
//			} catch (Exception $e) {
//				
//				throw $e;
//				
//			}
//		}
	
	//========================================
	// 編集画面
	//========================================
	public function editAction()
	{
		try {
			// 初期化
			$o_models_room2 = new models_Room2();
			
			// アクションの実行
			$this->editMethod();
			
			// 結果のアサイン
			$this->box->item->assign = $this->_assign;
			$this->set_assign();
			
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	//========================================
	// 編集画面(施設ログイン時の基幹在庫)
	//========================================
	public function previewAction()
	{
		try {
			
			// アクションの実行
			$this->editMethod();
			
			// 結果のアサイン
			$this->box->item->assign = $this->_assign;
			$this->set_assign();
			
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	//========================================
	// 更新画面
	//========================================
	public function updateAction()
	{
		
		try{
			// トランザクション開始
			$this->oracle->beginTransaction();
			
			// 登録アクションが成功
			if ( $this->updateMethod() ) {
				// コミット
				$this->oracle->commit();
			} else {
				// ロールバック
				$this->oracle->rollback();
				
				// new アクションに転送します
				return $this->_forward('edit');
			}
			
			// 結果のアサイン
			$this->box->item->assign = $this->_assign;
			$this->set_assign();
			
		} catch (Exception $e) { // 各メソッドで Exception が投げられた場合
			throw $e;
		}
	}
		
} //--->
	
?>