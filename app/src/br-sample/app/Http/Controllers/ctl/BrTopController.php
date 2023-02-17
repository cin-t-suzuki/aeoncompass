<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use App\Services\BrTopService as Service;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * MEMO: BrtopController にたくさんの機能が追加されているため、
 * Top 表示のためだけのクラスを新たに作成
 */
class BrTopController extends Controller
{
    /**
     * トップページ表示
     *
     * @param Request $request
     * @param Service $service
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Service $service)
    {
        $this_month = Carbon::now();
        $last_month = $this_month->copy()->subMonthsNoOverflow();
        $next_month = $this_month->copy()->addMonthNoOverflow();

        // 経理関係スケジュールの一覧を取得
        $schedules = [
            'last_month'    => $service->getSchedules($last_month->format('Y-m')),
            'this_month'    => $service->getSchedules($this_month->format('Y-m')),
            'next_month'    => $service->getSchedules($next_month->format('Y-m')),
        ];

        // 自身に許可されているライセンストークン取得
        // TODO: ユーザー情報から取得し移送
        $operator_cd = '11'; // 仮実装
        $licenses = $service->getApplicantLicense($operator_cd);

        return view("ctl.br.top.index", [
            'schedules'  => $schedules,
            'licenses'   => $licenses,

            // Carbon インスタンス
            'last_month' => $last_month,
            'this_month' => $this_month,
            'next_month' => $next_month,

            'guides' => $request->session()->pull('guides', []),
        ]);
    }
}
