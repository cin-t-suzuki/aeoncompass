<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Exception;
	
	class BrRoomPlanInfoController extends _commonController
	{

		// 検索
		public function index()
		{


				 // ビューを表示
				 return view("ctl.brroomplaninfo.index");

		}
	}
?>