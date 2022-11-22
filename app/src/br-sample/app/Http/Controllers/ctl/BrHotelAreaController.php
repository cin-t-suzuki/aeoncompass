<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use App\Services\BrHotelAreaService as Service;
use Illuminate\Http\Request;

class BrHotelAreaController extends Controller
{
    /**
     * 表示
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\BrHotelAreaService $service
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Service $service)
    {
        $targetCd   = $request->input('target_cd');
        $targetNo   = $request->session()->pull('target_no');
        $hotelInfo  = $service->getHotelInfo($targetCd);
        $hotelAreas = $service->getHotelAreas($targetCd);

        return view('ctl.brHotelArea.index', [
            'target_cd'     => $targetCd,
            'target_no'     => $targetNo,
            'hotel_info'    => $hotelInfo,
            'hotel_areas'   => $hotelAreas,
            'guides'        => $request->session()->pull('guides', []),
        ]);
    }

    /**
     * 新規追加入力
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\BrHotelAreaService $service
     * @return \Illuminate\Http\Response
     */
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

    /**
     * 新規追加処理
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\BrHotelAreaService $service
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Service $service)
    {
        $targetCd = $request->input('target_cd');
        $inputData = $request->only([
            'area_large',
            'area_pref',
            'area_middle',
            'area_small',
        ]);
        $entryNo = $service->issueEntryNo($targetCd);

        // データ整形・バリデーション・挿入
        $errorMessages = $service->create($targetCd, $entryNo, $inputData);
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
        return redirect()->route('ctl.br_hotel_area.complete', [
            'target_cd' => $targetCd
        ])->with([
            'target_no' => $entryNo,
        ]);
    }

    /**
     * 編集
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\BrHotelAreaService $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Service $service)
    {
        $targetCd = $request->input('target_cd');
        $entryNo = $request->input('entry_no');
        $areaIdSet = [];

        // 登録情報の設定
        if ($request->session()->has('input_data')) {
            // input_data があれば、入力を保持して表示
            $areaIdSet = $request->session()->pull('input_data');
        } else {
            // input_data がなければ初期表示なので、登録されているデータを表示
            $areaIdSet = $service->getHotelAreaDefault($targetCd, $entryNo);
        }

        $hotelInfo = $service->getHotelInfo($targetCd);
        return view('ctl.brHotelArea.edit', [
            'request_params' => $areaIdSet,
            'target_cd'     => $targetCd,
            'entry_no'      => $entryNo,
            'hotel_info'    => $hotelInfo,
        ]);
    }

    /**
     * 更新
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\BrHotelAreaService $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $targetCd   = $request->input('target_cd');
        $entryNo    = $request->input('entry_no');
        $inputData  = $request->only([
            'area_large',
            'area_pref',
            'area_middle',
            'area_small',
        ]);

        $errorMessages = $service->update($targetCd, $entryNo, $inputData);
        if (count($errorMessages) > 0) {
            // 失敗
            return redirect()->route('ctl.br_hotel_area.edit', [
                'target_cd' => $targetCd,
                'entry_no' => $entryNo,
            ])->with([
                'errors' => $errorMessages,
                'input_data' => $inputData,
            ]);
        }

        // 成功
        return redirect()->route('ctl.br_hotel_area.complete', [
            'target_cd' => $targetCd,
        ])->with([
            'target_no' => $entryNo,
        ]);
    }

    /**
     * 削除
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\BrHotelAreaService $service
     * @return \Illuminate\Http\Response
     */
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

    /**
     * 完了
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\BrHotelAreaService $service
     * @return \Illuminate\Http\Response
     */
    public function complete(Request $request)
    {
        return redirect()->route('ctl.br_hotel_area.index', [
            'target_cd' => $request->input('target_cd'),
        ])->with([
            'target_no' => $request->session()->pull('target_no'),
            'guides' => ['施設・地域情報を更新しました。'],
        ]);
    }

    /**
     * JSON取得
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\BrHotelAreaService $service
     */
    public function json(Request $request, Service $service)
    {
        header('Content-type: application/json; charset=UTF-8');
        print json_encode($service->getMastAreas());
        exit;
    }
}
