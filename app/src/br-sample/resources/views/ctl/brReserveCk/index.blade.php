@extends('ctl.common.base')
@section('title', '送客実績・料金変更')
@inject('service', 'App\Http\Controllers\ctl\BrReserveCkController')

@section('page_blade')

  @include ('ctl.brReserveCk._form')

{{-- メッセージbladeの読込 --}}
@include('ctl.common.message')
<br>
@endsection