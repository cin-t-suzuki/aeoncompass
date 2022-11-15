<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrHotelAreaController extends Controller
{
    public function index()
    {
        return view('ctl.brHotelArea.index', [
            'hotel_areas' => [],
        ]);
    }

    public function new()
    {
        return view('ctl.brHotelArea.new', [

        ]);
    }

    public function edit()
    {
        return view('ctl.brHotelArea.edit', [

        ]);
    }

}
