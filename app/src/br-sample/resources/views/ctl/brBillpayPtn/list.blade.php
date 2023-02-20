
{{--  css  --}}
@include('ctl.brBillpayPtn._css')

@inject('service', 'App\Http\Controllers\ctl\BrBillPayPtnController')

{{--削除でいい？ {strip} --}}
@extends('ctl.common.base2')
@section('title', 'パートナー精算一覧')

@section('content')

    <hr class="contents-margin" />

  {{-- 元々なかったが、コントローラでメッセージは渡していたので追記 --}}
  {{-- メッセージbladeの読込 --}}
  @include('ctl.common.message')

    <div style="text-align:left;">
    @include ('ctl.brBillpayPtn._form')
    </div>

    <hr class="contents-margin" />

    <table class="br-detail-list">
      <tr>
        <th>精算NO</th>
        <th>精算先</th>
        <th>精算額（税込）</th>
        <th>原稿作成日</th>
        <th>内容表示</th>
        {{-- 原稿表示はリリース時未実装。必要であれば元ソースより追記する --}}
      </tr>
    @foreach ($billpayptn as $billpayptn)
      <tr>
        <td>{{$billpayptn['billpay_ptn_cd']}}</td>
        <td>{{$billpayptn['customer_nm']}}<br />{{$billpayptn['person_post']}} {{$billpayptn['person_nm']}}</td>
        <td class="charge">{{number_format($billpayptn['billpay_charge_total'])}}</td>
        <td>@include ('ctl.common._date',['timestamp' => $billpayptn['book_create_dtm'] , 'format' => 'y-m-d'])</td>
        <td style="text-align:center;">
          @if ($service->is_empty($billpayptn['book_path']))未作成 
          @else 
          {{ Form::open(['route' => 'ctl.brBillpayPtn.customer', 'method' => 'get']) }}
            <input type="submit" value=" 表示 ">
            <input type="hidden" name="billpay_ym"       value="{{$billpayptn['billpay_ym']}}" />
            <input type="hidden" name="customer_id"      value="{{$billpayptn['customer_id']}}" />
            <input type="hidden" name="billpay_ptn_cd"   value="{{$billpayptn['billpay_ptn_cd']}}" />
            {{--上記 {{$billpayptn['billpay_ym']}}の日付フォーマットしていないが、コントローラ側で既に済 --}}
          {{ Form::close() }}
          @endif
        </td>
        {{-- 原稿表示はリリース時未実装。必要であれば元ソースより追記する --}}
      </tr>
    @endforeach
    </table>
    <hr class="contents-margin" />

  @endsection

{{--削除でいい？ {/strip} --}}