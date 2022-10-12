<?php
	namespace App\Http\Controllers\ctl;

	use App\Http\Controllers\ctl\_commonController;
	use Illuminate\Support\Facades\Request;
	use App\Models\HotelSupervisorHotel;
	use App\Models\HotelSupervisor;

	use App\Common\Traits;

	class BrsupervisorController extends _commonController
	{

	// 施設統括一覧表示 hotelsupervisor
		public function list()
		{
			$supervisor_cd = Request::input('supervisor_cd');
			$supervisor_nm = Request::input('supervisor_nm');

			$brsupervisormodel = new HotelSupervisor();
			$a_hotel_supervisor = $brsupervisormodel->getHotelSupervisor();

			//TODO 取得できなかった場合のエラーメッセージ表示する処理

			$this->addViewData("a_hotel_supervisor",$a_hotel_supervisor);
			$this->addViewData("supervisor_cd",$supervisor_cd);
			$this->addViewData("supervisor_nm",$supervisor_nm);


		return view("ctl.brsupervisor.list",$this->getViewData());
		}
	
	
	// 施設統括施設一覧表示 hotelsupervisorhotel
		public function listhotel()
		{
			$id = Request::input('id');
			$supervisor_cd = Request::input('supervisor_cd');
			$brsupervisormodel = new HotelSupervisorHotel();
			$a_hotel_supervisor_hotel = $brsupervisormodel->getHotelSupervisorHotel(array('supervisor_cd'=>$supervisor_cd));//get~の処理に使う条件を配列で渡す。=supervisor_cdで絞込みしたいから。

			//TODO 取得できなかった場合のエラーメッセージ表示する処理

			$this->addViewData("a_hotel_supervisor_hotel",$a_hotel_supervisor_hotel);
			$this->addViewData("id",$id);
			$this->addViewData("supervisor_cd",$supervisor_cd);

		return view("ctl.brsupervisor.listhotel",$this->getViewData());
		}
	
	// 施設統括ホテル入力
		public function newhotel()
		{
			$supervisor_cd = Request::input('supervisor_cd');
			$a_hotel_supervisor_hotel = Request::input('Hotel_Supervisor_Hotel');
			
			// アサインの登録
			$this ->addViewData("a_hotel_supervisor_hotel",$a_hotel_supervisor_hotel);
			$this ->addViewData("supervisor_cd",$supervisor_cd);
			

		return view("ctl.brsupervisor.newhotel",$this->getViewData());
		}
		
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	}


?>