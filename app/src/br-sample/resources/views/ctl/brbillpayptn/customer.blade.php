{{--  css  --}}
@include('ctl.brbillpayptn._css')

@inject('service', 'App\Http\Controllers\ctl\BrBillPayPtnController')

{{--削除でいい？ {strip} --}}
  @extends('ctl.common.base2')
  @section('title', 'パートナー精算実績の内容')

  @section('content')

  <hr class="contents-margin" />

  {{-- メッセージbladeの読込 --}}
  @include('ctl.common.message')

  <hr class="contents-margin" />

  {{-- 検索条件 --}}
    <table class="br-detail-list">
    <tr>
      <th>精算年月</th>
      <td>{{substr($form_params['billpay_ym'],0,4)}}年{{substr($form_params['billpay_ym'],5,2)}}月</td>
    </tr>
    <tr>
      <th>パートナー精算先</th>
      <td>{{$customer['customer_nm']}}</td>
    </tr>
  </table>

  <hr class="contents-margin" />

  {{-- サイト単位台帳（元ソースNTA分への分岐は削除） --}}
  @if (!$service->is_empty($book))
    <table class="br-detail-list">
        @include ('ctl.common._billpayptn_book')
    </table>
  @endif
  {{-- /サイト単位台帳 --}}

  {{-- 予約明細ＣＳＶ --}}
  @if (!$service->is_empty($book))
  <hr class="contents-margin" />
  {{ Form::open(['route' => 'ctl.brbillpayptn.csv', 'method' => 'get', 'target' => "_blank"]) }}
    <input type="submit" value="ＣＳＶデータダウンロード（予約明細）" />
    <input type="hidden" name="customer_id"    value="{{$customer['customer_id']}}" />
    <input type="hidden" name="billpay_ptn_cd" value="{{$customer['billpay_ptn_cd']}}" />
    <input type="hidden" name="billpay_ym"     value="{{$form_params['billpay_ym']}}" />
        {{-- パートナーコードを持ち回すのは社内ログイン時のみ --}}
        {{-- {if $v->user->operator->is_staff() and !is_empty($v->user->partner.partner_cd)}<input type="hidden" name="partner_cd" value="{$v->user->partner.partner_cd}" />{/if} --}}
        {{--一旦非表示（ログイン処理） @if ()<input type="hidden" name="partner_cd" value="{{$v->user->partner.partner_cd}}" />@endif --}}
  {{ Form::close() }}
  @endif

  <hr class="contents-margin" />

  {{-- パートナー精算一覧への遷移 --}}
  {{ Form::open(['route' => 'ctl.brbillpayptn.list', 'method' => 'get']) }}
    <div class="ptn-back-main-menu-form">
      <input type="hidden" name="year"     value="{{substr($form_params['billpay_ym'],0,4)}}" />
      <input type="hidden" name="month"    value="{{substr($form_params['billpay_ym'],5,2)}}" />
      <input type="submit" value="パートナー精算一覧へ" />
    </div>
  {{ Form::close() }}
  {{-- /パートナー精算一覧への遷移 --}}

  <hr class="contents-margin" />

  @endsection
{{--削除でいい？ {/strip} --}}