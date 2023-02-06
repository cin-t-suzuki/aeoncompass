{{--print_flgとは？ {include file=$v->env['module_root']|cat:'/views/_common/_br_header.tpl' title="部屋登録状況一覧" print_flg = true} --}}
@extends('ctl.common.base')
@section('title', '部屋登録状況一覧')
@inject('service', 'App\Http\Controllers\ctl\BrRoomDemandController')

@section('page_blade')

     <table border="0" cellpadding="4" cellspacing="0">
       <tr>
         <td>
          {{-- $partner_group_listは渡されていない 一旦??[]追記でいいか？ --}}
          @foreach ($partner_group_list ?? [] as $value)
            <a href="/ctl/brpartnertotal/search/partner_group_id/{{ $value['partner_group_id'] }}">{{ $value['partner_group_nm'] }}</a>
            @if (!$smarty['foreach']['partner_group_list']['last'])
              |
            @endif
          @endforeach
         </td>
       </tr>
     </table>
     @if (count($room_demand_list) > 0)
       <table border="1" cellpadding="4" cellspacing="0">
         <tr>
           <td nowrap bgcolor="#cccccc">施設コード</td>
           <td nowrap bgcolor="#cccccc">施設名</td>
           <td nowrap bgcolor="#cccccc">公開日</td>
           <td nowrap bgcolor="#cccccc">都道府県</td>
           <td nowrap bgcolor="#cccccc">市</td>
           <td nowrap bgcolor="#cccccc">エリア</td>
           <td nowrap bgcolor="#cccccc">管理方法</td>
           <td nowrap bgcolor="#cccccc">部署・役職</td>
           <td nowrap bgcolor="#cccccc">担当者</td>
           <td nowrap bgcolor="#cccccc">担当者電話番号</td>
           <td nowrap bgcolor="#cccccc">担当者ファックス番号</td>
           <td nowrap bgcolor="#cccccc">カテゴリ</td>
           <td nowrap bgcolor="#cccccc">管理画面</td>
           <td nowrap bgcolor="#cccccc">BRサイト料率</td>
           <td nowrap bgcolor="#cccccc">他サイト料率</td>
           <td nowrap bgcolor="#cccccc">JRC契約審査</td>
           <td nowrap bgcolor="#cccccc">JETStar契約</td>
           <td nowrap bgcolor="#cccccc">MSDの可否</td>
           <td nowrap bgcolor="#cccccc">自動延長</td>
           <td nowrap bgcolor="#cccccc">自動延長タイミング</td>
           <td nowrap bgcolor="#cccccc">自動延長可能部屋数</td>
           <td nowrap bgcolor="#cccccc">自動延長可能プラン数</td>
           <td nowrap bgcolor="#cccccc">自動延長不可部屋数</td>
           <td nowrap bgcolor="#cccccc">自動延長不可プラン数</td>
           <td nowrap bgcolor="#cccccc">受付状態</td>
           <td nowrap bgcolor="#cccccc">受付変更日時</td>
           <td nowrap bgcolor="#cccccc">受付夜間解除</td>
           {{--書き換えあっているか？(2行目以降が全く同じ×12行あった→＋月数に変えた)
           <td nowrap bgcolor="#cccccc">@if ($v->helper->date->set())@endif {{( $v->helper->date->to_format('Y/m'))}} </td>
           <td nowrap bgcolor="#cccccc">@if ($v->helper->date->add('m', 1))@endif {{( $v->helper->date->to_format('Y/m'))}} </td>
            --}}
           <td nowrap bgcolor="#cccccc">{{ date('Y/m') }}</td>
           <td nowrap bgcolor="#cccccc">{{ date('Y/m', strtotime('+1 month')) }}</td>
           <td nowrap bgcolor="#cccccc">{{ date('Y/m', strtotime('+2 month')) }}</td>
           <td nowrap bgcolor="#cccccc">{{ date('Y/m', strtotime('+3 month')) }}</td>
           <td nowrap bgcolor="#cccccc">{{ date('Y/m', strtotime('+4 month')) }}</td>
           <td nowrap bgcolor="#cccccc">{{ date('Y/m', strtotime('+5 month')) }}</td>
           <td nowrap bgcolor="#cccccc">{{ date('Y/m', strtotime('+6 month')) }}</td>
           <td nowrap bgcolor="#cccccc">{{ date('Y/m', strtotime('+7 month')) }}</td>
           <td nowrap bgcolor="#cccccc">{{ date('Y/m', strtotime('+8 month')) }}</td>
           <td nowrap bgcolor="#cccccc">{{ date('Y/m', strtotime('+9 month')) }}</td>
           <td nowrap bgcolor="#cccccc">{{ date('Y/m', strtotime('+10 month')) }}</td>
           <td nowrap bgcolor="#cccccc">{{ date('Y/m', strtotime('+11 month')) }}</td>
           <td nowrap bgcolor="#cccccc">{{ date('Y/m', strtotime('+12 month')) }}</td>
           <td nowrap bgcolor="#cccccc">請求連番</td>
           <td nowrap bgcolor="#cccccc">精算先名称</td>
         </tr>
         @foreach ($room_demand_list as $value)
           <tr>
             <td nowrap bgcolor="#ffffff">{{ $value->hotel_cd }}</td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->hotel_nm))<br />@else {{ $value->hotel_nm }}@endif </td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->open_ymd))<br />@else {{ $value->open_ymd }}@endif </td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->pref_nm))<br />@else {{ $value->pref_nm }}@endif </td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->city_nm))<br />@else {{ $value->city_nm }}@endif </td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->area_nm))設定なし@else {{ $value->area_nm }}@endif </td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->management_status))<br />@elseif ($value->management_status == '1')ファックス管理 @elseif ($value->management_status == '2')インターネット管理 @elseif ($value->management_status == '3') ファックス管理＋インターネット管理@endif </td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->person_post))<br />@else {{ $value->person_post }}@endif </td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->person_nm))<br />@else {{ $value->person_nm }}@endif </td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->person_tel))<br />@else {{ $value->person_tel }}@endif </td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->person_fax))<br />@else {{ $value->person_fax }}@endif </td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->hotel_category))<br />@else {{ $value->hotel_category }}@endif </td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->migration_status))<br />@else {{ $value->migration_status }}@endif </td>
             <td nowrap bgcolor="#ffffff" style="text-align:right">@if ($service->is_empty($value->system_rate))<br />@else {{ $value->system_rate }}%@endif </td>
             <td nowrap bgcolor="#ffffff" style="text-align:right">@if ($service->is_empty($value->system_rate_out))<br />@else {{ $value->system_rate_out }}%@endif </td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->judge_status))<br />@else {{ $value->judge_status }}@endif </td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->jetstar_status))<br />@else {{ $value->jetstar_status }}@endif </td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->msd_status))<br />@else {{ $value->msd_status }}@endif </td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->extend_status))<br />@else {{ $value->extend_status }}@endif </td>
             <td nowrap bgcolor="#ffffff" style="text-align: right;">@if ($service->is_empty($value->after_months))<br />@else {{ $value->after_months }}@endif </td>
             <td nowrap bgcolor="#ffffff" style="text-align: right;">@if ($service->is_empty($value->valid_room_on_cnt))<br />@else {{ $value->valid_room_on_cnt }}@endif </td>
             <td nowrap bgcolor="#ffffff" style="text-align: right;">@if ($service->is_empty($value->valid_charge_on_cnt))<br />@else {{ $value->valid_charge_on_cnt }}@endif </td>
             <td nowrap bgcolor="#ffffff" style="text-align: right;">@if ($service->is_empty($value->invalid_room_off_cnt))<br />@else {{ $value->invalid_room_off_cnt }}@endif </td>
             <td nowrap bgcolor="#ffffff" style="text-align: right;">@if ($service->is_empty($value->invalid_charge_off_cnt))<br />@else {{ $value->invalid_charge_off_cnt }}@endif </td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->accept_status))<br />@else {{ $value->accept_status }}@endif </td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->accept_dtm))<br />@else {{ $value->accept_dtm }}@endif </td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->accept_auto))<br />@else {{ $value->accept_auto }}@endif </td>
             <td nowrap bgcolor="#ffffff">{{ $value->month_01 }}</td>
             <td nowrap bgcolor="#ffffff">{{ $value->month_02 }}</td>
             <td nowrap bgcolor="#ffffff">{{ $value->month_03 }}</td>
             <td nowrap bgcolor="#ffffff">{{ $value->month_04 }}</td>
             <td nowrap bgcolor="#ffffff">{{ $value->month_05 }}</td>
             <td nowrap bgcolor="#ffffff">{{ $value->month_06 }}</td>
             <td nowrap bgcolor="#ffffff">{{ $value->month_07 }}</td>
             <td nowrap bgcolor="#ffffff">{{ $value->month_08 }}</td>
             <td nowrap bgcolor="#ffffff">{{ $value->month_09 }}</td>
             <td nowrap bgcolor="#ffffff">{{ $value->month_10 }}</td>
             <td nowrap bgcolor="#ffffff">{{ $value->month_11 }}</td>
             <td nowrap bgcolor="#ffffff">{{ $value->month_12 }}</td>
             <td nowrap bgcolor="#ffffff">{{ $value->month_13 }}</td>
             <td nowrap bgcolor="#ffffff" style="text-align: right;">@if ($service->is_empty($value->customer_id))<br />@else {{ $value->customer_id }}@endif </td>
             <td nowrap bgcolor="#ffffff">@if ($service->is_empty($value->customer_nm))<br />@else {{ $value->customer_nm }}@endif </td>
           </tr>
         @endforeach
       @endif
     </table>
@endsection