@inject('service', 'App\Http\Controllers\ctl\BrAdditonalZenginController')
<table cellspacing="0" cellpadding="2" border="1">
  <tr>
    <td bgcolor="#EEFFEE" nowrap>引落日</td>
    <td bgcolor="#EEFFEE" nowrap>引落入金予定日</td>
    <td bgcolor="#EEFFEE" nowrap>施設コード<br>施設名</td>
    <!--<td bgcolor="#EEFFEE" nowrap>送客請求実績</td> -->
    <td bgcolor="#EEFFEE" nowrap>精算先名称</td>
    <!--<td bgcolor="#EEFFEE" nowrap>引落口座情報</td> -->
    <!--<td bgcolor="#EEFFEE" nowrap>精算先情報</td> -->
    <!-- <td bgcolor="#EEFFEE" nowrap>理由(施設向け)</td> -->
    <!--<td bgcolor="#EEFFEE" nowrap>備考(内部のみ)</td> -->
    <td bgcolor="#EEFFEE" nowrap>振替追加額</td>
    <td bgcolor="#EEFFEE" nowrap>登録者</td>
    <td bgcolor="#EEFFEE" nowrap>登録日</td>
    <td bgcolor="#EEFFEE" nowrap>送客請求実績</td>
    <td bgcolor="#EEFFEE" nowrap></td>
  </tr>


@foreach ($views->additional_zengin['values'] as $zengin_list)

  <tr>
    <td nowrap>
      @include('ctl.common._date',["timestamp" => $zengin_list->billpay_ymd, "format" =>"y/m/d" ] )
    </td>
    <td nowrap align="middle">
      @include('ctl.common._date',["timestamp" => $zengin_list->date_ymd, "format" =>"y/m/d" ] )
    </td>
    <td nowrap>
      {{$zengin_list->hotel_cd}}<br>
<!--      <a href="http://{$v->config->system->rsv_host_name}/hotel/{$zengin_list.hotel_cd}/" target="_blank" style="text-decoration: none; color:#000066;">-->
      {{-- ↓hotel_old_nmはDB::selectされていない→null追記でいいか --}}
      {{$zengin_list->hotel_nm}}@if (!$service->is_empty($zengin_list->hotel_old_nm ?? null))（{{$zengin_list->hotel_old_nm ?? null}}）</font>@endif
<!--      </a>-->
    </td>

<!--
     <td nowrap align="middle">
      <form action="{$v->env.source_path}{$v->env.module}/brcustomer/edit/" method="post" target= "_blank">
        <input type="submit" value="詳細情報">
        <input type="hidden" name="customer_id" value="{$zengin_list.customer_id}" />
      </form>
    </td>
 -->
    <td nowrap>
      ({{$zengin_list->customer_id}}){{$zengin_list->customer_nm}}
    </td>

    <td nowrap align="middle">
      {{number_format($zengin_list->additional_charge)}}
    </td>
    <td nowrap align="middle">
      {{$zengin_list->staff_nm}}
    </td>
    <td nowrap align="middle">
      @include('ctl.common._date',["timestamp" => $zengin_list->entry_ts, "format" =>"y/m/d" ] )
    </td>
    <td nowrap align="middle">
      		{{-- TODO htldemand作成後に遷移先設定 --}}
      <form action="{$v->env.source_path}{$v->env.module}/htldemand/" method="post" target= "_blank">
        <input type="submit" value="確認">
        <input type="hidden" name="target_cd" value="{{$zengin_list->hotel_cd}}" />
      </form>
    </td>


    <td nowrap align="middle">
      {{ Form::open(['route' => 'ctl.brAdditionalZengin.detail', 'method' => 'get']) }}
        <input type="submit" value="詳細情報">
        <input type="hidden" name="zengin_ym" value="{{$zengin_list->zengin_ym}}" />
        <input type="hidden" name="branch_id" value="{{$zengin_list->branch_id}}" />
      {{ Form::close() }}
    </td>

  </tr>

@endforeach
</table>