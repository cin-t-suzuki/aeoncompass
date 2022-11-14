{{-- {strip} 削除していいか？--}}

{{-- 検索用パラメータのhiddenタグ作成（このコントローラ内で持ち回す形式） --}}
{{-- {capture name=search_hidden_vars} --}}
@section('search_hidden_vars')
  @foreach ($views->search_params as $key => $value)
    <input type="hidden" name="{{$key}}" value="{{$value}}" />
  @endforeach
@endsection
{{-- {/capture} --}}

@section('title', 'パートナー管理')
@include('ctl.common.base')

<div class="box-keyword-form">

  {{-- メッセージボックス --}}
  {{-- メッセージ/TODO 他と書き方違う --}}
  @section('message')
  @include('ctl.common.message',['guides'=>$messages["guides"]])
  
  {{-- 入力フォーム --}}
  {!! Form::open(['route' => ['ctl.brpartnerKeyword.update'], 'method' => 'post']) !!}
    
    {{-- 入力フォーム：タイトル --}}
    <div class="form-br-title">
      <div class="form-br-title-back">
        <div class="form-br-title-conntents">キーワードの編集</div>
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
            <input type="hidden" name="branch_no"    value="{{$views->form_params['branch_no']}}" />
            <input type="hidden" name="is_update"    value="1" />
            @yield('search_hidden_vars')
            <input type="submit" value="更新" />
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
  
  <br>【入力方法】
  <br>①キーワードの文言で検索したい場合
  <br>　→ 検索値を未入力にする。
  <br>②キーワードと別の文言で検索したい場合
  <br>　→ 検索値に【/keywords/?keywords=○○○】を入力する。　
  <br>　　（例）蟹で検索：　/keywords/?keywords=蟹　を入力
  <br>③ベストリザーブ上の別ページにとばしたい場合
  <br>　→ 検索値にwww.bestrsv.com の/以降を入力する。　
  <br>　　（例）ベストプライスを検索：　/hotel/highrank/　を入力
  <br>　
</div>

@section('title', 'footer')
@include('ctl.common.footer')

{{-- {/strip} 削除していいか？--}}