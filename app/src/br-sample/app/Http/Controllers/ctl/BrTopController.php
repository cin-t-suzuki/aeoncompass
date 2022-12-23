<?php

namespace App\Http\Controllers\ctl;

use App\Common\DateUtil;
use App\Http\Controllers\Controller;
use App\Models\ModelsLicense;
use App\Models\ModelsSchedule;
use Illuminate\Http\Request;

class BrTopController extends Controller
{
    public function index(Request $request)
    {
        // MEMO: BrtopController が役割過多になっているため、新たに作成

        $a_schedules = [];
        $a_license_tokens = [];

        // ライセンス、 スケジュール モデルの取得
        $modelsSchedule = new ModelsSchedule();
        $modelsLicense  = new ModelsLicense();

        // 経理関係スケジュールの一覧を取得
        $o_date = new DateUtil();
        //当月
        // $a_schedules['this_month'] = $modelsSchedule->get_schedules(array('date_ym' => $o_date->to_format('Y-m')));
        //前月
        $o_date->add('m', -1);
        // $a_schedules['pre_month'] = $modelsSchedule->get_schedules(array('date_ym' => $o_date->to_format('Y-m')));
        //翌月
        $o_date->add('m', 2);
        // $a_schedules['next_month'] = $modelsSchedule->get_schedules(array('date_ym' => $o_date->to_format('Y-m')));

        // TODO:
        $a_schedules = [
            'pre_month' => [],
            'this_month' => [],
            'next_month' => [],
        ];
        // 自身に許可されているライセンストークン取得
        //TODO ユーザー情報から取得し移送
        $operator_cd = '1'; // magic number
        $a_license_tokens = $modelsLicense->get_applicant_license($operator_cd);

        return view("ctl.br.top.index", [
            'Schedules'  => $a_schedules,
            'licenses'   => $a_license_tokens,
        ]);
    }
}
