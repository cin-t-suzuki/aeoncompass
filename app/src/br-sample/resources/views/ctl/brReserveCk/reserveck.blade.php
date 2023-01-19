@extends('ctl.common.base')
@section('title', '送客実績・料金変更')
@inject('service', 'App\Http\Controllers\ctl\BrReserveCkController')

@section('page_blade')

<!-- Hotel Information -->
<p>
{{-- TODO ↓$v->user->hotelの実装が済まないと表示ができないため一旦非表示中 --}}
{{-- @include ('ctl.common._br_hotel_info') --}}
</p>
<!-- Hotel Information -->

@if ($service->is_empty($search_reserve_cd))
      <p><big>対象年月：@include('ctl.common._date',["timestamp" => $date_ymd['before'], "format" =>"ym" ] )</big></p>
@endif

@if (!$service->is_empty($reserve_data['values']))
  @if (!$service->is_empty($search_reserve_cd))
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
      <td valign="top" nowrap>
        <p><big>[予約参照コード：{{strip_tags($search_reserve_cd)}}]</big></p>
      </td>
      <td valign="top" nowrap align="right">
        <br /> 
      </td>
    </tr>
  </table>
  @endif

  <table border="1" cellspacing="0" cellpadding="1">
    <tr>
      <td  bgcolor="#EEFFEE"  nowrap align="center"><small>行</small></td>
      <td  bgcolor="#EEFFEE"  colspan="2" nowrap>詳細情報</td>
      <td  bgcolor="#EEFFEE"  nowrap>予約コード<br>参照コード<br><small>　(予約受付日)</small></td>
      <td  bgcolor="#EEFFEE"  nowrap>宿泊日</td>
      <td  bgcolor="#EEFFEE"  nowrap>部屋<br>プラン内容</td>
      <td  bgcolor="#EEFFEE"  nowrap>宿泊代表者</td>
      <td  bgcolor="#EEFFEE"  nowrap><small>予約状態</SMALL></td>
      <td  bgcolor="#EEFFEE"  nowrap><small>登録割引料金<br />税別サ込</small></td>
      <td  bgcolor="#EEFFEE"  nowrap><small>システム<br>利用料</small></td>
      <td  bgcolor="#EEFFEE"  nowrap><small>ポイント<br>負担料</small></td>
      <td  bgcolor="#EEFFEE"  nowrap><small>@if ($is_power == true)変更@else 料金変更<br>TELキャンセル<br>無断不泊(NOSHOW)@endif</small></td>
    </tr>
    @foreach ($reserve_data['values'] as $reserve_data)
      @php
        $total_page = $reserve_data->total_page;
        $total_count = $reserve_data->total_count;
      @endphp
      {{-- searchList start --}}
        @include ('ctl.brReserveCk._list')
      {{-- searchList end --}}
    @endforeach
  </table>
  <style type="text/css">
    /* 以下はコメントアウトでいいのか？削除？ */
    /* <!-- */ 
      .a {background-color:#ffffff;border:0px;color:#0000ff;text-decoration: underline; cursor: pointer;font-size:100%}
    /* --> */
  </style>
  <br>

{{-- _paging.tpl start --}}
  @include ('ctl.common._pager')
{{-- _paging.tpl end --}}


  {{-- 改ページ処理--}}
<div style="margin:1em 0em">注）システム利用料とポイント負担料は算出方法が異なります。</div>
  <br><hr size="0" color="#cccccc">
  <table border="0" cellpadding="2" cellspacing="0"><tr>
    {{-- <form action="{$v->env.source_path}{$v->env.module}/dl/brreserveck.csv" method="post"> --}}
    {!! Form::open(['route' => ['ctl.dl.reserveck'], 'method' => 'get']) !!}
      <td>
        <input type="hidden" name="target_cd" value="{{strip_tags($conditions['hotel_cd'])}}">
        <input type="hidden" name="reserve_cd" value="{{strip_tags($conditions['reserve_cd'])}}">
        <input type="hidden" name="date_ymd[after]" value="{{strip_tags($date_ymd['after'])}}">
        <input type="hidden" name="date_ymd[before]" value="{{strip_tags($date_ymd['before'])}}">
        <input type="submit" name="" value="ＣＳＶデータダウンロード"> ＣＳＶダウンロードは予約が成立している予約のみを表示しております。
      </td>
    {!! Form::close() !!}
  </tr></table> 
@else
  {{-- 検索結果が存在しない場合 --}}
  <div style="border-style:solid;border-color:#f00;border-width:1px;padding:6px;background-color:#fee;">
    該当する予約情報は見つかりませんでした。
  </div>
  <br>
  <br>
  ※ 過去の予約の変更は「送客実績・料金変更」から行えます。
@endif
<br>
@endsection
