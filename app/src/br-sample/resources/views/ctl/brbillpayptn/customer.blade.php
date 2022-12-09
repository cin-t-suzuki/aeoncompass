{{--  css  --}}
@include('ctl.brbillpayptn._css')

@inject('service', 'App\Http\Controllers\ctl\BrBillPayPtnController')

{{--削除でいい？ {strip} --}}
  {{-- 提携先管理ヘッダー --}}
  {{--元 {include file='../_common/_br_header2.tpl' title="パートナー精算実績の内容"} --}}
  @section('title', 'パートナー精算実績の内容')
  @include('ctl.common.base')

  <hr class="contents-margin" />

  {{-- メッセージボックス --}}
  {{-- {include file='../_common/_message.tpl'} --}}
  @section('message')
  @include('ctl.common.message', $messages)

  <hr class="contents-margin" />

  {{-- 検索条件 --}}
    <table class="br-detail-list">
    <tr>
      <th>精算年月</th>
      <td>{{substr($views->form_params['billpay_ym'],0,4)}}年{{substr($views->form_params['billpay_ym'],5,2)}}月</td>
    </tr>
    <tr>
      <th>パートナー精算先</th>
      <td>{{$views->customer['customer_nm']}}</td>
    </tr>
  </table>

  <hr class="contents-margin" />

  {{-- サイト単位台帳（元ソースNTA分への分岐は削除） --}}
  @if (!$service->is_empty($views->book))
    <table class="br-detail-list">
        @include ('ctl.common._billpayptn_book')
    </table>
  @endif
  {{-- /サイト単位台帳 --}}

  {{-- 予約明細ＣＳＶ --}}
  @if (!$service->is_empty($views->book))
  <hr class="contents-margin" />
  {{ Form::open(['route' => 'ctl.brbillpayptn.csv', 'method' => 'post', 'target' => "_blank"]) }}
    <input type="submit" value="ＣＳＶデータダウンロード（予約明細）" />
    <input type="hidden" name="customer_id"    value="{{$views->customer['customer_id']}}" />
    <input type="hidden" name="billpay_ptn_cd" value="{{$views->customer['billpay_ptn_cd']}}" />
    <input type="hidden" name="billpay_ym"     value="{{$views->form_params['billpay_ym']}}" />
        {{-- パートナーコードを持ち回すのは社内ログイン時のみ --}}
        {{-- {if $v->user->operator->is_staff() and !is_empty($v->user->partner.partner_cd)}<input type="hidden" name="partner_cd" value="{$v->user->partner.partner_cd}" />{/if} --}}
        {{--一旦非表示（ログイン処理） @if ()<input type="hidden" name="partner_cd" value="{{$v->user->partner.partner_cd}}" />@endif --}}
  {{ Form::close() }}
  @endif

  <hr class="contents-margin" />

  {{-- パートナー精算一覧への遷移 --}}
  {{ Form::open(['route' => 'ctl.brbillpayptn.list', 'method' => 'post']) }}
    <div class="ptn-back-main-menu-form">
      <input type="hidden" name="year"     value="{{substr($views->form_params['billpay_ym'],0,4)}}" />
      <input type="hidden" name="month"    value="{{substr($views->form_params['billpay_ym'],5,2)}}" />
      <input type="submit" value="パートナー精算一覧へ" />
    </div>
  {{ Form::close() }}
  {{-- /パートナー精算一覧への遷移 --}}

  <hr class="contents-margin" />

  {{-- 提携先管理フッター --}}
  {{-- @include file='../_common/_br_footer.tpl'} --}}
  @section('title', 'footer')
  @include('ctl.common.footer')
{{--削除でいい？ {/strip} --}}