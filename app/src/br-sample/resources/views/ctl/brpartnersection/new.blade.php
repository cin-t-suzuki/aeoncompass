@section('title', 'パートナー管理')
@include('ctl.common.base')

{{-- 検索用パラメータのhiddenタグ作成（このコントローラ内で持ち回す形式） --}}
{{-- {capture name=search_hidden_vars} --}}
@section('search_hidden_vars')
  @foreach ($views->search_params as $key => $value)
    <input type="hidden" name="{{$key}}" value="{{$value}}" />
  @endforeach
@endsection
{{-- {/capture} --}}
{{-- /検索用パラメータのhiddenタグ作成（このコントローラ内で持ち回す形式） --}}
{{-- コンテンツ --}}
<div class="box-section-form">

  {{-- メッセージボックス ※引数入れていない --}}
  @include('ctl.common.message')

  {!! Form::open(['route' => ['ctl.brpartnersection.create'], 'method' => 'post']) !!}
    {{-- 入力フォーム：タイトル --}}
    <div class="form-br-title">
      <div class="form-br-title-back">
        <div class="form-br-title-conntents">所属団体名称の追加</div>
      </div>
    </div>
    {{-- /入力フォーム：タイトル --}}

    {{-- 入力フォーム：内容 --}}
    <div class="form-br-box">
      <div class="form-br-box-back">
        <div class="form-br-box-contents">
          <p class="item-name">所属団体名称</p>
          <input type="text" name="section_nm" value="{{$views->form_params['section_nm']??null}}" size="50" />
          {{-- ??null追加でいいか --}}
          <hr class="item-margin" />
          <hr />
          <div class="menu">
            <input type="hidden" name="partner_cd" value="{{$views->form_params['partner_cd']}}" />
            @yield('search_hidden_vars')
            <input type="submit" value="登録" />
          </div>
        </div>
      </div>
    </div>
    {{-- /入力フォーム：内容 --}}
  {!! Form::close() !!}
  {{-- /入力フォーム --}}
  <hr class="contents-margin" />
  {{-- /一覧へ戻るフォーム --}}
  {!! Form::open(['route' => ['ctl.brpartnersection.index'], 'method' => 'post']) !!}
    <div class="br-back-main-menu-form">  
      <input type="hidden" name="partner_cd" value="{{$views->form_params['partner_cd']}}" />
      @yield('search_hidden_vars')
      <input type="submit" value="所属団体一覧へ" />
    </div>
  {!! Form::close() !!}
  {{-- 一覧へ戻るフォーム --}}
</div>
{{-- /コンテンツ --}}

@section('title', 'footer')
@include('ctl.common.footer')