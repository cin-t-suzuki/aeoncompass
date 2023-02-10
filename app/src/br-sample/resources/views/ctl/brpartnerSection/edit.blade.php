@extends('ctl.common.base2')
@section('title', 'パートナー管理')

@section('content')

{{-- 検索用パラメータのhiddenタグ作成（このコントローラ内で持ち回す形式） --}}
{{-- {capture name=search_hidden_vars} --}}
@section('search_hidden_vars')
  @foreach ($views->search_params as $key => $value)
    <input type="hidden" name="{{$key}}" value="{{$value}}" />
  @endforeach
@endsection
{{-- /検索用パラメータのhiddenタグ作成（このコントローラ内で持ち回す形式） --}}

{{-- コンテンツ --}}
<div class="box-section-form">
  
  {{-- メッセージボックス ※引数入れていない --}}
{{-- content内の書き換えあっているか？ --}}
  @include('ctl.common.message',['guides'=>$messages["guides"]])

  {{-- 入力フォーム --}}
  {!! Form::open(['route' => ['ctl.brpartnerSection.update'], 'method' => 'post']) !!}
    {{-- 入力フォーム：タイトル --}}
    <div class="form-br-title">
      <div class="form-br-title-back">
        <div class="form-br-title-conntents">所属団体名称の編集</div>
      </div>
    </div>
    {{-- /入力フォーム：タイトル --}}
    {{-- 入力フォーム：内容 --}}
    <div class="form-br-box">
      <div class="form-br-box-back">
        <div class="form-br-box-contents">
          <p class="item-name">所属団体名称</p>
          <input type="text" name="section_nm" value="{{$views->form_params['section_nm']}}" size="50" />
          <hr class="item-margin" />
          <hr />
          <div class="menu">
            <input type="hidden" name="partner_cd" value="{{$views->form_params['partner_cd']}}" />
            <input type="hidden" name="section_id" value="{{$views->form_params['section_id']}}" />
            @yield('search_hidden_vars')
            <input type="submit" value="更新" />
          </div>
        </div>
      </div>
    </div>
    {{-- /入力フォーム：内容 --}}
  {!! Form::close() !!}
  {{-- /入力フォーム --}}
  <hr class="contents-margin" />
  {{-- /一覧へ戻るフォーム --}}
  {!! Form::open(['route' => ['ctl.brpartnerSection.index'], 'method' => 'post']) !!}
    <div class="br-back-main-menu-form">  
      <input type="hidden" name="partner_cd" value="{{$views->form_params['partner_cd']}}" />
      @yield('search_hidden_vars')
      <input type="submit" value="所属団体一覧へ" />
    </div>
  {!! Form::close() !!}
  {{-- 一覧へ戻るフォーム --}}
</div>
{{-- /コンテンツ --}}

@endsection