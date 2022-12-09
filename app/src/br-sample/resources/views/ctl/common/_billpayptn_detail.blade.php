  <tr>
    <th rowspan="2">No.</th>
    <th rowspan="2">予約参照コード<br />予約日</th>
    <th rowspan="2">宿泊日</th>
    <th rowspan="2">ホテル名<br />部屋<br />プラン</th>
    <th rowspan="2">宿泊代表者</th>
    <th rowspan="2">予約状態<br />確認状態<br />確認日</th>
    <th rowspan="2">成約料金 @if ($views->customer['billpay_ptn_cd']=='P20140400001P')（税抜）@else（税込）@endif <br />消費税</th>
    @if ($views->customer['document_type'] == 2 || $views->customer['document_type'] == 3)
      <th colspan="2">お支払項目</th>
    @endif
    @if ($views->customer['document_type'] == 1 || $views->customer['document_type'] == 3)
      <th colspan="2">ご請求項目</th>
    @endif
  </tr>
  <tr>
    @if ($views->customer['document_type'] == 2 || $views->customer['document_type'] == 3)
      <th>率（％）</th>
      <th>広告宣伝料（税抜）</th>
    @endif
    @if ($views->customer['document_type'] == 1 || $views->customer['document_type'] == 3)
      <th>宿泊料（税込）</th>
      <th>取消料</th>
    @endif
  </tr>

  {{-- {foreach from=$v->assign->detail.values item=detail name=detail_list} --}}
  {{-- @foreach ($views->detail['values'] as $detail) --}}
  @foreach ($pager as $detail)
  <tr>
      <td style="text-align:right;">
        {{-- {$v->assign->pager->firstItemNumber+$smarty.foreach.detail_list.iteration-1|number_format} --}}
        {{number_format($pager->firstItem()+$loop->iteration-1)}}
      </td>
      <td >{{$detail['reserve_cd']}}<br />@include ('ctl.common._date',['timestamp' => $detail['reserve_dtm'] , 'format' => 'y/m/d'])</td>
      <td >@include ('ctl.common._date',['timestamp' => $detail['date_ymd'] , 'format' => 'y/m/d'])</td>
      <td >{{$detail['hotel_nm']}}<br />{{$detail['room_nm']}}（{{$detail['room_cd']}}）<br />{{$detail['plan_nm']}}（{{$detail['plan_cd']}}）</td>
      <td >{{$detail['guest_nm']}}</td>
      <td >
        @if ($detail['bill_type'] == 0)
            <span class="msg-text-info">予約</span>
            @if (!$service->is_empty($detail['operation_ymd']))
              <br />料金変更
              <br />@include ('ctl.common._date',['timestamp' => $detail['operation_ymd'] , 'format' => 'y/m/d'])
            @endif
        @else
            <span class="msg-text-error">キャンセル</span>
            @if ($detail['reserve_status'] == 2)
              <br />電話キャンセル
            @elseif ($detail['reserve_status'] == 4)
              <br />無断不泊
            @endif
            <br />@include ('ctl.common._date',['timestamp' => $detail['cancel_dtm'] , 'format' => 'y/m/d'])
        @endif
      </td>
      <td class="charge">@if ($views->customer['billpay_ptn_cd']=='P20140400001P'){{number_format($detail['bill_charge']-$detail['bill_charge_tax'])}} @else {{number_format($detail['bill_charge'])}} @endif <br />{{number_format($detail['bill_charge_tax'])}}</td>
    @if ($views->customer['document_type'] == 2 || $views->customer['document_type'] == 3)
      <td class="charge">{{number_format($detail['rate'],2)}}%</td>
      <td class="charge">{{number_format($detail['fee'])}}</td>
    @endif
    @if ($views->customer['document_type'] == 1 || $views->customer['document_type'] == 3)
      <td class="charge">{{number_format($detail['later_sales_charge'])}}</td>
      <td class="charge">{{number_format($detail['later_cancel_charge'])}}</td>
    @endif
  </tr>
  @endforeach
  
