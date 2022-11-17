<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use App\Services\BrHotelAreaService as Service;
use Illuminate\Http\Request;

class BrHotelAreaController extends Controller
{
    public function index(Request $request, Service $service)
    {
        $targetCd = $request->input('target_cd');
        $hotelInfo = $service->getHotelInfo($targetCd);

        $hotelAreas = [
            $service->dummyHotelArea($targetCd),
            $service->dummyHotelArea($targetCd),
            $service->dummyHotelArea($targetCd),
            $service->dummyHotelArea($targetCd),
            $service->dummyHotelArea($targetCd),
        ];
        $hotelAreas = $service->getHotelAreas($targetCd);

        return view('ctl.brHotelArea.index', [
            'request_params' => $request->input(),
            // 'target_cd' => $targetCd,
            'hotel_info' => $hotelInfo,
            'hotel_areas' => $hotelAreas,
        ]);
    }

    public function new(Request $request)
    {
        $targetCd = $request->input('target_cd');
        $hotelInfo = $this->dummyHotelInfo($targetCd);

        return view('ctl.brHotelArea.new', [
            'target_cd'     => $targetCd,
            'hotel_info'    => $hotelInfo,
        ]);
    }

    public function edit(Request $request)
    {
        $targetCd = $request->input('target_cd');
        $hotelInfo = $this->dummyHotelInfo($targetCd);

        return view('ctl.brHotelArea.edit', [
            'target_cd'     => $targetCd,
            'hotel_info'    => $hotelInfo,
        ]);
    }

    public function create(Request $request)
    {
        $targetCd = $request->input('target_cd');
        return redirect()->route('ctl.br_hotel_area.index', ['target_cd' => $targetCd]);

    }

    public function update(Request $request)
    {
        $targetCd = $request->input('target_cd');
        return redirect()->route('ctl.br_hotel_area.index', ['target_cd' => $targetCd]);
    }

    public function delete(Request $request)
    {
        $targetCd = $request->input('target_cd');
        return redirect()->route('ctl.br_hotel_area.index', ['target_cd' => $targetCd]);
    }
}
