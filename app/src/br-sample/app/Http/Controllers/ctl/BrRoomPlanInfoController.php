<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Services\BrRoomPlanInfoService;

	
	class BrRoomPlanInfoController extends _commonController
	{

		// 検索
		public function index(Request $request ,BrRoomPlanInfoService $brRoomPlanInfoService)
		{
			try{

				// 検索条件が存在すれば
				if (empty($request ) == false){

					//検索一覧の表示
					/**
					* @param array
					*       request
					*			hotel_cd ホテルID
					* @return array
					* 		planinfo		結果内容
					*			hotel_nm		ホテル名
					*			hotel_cd		ホテルID
					*			room_list		部屋リスト
					*			plan_list	    プランリスト
					*/
					$planinfo=$brRoomPlanInfoService->listMethod($request['hotel_cd']);

				}

				$hotel_cd=$request['hotel_cd'];

				// ビューを表示
				return view("ctl.brroomplaninfo.index",compact('hotel_cd','planinfo'));

			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
				throw $e;
			}	 

		}
		
		// アクションを呼び出す際、毎回ログインチェックを行う
		public function preDispatch()
		{
			try{
				// アクションを呼び出す際、毎回処理を行う。
				parent::brDispatch();

			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
				throw $e;
			}
		}


	}
?>