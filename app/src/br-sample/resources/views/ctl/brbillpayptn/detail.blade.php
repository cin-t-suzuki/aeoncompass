{{--  css  --}}
@include('ctl.brbillpayptn._css')

@inject('service', 'App\Http\Controllers\ctl\BrBillPayPtnController')

{{--削除でいい？ {strip} --}}
@extends('ctl.common.base2')
@section('title', 'パートナー精算実績の予約明細')

  @section('content')
  <hr class="contents-margin" />

  {{-- 検索条件 --}}
    <table class="br-detail-list">
        <tr>
          <th>精算年月</th>
          <td>{{substr(($form_params['billpay_ym'] ?? null),0,4)}}年{{substr(($form_params['billpay_ym'] ?? null),5,2)}}月</td>
        </tr>
        <tr>
          <th>パートナー精算先</th>
          {{-- null追記 --}}
          <td>{{$customer['customer_nm']??null}}</td>
        </tr>
        <tr>
          <th>サイト名</th>
          {{-- null追記 --}}
          <td>{{$customer['site_nm']??null}}</td>
        </tr>
        {{-- null追記 --}}
        @if (!$service->is_empty($form_params['stock_type']??null))
        <tr>
          <th>属性</th>
          <td style="text-align:left;">@if ($form_params['stock_type'] == 1)一般ネット在庫
              @elseif ($form_params['stock_type'] == 2)連動在庫
              @elseif ($form_params['stock_type'] == 3)東横イン在庫
              @endif
          </td>
        </tr>
        @endif
        <tr>
          <th>内容</th>
          {{-- null追記 --}}
          <td>@if (($form_params['billpay']??null) == 1)
                @php $target_ym = $form_params['target_ym'] . '-01'; @endphp
                {{ date('Y年m月', strtotime('-1 month' . $target_ym)) }}
              @else 精算分
              @endif
          </td>
        </tr>
        {{-- null追記 --}}
        @if (($form_params['billpay']??null) == 2 || ($form_params['billpay']??null) == 3)
        <tr>
          <th>率（%）</th>
          <td>@if ($form_params['msd_rate'] == 0){{number_format($form_params['rate'],2)}}@else{{number_format($form_params['rate'],2)}}@endif %</td>
        </tr>
        @endif
      </table>

  <div class="clear"></div>

  <hr class="contents-margin" />

  {{-- メッセージbladeの読込 --}}
  @include('ctl.common.message')
  
  <hr class="contents-margin" />

  {{-- 予約データ --}}
  @if (!$service->is_empty($detail['values']))
    <table class="br-detail-list">
      {{-- pager追記 --}}
      @include ('ctl.common._billpayptn_detail',['pager' => $pager,'params' => $search_params])
    </table>
  @endif
  {{-- /予約データ --}}

  <hr class="contents-margin" />

  {{-- ページャー --}}
  {{-- {include file='../_common/_pager.tpl' pager=$v->assign->pager params=$v->assign->search_params} --}}
    @include ('ctl.common._pager2', ['pager' => $pager,'params' => $search_params])
  {{-- /ページャー --}}

  <hr class="contents-margin" />

  {{ Form::open(['route' => 'ctl.brbillpayptn.csv', 'method' => 'get', 'target' => "_blank"]) }}
    <input type="submit" value="ＣＳＶデータダウンロード" />
    {{-- Getパラメータ作成 --}}
    {{-- {assign var=get_params value=''} --}}
    @php $get_params = ''; @endphp
    @foreach ($search_params as $key => $value)
      <input type="hidden" name="{{$key}}" value="{{$value}}" />
    @endforeach
  {{ Form::close() }}

  <hr class="contents-margin" />

  {{-- 精算実績の確認への遷移 --}}
  {{ Form::open(['route' => 'ctl.brbillpayptn.customer', 'method' => 'get']) }}
    <div class="ptn-back-main-menu-form">
      <input type="hidden" name="billpay_ym"     value="{{$form_params['billpay_ym'] ?? null}}" />
      <input type="hidden" name="customer_id"     value="{{$form_params['customer_id'] ?? null}}" />
      <input type="submit" value="精算実績の確認へ" />
    </div>
  {{ Form::close() }}
  {{-- /精算実績の確認への遷移 --}}

  <hr class="contents-margin" />

@endsection