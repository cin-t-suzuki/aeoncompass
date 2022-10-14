@section('title', 'パートナー管理')
@include('ctl.common.base')

<!-- ↓postからgetへ変更している -->
{!! Form::open(['route' => ['ctl.brpartner.searchlist'], 'method' => 'get']) !!} 
  <table border="1" cellspacing="0" cellpadding="3">
    <tr>
      <td bgcolor="#EEFFEE" nowrap align="center">表示条件</td>
      <td bgcolor="#EEFFEE" nowrap align="center">表示項目</td>
    </tr>
    <tr>
      <td nowrap valign="top">
        <table border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td nowrap>提携先コード</td> 
            <!-- GETだとパラメータないと以下二つは引っかかる -->
            <td nowrap><input type="text" name="partner_cd" value="{{$views->params["partner_cd"] ?? null }}" size="20" maxlength="20" /></td>
          <tr>
            <td nowrap>提携先名称</td>
            <td nowrap><input type="text" name="partner_nm" value="{{$views->params["partner_nm"] ?? null }}" size="20" maxlength="20" /></td>
          </tr>
          <tr>
            <td nowrap>接続形態</td>
            <td nowrap>
            <select size="1" name="connect_cls">
                <option value="" @if ($views->params["connect_cls"] ?? null  == '') selected="selected" @endif >すべて</option>
                <option value="inquiry" @if ($views->params["connect_cls"] ?? null  == 'inquiry') selected="selected" @endif >INQUIRY</option>
                <option value="gather" @if ($views->params["connect_cls"] ?? null  == 'gather') selected="selected" @endif >GATHER</option>
                <option value="dash" @if ($views->params["connect_cls"] ?? null  == 'dash') selected="selected" @endif >DASH</option>
                <option value="clone" @if ($views->params["connect_cls"] ?? null  == 'clone') selected="selected" @endif >CLONE</option>
                <option value="ccg" @if ($views->params["connect_cls"] ?? null  == 'ccg') selected="selected" @endif >CCG</option>
              </select>（CONNECT_CLS）
            </td>
          </tr>
          <tr>
            <td nowrap>
              接続形態（詳細）
            </td>
            <td>
              <select size="1" name="connect_type">
                <option value="" @if ($views->params["connect_type"] ?? null  == '') selected="selected" @endif>すべて</option>
                <option value="jorudan" @if ($views->params["connect_type"] ?? null  == 'jorudan') selected="selected" @endif>JORUDAN</option>
                <option value="inquiry" @if ($views->params["connect_type"] ?? null  == 'inquiry') selected="selected" @endif>INQUIRY</option>
                <option value="gather" @if ($views->params["connect_type"] ?? null  == 'gather') selected="selected" @endif>GATHER</option>
                <option value="dash" @if ($views->params["connect_type"] ?? null  == 'dash') selected="selected" @endif>DASH</option>
                <option value="clutch" @if ($views->params["connect_type"] ?? null  == 'clutch') selected="selected" @endif>CLUTCH</option>
                <option value="clout" @if ($views->params["connect_type"] ?? null  == 'clout') selected="selected" @endif>CLOUT</option>
                <option value="clone" @if ($views->params["connect_type"] ?? null  == 'clone') selected="selected" @endif>CLONE</option>
                <option value="clowduc" @if ($views->params["connect_type"] ?? null  == 'clowduc') selected="selected" @endif>CLOWDUC</option>
                <option value="yahoo" @if ($views->params["connect_type"] ?? null  == 'yahoo') selected="selected" @endif>YAHOO</option>
                <option value="livedoor" @if ($views->params["connect_type"] ?? null  == 'livedoor') selected="selected" @endif>LIVEDOOR</option>
                <option value="ccg-c" @if ($views->params["connect_type"] ?? null  == 'ccg-c') selected="selected" @endif>CCG-C</option>
                <option value="ccg" @if ($views->params["connect_type"] ?? null  == 'ccg') selected="selected" @endif>CCG</option>
              </select>（CONNECT_TYPE）

            </td>
          </tr>
        </table><INPUT TYPE="submit" VALUE="表示">
      </td>
      <td nowrap>
        <small><!-- GETだとすべてパラメータないと以下引っかかる、partner_disply[1]→partner_disply_1 -->
          <input type="checkbox" name="partner_disply_1" value="1" @if ($views->params["partner_disply_1"] ?? null == 1) checked="checked" @endif id="partner_disply_1"><label for="partner_disply_1">登録状態</label><br>
          <input type="checkbox" name="partner_disply_2" value="1" @if ($views->params["partner_disply_2"] ?? null == 1 || ($views->params["partner_disply_2"] ?? null == "" && $views->partner_search_flg == "")) checked="checked" @endif id="partner_disply_2"><label for="partner_disply[2]">接続形態</label><br>
          <input type="checkbox" name="partner_disply_3" value="1" @if ($views->params["partner_disply_3"] ?? null == 1 || ($views->params["partner_disply_3"] ?? null == "" && $views->partner_search_flg == "")) checked="checked" @endif id="partner_disply_3"><label for="partner_disply[3]">提携日</label><br>
          <input type="checkbox" name="partner_disply_4" value="1" @if ($views->params["partner_disply_4"] ?? null == 1 || ($views->params["partner_disply_4"] ?? null == "" && $views->partner_search_flg == "")) checked="checked" @endif id="partner_disply_4"><label for="partner_disply[4]">マスタ設定</label><br>
          <input type="checkbox" name="partner_disply_5" value="1" @if ($views->params["partner_disply_5"] ?? null == 1 || ($views->params["partner_disply_5"] ?? null == "" && $views->partner_search_flg == "")) checked="checked" @endif id="partner_disply_5"><label for="partner_disply[5]">外部向け画面</label><br>
        </small>
      </td>
    </tr>
  </TABLE>
  <input type='hidden' name='search_flg' value='true'>
  {!! Form::close() !!}
<br>
@if ($views->partner_search_flg == "true")
  @if (count($views->partner_list["values"]) != 0)
    <table border="1" cellspacing="0" cellpadding="0">
      <tr>
        <td bgcolor="#EEFFEE" nowrap align="center">提携先コード<br>提携先名称<br>システム名</td>
        @if ($views->params["partner_disply_1"] ?? null == 1)
          <td bgcolor="#EEFFEE" nowrap align="center">登録状態</td>
        @endif
        @if ($views->params["partner_disply_2"] ?? null == 1)
          <td bgcolor="#EEFFEE" nowrap align="center">接続形態<br>詳細</td>
          <td bgcolor="#EEFFEE" nowrap align="center">システム<br />バージョン</td>
        @endif
        @if ($views->params["partner_disply_3"] ?? null == 1)
        <td bgcolor="#EEFFEE" nowrap align="center">提携日</td>
        @endif
      @if ($views->params["partner_disply_4"] ?? null == 1)
        <td bgcolor="#EEFFEE" nowrap align="center"><small>PARTNER<br>設定</small></td>
        <td bgcolor="#EEFFEE" nowrap align="center"><small>CONTROL<br>設定</small></td>
        <td bgcolor="#EEFFEE" nowrap align="center"><small>所属団体<br>設定</small></td>
        <td bgcolor="#EEFFEE" nowrap align="center"><small>キーワード<br>設定</small></td>
      @endif
      @if ($views->params["partner_disply_5"] ?? null == 1)
        <td bgcolor="#EEFFEE" nowrap align="center">外部向け<br>画面</td>
      @endif
    </tr>

    @foreach ($views->partner_list["values"] as $partners)
      <tr>
        <td>
        {{$partners["partner_cd"]}}<br>
        {{$partners["partner_nm"]}}<br>
        {{$partners["system_nm"]}}<br>
        </td>
        @if ($views->params["partner_disply_1"] ?? null == 1)
        <td>
            @if ($partners["entry_status"] ==	 0)
              公開中
            @elseif ($partners["entry_status"] ==	 1)
              接続テスト中
            @elseif ($partners["entry_status"] ==	 9)
              解約
            @endif
         </td>
        @endif
        @if ($views->params["partner_disply_2"] ?? null == 1)
          <td>
            @if ($partners["connect_cls"] != "" && $partners["connect_type"] != "")
            {{$partners["connect_cls"]}}<br>
            {{$partners["connect_type"]}}
            @else
               <font color="red">未設定</font>
            @endif
          </td>
          @if ($partners["connect_cls"] === 'dash' || $partners["connect_cls"] === 'clone')
            <td style="text-align: center;">
              @if ($partners["version"] == 1) Ver.1
              @elseif ($partners["version"] == 2) Ver.2
              @else 不明
              @endif
            </td>
          @else
            <td style="text-align: center;">
              -
            </td>
          @endif
        @endif
        @if ($views->params["partner_disply_3"] ?? null == 1)
          <td>@include ('ctl.common._date',['timestamp' => $partners["tieup_ymd"] , 'format' => 'ymd'])</td>
        @endif
        @if ($views->params["partner_disply_4"] ?? null == 1)
          {!! Form::open(['route' => ['ctl.brpartner.partnerconf'], 'method' => 'get']) !!} <!-- ↓postからgetへ変更している -->
            <td align="center">
              <INPUT TYPE="submit" VALUE="変更">
              <input type="hidden" name="partner_cd" value="{{$partners["partner_cd"]}}" />
            </td>
          {!! Form::close() !!}
          <form action="{$v->env.source_path}{$v->env.module}/brpartner/partnercontroledt/" method="post">
            <td align="center">
              <INPUT TYPE="submit" VALUE="変更">
              <input type="hidden" name="partner_cd" value="{$v->helper->form->strip_tags($partner.partner_cd)}" />
              <input type="hidden" name="partner_nm" value="{$v->helper->form->strip_tags($partner.partner_nm)}" />
            </td>
          </form>
          <form action="{$v->env.source_path}{$v->env.module}/brpartnersection/" method="post">
            <td align="center">
              <input type="submit" value="変更">
              <input type="hidden" name="partner_cd"               value="{$v->helper->form->strip_tags($partner.partner_cd)}" />
              <input type="hidden" name="search_partner_cd"        value="{$v->helper->form->strip_tags($v->assign->params.partner_cd)}" />
              <input type="hidden" name="search_partner_nm"        value="{$v->helper->form->strip_tags($v->assign->params.partner_nm)|urlencode}" />
              <input type="hidden" name="search_connect_cls"       value="{$v->helper->form->strip_tags($v->assign->params.connect_cls)}" />
              <input type="hidden" name="search_connect_type"      value="{$v->helper->form->strip_tags($v->assign->params.connect_type)}" />
              <input type="hidden" name="search_partner_disply_1" value="{$v->helper->form->strip_tags($v->assign->params.partner_disply[1])}" />
              <input type="hidden" name="search_partner_disply_2" value="{$v->helper->form->strip_tags($v->assign->params.partner_disply[2])}" />
              <input type="hidden" name="search_partner_disply_3" value="{$v->helper->form->strip_tags($v->assign->params.partner_disply[3])}" />
              <input type="hidden" name="search_partner_disply_4" value="{$v->helper->form->strip_tags($v->assign->params.partner_disply[4])}" />
              <input type="hidden" name="search_partner_disply_5" value="{$v->helper->form->strip_tags($v->assign->params.partner_disply[5])}" />
            </td>
          </form>
          <form action="{$v->env.source_path}{$v->env.module}/brpartnerkeyword/" method="post">
            <td align="center">
              <input type="submit" value="変更">
              <input type="hidden" name="partner_cd"               value="{$v->helper->form->strip_tags($partner.partner_cd)}" />
              <input type="hidden" name="search_partner_cd"        value="{$v->helper->form->strip_tags($v->assign->params.partner_cd)}" />
              <input type="hidden" name="search_partner_nm"        value="{$v->helper->form->strip_tags($v->assign->params.partner_nm)|urlencode}" />
              <input type="hidden" name="search_connect_cls"       value="{$v->helper->form->strip_tags($v->assign->params.connect_cls)}" />
              <input type="hidden" name="search_connect_type"      value="{$v->helper->form->strip_tags($v->assign->params.connect_type)}" />
              <input type="hidden" name="search_partner_disply_1" value="{$v->helper->form->strip_tags($v->assign->params.partner_disply[1])}" />
              <input type="hidden" name="search_partner_disply_2" value="{$v->helper->form->strip_tags($v->assign->params.partner_disply[2])}" />
              <input type="hidden" name="search_partner_disply_3" value="{$v->helper->form->strip_tags($v->assign->params.partner_disply[3])}" />
              <input type="hidden" name="search_partner_disply_4" value="{$v->helper->form->strip_tags($v->assign->params.partner_disply[4])}" />
              <input type="hidden" name="search_partner_disply_5" value="{$v->helper->form->strip_tags($v->assign->params.partner_disply[5])}" />
            </td>
          </form>
        @endif
          @if ($views->params["partner_disply_5"] ?? null == 1)
          <form action="{$v->env.source_path}{$v->env.module}/ptntop/" method="post" target="_blank">
            <input type="hidden" name="partner_cd" value="{$v->helper->form->strip_tags($partner.partner_cd)}" />
            <td bgcolor="#FFDD99">
              <input type="submit" value="表示">
            </td>
          </form>
          @endif
      </tr>
   @endforeach
    </table>
  @else
    <font color="red">該当する予約が見つかりません。</font>
  @endif
@endif

@section('title', 'footer')
@include('ctl.common.footer')