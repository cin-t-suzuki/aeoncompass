@extends('ctl.common.base')
@section('title', '口座振替　追加処理')
@inject('service', 'App\Http\Controllers\ctl\BrAdditonalZenginController')

@section('page_blade')
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message', $messages)

<div style="line-height:150%" style="margin:1em 1em">


  <strong>口座振替追加額　一覧</strong><br>

  @include('ctl.brAdditionalZengin._form')

  {{ Form::open(['route' => 'ctl.brAdditionalZengin.search', 'method' => 'post']) }}
 <small style="color:#336">施設の口座振替額に追加処理を行います。</small><input type="submit" value="追加" style="width: 80px;">
  {{ Form::close() }}
  <hr size="0" style="margin:1em 0">

<span id="zengin_list"></span>

</div>

@endsection
