@extends('ctl.common.base')
@section('title', '精算先登録')
@inject('service', 'App\Http\Controllers\ctl\BrCustomerController')

@section('page_blade')
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message', $messages)

以下の内容で登録しました。
  @include ('ctl.brCustomer._info_customer')
<p>
{!! Form::open(['route' => ['ctl.brCustomer.edit'], 'method' => 'post']) !!}
  <small>
<input type="hidden" name="customer_id" value="{{strip_tags($views->customer['customer_id'])}}" >
    <input type="submit" value="詳細情報">
  </small>
{!! Form::close() !!}
</p>
<p>
{!! Form::open(['route' => ['ctl.brCustomer.list'], 'method' => 'post']) !!}
  <small>
    <input type="submit" value="請求先検索へ">
    <input type="hidden" name="keywords" value="{{strip_tags($views->keywords)}}">
  </small>
{!! Form::close() !!}
</p>

@endsection