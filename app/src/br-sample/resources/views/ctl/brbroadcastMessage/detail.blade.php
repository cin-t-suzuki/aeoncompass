{{-- 提携先管理ヘッダー  TODO include file='../_common/_br_header2.tpl' --}}
@section('title', '施設管理TOPお知らせ情報詳細画面')
@include('ctl.common.base')

{{-- css  --}}
@extends('ctl.brbroadcastMessage._css')
{{-- js  --}}
@extends('ctl.brbroadcastMessage._js')

@section('page_blade')
{{-- メッセージ --}}
@include('ctl.common.message', $messages)
  <!--hr class="contents-margin" /-->

  {{-- 入力フォーム --}}
  <div style="width:960px;">
    <div style="width:200px; float: right; margin-bottom: 15px;">
        
      {!! Form::open(['route' => ['ctl.brbroadcastMessage.index'], 'method' => 'get']) !!}
        <small>
          <input type="submit" style="width:110px; height: 30px; border-radius: 4px; background-color: #d4dcd6" value="<<一覧へ戻る">
        </small>
      {!! Form::close() !!}

     </div>
     <div style="clear:both;"></div>
  <h2 style="color:#444">施設管理TOPお知らせ情報詳細画面</h2>
  
{{-- 登録内容 --}}
@section('detail')
@include('ctl.brbroadcastMessage._info_broadcast_message',["form_params" => $views->broadcastMessageDetail])

</div>
{{-- TODO 提携先管理フッター --}}
{{--  '/_common/_br_footer.tpl' --}}

<!--hr class="contents-margin" /> 
  <hr class="contents-margin" /-->
  