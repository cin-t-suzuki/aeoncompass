<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use App\Services\BrHotelAreaService as Service;
use Illuminate\Http\Request;

class BrHotelAreaController extends Controller
{
    /**
     * Undocumented function
     *
     * @param Request $request
     * @param Service $service
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Service $service)
    {
        $targetCd = $request->input('target_cd');
        $hotelInfo = $service->getHotelInfo($targetCd);
        $hotelAreas = $service->getHotelAreas($targetCd);

        return view('ctl.brHotelArea.index', [
            'request_params' => $request->input(),
            // 'target_cd' => $targetCd,
            'hotel_info' => $hotelInfo,
            'hotel_areas' => $hotelAreas,
        ]);
    }

    public function new(Request $request, Service $service)
    {
        $targetCd = $request->input('target_cd');
        $request_params = $request->input();

        // ↓ 貼付け
        $a_temp_hotel_area_default = array();

        // 施設コードの設定
        $this->o_models_hotel_area->set_hotel_cd($this->a_request_params['target_cd']);

        // 登録情報の設定
        if ( is_empty($this->a_request_params['is_submit']) ) {
            $a_temp_hotel_area_default = $this->o_models_hotel_area->get_hotel_area_default();

            $this->a_request_params['area_large']  = nvl($a_temp_hotel_area_default['area_large'],  -1);
            $this->a_request_params['area_pref']   = nvl($a_temp_hotel_area_default['area_pref'],   -1);
            $this->a_request_params['area_middle'] = nvl($a_temp_hotel_area_default['area_middle'], -1);
            $this->a_request_params['area_small']  = nvl($a_temp_hotel_area_default['area_small'],  -1);
        } else {
            $this->a_request_params['area_large']  = nvl($this->a_request_params['area_large'],  -1);
            $this->a_request_params['area_pref']   = nvl($this->a_request_params['area_pref'],   -1);
            $this->a_request_params['area_middle'] = nvl($this->a_request_params['area_middle'], -1);
            $this->a_request_params['area_small']  = nvl($this->a_request_params['area_small'],  -1);
        }
        // ↑ 貼付け

        $hotelInfo = $service->getHotelInfo($targetCd);

        return view('ctl.brHotelArea.new', [
            'target_cd'     => $targetCd,
            'hotel_info'    => $hotelInfo,
        ]);
    }

    public function edit(Request $request, Service $service)
    {
        $targetCd = $request->input('target_cd');
        $hotelInfo = $service->getHotelInfo($targetCd);

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
