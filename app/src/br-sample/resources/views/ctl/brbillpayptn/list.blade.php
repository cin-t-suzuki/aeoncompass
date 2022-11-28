
{{--  css  --}}
@include('ctl.brbillpayptn._css')

@inject('service', 'App\Http\Controllers\ctl\BrBillPayPtnController')

{{--削除でいい？ {strip} --}}
  {{-- 提携先管理ヘッダー --}}
  {{--元 {include file='../_common/_br_header2.tpl' title="パートナー精算一覧"} --}}
  @section('title', 'パートナー精算一覧')
  @include('ctl.common.base')

    <hr class="contents-margin" />

    <div style="text-align:left;">
    @include ('ctl.brbillpayptn._form')
    </div>

    <hr class="contents-margin" />

    <table class="br-detail-list">
      <tr>
        <th>精算NO</th>
        <th>精算先</th>
        <th>精算額（税込）</th>
        <th>原稿作成日</th>
        <th>内容表示</th>
        <th>原稿表示</th>
      </tr>
    @foreach ($views->billpayptn as $billpayptn)
      <tr>
        <td>{{$billpayptn['billpay_ptn_cd']}}</td>
        <td>{{$billpayptn['customer_nm']}}<br />{{$billpayptn['person_post']}} {{$billpayptn['person_nm']}}</td>
        <td class="charge">{{number_format($billpayptn['billpay_charge_total'])}}</td>
        <td>@include ('ctl.common._date',['timestamp' => $billpayptn['book_create_dtm'] , 'format' => 'y-m-d'])</td>
        <td style="text-align:center;">
          @if ($service->is_empty($billpayptn['book_path']))未作成 
          @else 
          <form action="{$v->env.path_base_module}/brbillpayptn/customer/" method="post">
            <input type="submit" value=" 表示 ">
            <input type="hidden" name="billpay_ym"       value="@include ('ctl.common._date',['timestamp' => $billpayptn['billpay_ym'] , 'format' => 'ym'])" />
            <input type="hidden" name="customer_id"      value="{{$billpayptn['customer_id']}}" />
            <input type="hidden" name="billpay_ptn_cd"   value="{{$billpayptn['billpay_ptn_cd']}}" />
          </form>
          @endif
        </td>
        <td style="text-align:center;">
          @if ($service->is_empty($billpayptn['book_path']))未作成
          @else 
          <form action="{$v->env.path_base_module}/brbillpayptn/book/" method="post" target="_blank">
            <input type="submit" value=" 原稿 ">
            <input type="hidden" name="billpay_ym"    value="@include ('ctl.common._date',['timestamp' => $billpayptn['billpay_ym'] , 'format' => 'ym'])" />
            <input type="hidden" name="customer_id"   value="{{$billpayptn['customer_id']}}" />
            <input type="hidden" name="key"           value="{{$billpayptn['book_path_encrypt']}}" />
          </form>
          @endif
        </td>
      </tr>
    @endforeach
    </table>
    <hr class="contents-margin" />

  {{-- 提携先管理フッター --}}
  @section('title', 'footer')
  @include('ctl.common.footer')

{{--削除でいい？ {/strip} --}}