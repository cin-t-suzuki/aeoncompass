@extends('ctl.common.base')
@section('title', '精算先')
@inject('service', 'App\Http\Controllers\ctl\BrCustomerController')

@section('page_blade')
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message')
<table border="1" cellspacing="0" cellpadding="2">
  {!! Form::open(['route' => ['ctl.brCustomer.list'], 'method' => 'get']) !!}
    <tr>
      <td  bgcolor="#eeffee" nowrap>精算先検索</td>
      <td>
        {{-- ↓null追記でいいか --}}
        <input type="text" name="keywords" SIZE="20" MAXLENGTH="20" value="{{strip_tags($keywords ?? null)}}">
        <input type="submit" value="検索">
      </td>
    </tr>
	{!! Form::close() !!}
</table>
<br />
{{-- ↓null,[]追記でいいか --}}
@if (!$service->is_empty($keywords ?? null))
  「{{strip_tags($keywords)}}」から{{$cnt}}件
  @if (count($customer_list['values'] ?? []) == 0)
    <font color="#ff0000">請求先未登録です。<br></font>
  @endif
@endif
{{-- ↓[]追記でいいか --}}
@if (count($customer_list['values'] ?? []) == 0)
@else
  <table border="1" cellpadding="1" cellspacing="0">
    <tr>
      <td bgcolor="#eeffee" nowrap="nowrap">請求連番</td>
      <td bgcolor="#eeffee" nowrap="nowrap">精算先名称</td>
      <td bgcolor="#eeffee" nowrap="nowrap">請求書宛名</td>
      <td bgcolor="#eeffee" nowrap="nowrap">担当者</td>
      <td bgcolor="#eeffee" nowrap="nowrap">引落顧客番号</td>
      <td bgcolor="#eeffee" nowrap="nowrap">引落口座名義</td>
      <td bgcolor="#eeffee" nowrap="nowrap">登録日</td>
      <td bgcolor="#eeffee" nowrap="nowrap">&nbsp;</td>
      <td bgcolor="#eeffee" nowrap="nowrap">&nbsp;</td>
    </tr>
    {{--書き替えあっている？customer_listだと変数名かぶるためエラー {section start = 0 loop = $views->customer_list.values|@count max = $views->limit name = customer_list} --}}
    @for ($list = 0; $list < count($customer_list['values']) && $list <= $limit ; $list++)
      <tr>
        <td>
          {{-- $customer_listはで設定（以下同様）問題ないか？ --}}
          {{strip_tags($customer_list['values'][$list]->customer_id)}}<br />
        </td>
        <td>
          {{strip_tags($customer_list['values'][$list]->customer_nm)}}<br />
        </td>
        <td>
          {{strip_tags($customer_list['values'][$list]->section_nm)}}<br />
        </td>
        <td>
         {{strip_tags($customer_list['values'][$list]->person_nm)}}<br />
        </td>
        <td>
         {{strip_tags($customer_list['values'][$list]->factoring_cd)}}<br />
        </td>
        <td>
         {{strip_tags($customer_list['values'][$list]->factoring_bank_account_no)}}<br />
        </td>
        <td>
          <small>
            @include('ctl.common._date',["timestamp" => strip_tags($customer_list['values'][$list]->entry_ts), "format" =>"ymd" ] )
          </small><br />
        </td>
        <td>
          {!! Form::open(['route' => ['ctl.brCustomer.edit'], 'method' => 'get']) !!}
            <input type="hidden" name="customer_id" value="{{strip_tags($customer_list['values'][$list]->customer_id)}}">
            <input type="hidden" name="keywords" value="{{strip_tags($keywords)}}">
            <input value="請求先変更" type="submit"><br />
          {!! Form::close() !!}
        </td>
        <td>
          {!! Form::open(['route' => ['ctl.brCustomerHotel.hotellist'], 'method' => 'get']) !!}
            <input type="hidden" name="customer_id" value="{{strip_tags($customer_list['values'][$list]->customer_id)}}">
            <input type="hidden" name="keywords" value="{{strip_tags($keywords)}}">
            <input value="登録施設" type="submit"><br />
          {!! Form::close() !!}
        </td>
        </form>
      </tr>
    @endfor
  </table>
{{$cnt}}
/
{{count($customer_list['values'])}}
@endif
<br>
<hr size="1" />
{!! Form::open(['route' => ['ctl.brCustomer.create'], 'method' => 'post']) !!}
精算先　新規登録
@include ('ctl.brCustomer._input_customer')
  <input type="submit" value="登　　　　録">
  <input type="hidden" name="keywords" value="{{strip_tags($keywords)}}">
{!! Form::close() !!}

@endsection