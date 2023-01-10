@extends('ctl.common.base')
@section('title', '精算先')
@inject('service', 'App\Http\Controllers\ctl\BrCustomerController')

@section('page_blade')
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message', $messages)
<table border="1" cellspacing="0" cellpadding="2">
  {!! Form::open(['route' => ['ctl.brCustomer.list'], 'method' => 'post']) !!}
    <tr>
      <td  bgcolor="#eeffee" nowrap>精算先検索</td>
      <td>
        {{-- ↓null追記でいいか --}}
        <input type="text" name="keywords" SIZE="20" MAXLENGTH="20" value="{{strip_tags($views->keywords ?? null)}}">
        <input type="submit" value="検索">
      </td>
    </tr>
	{!! Form::close() !!}
</table>
<br />
{{-- ↓null,[]追記でいいか --}}
@if (!$service->is_empty($views->keywords ?? null))
  「{{strip_tags($views->keywords)}}」から{{$views->cnt}}件
  @if (count($views->customer_list['values'] ?? []) == 0)
    <font color="#ff0000">請求先未登録です。<br></font>
  @endif
@endif
{{-- ↓[]追記でいいか --}}
@if (count($views->customer_list['values'] ?? []) == 0)
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
    {{--書き替えあっている？ {section start = 0 loop = $views->customer_list.values|@count max = $views->limit name = customer_list} --}}
    @for ($customer_list = 0; $customer_list < count($views->customer_list['values']) && $customer_list <= $views->limit ; $customer_list++)
      <tr>
        <td>
          {{-- $customer_listはで設定（以下同様）問題ないか？ --}}
          {{strip_tags($views->customer_list['values'][$customer_list]->customer_id)}}<br />
        </td>
        <td>
          {{strip_tags($views->customer_list['values'][$customer_list]->customer_nm)}}<br />
        </td>
        <td>
          {{strip_tags($views->customer_list['values'][$customer_list]->section_nm)}}<br />
        </td>
        <td>
         {{strip_tags($views->customer_list['values'][$customer_list]->person_nm)}}<br />
        </td>
        <td>
         {{strip_tags($views->customer_list['values'][$customer_list]->factoring_cd)}}<br />
        </td>
        <td>
         {{strip_tags($views->customer_list['values'][$customer_list]->factoring_bank_account_no)}}<br />
        </td>
        <td>
          <small>
            @include('ctl.common._date',["timestamp" => strip_tags($views->customer_list['values'][$customer_list]->entry_ts), "format" =>"ymd" ] )
          </small><br />
        </td>
        <td>
          {!! Form::open(['route' => ['ctl.brCustomer.edit'], 'method' => 'post']) !!}
            <input type="hidden" name="customer_id" value="{{strip_tags($views->customer_list['values'][$customer_list]->customer_id)}}">
            <input type="hidden" name="keywords" value="{{strip_tags($views->keywords)}}">
            <input value="請求先変更" type="submit"><br />
          {!! Form::close() !!}
        </td>
        <td>
          {!! Form::open(['route' => ['ctl.brCustomerHotel.hotellist'], 'method' => 'post']) !!}
            <input type="hidden" name="customer_id" value="{{strip_tags($views->customer_list['values'][$customer_list]->customer_id)}}">
            <input type="hidden" name="keywords" value="{{strip_tags($views->keywords)}}">
            <input value="登録施設" type="submit"><br />
          {!! Form::close() !!}
        </td>
        </form>
      </tr>
    @endfor
  </table>
{{$views->cnt}}
/
{{count($views->customer_list['values'])}}
@endif
<br>
<hr size="1" />
{!! Form::open(['route' => ['ctl.brCustomer.create'], 'method' => 'post']) !!}
精算先　新規登録
@include ('ctl.brCustomer._input_customer')
  <input type="submit" value="登　　　　録">
  <input type="hidden" name="keywords" value="{{strip_tags($views->keywords)}}">
{!! Form::close() !!}

@endsection