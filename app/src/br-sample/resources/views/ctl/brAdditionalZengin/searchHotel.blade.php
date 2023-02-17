@inject('service', 'App\Http\Controllers\ctl\BrAdditonalZenginController')
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message')

{{-- ??[]追記でいいか？ --}}
@if (count($hotel_list['values'] ?? []) != 0)
<table cellspacing="0" cellpadding="2" border="1">
  <tr>
    <td bgcolor="#EEFFEE" nowrap>登録状態</td>
    <td bgcolor="#EEFFEE" nowrap>施設コード<br>施設名</td>
    <td bgcolor="#EEFFEE" nowrap>精算先名称</td>
    <td bgcolor="#EEFFEE" nowrap>請求方法</td>
    <td bgcolor="#EEFFEE" nowrap>口座情報設定</td>
    <td bgcolor="#EEFFEE" nowrap>選択</td>

  </tr>


  @foreach ($hotel_list['values'] as $hotel_list)
  <tr>
    <td nowrap>
      @if ($hotel_list['entry_status'] == 0)
        公開中
      @elseif ($hotel_list['entry_status'] == 1)
        登録作業中<br>
      @elseif ($hotel_list['entry_status'] == 2)
        解約
      @endif<br />
      @if ($hotel_list['accept_status'] == 1)
        [受付中]
      @elseif ($hotel_list['accept_status'] == 0)
        <font color="#ff0000">[停止中]</font>
      @endif
    </td>

    <td nowrap>
      {{$hotel_list['hotel_cd']}}<br>
      <a href="http://{$v->config->system->rsv_host_name}/hotel/{{$hotel_list['hotel_cd']}}/" target="_blank" style="text-decoration: none; color:#000066;">{{$hotel_list['hotel_nm']}}@if (!$service->is_empty($hotel_list['hotel_old_nm']))（{{$hotel_list['hotel_old_nm']}}）</font>@endif</a>
      @if ($hotel_list['stock_type'] == 1)
        <font color="#0000ff">[買]</font>
      @endif
      @if (!$service->is_empty($hotel_list['pref_nm']))
        （{{$hotel_list['pref_nm']}}）</font>
      @endif
    </td>

    <td>
    @if (!$service->is_empty($hotel_list['customer_id']))
      ({{$hotel_list['customer_id']}}){{$hotel_list['customer_nm']}}
    @else
    	未設定
    @endif
    </td>

    <td>
      {{-- nullだと==0がtrueになるので空の場合は""追記でいいか --}}
    @if (($hotel_list['bill_way'] ?? "") ==0)
      振込
    @elseif (($hotel_list['bill_way'] ?? "") ==1)
      引落
    @else
      未設定
    @endif
    </td>

    <td nowrap align="middle">
    @if (!$service->is_empty($hotel_list['customer_id']))
        @if (!$hotel_list['factoring_flg'])
          <font color="#ff0000">未設定</font>
        @endif

        {{-- TODO customer側できてから修正 --}}
      <form action="{$v->env.source_path}{$v->env.module}/brcustomer/edit/" method="post" target= "_blank">
        <input type="submit" value="詳細情報">
        <input type="hidden" name="customer_id" value="{{$hotel_list['customer_id']}}" />
      {{ Form::close() }}
    @endif

    </td>
    <td nowrap align="middle">
      {{ Form::open(['route' => 'ctl.brAdditionalZengin.edit', 'method' => 'get']) }}
        <input type="submit" value="設定"  @if (!$hotel_list['factoring_flg'])disabled @endif>
        <input type="hidden" name="hotel_cd" value="{{$hotel_list['hotel_cd']}}" />
        <input type="hidden" name="customer_id" value="{{$hotel_list['customer_id']}}" />
      {{ Form::close() }}
    </td>

  </tr>
  @endforeach
</table>
@endif