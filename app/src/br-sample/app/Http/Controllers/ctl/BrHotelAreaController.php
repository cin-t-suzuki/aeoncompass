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

        // 施設コードの設定
        $service->setHotelCd($targetCd);

        // 登録情報の設定
        // if ( is_empty($this->a_request_params['is_submit']) ) {
        if ($request->missing('is_submit')) {
            $a_temp_hotel_area_default = $service->getHotelAreaDefault();

            $request_params['area_large']  = $a_temp_hotel_area_default['area_large'] ??  -1;
            $request_params['area_pref']   = $a_temp_hotel_area_default['area_pref'] ??   -1;
            $request_params['area_middle'] = $a_temp_hotel_area_default['area_middle'] ?? -1;
            $request_params['area_small']  = $a_temp_hotel_area_default['area_small'] ??  -1;
        } else {
            $request_params['area_large']  = $request_params['area_large'] ??  -1;
            $request_params['area_pref']   = $request_params['area_pref'] ??   -1;
            $request_params['area_middle'] = $request_params['area_middle'] ?? -1;
            $request_params['area_small']  = $request_params['area_small'] ??  -1;
        }

        $hotelInfo = $service->getHotelInfo($targetCd);

        return view('ctl.brHotelArea.new', [
            'request_params' => $request_params,
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


        // ↓貼付け
        $this->oracle->beginTransaction();

        // アクションの処理
        if (!$this->createMethod()) {
            $this->oracle->rollback();
            return $this->_forward('new');
        }

        // コミット
        $this->oracle->commit();

        $s_uri .= $this->box->info->env->source_path;
        $s_uri .= $this->box->info->env->module;
        $s_uri .= '/';
        $s_uri .= $this->box->info->env->controller;
        $s_uri .= '/complete/target_cd/' . $this->box->user->hotel['hotel_cd'] . '/target_no/' . $this->o_models_hotel_area->get_active_entry_no() . '/';

        return $this->_redirect($s_uri);
        // ↑貼付け


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

    public function json(Request $request, Service $service)
    {
        header('Content-type: application/json; charset=UTF-8');
        print json_encode($service->getMastAreas());
        exit;
    }
}
