@extends('ctl.common.base')
@section('title', 'MAIL_BUFFER一覧')
@inject('service', 'App\Http\Controllers\ctl\BrMailBufferController')

@section('page_blade')

{{-- メッセージbladeの読込 --}}
@include('ctl.common.message')

{{-- 検索フォーム --}}
@include ('ctl.brMailBuffer._form')

<hr size="1">
<br>

@if (count($send_mail_queues['values']?? []) != 0)
  {{-- 一覧  --}}
  @include ('ctl.brMailBuffer._list')
@endif

@endsection