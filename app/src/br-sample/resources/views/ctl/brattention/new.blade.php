{{--  css  --}}
@include('ctl.brattention._css')
{{-- 削除でいい？{strip} --}}

@inject('service', 'App\Http\Controllers\ctl\BrattentionController')

  {{-- 提携先管理ヘッダー --}}
  @section('title', '施設管理TOP注目登録画面')
  @include('ctl.common.base')

    {{-- メッセージ --}}
    @section('message')
    @include('ctl.common.message', $messages)

   <hr class="contents-margin" />

  <div style="width:960px;">
    <div style="width:200px; float: right; margin-bottom: 15px;">
         {!! Form::open(['route' => ['ctl.brattention.list'], 'method' => 'post']) !!} 
           <small>
             <input type="submit" style="width:110px; height: 30px; border-radius: 4px; background-color: #d4dcd6"value="<<一覧へ戻る">
           </small>
         {!! Form::close() !!}
     </div>
     <div style="clear:both;"></div>
  {{-- 入力フォーム --}}
  {!! Form::open(['route' => ['ctl.brattention.create'], 'method' => 'post']) !!} 
      <h2 style="color:#444">注目登録画面</h2>
    {{-- 精算先内容 --}}
    @include('ctl.brattention._input_brattention_message')

    <hr class="contents-margin" />
    <input type="submit" id="submit-new" style="width:80px; height: 35px; border-radius: 4px; background-color: #c3d825" value="登録">

  {!! Form::close() !!}
  </div>
  <hr class="contents-margin" />

  {{-- 提携先管理フッター --}}
  @section('title', 'footer')
  @include('ctl.common.footer')
  {{-- /提携先管理フッター --}}
{{-- 削除でいい？{/strip} --}}