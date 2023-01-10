{{-- {strip} 削除していいか？--}}

{{-- 検索用パラメータのhiddenタグ作成（このコントローラ内で持ち回す形式） --}}
{{-- {capture name=search_hidden_vars} --}}
@section('search_hidden_vars')
  @foreach ($views->search_params as $key => $value)
    <input type="hidden" name="{{$key}}" value="{{$value}}" />
  @endforeach
@endsection
{{-- {/capture} --}}

@extends('ctl.common.base2')
@section('title', 'パートナー管理')

@section('content')

<div class="box-keyword-form">

  {{-- メッセージボックス --}}
  {{-- content内の書き方はこれであっているか？ --}}
  @include('ctl.common.message',['guides'=>$messages["guides"]])
  
  {{-- 入力フォーム --}}
  {!! Form::open(['route' => ['ctl.brpartnerKeyword.create'], 'method' => 'post']) !!}
    
    {{-- 入力フォーム：タイトル --}}
    <div class="form-br-title">
      <div class="form-br-title-back">
        <div class="form-br-title-conntents">キーワードの追加</div>
      </div>
    </div>
    
    {{-- 入力フォーム：内容 --}}
    <div class="form-br-box">
      <div class="form-br-box-back">
        <div class="form-br-box-contents">

          @include ('ctl.brpartnerKeyword._input',['form_params' => $views->form_params , 'display_status_selecter' => $views->display_status_selecter])
          
          <hr />
          
          <div class="menu">
            <input type="hidden" name="partner_cd" value="{{$views->partner['partner_cd']}}" />
            @yield('search_hidden_vars')
            <input type="submit" value="登録" />
          </div>
          
        </div>
      </div>
    </div>
    
  {!! Form::close() !!}
  
  <hr class="contents-margin" />
  
  {!! Form::open(['route' => ['ctl.brpartnerKeyword.index'], 'method' => 'post']) !!}
    <div class="br-back-main-menu-form">
      <input type="hidden" name="partner_cd" value="{{$views->partner['partner_cd']}}" />
      @yield('search_hidden_vars')
      <input type="submit" value="キーワード一覧へ" />
    </div>
  {!! Form::close() !!}
  
</div>

@endsection

{{-- {/strip} 削除していいか？--}}