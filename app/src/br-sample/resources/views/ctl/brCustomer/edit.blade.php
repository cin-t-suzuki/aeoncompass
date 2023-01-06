@extends('ctl.common.base')
@section('title', '精算先変更')
@inject('service', 'App\Http\Controllers\ctl\BrCustomerController')

@section('page_blade')
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message', $messages)
{!! Form::open(['route' => ['ctl.brCustomer.update'], 'method' => 'post']) !!}
@include ('ctl.brCustomer._input_customer')
  <input type="hidden" name="customer_id" value="{{strip_tags($views->customer_id)}}">
  <input type="hidden" name="keywords" value="{{strip_tags($views->keywords)}}">
  <input type="submit" value="変　　　　更">
{!! Form::close() !!}
<hr SIZE="1">
{!! Form::open(['route' => ['ctl.brCustomer.list'], 'method' => 'post']) !!}
  <small>
    <input type="hidden" name="keywords" value="{{strip_tags($views->keywords)}}">
    <input type="submit" value="請求先検索へ">
  </small>
{!! Form::close() !!}

<br />

@include ('ctl.brCustomer._log_customer_person_form')

<br />

@endsection