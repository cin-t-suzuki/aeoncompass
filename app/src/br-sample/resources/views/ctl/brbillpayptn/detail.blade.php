{{--  css  --}}
@include('ctl.brbillpayptn._css')

@inject('service', 'App\Http\Controllers\ctl\BrBillPayPtnController')

{{--削除でいい？ {strip} --}}
  {{-- 提携先管理ヘッダー --}}
  {{--元 {include file='../_common/_br_header2.tpl' title="パートナー精算実績の予約明細"} --}}
  @section('title', 'パートナー精算実績の内容')
  @include('ctl.common.base')

  <hr class="contents-margin" />

  {{-- 検索条件 --}}
    <table class="br-detail-list">
        <tr>
          <th>精算年月</th>
          <td>{{substr($views->form_params['billpay_ym'],0,4)}}年{{substr($views->form_params['billpay_ym'],5,2)}}月</td>
        </tr>
        <tr>
          <th>パートナー精算先</th>
          {{-- null追記 --}}
          <td>{{$views->customer['customer_nm']??null}}</td>
        </tr>
        <tr>
          <th>サイト名</th>
          {{-- null追記 --}}
          <td>{{$views->customer['site_nm']??null}}</td>
        </tr>
        {{-- null追記 --}}
        @if (!$service->is_empty($views->form_params['stock_type']??null))
        <tr>
          <th>属性</th>
          <td style="text-align:left;">@if ($views->form_params['stock_type'] == 1)一般ネット在庫
              @elseif ($views->form_params['stock_type'] == 2)連動在庫
              @elseif ($views->form_params['stock_type'] == 3)東横イン在庫
              @endif
          </td>
        </tr>
        @endif
        <tr>
          <th>内容</th>
          {{-- null追記 --}}
          <td>@if (($views->form_params['billpay']??null) == 1)
                {{-- {assign var=target_ym value=$v->assign->form_params.target_ym|cat:'-01'} --}}
                @php $target_ym = $views->form_params['target_ym'] . '-01'; @endphp
                {{-- {if $v->helper->date->set($target_ym)}{/if}{$v->helper->date->add('m', -1)}{$v->helper->date->to_format('Y年m月')}分 --}}
                @include ('ctl.common._date',['timestamp' => $views->customer['bill_ymd'] , 'format' => 'ym'])
                {{-- ↑１か月戻すの未実装 --}}
              @else 精算分
              @endif
          </td>
        </tr>
        {{-- null追記 --}}
        @if (($views->form_params['billpay']??null) == 2 || ($views->form_params['billpay']??null) == 3)
        <tr>
          <th>率（%）</th>
          <td>@if ($views->form_params['msd_rate'] == 0){{number_format($views->form_params['rate'],2)}}@else{{number_format($views->form_params['rate'],2)}}@endif %</td>
        </tr>
        @endif
      </table>

  <div class="clear"></div>

  <hr class="contents-margin" />

  {{-- メッセージボックス --}}
  {{-- {include file='../_common/_message.tpl'} --}}
  @section('message')
  @include('ctl.common.message', $messages)


  <hr class="contents-margin" />

  {{-- 予約データ --}}
  @if (!$service->is_empty($views->detail['values']))
    <table class="br-detail-list">
      {{-- pager追記 --}}
      @include ('ctl.common._billpayptn_detail',['pager' => $views->pager,'params' => $views->search_params])
    </table>
  @endif
  {{-- /予約データ --}}

  <hr class="contents-margin" />

  {{-- ページャー --}}
  {{-- {include file='../_common/_pager.tpl' pager=$v->assign->pager params=$v->assign->search_params} --}}
  {{-- laravel形式に書き換え→include先は削除 getになるがいい？（パラメータたくさん）  --}}
  {{-- TODO: デザイン修正要、bootstrap入れていないから崩れている？  --}}
  {{ $views->pager->appends($views->search_params)->links('pagination::bootstrap-4') }}  
  {{-- /ページャー --}}

  <hr class="contents-margin" />

  {{ Form::open(['route' => 'ctl.brbillpayptn.csv', 'method' => 'post', 'target' => "_blank"]) }}
    <input type="submit" value="ＣＳＶデータダウンロード" />
    {{-- Getパラメータ作成 --}}
    {{-- {assign var=get_params value=''} --}}
    @php $get_params = ''; @endphp
    @foreach ($views->search_params as $key => $value)
      <input type="hidden" name="{{$key}}" value="{{$value}}" />
    @endforeach
  {{ Form::close() }}

  <hr class="contents-margin" />

  {{-- 精算実績の確認への遷移 --}}
  {{ Form::open(['route' => 'ctl.brbillpayptn.customer', 'method' => 'post']) }}
    <div class="ptn-back-main-menu-form">
      <input type="hidden" name="billpay_ym"     value="{{$views->form_params['billpay_ym']}}" />
      <input type="hidden" name="customer_id"     value="{{$views->form_params['customer_id']}}" />
      <input type="submit" value="精算実績の確認へ" />
    </div>
  {{ Form::close() }}
  {{-- /精算実績の確認への遷移 --}}

  <hr class="contents-margin" />

  {{-- 提携先管理フッター --}}
  {{-- @include file='../_common/_br_footer.tpl'} --}}
  @section('title', 'footer')
  @include('ctl.common.footer')
{{--削除でいい？ {/strip} --}}