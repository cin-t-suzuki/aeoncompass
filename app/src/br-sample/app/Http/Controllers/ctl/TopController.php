<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;
	
	class TopController extends _commonController
	{

		// インデックス
		public function index()
		{

				if (env('APP_ENV') == 'product') {
					return $this->_forward('output', 'error', null, array('error_no' => '404'));
				}

				 // ビューを表示
				 return view("ctl.top.index");

		}
	}
?>