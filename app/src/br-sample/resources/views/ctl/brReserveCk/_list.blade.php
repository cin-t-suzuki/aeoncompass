  {{-- {assign var="total_page" value=$reserve_data.total_page}
  {assign var="total_count" value=$reserve_data.total_count} --}}
  @php
    $total_page = $reserve_data->total_page;
    $total_count = $reserve_data->total_count;
  @endphp
  @if ($views->page == 1)
    {{-- {assign var="page" value=0} --}}
    @php $page = 0; @endphp
  @else
    {{-- {assign var="page" value=$v->assign->page*20-20} --}}
    @php $page = $views->page*20-20; @endphp
  @endif
<tr>
<td nowrap align="right">
  {{-- $smarty.foreach.reserve_data.iteration→$loop->iterationでいいか？ --}}
@if ($reserve_data->stock_type == 1)<font color="#0000ff">[買] </font>@endif{{strip_tags($loop->iteration+$page)}} 
</td>
@if ($reserve_data->contact_status)
<FORM ACTION="{$v->env.source_path}{$v->env.module}/htlreserve/memberinfo/" METHOD="POST">
  <td><small>
    <input type="hidden" name="target_cd" value="{$reserve_data.hotel_cd}" />
    <input type="hidden" name="member_cd" value="{$reserve_data.member_cd}" />
    <input type="hidden" name="partner_cd" value="{$reserve_data.partner_cd}" />
    <input type="hidden" name="reserve_cd" value="{$reserve_data.reserve_cd}" />
    <input type="hidden" name="auth_type" value="{$reserve_data.auth_type}" />
    <INPUT TYPE="submit" VALUE="予約者">
  </small></td>
</FORM>
@else
  <td><small>予約者</small></td>
@endif
<FORM ACTION="{$v->env.source_path}{$v->env.module}/htlreservemanagement/reserveinfo/" METHOD="POST">
  <input type="hidden" name="target_cd" value="{$reserve_data.hotel_cd}">
  <input type="hidden" name="reserve_cd" value="{$reserve_data.reserve_cd}">
  <input type="hidden" name="partner_ref" value="{$reserve_data.partner_ref}">
  <input type="hidden" name="date_ymd" value="{$reserve_data.date_ymd|date_format:'%Y-%m-%d'}">
  <td nowrap>
    <small>
      <INPUT TYPE="submit" VALUE="予約">
    </small>
  </td>
</FORM>
<td nowrap>
{{strip_tags($reserve_data->partner_ref)}}
<br>
{{strip_tags($reserve_data->reserve_cd)}}
<br><small>　(@include ('ctl.common._date',['timestamp' => strip_tags($reserve_data->reserve_dtm) , 'format' => 'y-m-d']))</small></td>

<td nowrap>
{{--color_on=trueはどうする？ {include file=$v->env.module_root|cat:'/views/_common/_date.tpl' timestamp=$reserve_data.date_ymd format='ymd(w)' color_on=true}</td> --}}
@include ('ctl.common._date',['timestamp' => strip_tags($reserve_data->date_ymd) , 'format' => 'ymd(w)'])</td>
<td nowrap>
{{strip_tags($reserve_data->room_nm)}}<br>（{{strip_tags($reserve_data->room_cd)}}）
<hr size="0" color="#cccccc">
{{strip_tags($reserve_data->plan_nm)}}<br>（{{strip_tags($reserve_data->plan_cd)}}）<br>
{{-- {include file=$v->env.module_root|cat:'/views/_common/_icons.tpl' value=$reserve_data} --}}
@include ('ctl.common._icons', ['value' => $reserve_data])
</td>
<td nowrap>{{strip_tags($reserve_data->guest_nm)}}</td>
<td nowrap><small>
  @if ($reserve_data->reserve_status == '0')
    <font color="#0000FF">予約</font>
  @elseif ($reserve_data->reserve_status == '1')
    <font color="#FF0000">キャンセル</font>
  @elseif ($reserve_data->reserve_status == '2')
    <font color="#FF0000">電話キャンセル</font>
  @elseif ($reserve_data->reserve_status == '4')
    <font color="#FF0000">無断不泊</font>
  @endif
<br>
@if ($reserve_data->before_sales_charge != $reserve_data->sales_charge)
    <font color="#FF0000">料金変更あり</font>
@endif
</small></td>
<td nowrap ALIGN="right">
@if ($reserve_data->reserve_status == '0')
  <font color="#999999">{{number_format(strip_tags($reserve_data->sales_charge))}}</font>
  <br />
  {{number_format($reserve_data->sales_charge-$reserve_data->tax_charge)}}
@else
  {{number_format($reserve_data->cancel_charge-$reserve_data->cancel_tax_charge)}}
  @if (0 < $reserve_data->cancel_charge-$reserve_data->cancel_tax_charge)<br /><small style="color:#999">（キャンセル料金）</small>@endif
@endif
</td>
<td nowrap align="right">
{{number_format(strip_tags($reserve_data->bill['bill_charge']))}}<br>{{strip_tags($reserve_data->bill['rate'])}}%
</td>
<td nowrap align="right">
{{number_format(strip_tags($reserve_data->bill['point_charge']))}}<br>{{strip_tags($reserve_data->bill['point_rate'])}}%
</td>
<td nowrap @if ($reserve_data->partner_cd == '3016007888')align="right" bgcolor="#FF0000"@endif>
@if ($reserve_data->reserve_status != 0)
  ［変更不可］
@elseif (now() < $reserve_data->date_ymd)
  未来の予約は<br>
  変更できません。
@else
  @if ($reserve_data->reserve_type == 1)
      <a href="//{$v->config->system->rsv_host_name}/hs/manual/pdf/JRCollection-BRYContact.pdf" target="_blank">募集型企画旅行の為、<br />キャンセル、料金変更、無断不泊は<br />日本旅行にご確認ください。</a>
  @else
    @if ($reserve_data->partner_cd == '3016007888')
      <font color="#FFFFFF">【注意】アーク・スリーと施設の両方から<br />依頼があった時以外は絶対に　<br />キャンセルしないでください。　　<br /></font><a href="//{$v->config->system->rsv_host_name}/hs/manual/pdf/jetstarDynamicPackaging-Manual2016-3.pdf#page=10" target="_blank">アークスリーの説明</a>
    @endif
    {{--会員キャンセル--}}
    {{--書き換え以下であっている？ {if $v->user->operator->is_staff() and $smarty.now|date_format:'%Y%m%d' <= $reserve_data.date_ymd|date_format:'%Y%m%d'} --}}
    @php $date_ymd =  date('%Y%m%d', strtotime($reserve_data->date_ymd)); @endphp
    {{--TODO userの部分が未実装なので、一旦一方の条件のみで設定 @if ($v->user->operator->is_staff() && date('%Y%m%d') <= $date_ymd) --}}
    @if (date('%Y%m%d') <= $date_ymd)
      <FORM ACTION="{$v->env.source_path}{$v->env.module}/htlreserveuseroperation/index#guide" METHOD="POST">
        <input type="hidden" name="target_cd" value="{strip_tags($reserve_data.hotel_cd)}">
        <input type="hidden" name="reserve_cd" value="{strip_tags($reserve_data.reserve_cd)}">
        <input type="hidden" name="date_ymd[data]" value="{strip_tags($reserve_data.date_ymd)|date_format:'%Y-%m-%d'}">
        <input type="hidden" name="return_pass" value="{strip_tags($v->assign->return_pass)}" />

        <!-- 検索項目 -->
        <input type="hidden" name="date_ymd[after]" value="{strip_tags($v->assign->conditions.date_ymd.after)}">
        <input type="hidden" name="date_ymd[before]" value="{strip_tags($v->assign->conditions.date_ymd.before)}">
        <input type="hidden" name="search_reserve_cd" value="{strip_tags($v->assign->conditions.reserve_cd)}">
        <input type="hidden" name="page" value="{strip_tags($v->assign->page)}">
        <INPUT TYPE="submit" VALUE="会員キャンセル">
      </FORM>
    @endif
    {{--料金変更--}}
      @if ($reserve_data->payment_way != 1)
      <FORM ACTION="{$v->env.source_path}{$v->env.module}/htlreservemanagement/modifycharge1#guide" METHOD="POST">
        <input type="hidden" name="target_cd" value="{strip_tags($reserve_data.hotel_cd)}">
        <input type="hidden" name="reserve_cd" value="{strip_tags($reserve_data.reserve_cd)}">
        <input type="hidden" name="date_ymd[data]" value="{strip_tags($reserve_data.date_ymd)|date_format:'%Y-%m-%d'}">
        <input type="hidden" name="return_pass" value="{strip_tags($v->assign->return_pass)}" />

        <!-- 検索項目 -->
        <input type="hidden" name="date_ymd[after]" value="{strip_tags($v->assign->conditions.date_ymd.after)}">
        <input type="hidden" name="date_ymd[before]" value="{strip_tags($v->assign->conditions.date_ymd.before)}">
        <input type="hidden" name="search_reserve_cd" value="{strip_tags($v->assign->conditions.reserve_cd)}">
        <input type="hidden" name="page" value="{strip_tags($v->assign->page)}">
        <INPUT TYPE="submit" VALUE="料金変更">
      </FORM>
      @endif
    {{--TELキャンセル--}}
        <FORM ACTION="{$v->env.source_path}{$v->env.module}/htlreservemanagement/cancel1#guide" METHOD="POST">
        <input type="hidden" name="target_cd" value="{strip_tags($reserve_data.hotel_cd)}">
        <input type="hidden" name="reserve_cd" value="{strip_tags($reserve_data.reserve_cd)}">
        <input type="hidden" name="date_ymd[data]" value="{strip_tags($reserve_data.date_ymd)|date_format:'%Y-%m-%d'}">
        <input type="hidden" name="return_pass" value="{strip_tags($v->assign->return_pass)}" />

        <!-- 検索項目 -->
        <input type="hidden" name="date_ymd[after]" value="{strip_tags($v->assign->conditions.date_ymd.after)}">
        <input type="hidden" name="date_ymd[before]" value="{strip_tags($v->assign->conditions.date_ymd.before)}">
        <input type="hidden" name="search_reserve_cd" value="{strip_tags($v->assign->conditions.reserve_cd)}">
        <input type="hidden" name="page" value="{strip_tags($v->assign->page)}">
        <INPUT TYPE="submit" VALUE="電話キャンセル">
      </FORM>
    {{--無断不泊--}}
        <FORM ACTION="{$v->env.source_path}{$v->env.module}/htlreservemanagement/noshow1#guide" METHOD="POST">
        <input type="hidden" name="target_cd" value="{strip_tags($reserve_data.hotel_cd)}">
        <input type="hidden" name="reserve_cd" value="{strip_tags($reserve_data.reserve_cd)}">
        <input type="hidden" name="date_ymd[data]" value="{strip_tags($reserve_data.date_ymd)|date_format:'%Y-%m-%d'}">
        <input type="hidden" name="return_pass" value="{strip_tags($v->assign->return_pass)}" />

        <!-- 検索項目 -->
        <input type="hidden" name="date_ymd[after]" value="{strip_tags($v->assign->conditions.date_ymd.after)}">
        <input type="hidden" name="date_ymd[before]" value="{strip_tags($v->assign->conditions.date_ymd.before)}">
        <input type="hidden" name="search_reserve_cd" value="{strip_tags($v->assign->conditions.reserve_cd)}">
        <input type="hidden" name="page" value="{strip_tags($v->assign->page)}">
        <INPUT TYPE="submit" VALUE="無断不泊">
      </FORM>
  @endif
@endif
</td>
</tr>