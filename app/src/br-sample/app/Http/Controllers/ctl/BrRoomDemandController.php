<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Common\Traits;
use App\Models\Datum;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BrRoomDemandController extends _commonController
{
    use Traits;

    // インデックス
    public function search(Request $request)
    {
        //オブジェクト取得
        $datumModel = new Datum();

        $a_room_demand = $datumModel->getRoomDemand(['partner_group_id' => $request->input('partner_group_id')]);

        // // エラーメッセージがあれば取得
        // $errors = $request->session()->get('errors', []);

        return view('ctl.brroomdemand.search', [
            'room_demand_list' => $a_room_demand['values'],

            // 'errors' => $errors
        ]);
    }

    //csvダウンロードアクション
    public function download(Request $request) //laravel形式で実装
    {

        // CSV側で日付が自動変換されてしまうが対処法はあるか？（データは2023/02(文字列)を渡している→Feb-23に変換されて表示される）

        $datumModel = new Datum();

        $a_room_demand = $datumModel->getRoomDemand(['partner_group_id' => $request->input('partner_group_id')]);

        $room_demand_list          = $a_room_demand['values'];

        $header = $datumModel->setCsvHeader($room_demand_list); //renderでbladeからではなく、モデルからの取得に変更
        $data = $datumModel->setCsvData($room_demand_list);
        $csvList = array_merge([$header], $data);

        $response = new StreamedResponse(function () use ($request, $csvList) {
            $stream = fopen('php://output', 'w');

            //　文字化け回避
            // ↓への書き換えで事足りる？print mb_convert_encoding($s_response, 'sjis-win', 'UTF-8');
            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');

            // CSVデータ
            foreach ($csvList as $key => $value) {
                fputcsv($stream, $value);
            }
            fclose($stream);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="部屋登録状況一覧.csv"');

        return $response;
        exit;
    }
}
