{{-- MEMO: 移植元 public\app\ctl\views\brhotel\_input_state_form.tpl --}}

<table border="1" cellspacing="0" cellpadding="3">


    <tr>
      <td  bgcolor="#EEFFEE" >施設コード</td>
      <td>
        {$v->helper->form->strip_tags($v->assign->target_cd)}
      </td>
      <td><br /></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >通知媒体</td>
      <td>
        <INPUT TYPE="checkbox" NAME="notify_device[]" VALUE="1" {if is_empty($v->assign->notify_device)}checked{else}{foreach from=$v->assign->notify_device item=notify_device}{if $notify_device == 1}checked{/if}{/foreach}{/if} id="nd1">
          <LABEL for="nd1">
            ファックス
          </LABEL>
        <INPUT TYPE="checkbox" NAME="notify_device[]" VALUE="2" {foreach from=$v->assign->notify_device item=notify_device}{if $notify_device == 2}checked{/if}{/foreach} id="nd2">
          <LABEL for="nd2">
            電子メール
          </label>
        <INPUT TYPE="checkbox" NAME="notify_device[]" VALUE="4" {foreach from=$v->assign->notify_device item=notify_device}{if $notify_device == 4}checked{/if}{/foreach} id="nd3">
          <LABEL for="nd3">
            オペレータ連絡
          </label>
        <INPUT TYPE="checkbox" NAME="notify_device[]" VALUE="8" {foreach from=$v->assign->notify_device item=notify_device}{if $notify_device == 8}checked{/if}{/foreach} id="nd4">
          <LABEL for="nd4">
            リンカーン
          </label>
      </td>
      <td><small>選択 <font color="#ff0000">※ここを変更する場合は2行下の通知ステータスも必ず確認すること</font></small></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE">予約情報プッシュ通知（ねっぱん）</td>
      <td>
        <label><input type="radio" name="Hotel_Notify[neppan_status]" value="1" {if $v->assign->hotel_notify.neppan_status === '1'  }checked="checked"{/if} />通知する</label>
        <label><input type="radio" name="Hotel_Notify[neppan_status]" value="0" {if $v->assign->hotel_notify.neppan_status === '0'  }checked="checked"{/if} />通知しない</label>
        <label><input type="radio" name="Hotel_Notify[neppan_status]" value=""  {if is_empty($v->assign->hotel_notify.neppan_status)}checked="checked"{/if} />通知しない(※連動時に「通知する」に自動切替)</label>
      </td>
      <td><small>選択</small></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >通知ステータス</td>
      <td>
        <INPUT TYPE="radio" NAME="Hotel_Notify[notify_status]" VALUE="1" {if $v->assign->hotel_notify.notify_status == 1 || is_empty($v->assign->hotel_notify.notify_status)} CHECKED {/if} id="j4">
          <LABEL for="j4">
            通知する
          </label>
        <INPUT TYPE="radio" NAME="Hotel_Notify[notify_status]" VALUE="0" {if $v->assign->hotel_notify.notify_status == 0 && !is_empty($v->assign->hotel_notify.notify_status)} CHECKED {/if} id="j5">
          <LABEL for="j5">
            通知しない
          </LABEL>
      </td>
      <td><small>選択<font color="#ff0000">※ここが「通知しない」だとファックス、電子メール、リンカーンの通知はされません</font></small></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >通知電子メールアドレス</td>
      <td>
        <INPUT TYPE="text" NAME="Hotel_Notify[notify_email]" value="{$v->helper->form->strip_tags($v->assign->hotel_notify.notify_email)}" SIZE="50" MAXLENGTH="50">
      </td>
      <td><br /></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >通知ファックス番号</td>
      <td>
        <INPUT TYPE="text" NAME="Hotel_Notify[notify_fax]" value="{$v->helper->form->strip_tags($v->assign->hotel_notify.notify_fax)}" SIZE="20" MAXLENGTH="15">
      </td>
      <td><small>xxxx-xxxx-xxxx</small></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >FAXPR</td>
      <td>
        <INPUT TYPE="radio" NAME="Hotel_Notify[faxpr_status]" VALUE="1" {if $v->assign->hotel_notify.faxpr_status == 1 || is_empty($v->assign->hotel_notify.faxpr_status)} CHECKED {/if} id="j9">
          <LABEL for="j9">
            表示する
          </label>
        <INPUT TYPE="radio" NAME="Hotel_Notify[faxpr_status]" VALUE="0" {if $v->assign->hotel_notify.faxpr_status == 0 && !is_empty($v->assign->hotel_notify.faxpr_status)} CHECKED {/if} id="j8">
          <LABEL for="j8">
            表示しない
          </LABEL>
      </td>
      <td><small>選択</small></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >仕入タイプ</td>
      <td>
  { if $v->assign->hotel_control.stock_type == "2"} 一括受託（東横イン）<input type="hidden" name="Hotel_Control[stock_type]" value="2" />
  {  else}
        <label>
        <INPUT TYPE="radio" NAME="Hotel_Control[stock_type]" VALUE="0" {if $v->assign->hotel_control.stock_type == 0} CHECKED {/if} id="i1">
          <LABEL for="i1">
            受託販売
          </LABEL>
        </label>
        <label>
        <INPUT TYPE="radio" NAME="Hotel_Control[stock_type]" VALUE="1" {if $v->assign->hotel_control.stock_type == 1} CHECKED {/if} id="i2">
          <LABEL for="i2">
            買取販売
          </label>
      <INPUT TYPE="radio" NAME="Hotel_Control[stock_type]" VALUE="3" {if $v->assign->hotel_control.stock_type == 3} CHECKED {/if} id="i3">
          <LABEL for="i3">
            特定施設(三普)
          </label>
  {/if}
      </td>
      <td><small>選択</small></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >送客実績送信</td>
      <td>
        <INPUT TYPE="radio" NAME="Hotel_Control[checksheet_send]" VALUE="1" {if $v->assign->hotel_control.checksheet_send == 1 && !is_empty($v->assign->hotel_control.checksheet_send)} CHECKED {/if} id="i4">
          <LABEL for="i4">
            送信する
          </label>
        <INPUT TYPE="radio" NAME="Hotel_Control[checksheet_send]" VALUE="0" {if $v->assign->hotel_control.checksheet_send == 0 || is_empty($v->assign->hotel_control.checksheet_send)} CHECKED {/if} id="i3">
          <LABEL for="i3">
            送信しない
          </LABEL>
      </td>
      <td><small>選択</small></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >金額切り捨て桁</td>
      <td>
        <label>
        <INPUT TYPE="radio" NAME="Hotel_Control[charge_round]" VALUE="1" {if $v->assign->hotel_control.charge_round == 1 || is_empty($v->assign->hotel_status.entry_status)} CHECKED {/if} id="i5">
          <LABEL for="i5">
            1の位で丸める
          </LABEL>
          </label>
        <label>
        <INPUT TYPE="radio" NAME="Hotel_Control[charge_round]" VALUE="10" {if $v->assign->hotel_control.charge_round == 10} CHECKED {/if} id="i6">
          <LABEL for="i6">
            10の位で丸める
          </label>
        <INPUT TYPE="radio" NAME="Hotel_Control[charge_round]" VALUE="100" {if $v->assign->hotel_control.charge_round == 100} CHECKED {/if} id="i7">
          <LABEL for="i7">
            100の位で丸める
          </label>
      </td>
      <td><small>選択</small></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >連泊限界数</td>
      <td>
        <INPUT TYPE="text" NAME="Hotel_Control[stay_cap]" value="{$v->helper->form->strip_tags($v->assign->hotel_control.stay_cap)}" SIZE="4" MAXLENGTH="2"><small>設定する場合入力</small>
      </td>
      <td><small>数字2桁</small></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >利用方法</td>
      <td>
        <label>
        <INPUT TYPE="radio" NAME="Hotel_Control[management_status]" VALUE="1" {if $v->assign->hotel_control.management_status == 1} CHECKED {/if} id="management_status1">
          <LABEL for="management_status1">ファックス管理</LABEL>
        </label>
        <label>
        <INPUT TYPE="radio" NAME="Hotel_Control[management_status]" VALUE="2" {if $v->assign->hotel_control.management_status == 2 || is_empty($v->assign->hotel_control.management_status)} CHECKED {/if} id="management_status2">
          <LABEL for="management_status2">インターネット管理</label>
        <label>
        <INPUT TYPE="radio" NAME="Hotel_Control[management_status]" VALUE="3" {if $v->assign->hotel_control.management_status == 3} CHECKED {/if} id="management_status3">
          <LABEL for="management_status3">ファックス管理＋インターネット管理</label>
      </td>
      <td><small>選択</small></td>
    </tr>
    <tr>
      <td  bgcolor="#EEFFEE" >管理システムバージョン</td>
      <td>
        <input type="checkbox" name="version[]" value="1" id="system_version1" {if !is_empty($v->assign->version) and in_array(1, $v->assign->version)}checked{/if} /><label for="system_version1">旧インターフェース</label>
        <input type="checkbox" name="version[]" value="2" id="system_version2" {if is_empty($v->assign->version) or in_array(2, $v->assign->version)}checked{/if} /><label for="system_version2">新インターフェース</label>
      </td>
      <td><small>複数選択可<font color="#0000ff">（必須）</font></small></td>
    </tr>
    <tr>
      <td  bgcolor="#EEFFEE" >日本旅行在庫連携</td>
      <td>
        <input type="radio" id="akafu_status_1" name="Hotel_Control[akafu_status]" value="1" {if $v->assign->hotel_control.akafu_status == 1}checked="checked"{/if} /><label for="akafu_status_1">利用する</label>
        <input type="radio" id="akafu_status_0" name="Hotel_Control[akafu_status]" value="0" {if $v->assign->hotel_control.akafu_status != 1}checked="checked"{/if} /><label for="akafu_status_0">利用しない</label>
      </td>
      <td><small>選択</small></td>
    </tr>
    <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
  </table>
  