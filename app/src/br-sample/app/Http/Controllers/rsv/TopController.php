<?php

namespace App\Http\Controllers\rsv;

use App\Http\Controllers\Controller;
use App\Services\Rsv\TopService as Service;
use Illuminate\Http\Request;

class TopController extends Controller
{
    public function index(Request $request, Service $service)
    {
        // キーワード検索のおすすめキーワード
        $keywords = $service->getKeywords($request->input('partner_cd'));

        // 空室検索の選択肢
        $searchCondition = $service->getSearchCondition($request);

        return view('rsv.top.index', [
            'keywords' => $keywords,
            'search_condition' => $searchCondition,

            // TODO: ビューに渡す変数
            'senior' => 30,
            'area_id' => 10,
            'place_cd' => 10,
            'pre_uri' => '',
            'piece' => [
                'hotels' => [
                    [
                        'hotel_cd' => null,
                    ],
                ],
            ],
            'bgcolor' => '',
            'params' => [
                'child1' => 1,
                'child2' => 1,
                'child3' => 1,
                'child4' => 1,
                'child5' => 1,
            ],
            'top_attention' => (object)[],
        ]);
    }
}
