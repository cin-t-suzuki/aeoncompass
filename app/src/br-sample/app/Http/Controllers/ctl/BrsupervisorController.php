<?php
	namespace App\Http\Controllers\ctl;

	use App\Http\Controllers\ctl\_commonController;
	use Illuminate\Support\Facades\Request;
	use App\Models\HotelSupervisorHotel;
	use App\Models\HotelSupervisor;
	use App\Models\Hotel;
	use App\Models\CommonDBModel;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Log;
	use Exception;

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
			$a_hotel_supervisor_hotel = $brsupervisormodel->getHotelSupervisorHotel(array('supervisor_cd'=>$supervisor_cd));

			//TODO 取得できなかった場合のエラーメッセージ表示する処理
			// if (count($a_hotel_supervisor_hotel['values']) == 0){
			// エラーメッセージ
			// $this->box->item->error->add('グループのホテルは存在しません。');
			// }		

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
		


	// 施設統括ホテル登録 10/13追記
	public function createhotel()
	{
		// 登録用の値をセット
		$supervisor_cd = Request::input('supervisor_cd');
		$hotel_cd = Request::input('hotel_cd');


		$HotelSupervisorHotelData = $this->getHotelSupervisorHotelData($supervisor_cd, $hotel_cd);
		
		// モデルの取得
		$HotelSupervisorHotelModel = new HotelSupervisorHotel();


		//発行前に事前チェック
		$errorList = $HotelSupervisorHotelModel->validation($HotelSupervisorHotelData);
	
		// 施設統括施設登録処理
		// 共通カラムをセット
		$HotelSupervisorHotelModel->SetInsertCommonColumn($HotelSupervisorHotelData, 'Brsupervisor/createhotel.');

		try{
			$con = DB::connection('mysql');
			$dbErr = $con->transaction(function()use($con,$HotelSupervisorHotelModel,$HotelSupervisorHotelData)
			{
				$HotelSupervisorHotelModel->singleInsert($con, $HotelSupervisorHotelData);
			});
		}catch(Exception $e){
			Log::error($e);
			$errorList[] = "グループホテル登録処理でエラーが発生しました。";
		}

		if (count($errorList) > 0){
			$errorList[] = "ご希望のデータを登録できませんでした";
			return $this->viewAgainNewScreen($errorList, $HotelSupervisorHotelData);
		}
		$this->addGuideMessage("グループホテルの登録が完了しました。");

		return $this->listhotel();
	}
		
	// 画面の値をモデルにセットする
	private function getHotelSupervisorHotelData($supervisor_cd, $hotel_cd){
		$HotelSupervisorHotelData = [];
		$HotelSupervisorHotelData['supervisor_cd'] = $supervisor_cd;
		$HotelSupervisorHotelData['hotel_cd'] = $hotel_cd;
		$HotelSupervisorHotelData['id'] = 90000;//TODOシーケンス取得。insert用に仮でIDを指定。

		return $HotelSupervisorHotelData;
	}

	/** エラー時にnew画面を表示する 
	 * 
	 * @param [type] $errorList
	 * @param [type] $hotelStatusData
	 * @return view
	 */
	private function viewAgainNewScreen($errorList, $HotelSupervisorHotelData){
		$this->addErrorMessageArray($errorList);
		$supervisor_cd = $HotelSupervisorHotelData['supervisor_cd'];
		$this->addViewData("hotel_cd", $HotelSupervisorHotelData);
		// ビューを表示
		return $this->newhotel();
	}

	
	
}
?>