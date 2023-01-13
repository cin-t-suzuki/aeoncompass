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
  {{-- メッセージボックス --}}
  {{-- content内の書き方はこれであっているか？ --}}
  @include('ctl.common.message',['guides'=>$messages["guides"]])


<br>※TOP完成後要修正※イオンコンパスサイトの表示確認はこちら ⇒ <a href="" target="_blank">イオンコンパス確認用ページ</a>
<br>キーワードの並び順によっては文字が枠内からはみ出ることがあります。
<br>表示やリンク先に誤りがないかご確認をお願い致します。
<br>
<br>【注意】毎時00分になると静的ページに反映されます。
<br>00分直前に設定を修正すると設定中のものが反映される可能性があるので、00分直前の設定変更は避けた方が無難です。
<br>万が一、即時での反映が必要な場合は開発までご連絡ください。
<br>　

<table class="br-list">
  <tr>
    <th>表示順番</th>
    <th class="fc">キーワード</th>
    <th>検索値</th>
    <th>表示状態</th>
    <th>並べ替え</th>
    <th class="lc">
      {!! Form::open(['route' => ['ctl.brpartnerKeyword.new'], 'method' => 'post']) !!}
        <input type="hidden" name="partner_cd" value="{{$views->partner['partner_cd']}}" />
        @yield('search_hidden_vars')
        <input type="submit" value="追加" />
      {!! Form::close() !!}
    </th>
  </tr>
    @if (count($views->keyword_list) > 0)
    @foreach ($views->keyword_list as $keyword)
      <tr class="{cycle values='odd,even'}">
        <td>{{$keyword->order_no}}</td>
        <td>{{$keyword->word}}</td>
        <td>{{Str::limit($keyword->value,15)}}</td>
        {{-- ↑truncate:15を書き換え --}}
        <td>
          @if ($keyword->display_status == 1) 表示
          @else 非表示
          @endif
        </td>
        <td>
          {!! Form::open(['route' => ['ctl.brpartnerKeyword.sort'], 'method' => 'post']) !!}
          <input type="hidden" name="partner_cd" value="{{$views->partner['partner_cd']}}" />
          <input type="hidden" name="branch_no" value="{{$keyword->branch_no}}" />
          <input type="hidden" name="other_branch_no" value="{{$keyword->pre_branch_no}}" />
          <input type="hidden" name="other_order_no" value="{{$keyword->pre_order_no}}" />
          <input type="hidden" name="order_no" value="{{$keyword->order_no}}" />
          {{-- 以下if文追記、1番最初に↑ボタンはいらない（あるとエラー） --}}
          @if (!$loop->first)
          <input type="submit" name="order_no_up" value="↑" />
          @endif
          {!! Form::close() !!}

          {!! Form::open(['route' => ['ctl.brpartnerKeyword.sort'], 'method' => 'post']) !!}
          <input type="hidden" name="partner_cd" value="{{$views->partner['partner_cd']}}" />
          <input type="hidden" name="branch_no" value="{{$keyword->branch_no}}" />
          <input type="hidden" name="other_branch_no" value="{{$keyword->rear_branch_no}}" />
          <input type="hidden" name="other_order_no" value="{{$keyword->rear_order_no}}" />
          <input type="hidden" name="order_no" value="{{$keyword->order_no}}" />
          {{-- 以下if文追記、1番最後に↓ボタンはいらない（あるとエラー） --}}
          @if (!$loop->last)
          <input type="submit" name="order_no_down" value="↓" />
          @endif
          {!! Form::close() !!}
        </td>
        <td>
          {!! Form::open(['route' => ['ctl.brpartnerKeyword.edit'], 'method' => 'post']) !!}
            <input type="hidden" name="partner_cd" value="{{$views->partner['partner_cd']}}" />
            <input type="hidden" name="branch_no" value="{{$keyword->branch_no}}" />
            @yield('search_hidden_vars')
            <input type="submit" value="編集" />
          {!! Form::close() !!}
        </td>
      </tr>
    @endforeach
  @else
    <tr>
      <td colspan="2">
        <span class="msg-text-error">キーワードが登録されていません。</span>
      </td>
    </tr>
  @endif
</table>
<div class="br-list-tail">&nbsp;</div>

<hr class="contents-margin" />
{!! Form::open(['route' => ['ctl.brpartner.searchlist'], 'method' => 'post']) !!}
  <div class="br-back-main-menu-form">
    <input type="hidden" name="partner_cd"        value="{{$views->search_params['search_partner_cd'] ?? null}}" />
    <input type="hidden" name="partner_nm"        value="{{urldecode($views->search_params['search_partner_nm'] ?? null)}}" />
    <input type="hidden" name="connect_cls"       value="{{$views->search_params['search_connect_cls'] ?? null}}" />
    <input type="hidden" name="connect_type"      value="{{$views->search_params['search_connect_type'] ?? null}}" />
    <input type="hidden" name="partner_disply_1" value="{{$views->search_params['search_partner_disply_1'] ?? null}}" />
    <input type="hidden" name="partner_disply_2" value="{{$views->search_params['search_partner_disply_2'] ?? null}}" />
    <input type="hidden" name="partner_disply_3" value="{{$views->search_params['search_partner_disply_3'] ?? null}}" />
    <input type="hidden" name="partner_disply_4" value="{{$views->search_params['search_partner_disply_4'] ?? null}}" />
    <input type="hidden" name="partner_disply_5" value="{{$views->search_params['search_partner_disply_5'] ?? null}}" />
    {{-- 上記すべて??null追記で大丈夫か？[1]→_1に書き換えてるが大丈夫か --}}
    <input type="hidden" name="search_flg"        value="true" />
    <input type="submit" value="パートナー管理（社内）へ" />
  </div>
{!! Form::close() !!}
  

@endsection

{{-- {/strip} 削除していいか？--}}