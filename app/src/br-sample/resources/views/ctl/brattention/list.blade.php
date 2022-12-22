{{--  css  --}}
@include('ctl.brattention._css')
{{--削除でいい？ {strip} --}}
  {{-- 提携先管理ヘッダー --}}
  @section('title', '施設管理TOP注目一覧')
  @include('ctl.common.base')

    {{--削除でいい？ <hr class="contents-margin" /> --}}

    <div style="text-align:left;">

    {{--削除でいい？ <hr class="contents-margin" /> --}}
    {{-- メッセージ --}}
    @section('message')
    @include('ctl.common.message', $messages)
    </div>
    <div style="width:960px; margin:auto">
    <h2 style="color:#444; text-align:center;">注目文言管理画面</h2>
    <p style="text-align:center;">
      この画面は表示の可否と掲載開始日によって並べ替えられています。<br>
    </p>
    {{-- 新規登録 --}}
    <div style="width:960px; overflow: hidden;">
        <div style="width:150px; float: right; margin-bottom: 5px;">
            {!! Form::open(['route' => ['ctl.brattention.new'], 'method' => 'post']) !!} 
              <small>
                <input type="submit" style="width:100px; height: 35px; border-radius: 4px; background-color: #a0d8ef" value="新規登録">
              </small>
            {!! Form::close() !!}
        </div>
    </div>
    <div style="clear:both;"></div>
    {{-- 一覧表示 --}}
    <table class="br-detail-list">
      <tr>
          <th style="text-align: center">掲載開始日</th>
          <th style="text-align: center">タイトル</th>
          <th style="text-align: center">表示</th>
          <th style="text-align: center">変更</th>
          <th style="text-align: center">プレビュー</th>
          <th style="text-align: center">更新日時</th>
      </tr>
    @foreach ($views->message_list as $message_list)
      <tr>
          <td nowrap @if ($message_list->display_flag == 0) style="background: #aaa" @elseif ($views->now_display_attention->attention_id == $message_list->attention_id) style="background: #F6FEC0" @endif>@if ($views->now_display_attention->attention_id == $message_list->attention_id)<div align="center" style="color:#FF0000" >★現在表示中★</div><br>@endif{{$message_list->start_date}} ～</td>
          <td @if ($message_list->display_flag == 0) style="background: #aaa" @elseif ($views->now_display_attention->attention_id == $message_list->attention_id) style="background: #F6FEC0" @endif>{{strip_tags($message_list->title)}}</td>
          <td @if ($message_list->display_flag == 0) style="background: #aaa" @elseif ($views->now_display_attention->attention_id == $message_list->attention_id) style="background: #F6FEC0" @endif>
          @foreach ($message_list->child_value as $child_value)
           <ul>
              <li><a href="{{$child_value['url']}}" target="_blank">{{$child_value['word']}}</a></li>
            </ul>
          @endforeach
          <td style="text-align:center;@if ($message_list->display_flag == 0)background: #aaa @elseif ($views->now_display_attention->attention_id == $message_list->attention_id)background: #F6FEC0 @endif">
            {!! Form::open(['route' => ['ctl.brattention.edit'], 'method' => 'post']) !!} 
              <input type="hidden" name="attention_id"      value="{{$message_list->attention_id}}" />
              <input type="hidden" name="send_edit" value= 1 />
              <input type="submit" style="border-radius: 4px; background-color: #FF6633" value=" 編集 ">
            {!! Form::close() !!}
            <br>
            {!! Form::open(['route' => ['ctl.brattention.change'], 'method' => 'post']) !!} 
              <input type="hidden" name="attention_id" value="{{$message_list->attention_id}}" />
              <input type="hidden" name="display_flag" value="{{$message_list->display_flag}}" />
              <input type="hidden" name="title" value="{{$message_list->title}}" />
              @if ($message_list->display_flag == 1)
              <input class="change" type="submit" style="border-radius: 4px; background-color: #FFDDFF " value=" 非表示 ">
              @else
              <input class="change" type="submit" style="border-radius: 4px; background-color: #3dfefa" value=" 再表示 ">
              @endif
            {!! Form::close() !!}
          </td>
          <td style="text-align:center;@if ($message_list->display_flag == 0)background: #aaa @elseif ($views->now_display_attention->attention_id == $message_list->attention_id)background: #F6FEC0 @endif">
            {{--書き換えあっている？ <p><a href= @if ($v->config->environment->status == "product") "http://www.bestrsv.com/rsv/"
              @else "http://www.dev.bestrsv.com/rsv/"@endif target="_blank">>></a></p> --}}
            {{-- TODO TOPページは動的。画面完成後にこちらにURL要設定。 --}}
            <p><a href= @if(App::environment('product')) ""
              @else "" @endif target="_blank">>></a></p>
          </td>
          <td nowrap @if ($message_list->display_flag == 0) style="background: #aaa" @elseif ($views->now_display_attention->attention_id == $message_list->attention_id) style="background: #F6FEC0" @endif>@include ('ctl.common._date',['timestamp' => $message_list->modify_ts , 'format' => 'ymdhi'])</td>
      </tr>
    @endforeach
    </table>
    </div>
    <hr class="contents-margin" />

  {{-- 提携先管理フッター --}}
  @section('title', 'footer')
  @include('ctl.common.footer')
  {{-- /提携先管理フッター --}}
{{--削除でいい？ {/strip} --}}