<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;
use Illuminate\Support\Facades\Request;
use App\Models\Partner;

	class BrpartnerController extends _commonController
	{
		// 移植後のイメージ
		public function searchlist()
		{
			// データを取得
			$a_params = Request::all();
			$a_partner = new Partner();
			$s_search_flg = Request::input("search_flg");
			if ($s_search_flg == "true"){
				$a_partner_list = $a_partner->getPartners($a_params); //引数追加
			} else {
				$a_partner_list = [];
			}
			
			// データを ビューにセット
			$this->addViewData("params", $a_params);
			$this->addViewData("partner_list", $a_partner_list);
			$this->addViewData("partner_search_flg", $s_search_flg);
			// ビューを表示
			return view("ctl.brpartner.searchlist", $this->getViewData());
		}

		public function partnerconf()
		{
				$a_params = Request::all();

				// Partner モデル の インスタンスを取得
				$partner = new Partner();

				// 検索実行用フラグ
				$s_return_flg = Request::input("return_flg");
				if ($s_return_flg != "true"){
					$a_row = $partner->find(array('partner_cd'=>$a_params["partner_cd"]));
					//サービス開始日表示用
					$config['time'] = '%H:%M:%S';
					//初期表示時に検索結果をassign
					$this->addViewData("partner_value", $a_row);
				}else{
					//初期表示以外は取得したデータをassign
					$this->addViewData("partner_value", $a_params);
				}
				// データを ビューにセット
				// $this->box->item->assign->partners = $a_params;
				$this->addViewData("partners", $a_params);
				$this->addViewData("config", $config);
				// ビューを表示
				return view("ctl.brpartner.partnerconf", $this->getViewData());
		}
		
	}
?>