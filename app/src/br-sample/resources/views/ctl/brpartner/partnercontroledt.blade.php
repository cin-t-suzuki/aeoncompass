@extends('ctl.common.base')
@section('title', 'PARTNER_CONTROL')

@section('page_blade')

  <script type="text/javascript" src="/js/jquery.js"></script>  {{-- script→jsへディレクトリ名変更 --}}
  <script type="text/javascript">
    <!--
      $(document).ready(function () {
        $('#add-input-address').on('click', function() { //.live→.onへ
          $(this).remove();
          var no_send_address = $('.jqs-send-report-address p:last-child').index();
          var no_send_address = no_send_address + 1;
          $('.jqs-send-report-address').append('<p>配信先アドレス' + no_send_address + '：<input type="text" name="result_email_list[]" value="" style="margin: 0px 12px; width: 300px;" /></p>');
          $('.jqs-send-report-address p:last-child').append('<input type="button" name="add_input_box" value="入力欄追加" id="add-input-address" />');
        });

        $('input[name="is_send_report"]:radio' ).change( function() {
          if ( $(this).val() == 1 ) {
            $('.jqs-send-report-address').show();
          } else {
            $('.jqs-send-report-address').hide();
          }
        });

        $('input[name="result_rpc_status"]:radio').change( function() {
          if ( $(this).val() != 0 ) {
            $('.jqs-report-url').show();
          } else {
            $('.jqs-report-url').hide();
          }
        });

        if ($('input[name="result_rpc_status"]:checked').val() != 0) {
          $('.jqs-report-url').show();
        } else {
          $('.jqs-report-url').css("display", "none");
        }

        if ($('input[name="is_send_report"]:checked').val() == 1) {
          $('.jqs-send-report-address').show();
        } else {
          $('.jqs-send-report-address').css("display", "none");
        }

      });
    //-->
  </script>


{{-- メッセージ --}}
@include('ctl.common.message',['guides'=>$messages["guides"],'errors'=>$messages["errors"]])

<br>
  <table border="1" cellspacing="0" cellpadding="1">
    <tr>
      <td  BGCOLOR="#EEFFEE" >提携先コード</td>
      <td>{{strip_tags($views->partners["partner_cd"])}}</td>
    </tr>
    <tr><td  BGCOLOR="#EEFFEE" >提携先名</td>
      <td>{{strip_tags($views->partners["partner_nm"])}}</big>
      </td>
    </tr>
  </table>
<p>更新</p>
{!! Form::open(['route' => ['ctl.brpartner.partnercontrolupd'], 'method' => 'post']) !!}
  <input type="hidden" name="partner_cd" value="{{strip_tags($views->partners["partner_cd"])}}" />
  <input type="hidden" name="partner_nm" value="{{strip_tags($views->partners["partner_nm"])}}" />
  <table cellspacing="0" cellpadding="10" border="1">
    <tr>
      <td bgcolor="#EEFFEE" nowrap>接続形態</td>
      <td>
        <select name="connect_cls">
          @foreach($views->connect_cls as $key => $item)
            <option value="{{$key}}" @if ($views->control_value["connect_cls"] == $key) selected="selected" @endif>{{$item}}</option>
          @endforeach
        </select>
      </td>
    </tr>
    <tr>
      <td bgcolor="#EEFFEE" nowrap>接続形態（詳細）</td>
      <td>
        <select name="connect_type">
          @foreach($views->connect_type as $key => $item)
            <option value="{{$key}}" @if ($views->control_value["connect_type"] == $key) selected="selected" @endif>{{$item}}</option>
          @endforeach
        </select>
      </td>
    </tr>
    <tr>
      <td bgcolor="#EEFFEE" nowrap>登録状態</td>
      <td>
        <SELECT NAME="entry_status">
          <option value="0" @if ($views->control_value["entry_status"] == 0) selected="selected" @endif>0:公開中</option>
          <option value="1" @if ($views->control_value["entry_status"] == 1) selected="selected" @endif>1:接続テスト中</option>
          <option value="9" @if ($views->control_value["entry_status"] == 9) selected="selected" @endif>9:解約</option>
        </SELECT>
      </td>
    </tr>
    <tr>
      <td bgcolor="#EEFFEE" nowrap>接続パスワード</td>
      <td>
        <input type="text" name="pw_user" value="{{strip_tags($views->control_value["pw_user"])}}" size="12" maxlength="12" />
      </td>
    </tr>
    <tr>
      <td bgcolor="#EEFFEE" nowrap>キャラクタセット</td>
      <td>
        <SELECT NAME="charset">
          <option value="sjis" @if ($views->control_value["charset"] == "sjis") selected="selected"@endif>シフトJIS</option>
          <option value="euc" @if ($views->control_value["charset"] == "euc") selected="selected"@endif>日本語EUC</option>
        </SELECT>
      </td>
    </tr>
    {{-- {* 新バージョンではこのフィールドは参照されないので非表示（※Partner_Layout2に移動した） *} --}}
    {{-- 以下null追記でいいか --}}
    @if ($views->control_value["version"]??null != 2)
      <tr>
        <td bgcolor="#EEFFEE" nowrap>掲示板表示設定</td>
        <td>
          <SELECT NAME="voice_status">
            <option value="0"  @if ($views->control_value["voice_status"] == 0) selected="selected" @endif>0:表示</option>
            <option value="1" @if ($views->control_value["voice_status"] == 1) selected="selected" @endif>1:非表示</option>
          </SELECT>
        </td>
      </tr>
    @else
      <input type="hidden" name="voice_status" value="{{$views->control_value["voice_status"]}}" />
    @endif
    <tr>
      <td bgcolor="#EEFFEE" nowrap>ページ有効時間</td>
      <td>
        <input type="text" name="page_timelimit" value="{{strip_tags($views->control_value["page_timelimit"])}}" size="4" maxlength="4" />
          分 （ゼロの時は無制限）<br>
        <font color="#FF0000">connect_type=（CLONE,CLOUT）のとき</font>
      </td>
    </tr>
    <tr>
      <td bgcolor="#EEFFEE" nowrap>料率</td>
      <td>
        <input type="text" name="rate" value="{{strip_tags($views->control_value["rate"])}}" size="3" maxlength="3" />％
      </td>
    </tr>
    <tr>
      <td bgcolor="#EEFFEE" nowrap>実績レポート</td>
      <td>
        <div> {{-- 以下のis_empty（３つ）はemptyへの書き換えでいい？ --}}
          <input type="radio" id="is_send_report_0" name="is_send_report" value="0" @if (empty($views->control_value["result_email"])) checked="checked" @endif /><label for="is_send_report_0">配信しない</label>
          <input type="radio" id="is_send_report_1" name="is_send_report" value="1" @if (!empty($views->control_value["result_email"])) checked="checked" @endif /><label for="is_send_report_1">配信する</label>
        </div>
        <div class="jqs-send-report-address" @if (empty($views->control_value["result_email"])) style="display: none;" @endif>
          <hr />
          {{-- foreachのfrom=','|explode:とnameはなしでいい？ --}}
          @foreach (explode(',',$views->control_value["result_email"]) as $result_email)
            <p>
              配信先アドレス{{$loop->iteration}}：<input type="text" name="result_email_list[]" value="{{$result_email}}" style="margin: 0px 12px; width: 300px;" />
              {{-- 以上以下loopあってる？ --}}
              @if($loop->last)
                <input type="button" name="add_input_box" value="入力欄追加" id="add-input-address" />
              @endif
            </p>
          @endforeach
        </div>
      </td>
    </tr>
    <tr>
      <td bgcolor="#EEFFEE" nowrap>お客様向けメール送信者名称</td>
      <td>
        <input type="text" name="email_from_nm" value="{{$views->control_value["email_from_nm"]}}" size="30" />
      </td>
    </tr>
    <tr>
      <td bgcolor="#EEFFEE" nowrap>予約時実績報告レポート配信</td>
      <td>
        {{-- 以下checkedのif文、===を==にしないと引っかからないが、書き換えていいか（3か所） --}}
        <div>
          <input  id="result_rpc_status_0" type="radio" name="result_rpc_status" value="0" @if ($views->control_value["result_rpc_status"] == '0') checked="checked" @endif />
          <label for="result_rpc_status_0">実績報告なし</label>
        </div>
        <div>
          <input  id="result_rpc_status_1" type="radio" name="result_rpc_status" value="1" @if ($views->control_value["result_rpc_status"] == '1') checked="checked" @endif />
          <label for="result_rpc_status_1">BTM向け</label>
        </div>
        <div class="jqs-report-url" @if ($views->control_value["result_rpc_status"] == '0')style="display: none;" @endif>
          <hr />
          <p>予約実績配信先URL：</p>
          <p><input type="text" name="result_rpc_url" value="{{$views->control_value["result_rpc_url"]}}" style="width: 500px;" /></p>
        </div>
      </td>
    </tr>
  </table>
  <p><INPUT TYPE="submit" VALUE="設定を変更する"></p>
  {!! Form::close() !!}

@endsection