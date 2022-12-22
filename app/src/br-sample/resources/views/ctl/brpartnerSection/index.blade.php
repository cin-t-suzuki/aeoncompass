@extends('ctl.common.base2')
@section('title', 'パートナー管理')

@section('content')

  <script type="text/javascript">
  <!--
  // TODO ↑消していいもの？コメントアウト？
    $(document).ready(function () {
      $('input.jqs-btn-section-delete').click(function(){
         return confirm($('.jqs-section-nm').eq($('input.jqs-btn-section-delete').index(this)).text() + '\n\nこの所属団体名称を削除します。\nよろしいですか？');
      });
    });
  -->
</script>
  {{-- メッセージボックス --}}
  {{-- content内の書き換えあっているか？ --}}
  @include('ctl.common.message',['guides'=>$messages["guides"]])
  
{{-- 検索用パラメータのhiddenタグ作成（このコントローラ内で持ち回す形式） --}}
{{-- {capture name=search_hidden_vars} --}}
@section('search_hidden_vars')
  @foreach ($views->search_params as $key => $value)
    <input type="hidden" name="{{$key}}" value="{{$value}}" />
  @endforeach
@endsection
{{-- {/capture} --}}
{{-- /検索用パラメータのhiddenタグ作成（このコントローラ内で持ち回す形式） --}}
{{-- 所属団体一覧 --}}
<table class="br-list">
  <tr>
    <th class="fc">表示順</th>
    <th>所属団体名称</th>
    <th>表示順操作</th>
    <th class="lc action-menu">
      {!! Form::open(['route' => ['ctl.brpartnerSection.new'], 'method' => 'post']) !!}
        <div>
          <input type="hidden" name="partner_cd" value="{{$views->form_params['partner_cd']}}" />
          {{-- {$smarty.capture.search_hidden_vars}書きすべて書き替え --}}
          @yield('search_hidden_vars')
          <input type="submit" value="追加" />
        </div>
      {!! Form::close() !!}
    </th>
  </tr>
  @foreach ($views->section_list as $section)
    {{-- ↑name=loop_sectionは削除した --}}
    <tr class="{cycle values='odd,even'}">
      <td>
        {{-- {$section.order_no|number_format} --}}
        {{$section->order_no}}
      </td>
      <td class="jqs-section-nm">
        {{$section->section_nm}}
      </td>
      <td>
        @if (!$loop->first){{-- 先頭行には「↑」、「先頭へ」ボタンは表示しない --}}
          {!! Form::open(['route' => ['ctl.brpartnerSection.head'], 'method' => 'post']) !!}
            <div class="form-menu-l">
              <input type="hidden" name="partner_cd" value="{{$section->partner_cd}}" />
              <input type="hidden" name="section_id" value="{{$section->section_id}}" />
              @yield('search_hidden_vars')
              <input type="submit" value="先頭" />
            </div>
          {!! Form::close() !!}
          {!! Form::open(['route' => ['ctl.brpartnerSection.up'], 'method' => 'post']) !!}
            <div class="form-menu-l">
              <input type="hidden" name="partner_cd" value="{{$section->partner_cd}}" />
              <input type="hidden" name="section_id" value="{{$section->section_id}}" />
              @yield('search_hidden_vars')
              <input type="submit" value="↑" />
            </div>
          {!! Form::close() !!}
        @endif
        @if (!$loop->last){{-- 末尾行には「↓」、「末尾へ」ボタンは表示しない --}}
          {!! Form::open(['route' => ['ctl.brpartnerSection.down'], 'method' => 'post']) !!}
            <div class="form-menu-l">
              <input type="hidden" name="partner_cd" value="{{$section->partner_cd}}" />
              <input type="hidden" name="section_id" value="{{$section->section_id}}" />
              @yield('search_hidden_vars')
              <input type="submit" value="↓" />
            </div>
          {!! Form::close() !!}
          {!! Form::open(['route' => ['ctl.brpartnerSection.tail'], 'method' => 'post']) !!}
            <div class="form-menu-l">
              <input type="hidden" name="partner_cd" value="{{$section->partner_cd}}" />
              <input type="hidden" name="section_id" value="{{$section->section_id}}" />
              @yield('search_hidden_vars')
              <input type="submit" value="末尾" />
            </div>
          {!! Form::close() !!}
        @endif
      </td>
      <td class="action-menu">
        {!! Form::open(['route' => ['ctl.brpartnerSection.delete'], 'method' => 'post']) !!}
          <div class="form-menu-r">
            <input type="hidden" name="partner_cd" value="{{$section->partner_cd}}" />
            <input type="hidden" name="section_id" value="{{$section->section_id}}" />
            @yield('search_hidden_vars')
            <input type="submit" value="削除" class="jqs-btn-section-delete" />
          </div>
        {!! Form::close() !!}
        {!! Form::open(['route' => ['ctl.brpartnerSection.edit'], 'method' => 'post']) !!}
          <div class="form-menu-r">
            <input type="hidden" name="partner_cd" value="{{$section->partner_cd}}" />
            <input type="hidden" name="section_id" value="{{$section->section_id}}" />
            @yield('search_hidden_vars')
            <input type="submit" value="編集" />
          </div>
        {!! Form::close() !!}
        <div class="clear"></div>
      </td>
    </tr>
  @endforeach
</table>
{{-- {if $v->assign->section_list|@count > 0} --}}
@if (count($views->section_list) > 0)
  <div class="br-list-tail">&nbsp;</div>
@else
  <div class="msg-box">
    <div class="msg-box-back">
      <div class="msg-box-contents msg-box-error">
        所属団体の登録はありません
      </div>
    </div>
  </div>
@endif
{{-- /所属団体一覧 --}}
<hr class="contents-margin" />
{{-- 戻るメニュー --}}
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
{{-- /戻るメニュー --}}
{{-- /コンテンツ --}}

@endsection