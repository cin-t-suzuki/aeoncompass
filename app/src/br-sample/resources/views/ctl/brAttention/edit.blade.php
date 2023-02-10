{{--削除でいい？ {strip} --}}
  {{-- 提携先管理ヘッダー --}}
  @extends('ctl.common.base2')

  @section('title', 'BRサイトTOP注目変更画面')

  {{--  css  --}}
  @section('headScript')
    @include('ctl.brAttention._css')
  @endsection

  @section('content')

  {{-- メッセージ --}}
  {{-- content内の書き換えあっている？ --}}
  @include('ctl.common.message', $messages)

   <hr class="contents-margin" />

  <div style="width:960px;">
    <div style="width:200px; float: right; margin-bottom: 15px;">
         {!! Form::open(['route' => ['ctl.brAttention.list'], 'method' => 'get']) !!} 
           <small>
             <input type="submit" style="width:110px; height: 30px; border-radius: 4px; background-color: #d4dcd6"value="<<一覧へ戻る">
           </small>
         {!! Form::close() !!}
     </div>
     <div style="clear:both;"></div>
  {{-- 入力フォーム --}}
  {!! Form::open(['route' => ['ctl.brAttention.update'], 'method' => 'post']) !!} 
      <h2 style="color:#444">注目編集画面</h2>
   <input type="hidden" name="attention_id" value="{{$views->form_params['attention_id']}}">
    @include('ctl.brAttention._edit_brattention_message')
    <hr class="contents-margin" />
    <input type="submit" style="width:80px; height: 35px; border-radius: 4px; background-color: #c3d825" value="変更">
  {!! Form::close() !!}
  </div>
  <hr class="contents-margin" />

  @endsection
{{--削除でいい？ {/strip} --}}