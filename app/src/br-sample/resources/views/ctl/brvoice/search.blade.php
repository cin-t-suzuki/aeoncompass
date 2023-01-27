@extends('ctl.common.base')
@section('title', '会員からの意見　確認と返答')
@inject('service', 'App\Http\Controllers\ctl\BrVoiceController')

@section('page_blade')

{{-- getメソッドへ変更 --}}
{!! Form::open(['route' => ['ctl.brvoice.search'], 'method' => 'get']) !!}
  <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">

  @include ('ctl.brvoice._form')

  <INPUT TYPE="submit" NAME="i_btn" VALUE="投稿内容を表示する">
{!! Form::close() !!}
<br>
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message')
<br />
@if (!$service->is_empty($voice_data['values']))
    @include ('ctl.brvoice._list')
@else
<hr size="1" width="100%">
  該当する投稿はありませんでした。
@endif
<br>
<br>
@endsection