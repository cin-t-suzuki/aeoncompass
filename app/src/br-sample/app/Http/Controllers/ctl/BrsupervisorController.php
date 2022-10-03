<?php
	namespace App\Http\Controllers\ctl;

	use App\Http\Controllers\ctl\_commonController;
	use Illuminate\Support\Facades\Request;
	use App\Models\HotelSupervisorHotel;
	use App\Common\Traits;

	class BrsupervisorController extends _commonController
	{
		public function listhotel()
		{
			$id = Request::input('id');
			$supervisor_cd = Request::input('supervisor_cd');


			$brsupervisormodel = new HotelSupervisorHotel();
			$a_hotel_supervisor_hotel = $brsupervisormodel->getHotelSupervisorHotel();

			//TODO 取得できなかった場合のエラーメッセージ表示する処理

			$this->addViewData("a_hotel_supervisor_hotel",$a_hotel_supervisor_hotel);
			$this->addViewData("id",$id);
			$this->addViewData("supervisor_cd",$supervisor_cd);

		return view("ctl.brsupervisor.listhotel",$this->getViewData());
		}
	}


?>