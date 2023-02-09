@extends('ctl.common.base')
@section('title', '送信内容')

@section('page_blade')

{{-- メッセージbladeの読込 --}}
@include('ctl.common.message')
<br>

@if ($disp == true)
FROM_ADDR:ベストリザーブ <rsv@bestrsv.com>
<br>TO_ADDR:****@********** {{-- 個人保護法の為 --}}
<br>RETURNPATH_ADDR:{{$send_mail_queue['return_path']}}
<br>SUBJECT:{{$send_mail_queue['subject']}}
<br>SEND_DATE:{{date('Y-m-d H:i:s', strtotime($send_mail_queue['send_dtm']))}}
<pre>{{$send_mail_queue['contents']}}</pre>
<br>
@endif

@endsection