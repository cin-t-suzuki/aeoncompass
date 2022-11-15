<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrHotelAreaController extends Controller
{
    public function index(Request $request)
    {
        $targetCd = $request->input('target_cd');
        return view('ctl.brHotelArea.index', [
            'target_cd' => $targetCd,
            'hotel_areas' => [
                (object)[
                    'hotel_cd' => $targetCd,
                ]
            ],
        ]);
    }
    
    public function new(Request $request)
    {
        $targetCd = $request->input('target_cd');
        return view('ctl.brHotelArea.new', [
            'target_cd' => $targetCd,
        ]);
    }
    
    public function edit(Request $request)
    {
        $targetCd = $request->input('target_cd');
        return view('ctl.brHotelArea.edit', [
            'target_cd' => $targetCd,
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
