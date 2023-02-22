<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Services\BrPartnerTotalService;
	
	class BrPartnerTotalController extends _commonController
	{

		// 検索
		public function search(Request $request,BrPartnerTotalService $brPartnerTotalService)
		{

			$a_partner_group ='';
			$total_material_list='';

			try{
				// 提携先専用料金設定を行っているプラン情報、選択肢の生成
				/**
				* @param array
				*       aa_conditions
				*			partner_group_id 提携先コード
				* @return array
				* 		a_result		結果内容
				*			partner_group_id		提携先コード
				*			partner_group_nm		提携先名
				*			
				*/
				$a_partner_group = $brPartnerTotalService->get_material_partner_group();

				if (!empty($request['partner_group_id'])) {

					// 提携先専用料金設定を行っているプラン情報の集計,リスト表示
					/**
					* @param array
					*       aa_conditions
					*			partner_group_id 提携先コード
					* @return array
					* 		a_row		結果内容
					*			hotel_cd		施設コード
					*			hotel_nm		施設名
					*			pref_nm		都道府県
					*			address		住所
					*			tel		ホテルTEL
					*			fax		ホテルFAX
					*			room_nm		部屋名称
					*			room_id		部屋コード
					*			plan_nm		プラン名称
					*			plan_id		プランコード
					*			room_charge_hotel_cd		ベストリザーブ料金設定有無
					*			extend_status		自動延長対象有無
					*			
					*/
					$total_material_list = $brPartnerTotalService->get_total_material(array('partner_group_id' => $request['partner_group_id']));
				}
			

				$partner_group_list = $a_partner_group['values'];
				$partner_group_id=$request['partner_group_id'];

					// ビューを表示
					return view("ctl.brpartnertotal.search",compact('partner_group_list','partner_group_id','total_material_list'));

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