  <tr>
  {{-- ヘッダ表示 --}}
  <tr>
      <th colspan="@if ($customer['document_type'] == 3) 12 @elseif ($customer['document_type'] == 1) 8 @else 7 @endif">
        @if ($service->is_empty($customer['billpay_ptn_cd']))<span style="font-weight:bold">未精算（繰越）</span>
        @else
          <span style="float:right">No.{{$customer['billpay_ptn_cd']}}</span>
          @if (!$service->is_empty($customer['bill_ymd']))
            <span style="float:left">
              {{-- {if $v->helper->date->set($v->assign->customer.bill_ymd*1)}{/if}お振込期日 {$v->helper->date->to_format('Y年m月d日')} --}}
              お振込期日 @include ('ctl.common._date',['timestamp' => $customer['bill_ymd'] , 'format' => 'ymd'])
            </span>
          @endif
          <div style="clear:both;"></div>
        @endif
      </th>
  </tr>
  <tr>
    <th rowspan="2">内容</th>
    @if ($customer['document_type'] == 2 || $customer['document_type'] == 3)
      <th colspan="5">ベストリザーブお支払項目</th>
    @endif
    @if ($customer['document_type'] == 1 || $customer['document_type'] == 3)
      <th colspan="@if ($customer['document_type'] == 1)6 @else 4 @endif">ベストリザーブ社ご請求項目</th>
    @endif
    {{-- 原稿表示はリリース時未実装（請求書、支払通知書？）。必要であれば元ソースより追記する --}}
  </tr>

  {{-- 列項目名表示 --}}
  <tr>
    @if ($customer['document_type'] == 2 || $customer['document_type'] == 3)
      <th>成約泊数</th>
      <th>成約料金（税込）</th>
      <th>率（％）</th>
      <th>広告宣伝料（税込）</th>
      <th>支払小計</th>
    @endif
    @if ($customer['document_type'] == 1)
      <th>宿泊泊数</th>
      <th>取消泊数</th>
    @endif
    @if ($customer['document_type'] == 1 || $customer['document_type'] == 3)
      <th>宿泊料（税込）</th>
      <th>取消料</th>
      <th>補助金</th>
      <th>請求小計</th>
    @endif
  </tr>

  {{-- 総合計初期化 --}}
  {{-- {assign var=total_bill_charge    value=0}
  {assign var=total_payment_charge value=0}
  {assign var=total_billpay_charge value=0} --}}
  @php
    $total_bill_charge = 0;
    $total_payment_charge = 0;
    $total_billpay_charge = 0;
  @endphp

  @foreach ($book as $site_cd => $sites)

    {{-- サイト合計値初期化 --}}
    {{-- {assign var=bill_charge    value=0}
    {assign var=payment_charge value=0}
    {assign var=billpay_charge value=0} --}}
    @php
    $bill_charge = 0;
    $payment_charge = 0;
    $billpay_charge = 0;
    @endphp

    {{-- サイトタイトル行表示 --}}
    <tr>
      {{-- affiliate_cd_subのみ??null追記 --}}
        <td colspan="@if ($customer['document_type'] == 1 || $customer['document_type'] == 2) 7 @else 10 @endif">{{$sites[0]['site_nm']}} 様 （{{$sites[0]['partner_cd']}}{{$sites[0]['affiliate_cd']}}@if (!$service->is_empty($sites[0]['affiliate_cd_sub']??null))-{{$sites[0]['affiliate_cd_sub']}}@endif）</td>
    </tr>

    {{-- サイト明細行表示 --}}
    @foreach ($sites as $site)
      <tr>
        {{--下記日付部分の書き換え {if $v->helper->date->set($site.billpay_ym*1)}{/if}{$v->helper->date->add('m', -1)}{$v->helper->date->to_format('Y年m月')}分{/if} --}}
        {{-- dateをincludeで持ってくると、うしろに半角スペースつく→_date側に新しく作るでいいか？ --}}
        <td>@if ($service->is_empty($site['billpay_ym']))精算分@else @include('ctl.common._date',['timestamp' => strtotime($site['billpay_ym']." -1 month") , 'format' => 'ym分']) @endif</td>
        @if ($customer['document_type'] == 2 || $customer['document_type'] == 3)
          <td class="charge">{{number_format($site['bill_sales_count']+$site['bill_cancel_count'])}}</td>
          <td class="charge">{{number_format($site['bill_charge'])}}</td>
          <td class="charge">{{number_format($site['rate'],2)}}%</td>
          <td class="charge">{{number_format($site['stock_fee']+$site['stock_fee_tax']+$site['sales_fee']+$site['sales_fee_tax'])}}</td>
          <td class="charge">{{number_format($site['stock_fee']+$site['stock_fee_tax']+$site['sales_fee']+$site['sales_fee_tax'])}}</td>
        @endif
        @if ($customer['document_type'] == 1)
          <td class="charge">{{number_format($site['later_sales_count'])}}</td>
          <td class="charge">{{number_format($site['later_cancel_count'])}}</td>
        @endif
        @if ($customer['document_type'] == 1 || $customer['document_type'] == 3)
          <td class="charge">{{number_format($site['later_sales_charge'])}}</td>
          <td class="charge">{{number_format($site['later_cancel_charge'])}}</td>
          <td class="charge">{{number_format($site['use_grants_total'])}}</td>
          <td class="charge">{{number_format($site['later_sales_charge']+$site['later_cancel_charge']+$site['use_grants_total'])}}</td>
        @endif
        <td style="text-align:center;">
          {{-- <form style="margin:0;" method="post" action="{$v->env.source_path}{$v->env.module}/{$v->env.controller}/detail/"> --}}
          {{ Form::open(['route' => 'ctl.brBillpayPtn.detail', 'method' => 'get', 'style' => 'margin:0;']) }}
            <input type="submit" value="明細" />
            <input type="hidden" name="site_cd"        value="{{$site['site_cd']}}" />
            <input type="hidden" name="customer_id"    value="{{$site['customer_id']}}" />
            <input type="hidden" name="billpay_ptn_cd" value="{{$customer['billpay_ptn_cd']}}" />
            {{--下記日付部分の書き換え　※要調整※ if ($v->helper->date->set($site.billpay_ym*1)}{/if}{$v->helper->date->to_format('Y-m')}{/if} --}}
            <input type="hidden" name="target_ym"     value="@if (!$service->is_empty($site['billpay_ym'])){{$site['billpay_ym']}} @endif" />
            @if ($service->is_empty($site['billpay_ym']))
              <input type="hidden" name="billpayed"     value="1" />
            @else
              <input type="hidden" name="billpay"     value="1" />
            @endif
            <input type="hidden" name="rate"           value="{{$site['rate']}}" />
            <input type="hidden" name="billpay_ym"     value="{{$form_params['billpay_ym']}}" />
            {{-- 下記非表示部分、ログイン機能？が実装後の実装？？ --}}
            {{-- パートナーコードを持ち回すのは社内ログイン時のみ --}}
            {{-- {if $v->user->operator->is_staff() and !is_empty($v->user->partner.partner_cd)}<input type="hidden" name="partner_cd" value="{$v->user->partner.partner_cd}" />{/if} --}}
            {{-- アフィリエイトコードを持ち回すのは社内ログイン時のみ --}}
            {{-- {if $v->user->operator->is_staff() and !is_empty($v->assign->affiliate.affiliate_cd)}<input type="hidden" name="affiliate_cd" value="{$v->assign->affiliate.affiliate_cd}" />{/if} --}}
          {{ Form::close() }}
        </td>
      </tr>
      {{-- サイト合計加算 --}}
      {{-- {assign var=bill_charge    value=$bill_charge+$site.later_sales_charge+$site.later_cancel_charge+$site.use_grants_total}
      {assign var=payment_charge value=$payment_charge+$site.stock_fee+$site.stock_fee_tax+$site.sales_fee+$site.sales_fee_tax}
      {assign var=billpay_charge value=$billpay_charge+$site.later_sales_charge+$site.later_cancel_charge+$site.use_grants_total-$site.stock_fee-$site.stock_fee_tax-$site.sales_fee-$site.sales_fee_tax} --}}
      @php
      $bill_charge = $bill_charge + $site['later_sales_charge'] + $site['later_cancel_charge'] + $site['use_grants_total'];
      $payment_charge = $payment_charge + $site['stock_fee'] + $site['stock_fee_tax'] + $site['sales_fee'] + $site['sales_fee_tax'];
      $billpay_charge = $billpay_charge + $site['later_sales_charge'] + $site['later_cancel_charge'] + $site['use_grants_total'] - $site['stock_fee'] - $site['stock_fee_tax'] - $site['sales_fee'] - $site['sales_fee_tax'];
      @endphp
    @endforeach

    {{-- サイト合計行表示 --}}
    @if ($customer['document_type'] == 3)
      <tr>
        <td>計</td>
        @if ($customer['document_type'] == 2 || $customer['document_type'] == 3)
          <td colspan="5" class="charge">{{number_format($payment_charge)}}</td>
        @endif
        @if ($customer['document_type'] == 1 || $customer['document_type'] == 3)
          <td colspan="@if ($customer['document_type'] == 1) 5 @else 3 @endif" class="charge">{{number_format($bill_charge)}}</td>
        @endif
        <td></td>
      </tr>
    @endif
    <tr>
      <td>@if ($billpay_charge >= 0) ご請求合計 @else お支払合計 @endif</td>
      @if ($customer['document_type'] == 2 || $customer['document_type'] == 3)
        <td colspan="5" class="charge">@if ($billpay_charge < 0){{number_format($billpay_charge*-1)}}@endif</td>
      @endif
      @if ($customer['document_type'] == 1 || $customer['document_type'] == 3)
        <td colspan="@if ($customer['document_type'] == 1) 5 @else 3 @endif" class="charge">@if ($billpay_charge >= 0){{number_format($billpay_charge)}} @endif</td>
      @endif
      <td></td>
    </tr>
    {{-- 行間 --}}
    <tr>
        <th colspan="@if ($customer['document_type'] == 1 || $customer['document_type'] == 2) 7 @else 10 @endif"></th>
    </tr>
    {{-- 総合計加算 --}}
    @if ($billpay_charge >= 0)
      @php $total_bill_charge = $total_bill_charge + $billpay_charge @endphp
    @else
      @php $total_payment_charge = $total_payment_charge + $billpay_charge @endphp
    @endif
    @php $total_billpay_charge = $total_billpay_charge + $billpay_charge @endphp
  @endforeach
  {{-- 総合計行表示 --}}
  @if ($customer['document_type'] == 2 || $customer['document_type'] == 3)
    <tr>
      <td>お支払合計</td>
      <td colspan="5" class="charge">{{number_format($total_payment_charge*-1)}}</td>
       @if ($customer['document_type'] == 3)
         <td colspan="3" class="charge"></td>
       @endif
      <td></td>
    </tr>
  @endif
  @if ($customer['document_type'] == 1 || $customer['document_type'] == 3)
    <tr >
      <td>ご請求合計</td>
       @if ($customer['document_type'] == 3)
          <td colspan="5" class="charge"></td>
       @endif
       <td colspan="@if ($customer['document_type'] == 1) 5 @else 3 @endif" class="charge">{{number_format($billpay_charge)}}</td>
       <td></td>
    </tr>
  @endif
  <tr>
    <td>総合計</td>
    @if ($customer['document_type'] == 2 || $customer['document_type'] == 3)
    {{-- $total_total_billpay_chargeなんてない→$total_billpay_chargeへ --}}
      <td colspan="5" class="charge">@if ($total_billpay_charge < 0){{number_format($total_billpay_charge*-1)}}@endif</td>
    @endif
    @if ($customer['document_type'] == 1 || $customer['document_type'] == 3)
      <td colspan="@if ($customer['document_type'] == 1) 5 @else 3 @endif" class="charge">@if ($total_billpay_charge >= 0){{number_format($total_billpay_charge)}}@endif</td>
    @endif
    <td></td>
  </tr>
