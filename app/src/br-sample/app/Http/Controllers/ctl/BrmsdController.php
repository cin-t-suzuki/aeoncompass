<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Services\BrmsdService;
	
	class BrmsdController extends _commonController
	{

		// プラン一覧
		public function planlist(BrmsdService $brmsdService)
		{

			
			try {

				// プラン一覧
				/**
				* @param ---
				*      
				* @return array
				* 		brmsdService		結果内容
				*			pref_nm			都道府県
				*			hotel_cd		ホテルコード
				*			hotel_nm		ホテル名
				*			plan_cd			プランコード
				*			plan_nm			プラン名
				*			room_cd			部屋コード
				*			room_nm			部屋名
				*			capacity		部屋キャパシティ
				*			partner_group_id	提携先ID
				*			capacity_value		キャパシティ内容 -> ○○、×× etc
				*
				*/
				$plan_list=$brmsdService->planlistMethod();
		
				// ビューを表示
				return view("ctl.brmsd.planlist",compact('plan_list'));

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

		

		// プラン一覧
		public function planlistcsv(BrmsdService $brmsdService)
		{

			try {

				// プラン一覧
				/**
				* @param ---
				*      
				* @return array
				* 		brmsdService		結果内容
				*			pref_nm			都道府県
				*			hotel_cd		ホテルコード
				*			hotel_nm		ホテル名
				*			plan_cd			プランコード
				*			plan_nm			プラン名
				*			room_cd			部屋コード
				*			room_nm			部屋名
				*			capacity		部屋キャパシティ
				*			partner_group_id	提携先ID
				*			capacity_value		キャパシティ内容 -> ○○、×× etc
				*
				*/
				$plan_list=$brmsdService->planlistMethod();

				//csv出力用に$plan_listの中身を成型
				$csvlist=array();
				for ($cnt = 0; $cnt < count($plan_list); $cnt++){
					$csvlist[$cnt]['pref_nm']     = $plan_list[$cnt]->pref_nm;
					$csvlist[$cnt]['hotel_cd']     = $plan_list[$cnt]->hotel_cd;
					$csvlist[$cnt]['hotel_nm']     = $plan_list[$cnt]->hotel_nm;
					$csvlist[$cnt]['plan_cd']     = $plan_list[$cnt]->plan_cd;
					$csvlist[$cnt]['plan_nm']     = $plan_list[$cnt]->plan_nm;
					$csvlist[$cnt]['room_cd']     = $plan_list[$cnt]->room_cd;
					$csvlist[$cnt]['room_nm']     = $plan_list[$cnt]->room_nm;

					if($plan_list[$cnt]->capacity == 1){
						$csvlist[$cnt]['capacity1']     = $plan_list[$cnt]->capacity_value;
					}else{
						$csvlist[$cnt]['capacity1']     ='';
					}
					
					if($plan_list[$cnt]->capacity == 2){
						$csvlist[$cnt]['capacity2']     = $plan_list[$cnt]->capacity_value;
					}else{
						$csvlist[$cnt]['capacity2']     ='';
					}
					
					if($plan_list[$cnt]->capacity == 3){
						$csvlist[$cnt]['capacity3']     = $plan_list[$cnt]->capacity_value;
					}else{
						$csvlist[$cnt]['capacity3']     ='';
					}
					
					if($plan_list[$cnt]->capacity == 4){
						$csvlist[$cnt]['capacity4']     = $plan_list[$cnt]->capacity_value;
					}else{
						$csvlist[$cnt]['capacity4']     ='';
					}
						
					if($plan_list[$cnt]->capacity == 5){
						$csvlist[$cnt]['capacity5']     = $plan_list[$cnt]->capacity_value;
					}else{
						$csvlist[$cnt]['capacity5']     ='';
					}
						
					if($plan_list[$cnt]->capacity == 6){
						$csvlist[$cnt]['capacity6']     = $plan_list[$cnt]->capacity_value;
					}else{
						$csvlist[$cnt]['capacity6']     ='';
					}

				}
				
				//laravel公開用フォルダ（./public/　配下）にplanlist.csvを作成OR上書き
				$target_file = fopen('planlist.csv', 'w');
				// 先頭行の作成
				$head = ['都道府県', '施設コード', '施設名称', 'プランコード', 'プラン名称', '部屋コード', '部屋名称', '利用人数1名', '利用人数2名', '利用人数3名', '利用人数4名', '利用人数5名', '利用人数6名'];
		   
				if ($target_file) {
					// 行の書き込み
					mb_convert_variables('sjis-win', 'UTF-8', $head);
					fputcsv($target_file, $head);
					// データの書き込み
					foreach ($csvlist as $list_item) {
					   mb_convert_variables('sjis-win', 'UTF-8', $list_item);
					   fputcsv($target_file, (array)$list_item);
					}
				}
				// ファイルを閉じる
				fclose($target_file);
		   
				// HTTPヘッダ
				header('Content-Type: text/csv');
				header('Content-Length: '.filesize('planlist.csv'));
				header('Content-Disposition: inline; filename=planlist.csv');
				readfile('planlist.csv');
		   


			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
				throw $e;
			}
		}


	}
?>