@extends('ctl.common.base')
@section('title', '送客実績・料金変更')
@inject('service', 'App\Http\Controllers\ctl\BrReserveCkController')

@section('page_blade')

@include ('ctl.brReserveCk._form')

{{-- メッセージbladeの読込 --}}
@include('ctl.common.message')

<table border="1" cellpadding="4" cellspacing="0">
  <tr>
    <td  bgcolor="#EEFFEE"  nowrap align="center">施設名</td>
    <td  bgcolor="#EEFFEE"  nowrap align="center">送客実績<br><small>（別Window）</small></td>
    <td  bgcolor="#EEFFEE"  nowrap align="center">チェック状態</td>
  </tr>
  @foreach ($hotel_lists['values'] as $key => $reserve_data)
  <tr>
    {!! Form::open(['route' => ['ctl.brReserveCk.reserveck'], 'method' => 'post', 'target' => 'hotel']) !!}

      @if ($date_ymd['after'] == '2009-06-30')
        <INPUT TYPE="hidden" NAME="date_ymd[after]" VALUE="2009-07-01">
      @else
        <input type="hidden" name="date_ymd[after]" value="{{strip_tags($date_ymd['after'])}}">
      @endif
      <input type="hidden" name="date_ymd[before]" value="{{strip_tags($date_ymd['before'])}}">
      <input type="hidden" name="target_cd" value="{{strip_tags($reserve_data->hotel_cd)}}">
      <td nowrap>
        <small>{{strip_tags($reserve_data->hotel_cd)}}</small><br>
        {{strip_tags($reserve_data->hotel_nm)}} @if ($reserve_data->stock_type == 1) <font color="#0000ff">[買]</font>@endif
      </td>
      <td nowrap>
        <INPUT TYPE="submit" VALUE="実績表示">
      </td>
    {!! Form::close() !!}
    {!! Form::open(['route' => ['ctl.brReserveCk.update'], 'method' => 'post']) !!}
      <td nowrap>
       @if ($send_customers_ymd <= now() && now() <= $dead_line_ymd)
         @if ($reserve_data->fix_status == 1)
           チェック完了
         @else
            <INPUT type="radio" name="checksheet_fix[fix_status]" value="0" id="fix_status_0_{{$key}}" checked /><label for="fix_status_0_{{$key}}">チェック中</label>
            <INPUT type="radio" name="checksheet_fix[fix_status]" value="1" id="fix_status_1_{{$key}}" /><label for="fix_status_1_{{$key}}">チェック完了</label>
            <INPUT TYPE="submit" VALUE="更新">
            <INPUT TYPE="hidden" NAME="checksheet_fix[hotel_cd]" VALUE="{{strip_tags($reserve_data->hotel_cd)}}">
            <INPUT TYPE="hidden" NAME="checksheet_fix[checksheet_ym]" VALUE="{{strip_tags($checksheet_ym)}}">
            <input type="hidden" name="Search[keywords]" value="{{strip_tags($search['keywords'])}}">
            <input type="hidden" name="Search[year]" value="{{strip_tags($search['year'])}}">
            <input type="hidden" name="Search[month]" value="{{strip_tags($search['month'])}}">
         @endif
        @else
          @if ($reserve_data->fix_status == 1)
           チェック完了
          @else
            チェック中
          @endif
        @endif
      </td>
    {!! Form::close() !!}
  </tr>
  @endforeach
</table>
<br>
@endsection