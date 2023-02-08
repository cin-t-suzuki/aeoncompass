<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Exception;
	
	class BrBlackListController extends _commonController
	{

		// 検索
		public function brblacklist()
		{


				 // ビューを表示
				 return view("ctl.brblacklist.brblacklist");

		}
	}
?>