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
            'guides' => $request->session()->pull('guides', []),
        ]);
    }

    public function new(Request $request, Service $service)
    {
        $targetCd = $request->input('target_cd');

        // 登録情報の設定
        if ($request->session()->has('input_data')) {
            // input_data があれば、入力を保持して表示
            $areaIdSet = $request->session()->pull('input_data');
        } else {
            // input_data がなければ、初期表示用データを表示
            $areaIdSet = $service->getHotelAreaDefault($targetCd, null);
        }

        $hotelInfo = $service->getHotelInfo($targetCd);

        return view('ctl.brHotelArea.new', [
            'request_params' => $areaIdSet,
            'target_cd'     => $targetCd,
            'hotel_info'    => $hotelInfo,
        ]);
    }

    public function edit(Request $request, Service $service)
    {
        $targetCd = $request->input('target_cd');
        $hotelInfo = $service->getHotelInfo($targetCd);
        $entryNo = $request->input('entry_no');
        $AreaIdSet = [];

        // 登録情報の設定
        if ($request->missing('is_submit')) {
            // is_submit がなければ初期表示なので、登録されているデータを表示
            $a_temp_hotel_area_default = $service->getHotelAreaDefault($targetCd, $entryNo);

            $AreaIdSet['area_large']  = $a_temp_hotel_area_default['area_large'];
            $AreaIdSet['area_pref']   = $a_temp_hotel_area_default['area_pref'];
            $AreaIdSet['area_middle'] = $a_temp_hotel_area_default['area_middle'];
            $AreaIdSet['area_small']  = $a_temp_hotel_area_default['area_small'];
        } else {
            // is_submit があれば、入力を保持して表示
            $AreaIdSet['area_large']  = $request->input('area_large',   -1);
            $AreaIdSet['area_pref']   = $request->input('area_pref',    -1);
            $AreaIdSet['area_middle'] = $request->input('area_middle',  -1);
            $AreaIdSet['area_small']  = $request->input('area_small',   -1);
        }

        return view('ctl.brHotelArea.edit', [
            'request_params' => $AreaIdSet,
            'target_cd'     => $targetCd,
            'hotel_info'    => $hotelInfo,
        ]);
    }

    public function create(Request $request, Service $service)
    {
        $targetCd = $request->input('target_cd');
        $inputData = $request->only([
            'area_large',
            'area_pref',
            'area_middle',
            'area_small',
        ]);

        // データ整形・バリデーション・挿入
        $errorMessages = $service->create($targetCd, $inputData);
        if (count($errorMessages) > 0) {
            // 失敗
            return redirect()->route('ctl.br_hotel_area.new', [
                'target_cd' => $targetCd,
            ])->with([
                'errors' => $errorMessages,
                'input_data' => $inputData,
            ]);
        }

        // 成功
        return redirect()->route('ctl.br_hotel_area.complete', ['target_cd' => $targetCd]);
    }

    public function update(Request $request)
    {
        $targetCd = $request->input('target_cd');
        return redirect()->route('ctl.br_hotel_area.complete', ['target_cd' => $targetCd]);
    }

    public function delete(Request $request, Service $service)
    {
        $targetCd = $request->input('target_cd');
        $entryNo = $request->input('entry_no');

        // 失敗
        if (!$service->delete($targetCd, $entryNo)) {
            return redirect()->route('ctl.br_hotel_area.index', ['target_cd' => $targetCd])
                ->with(['errors' => ['削除に失敗しました']]);
        }

        // 成功
        return redirect()->route('ctl.br_hotel_area.complete', ['target_cd' => $targetCd]);
    }

    public function complete(Request $request)
    {
        return redirect()->route('ctl.br_hotel_area.index', [
            'target_cd' => $request->input('target_cd'),
        ])->with([
            'guides' => ['施設・地域情報を更新しました。'],
        ]);
    }

    public function json(Request $request, Service $service)
    {
        header('Content-type: application/json; charset=UTF-8');
        print json_encode($service->getMastAreas());
        exit;
    }
}
