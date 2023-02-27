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

        // 注目文言取得
        $topAttention = $service->getAttention();

        return view('rsv.top.index', [
            'keywords' => $keywords,
            'search_condition' => $searchCondition,
            'top_attention' => $topAttention,

            // MEMO: 未定義変数でエラーになるため、影響の出ないであろう値をセット
            'piece' => [
                'hotels' => [
                    [
                        'hotel_cd' => null,
                    ],
                ],
            ],
            'senior' => null,
            'area_id' => null,
            'place_cd' => null,
            'pre_uri' => null, // MEMO: 移植元で、値がセットされている箇所が見られない
            'params' => [
                'child1' => $request->input('child1', 0),
                'child2' => $request->input('child2', 0),
                'child3' => $request->input('child3', 0),
                'child4' => $request->input('child4', 0),
                'child5' => $request->input('child5', 0),
            ],
        ]);
    }
}
