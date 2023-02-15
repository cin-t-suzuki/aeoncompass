@extends('ctl.common.base')
@section('title', '施設情報：請求関連（請求先）')
@inject('service', 'App\Http\Controllers\ctl\BrCustomerHotelController')

@section('page_blade')
<!-- Hotel Information -->
<p><table border="1" cellpadding="4" cellspacing="0">
  <tr>
    <td bgcolor="#EEFFEE" >施設</td>

    <td nowrap><small>{{strip_tags($hotel['hotel_cd'])}}</small><br />{{strip_tags($hotel['hotel_nm'])}}<br><small>@if (!$service->is_empty($hotel['tel']))TEL : {{strip_tags($hotel['tel'])}}&nbsp;@endif @if (!$service->is_empty($hotel['fax']))FAX : {{strip_tags($hotel['fax'])}}@endif</small></td>
  </tr>
</table></p>

<p>
  <table border="1" cellspacing="0" cellpadding="3">
    <tr>
      <td  bgcolor="#EEFFEE" >精算先名称</td>
      <td>
        @if (!$service->is_empty($customer_hotel))
          {{strip_tags($customer_hotel->customer_id)}}&nbsp;[{{strip_tags($customer_hotel->customer_nm)}}]
      </td>
      {!! Form::open(['route' => ['ctl.brCustomer.edit'], 'method' => 'get', 'target' => '_blank']) !!}
        <input type="hidden" name="customer_id" value="{{strip_tags($customer_hotel->customer_id)}}" >
        <td><small><input type="submit" value="詳細情報"></small><br />
        @else
          [未登録]
        @endif
        </td>
      {!! Form::close() !!}
    </tr>
  </TABLE>
</p>
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message', $messages)
<b>精算先の変更</b>
{!! Form::open(['route' => ['ctl.brCustomerHotel.list'], 'method' => 'get']) !!}
  <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}" />
  <font color="#0000FF">精算先を検索して結果から登録します。</font><table border="1" cellspacing="0" cellpadding="2">
    <tr>
      <td  bgcolor="#EEFFEE" >精算先名称</td>
      <td>
        <select size="1" name="like_type">
          <option value="">あいまい</option>
          <option value="front"@if ($like_type == "front") selected @endif>前方一致</option>
          <option value="back"@if ($like_type == "back") selected @endif>後方一致</option>
        </select>
        <INPUT TYPE="text" NAME="keyword" SIZE="30" MAXLENGTH="30" VALUE="{{strip_tags($keyword)}}">
        <INPUT TYPE="submit" VALUE="検索">
        <small>最大
          <INPUT TYPE="text" NAME="limit" SIZE="4" MAXLENGTH="4" VALUE="{{strip_tags($limit)}}">
          件表示
        </small>
      </td>
    </tr>
  </table>
{!! Form::close() !!}
@if ($customer_list['cnt'] < 1)
@else
  {{strip_tags($customer_list['cnt'])}}件見つかりました。
  @if ($limit > 0)
    <table border="1" cellspacing="0" cellpadding="2">
      <tr>
        <th  bgcolor="#EEFFEE" >施設への精算先を変更</th>
        <th  bgcolor="#EEFFEE" >精算先名称</th>
        <th  bgcolor="#EEFFEE"  COLSPAN="2">精算先</th>
      </tr>
    @endif
    {{--書き替えあっている？ {section start = 0 loop = $v->assign->customer_list.values|@count max = $v->assign->limit name = customer_list} --}}
    @for ($customer_list = 0; $customer_list < count($customer_list['values']) && $customer_list <= $limit ; $customer_list++)
      <tr>
        {!! Form::open(['route' => ['ctl.brCustomerHotel.setting'], 'method' => 'post']) !!}
          <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}" />
          <input type="hidden" name="limit" value="{{strip_tags($limit)}}" />
          <input type="hidden" name="keyword" value="{{strip_tags($keyword)}}" />
          <input type="hidden" name="like_type" value="{{strip_tags($like_type)}}" />
          <input type="hidden" name="customer_id" value="{{strip_tags($customer_list['values'][$customer_list]->customer_id)}}" >
          <td><small>
            <input type="submit" value="精算先を変更する">
          </small></td>
        {!! Form::close() !!}
        {{-- $smarty.section.customer_list.index　→　customer_listでいいか？ --}}
        <td nowrap>({{strip_tags($customer_list['values'][$customer_list]->customer_id)}}){{strip_tags($customer_list['values'][$customer_list]->customer_nm)}}</td>
        {!! Form::open(['route' => ['ctl.brCustomer.edit'], 'method' => 'get', 'target' => '_blank']) !!}
          <input type="hidden" name="customer_id" value="{{strip_tags($customer_list['values'][$customer_list]->customer_id)}}" >
          <td><small>
            <input type="submit" value="詳細情報">
          </small><br /></td>
        {!! Form::close() !!}
      </tr>
    @endfor
  </TABLE>
@endif
<br />
@if (count($customer_list['values']) > $limit)
  <font color="#ff0000">{{strip_tags($limit)}}件を越えたので表示を中断しました。</font>
  <br />
@endif
<hr size="1">
<div align="right">
  {!! Form::open(['route' => ['ctl.brCustomer.list'], 'method' => 'get', 'target' => '_blank']) !!}
    精算先が登録されていない場合は↓<br>
    <input type="submit" value="精算先の新規作成">
  {!! Form::close() !!}
</div>
<hr size="1">
{!! Form::open(['route' => ['ctl.brhotel.show'], 'method' => 'post']) !!}
  <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}" />
  <small>
    <input type="submit" value="施設情報変更へ">
  </small>
{!! Form::close() !!}
<br />
@endsection