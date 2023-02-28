<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;

use App\Services\Htlsroom2Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Exception;


class HtlsRoom2Controller extends _commonController
{
	/**
	 * display a new-register room
	 */
	public function new(Request $request, Htlsroom2Service $service)
	{
		$hotel_cd = $request->target_cd;
		$service->check_hotel_status($hotel_cd);

		$plan_elements = DB::table('mast_plan_element')
                           ->where('element_type', '=', 'room')
                           ->get();
        $plan_element_values = DB::table('mast_plan_element_value')
                                 ->get();

		foreach($plan_elements as $element){
			$element->element_value = [];
			foreach($plan_element_values as $element_value){
				if($element->element_id == $element_value->element_id){
					array_push($element->element_value, $element_value);
				}
			}
		}

		return view('ctl.htlsroom2.new', compact('hotel_cd', 'plan_elements'));
	}

	/**
	 * register new hotel room
	 */
	public function create(Request $request, Htlsroom2Service $service)
	{
		$service->check_hotel_status($request->target_cd);

		if(! $service->Validate($request)){
			$errorMsg = Session::pull('validate-error');
			return Redirect::route('ctl.htlsroom2.new')->withErrors($errorMsg);
		}

		$room = $service->create($request);

		if(! is_array($room)){
			return view('ctl.htlsroom2.create', compact('room'));
		}else{
			return Redirect::route('ctl.htlsroom2.new')->withErrors($errorMsg);
		}
	}

	/**
	 * display edit registered hotel room
	 */
	public function edit_display(Request $request, Htlsroom2Service $service)
	{
		$hotel_cd = $request->target_cd;
		$room_id = $request->room_id;
		if(is_null($hotel_cd) && is_null($room_id)){
			$request = Session::pull('_old_input');
			$hotel_cd = $request['target_cd'];
			$room_id = $request['room_id'];
		}
		$service->check_hotel_status($hotel_cd);

		$plan_elements = DB::table('mast_plan_element')
                           ->where('element_type', '=', 'room')
                           ->get();
        $plan_element_values = DB::table('mast_plan_element_value')
                                 ->get();

		foreach($plan_elements as $element){
			$element->element_value = [];
			foreach($plan_element_values as $element_value){
				if($element->element_id == $element_value->element_id){
					array_push($element->element_value, $element_value);
				}
			}
		}

		$room = DB::table('room2')
				  ->leftJoin('room_network2', function($join){
						$join->on('room_network2.hotel_cd', '=', 'room2.hotel_cd')
							 ->on('room_network2.room_id', '=', 'room2.room_id'); 
					})
				  ->where('room2.hotel_cd', '=', $hotel_cd)
				  ->where('room2.room_id', '=', $room_id)
				  ->first();
		if(is_null($room)){
			// return Redirect::route('ctl.htlsroomplan2.index')->withErrors();
		}

		$room_media = DB::table('room_media2')  
						->leftJoin('media', function($join){
								$join->on('media.hotel_cd', '=', 'room_media2.hotel_cd')
									 ->on('media.media_no', '=', 'room_media2.media_no'); 
							})
						->where('room_media2.hotel_cd', '=', $hotel_cd)
						->where('room_media2.room_id', '=', $room_id)
						->get();
		$room_spec = DB::table('room_spec2')
					   ->where('room_spec2.hotel_cd', '=', $hotel_cd)
					   ->where('room_spec2.room_id', '=', $room_id)
					   ->get();
		
		$room->room_spec = $room_spec;
		$room->room_media = $room_media;

		return view('ctl.htlsroom2.edit', compact('hotel_cd',
												  'room_id',
												  'room',
												  'plan_elements'));
	}
	
	/**
	 * update registered hotel room
	 */
	public function update(Request $request, Htlsroom2Service $service)
	{
		$service->check_hotel_status($request->target_cd);

		if(! $service->Validate($request)){
			$errorMsg = Session::pull('validate-error');
			return Redirect::route('ctl.htlsroom2.edit_display')
						   ->withInput()
						   ->withErrors($errorMsg); 
		}

		$result = $service->update($request);
		if($result){
			$room = DB::table('room2')
                  ->leftJoin('room_network2', function($join){
                        $join->on('room_network2.hotel_cd', '=', 'room2.hotel_cd')
                             ->on('room_network2.room_id', '=', 'room2.room_id'); 
                    })
                  ->where('room2.hotel_cd', '=', $request->target_cd)
                  ->where('room2.room_id', '=', $request->room_id)
                  ->first();

			$plan_elements = DB::table('room_spec2')
							->leftJoin('mast_plan_element', function($join){
									$join->on('mast_plan_element.element_id', '=', 'room_spec2.element_id');
								})
							->leftJoin('mast_plan_element_value', function($join){
									$join->on('mast_plan_element_value.element_id', '=', 'room_spec2.element_id')
										->on('mast_plan_element_value.element_value_id', '=', 'room_spec2.element_value_id');
								})
							->where('room_spec2.hotel_cd', '=', $request->target_cd)
							->where('room_spec2.room_id', '=', $request->room_id)
							->get();
			$room_media = DB::table('room_media2')  
							->leftJoin('media', function($join){
									$join->on('media.hotel_cd', '=', 'room_media2.hotel_cd')
										 ->on('media.media_no', '=', 'room_media2.media_no'); 
								})
							->where('room_media2.hotel_cd', '=', $request->target_cd)
							->where('room_media2.room_id', '=', $request->room_id)
							->get();
			$room->plan_elements = $plan_elements;
			$room->room_media = $room_media;

			return view('ctl.htlsroom2.update', compact('room'));
		}else{

			return Redirect::route('ctl.htlsroom2.edit_display')
						   ->withInput()
						   ->withErrors($errorMsg);
		}
	}		
} 	
?>