{{-- TODO 提携先管理ヘッダー '../_common/_br_header2.tpl' --}}
@extends('ctl.common.base')

@inject('service', 'App\Http\Controllers\ctl\BrbroadcastMessageController')

{{-- css  --}}
@extends('ctl.brbroadcastMessage._css')

{{-- js --}}
@extends('ctl.brbroadcastMessage._js')

@section('title', '施設管理TOPお知らせ情報一覧')

    <!--hr class="contents-margin" /-->

  

    <!--hr class="contents-margin" /-->

	@section('page_blade')
	{{-- メッセージbladeの読込 --}}
	@include('ctl.common.message', $messages)

   
    <div style="width:960px;">
      <h2 style="color:#444">施設管理TOPお知らせ情報画面</h2>

      {{-- 新規登録 --}}
      <div style="width:960px; overflow: hidden;">
          <div style="width:150px; float: right; margin-bottom: 5px;">
            {!! Form::open(['route' => ['ctl.brbroadcastMessage.new'], 'method' => 'post']) !!}
                <small>
                  <input type="submit" style="width:100px; height: 35px; border-radius: 4px; background-color: #a0d8ef" value="新規登録">
                </small>
              {!! Form::close() !!}
          </div>
          <div style="width:600px; float: left; margin-bottom: 5px; text-align: left;">
              <p>※おしらせが施設管理TOP画面に表示される順番はお知らせ表示期間の<strong><span style="color: red;">表示開始日時の降順</span></strong>です</p>
          </div>
      </div>
      
      <div style="clear:both;"></div>

      {{-- 一覧表示 --}}
      <table class="br-detail-list">
        <tr>
            <th style="text-align: center">ID</th>
            <th style="text-align: center">お知らせ欄タイトル</th>
            <th style="text-align: center">お知らせ表示期間　　　　　　　</th>
            <th style="text-align: center">ページ上部表示期間　　　　　　</th>
            <th style="text-align: center">詳細</th>
            <th style="text-align: center">変更</th>
            <th style="text-align: center">非表示</th>
        </tr>

        @foreach ($views->broadcastMessages as $message_list)
        <tr>
          <td>{{ strip_tags($message_list->brbroadcast_id) }}</td>

          <td>{{ strip_tags($message_list->title) }}</td>
          @php
            $nowDate=\Carbon\Carbon::now()->format("Y/m/d H:i:s")
          @endphp

          @if ($service->is_empty($message_list->accept_e_dtm))
            <td>
          @elseif ($message_list->accept_e_dtm < $nowDate )
            <td BGCOLOR=#D8D8D8>
          @else
            <td>
          @endif
          {{$message_list->accept_s_dtm}}～<br>{{$message_list->accept_e_dtm}}</td>
          @if ($service->is_empty($message_list->accept_header_e_dtm))
            <td>
          @elseif ($message_list->accept_header_e_dtm < $nowDate )
            <td BGCOLOR=#D8D8D8>
          @else
            <td>
          @endif
          {{$message_list->accept_header_s_dtm}}～<br>{{$message_list->accept_header_e_dtm}}</td>
          <td style="text-align:center;">
            {!! Form::open(['route' => ['ctl.brbroadcastMessage.detail'], 'method' => 'post']) !!}
                <input type="submit" style="border-radius: 4px; background-color: #dcd3b2" value="詳細">
                <input type="hidden" name="brbroadcast_id" value="{{ $message_list->brbroadcast_id }}">
            {!! Form::close() !!}
          </td>
          <td style="text-align:center;">
            {!! Form::open(['route' => ['ctl.brbroadcastMessage.edit'], 'method' => 'post']) !!}
                <input type="hidden" name="brbroadcast_id" value="{{ $message_list->brbroadcast_id }}">
                <input class="destroy" type="submit" style="border-radius: 4px; background-color: #f6bfbc" value=" 変更 ">
            {!! Form::close() !!}
          </td>
<!--TODO-->
          <td style="text-align:center;">
            {!! Form::open(['route' => ['ctl.brbroadcastMessage.destroy'], 'method' => 'post']) !!}
                <input type="hidden" name="brbroadcast_id" value="{{ $message_list->brbroadcast_id }}">
                <input class="destroy" type="submit" style="border-radius: 4px; background-color: #aaa" value=" 削除 ">
            {!! Form::close() !!}
<!--TODO-->
          </td>        
        </tr>
        @endforeach
      </table>

    </div>
    <!--hr class="contents-margin" /-->

  {{-- 提携先管理フッター --}}
  {{-- TODO include file='../_common/_br_footer.tpl'}}
  {{-- /提携先管理フッター --}}

@endsection
