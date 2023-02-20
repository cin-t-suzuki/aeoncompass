<?php

namespace App\Http\Controllers\rsv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TopController extends Controller
{
    public function index(Request $request)
    {
        // TODO:
        $keywords = [
            [
                'word' => 'サンプルキーワード',
            ],
        ];
        return view('rsv.top.index', [
            'keywords' => $keywords,

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
            'search_condition' => [
                'form' => [
                    'stay' => [],
                    'areas' => [
                        [
                            'place' => 'place',
                            'current_status' => false,
                            'place_nm' => '地名',
                        ],
                        [
                            'place' => 'place',
                            'current_status' => false,
                            'place_nm' => '地名',
                        ],
                        [
                            'place' => 'place',
                            'current_status' => false,
                            'place_nm' => '地名',
                        ],
                    ],
                    'cws' => [
                        [
                            'place' => 'place',
                            'current_status' => false,
                            'place_nm' => '地名',
                        ],
                        [
                            'place' => 'place',
                            'current_status' => false,
                            'place_nm' => '地名',
                        ],
                        [
                            'place' => 'place',
                            'current_status' => false,
                            'place_nm' => '地名',
                        ],
                    ],
                    'prefs' => [
                        [
                            'place' => 'place',
                            'current_status' => false,
                            'place_nm' => '地名',
                        ],
                        [
                            'place' => 'place',
                            'current_status' => false,
                            'place_nm' => '地名',
                        ],
                        [
                            'place' => 'place',
                            'current_status' => false,
                            'place_nm' => '地名',
                        ],
                    ],
                    'hotel' => [
                        'room_id' => null,
                        'hotel_cd' => null,
                        'title' => null,
                        'plan_id' => null,
                    ],
                    'type' => null,
                    'goto' => 0,
                    'charges' => [
                        'min' => [],
                        'max' => [],
                    ],
                    'senior' => [
                        'capacities' => [],
                    ],
                    'childs' => [
                        'accept_status' => true,
                        'child1_capacities' => [
                            ['capacity' => 1, 'current_status' => false,],
                            ['capacity' => 2, 'current_status' => false,],
                            ['capacity' => 3, 'current_status' => false,],
                        ],
                        'child2_capacities' => [
                            ['capacity' => 1, 'current_status' => false,],
                            ['capacity' => 2, 'current_status' => false,],
                            ['capacity' => 3, 'current_status' => false,],
                        ],
                        'child3_capacities' => [
                            ['capacity' => 1, 'current_status' => false,],
                            ['capacity' => 2, 'current_status' => false,],
                            ['capacity' => 3, 'current_status' => false,],
                        ],
                        'child4_capacities' => [
                            ['capacity' => 1, 'current_status' => false,],
                            ['capacity' => 2, 'current_status' => false,],
                            ['capacity' => 3, 'current_status' => false,],
                        ],
                        'child5_capacities' => [
                            ['capacity' => 1, 'current_status' => false,],
                            ['capacity' => 2, 'current_status' => false,],
                            ['capacity' => 3, 'current_status' => false,],
                        ],
                    ],
                    'date_status' => '',
                    'midnight' => [
                        'current_status' => false,
                        'date_ymd' => strtotime(date('Y-m-d')),
                    ],
                    'rooms' => [
                        [
                            'room_count' => 100,
                            'current_status' => true,
                        ],
                    ],
                    'year_month' => [
                        [
                            'date_ym' => '2023-06',
                            'current_status' => true,
                        ],
                    ],
                    'days' => [
                        [
                            'date_ymd' => '2023-06-12',
                            'current_status' => true,
                        ],
                    ],
                    'stay' => [
                        [
                            'days' => 'days value',
                            'current_status' => true,
                        ],
                    ],
                ],
            ],
        ]);
    }
}
