@extends('ctl.common.base')
@section('title', '精算先変更')
@inject('service', 'App\Http\Controllers\ctl\BrCustomerController')

@section('page_blade')
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message')

以下の内容で変更しました。
  @include ('ctl.brCustomer._info_customer')
<p>
{!! Form::open(['route' => ['ctl.brCustomer.edit'], 'method' => 'get']) !!}
  <small>
<input type="hidden" name="customer_id" value="{{strip_tags($customer['customer_id'])}}" >
    <input type="submit" value="詳細情報">
  </small>
{!! Form::close() !!}
</p>
<p>
{!! Form::open(['route' => ['ctl.brCustomer.list'], 'method' => 'get']) !!}
  <small>
    <input type="submit" value="請求先検索へ">
    <input type="hidden" name="keywords" value="{{strip_tags($keywords)}}">
  </small>
{!! Form::close() !!}
</p>
<br />
@endsection