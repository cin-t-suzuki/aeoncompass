@inject('service', 'App\Http\Controllers\ctl\BrRecordController')
{{-- titleの日時は$date_ymdをセットする --}}
@if (($type == 'f'))
@php $title = date('Y年度 月別', $date_ymd) @endphp
@elseif (($type == 'y') && !$service->is_empty($date_ymd))
@php $title = date('Y年 月別', $date_ymd) @endphp
@elseif (($type == 'w'))
@php $title = date('Y年m月 週別', $date_ymd) @endphp
@elseif (($type == 'd'))
@php $title = date('Y年m月 日別', $date_ymd) @endphp
@elseif (($type == 'y'))
@php $title = date('年別', $date_ymd) @endphp
@endif

{{-- footerはhtlになっているが、検証環境を確認する限りは_brでよさそう→baseを読み込む --}}
{{-- {include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title='予約・会員・アクセス数（全て '|cat:$title|cat:'）' no_print=true} --}}
@extends('ctl.common.base')
@section('title', '予約・会員・アクセス数（全て{{$title}}）')

@section('page_blade')

{{--削除でいいか？ {literal} --}}
<link rel="stylesheet" type="text/css" href="/styles/print.css"  media="print">
<style type="text/css">
/*削除でいいか？ <!-- */
.a {background-color:#ffffff;border:0px;color:#0000ff}
/*削除でいいか？ --> */

</style>
{{--削除でいいか？ {/literal} --}}

{{-- 年月 リンク --}}
{{-- 年の表示可能期間 --}}
{{-- ここでは現在時間をセットする --}}
@php 
$limit_after_y = 2000;
$date = date("Y-m-d", strtotime("+1 year"));
if (date('m', strtotime($date)) == '12'){
  $date = date("Y-m-d", strtotime("+1 year"));
}
$limit_before_y = date('Y', strtotime($date));
@endphp

{{-- 実行日セットの期間 --}}
{{-- ここから下ではdateは$date_ymdをセットする --}}
{{-- 実行年・実行年月等の作業変数設定 --}}
@php $date_m = date('m', $date_ymd) @endphp
@php $date_y = date('Y', $date_ymd) @endphp

{{-- 年の選択期間 デフォルトは、最終終了年から過去７年分表示する --}}
@php $years = 3 @endphp
@php $after_y = $date_y-$years @endphp
@php $before_y = $date_y+$years @endphp
{{-- 実行年が表示期間に含まれない場合--}}
{{-- 表示開始年より過去の場合 --}}
@if (($after_y < $limit_after_y))
  {{-- 実行年以降 --}}
  @php $after_y = $limit_after_y @endphp
  @php $before_y = $limit_after_y+$years+$years+1 @endphp

{{-- 表示終了年以上未来の場合 --}}
@elseif (($limit_before_y <= $before_y))
  {{-- 実行年以前 --}}
  @php $after_y = $limit_before_y-$years-$years-1 @endphp
  @php $before_y = $limit_before_y-1 @endphp
@endif
<div style="margin:1em 0;">
{{-- 年選択--}}
<table border="0" cellpadding="2" cellspacing="0">
  <tr>
    @for ($year = $after_y; $year < $before_y+1; $year++)
    <td>
      @if (($year == (int)date('Y', $date_ymd)) && !$service->is_empty($date_ymd))
        {{ $date_y }}年
      @else
        @if (($type == 'f'))
          @php $param = 'f/' @endphp
        @elseif (($type == 'y'))
          @php $param = '' @endphp
        @elseif (($type == 'w'))
          @php $param = $date_m . '/w/' @endphp
        @elseif (($type == 'd'))
          @php $param = $date_m . '/' @endphp
        @endif
        <a href="/ctl/brrecord/view/{{ strip_tags($year) }}/{{ $param }}">{{ strip_tags($year) }}年
      @endif
    </td>
    <td>|</td>
    @endfor
    <td>
      @if ($type == 'y' && $service->is_empty($date_ymd))年別@else <a href="/ctl/brrecord/view/y/">年別</a>@endif
    </td>
  </tr>
</table>
{{-- 月・年・年度メニュー --}}
<table border="0" cellpadding="2" cellspacing="0">
  <tr>
    @for ($month = 1; $month < 13; $month++)
    <td>
      @if (($month == (int)date('m', $date_ymd)) && (($type == 'w') || ($type == 'd')))
      {{ strip_tags($month) }}月
      @else
        {{-- string_format→sprintfでいいか？ --}}
        @php $date_m_tmp = sprintf('%02d', $month); @endphp
        @if (($type == 'w'))
          @php $param = $date_y . '/' . $date_m_tmp . '/w/' @endphp
        @else
          @php $param = $date_y . '/' . $date_m_tmp . '/' @endphp
        @endif
       <a href="/ctl/brrecord/view/{{ $param }}">{{ strip_tags($month) }}月</a>
      @endif
    </td>
    <td>|</td>
    @endfor
    <td>
      @if ($type == 'y' && !$service->is_empty($date_ymd))年@else <a href="/ctl/brrecord/view/{{ $date_y }}/">年</a>@endif
    </td>
    <td>|</td>
    <td>
      @if ($type == 'f')年度@else <a href="/ctl/brrecord/view/{{ $date_y }}/f/">年度</a>@endif
    </td>
  </tr>
</table>
{{-- 日・週 メニュー --}}
@if ((($type == 'f') || ($type == 'y')))
{{-- 表示しない --}}
@else
<table border="0" cellpadding="2" cellspacing="0">
  <tr>
    <td>
        @if ($type == 'd')日別@else <a href="/ctl/brrecord/view/{{ $date_y }}/{{ $date_m }}/">日別</a>@endif
      </td>
    <td>|</td>
      <td>
        @if ($type == 'w')週別@else <a href="/ctl/brrecord/view/{{ $date_y }}/{{ $date_m }}/w/">週別</a>@endif
    </td>
  </tr>
</table>
@endif

</div>
{{-- タイトル --}}
{{ $title }}
{{-- 一覧表示 --}}
@if (($type == 'f'))
@include('ctl.brRecord._list', ['date_format' => 'ym', 'date_color' => false])
@elseif (($type == 'y') && !$service->is_empty($date_ymd))
@include('ctl.brRecord._list', ['date_format' => 'ym', 'date_color' => false])
@elseif (($type == 'w'))
@include('ctl.brRecord._list', ['date_format' => 'ymd(w)', 'date_week' => '～', 'date_color' => false])
@elseif (($type == 'd'))
@include('ctl.brRecord._list', ['date_format' => 'ymd(w)', 'date_color' => true])
@elseif (($type == 'y'))
@include('ctl.brRecord._list', ['date_format' => 'y', 'date_color' => false])
@endif


{{-- {include file=$v->env.module_root|cat:'/views/_common/_htl_footer.tpl' no_print=true} --}}

@endsection