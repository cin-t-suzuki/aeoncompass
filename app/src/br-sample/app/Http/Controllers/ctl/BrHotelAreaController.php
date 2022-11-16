<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrHotelAreaController extends Controller
{
    public function index(Request $request)
    {
        $targetCd = $request->input('target_cd');
        $hotelInfo = $this->dummyHotelInfo($targetCd);
        $hotelAreas = [
            $this->dummyHotelArea($targetCd),
            $this->dummyHotelArea($targetCd),
            $this->dummyHotelArea($targetCd),
            $this->dummyHotelArea($targetCd),
            $this->dummyHotelArea($targetCd),
        ];
        return view('ctl.brHotelArea.index', [
            'target_cd' => $targetCd,
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

    // TODO: to be deleted
    private function dummyHotelInfo($targetCd)
    {
        return (object)[
            'hotel_cd'  => $targetCd,
            'hotel_nm'  => 'hotel_nm_' . Str::random(4),
            'postal_cd' => 'postal_cd_' . Str::random(4),
            'pref_nm'   => 'pref_nm_' . Str::random(4),
            'address'   => 'address_' . Str::random(4),
            'tel'       => 'tel_' . Str::random(4),
            'fax'       => 'fax_' . Str::random(4),
        ];
    }
    // TODO: to be deleted
    private function dummyHotelArea($targetCd)
    {
        return (object)[
            'entry_no'      => 'entry_no_val' . Str::random(5),
            'area_nm_l'     => 'area_nm_l_val' . Str::random(5),
            'area_nm_m'     => 'area_nm_m_val' . Str::random(5),
            'area_nm_p'     => 'area_nm_p_val' . Str::random(5),
            'area_nm_s'     => 'area_nm_s_val' . Str::random(5),
            'hotel_cd'      => $targetCd,
        ];
    }
}
