@extends('ctl.common.base')
@section('title', '精算先（登録施設）')
@inject('service', 'App\Http\Controllers\ctl\BrCustomerHotelController')

@section('page_blade')

<p><table border="1" cellpadding="4" cellspacing="0">
  <tr>
    <td bgcolor="#EEFFEE" >精算先</td>
    <td nowrap>{{$customer['customer_id']}}<br />{{$customer['customer_nm']}}<br />
@if (!$service->is_empty($customer['section_nm']) || !$service->is_empty($customer['person_nm'])){{$customer['section_nm']}} {{$customer['person_nm']}}<br />@endif
@if (!$service->is_empty($customer['tel']))TEL : {{$customer['tel']}} @endif
@if (!$service->is_empty($customer['fax']))FAX : {{$customer['fax']}}@endif
    </td>
  </tr>
</table></p>

@if (count($customer_hotel) < 1)
  対象の施設がありませんでした。
@else
    <table border="1" cellspacing="0" cellpadding="2">
      <tr>
        <th  bgcolor="#EEFFEE" >登録状態</th>
        <th  bgcolor="#EEFFEE" >施設コード<br />施設名</th>
        <th  bgcolor="#EEFFEE" >連絡先</th>
        <th  bgcolor="#EEFFEE" >詳細</th>
      </tr>
@foreach($customer_hotel as $customer_hotel)
      <tr>
        <td nowrap>
  @if ($customer_hotel->entry_status == '')新規発番中
  @elseif ($customer_hotel->entry_status == 0)公開中
  @elseif ($customer_hotel->entry_status == 1)登録作業中
  @else 解約
  @endif <br />
  @if ($customer_hotel->accept_status == 1)[受付中]
  @else <font color="#ff0000">[停止中]</font>
  @endif</td>
        <td nowrap>
  @if ($customer_hotel->entry_status == 0 && $customer_hotel->accept_status == 1)@else<font color="#996666">
    {{-- TODO：実装後に以下URLの修正（ホテル情報？） --}}
  @endif {{$customer_hotel->hotel_cd}}<br /><a href="/hotel/{{$customer_hotel->hotel_cd}}/" target="_blank" style="text-decoration: none; color:#000066;">{{$customer_hotel->hotel_nm}}</a>
  @if ($customer_hotel->stock_type == 1) <font color="#0000ff">[買]</font>@endif
  @if ($customer_hotel->entry_status == 0 && $customer_hotel->accept_status == 1) @else </font>
  @endif（{{$customer_hotel->pref_nm}}）</td>
        <td nowrap>{{$customer_hotel->person_post}} {{$customer_hotel->person_nm}}<br />
          TEL:{{$customer['tel']}} FAX:{{$customer['fax']}}
        </td>
        <td>{!! Form::open(['route' => ['ctl.brhotel.show'], 'method' => 'post']) !!}
            <input type="submit" value="表示">
            <input type="hidden" name="target_cd" value="{{$customer_hotel->hotel_cd}}" />
            {!! Form::close() !!}
        </td>
      </tr>
@endforeach
  </table>
登録施設 {{number_format(count($customer_hotel))}} 軒
@endif
<br />

@endsection
