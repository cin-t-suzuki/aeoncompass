
@extends('ctl.common.base')
@section('title', 'セキュリティログ一覧')


@section('page_blade')

  <!-- エラーメッセージの表示部分 -->
  @if (!empty($errors) && is_array($errors) && count($errors) > 0)
  <div style="border-style:solid;border-color:#f00;border-width:1px;padding:6px;background-color:#fee;">
      @foreach ($errors as $error)
          <div>{!! nl2br($error) !!}</div>
      @endforeach
  </div>
  @endif
  <br>
  <!-- 検索フォーム部分 -->
  @include('ctl.brsecurity._form')

  <hr size="1">
  <br>

  @if(isset($log_securities))
    <!-- 一覧表示部分 -->
    @include('ctl.brsecurity._list')
  @endif


@endsection