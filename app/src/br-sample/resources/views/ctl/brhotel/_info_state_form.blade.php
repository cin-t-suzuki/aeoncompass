{{-- MEMO: 移植元 public\app\ctl\views\brhotel\_info_state_form.tpl --}}

<table border="1" cellspacing="0" cellpadding="3">


    <tr>
      <td  bgcolor="#EEFFEE" >施設コード</td>
      <td>
        {$v->helper->form->strip_tags($v->assign->target_cd)}<br>
      </td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >通知媒体</td>
      <td>
        {assign var=device_first value=true}
        {foreach from=$v->assign->notify_device item=notify_device}
          {if $device_first != true}+{/if}
          {if $notify_device == 1}ファックス{assign var=device_first value=false}
          {elseif $notify_device == 2}電子メール{assign var=device_first value=false}
          {elseif $notify_device == 4}オペレータ連絡{assign var=device_first value=false}
          {elseif $notify_device == 8}リンカーン{assign var=device_first value=false}
          {/if}
        {/foreach}
        <br>
      </td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >予約情報プッシュ通知（ねっぱん）</td>
      <td>
        {if $v->assign->hotel_notify.neppan_status === '1'}
          通知する
        {elseif $v->assign->hotel_notify.neppan_status === '0'}
          通知しない
        {else}
          通知しない(※連動時に「通知する」に自動切替)
        {/if}<br>
      </td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >通知ステータス</td>
      <td>
        {if $v->assign->hotel_notify.notify_status == 0}
          通知しない
        {elseif $v->assign->hotel_notify.notify_status == 1}
          通知する
        {/if}<br>
      </td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >通知電子メールアドレス</td>
      <td>
        {$v->helper->form->strip_tags($v->assign->hotel_notify.notify_email)}<br>
      </td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >通知ファックス番号</td>
      <td>
        {$v->helper->form->strip_tags($v->assign->hotel_notify.notify_fax)}<br>
      </td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >FAXPR</td>
      <td>
        {if $v->assign->hotel_notify.faxpr_status == 0}
          表示しない
        {elseif $v->assign->hotel_notify.faxpr_status == 1}
          表示する
        {/if}
      </td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >仕入タイプ</td>
      <td>
        {if $v->assign->hotel_control.stock_type == 0}
          受託販売
        {elseif $v->assign->hotel_control.stock_type == 1}
          買取販売
        {elseif $v->assign->hotel_control.stock_type == 2}
          一括受託（東横イン）
        {elseif $v->assign->hotel_control.stock_type == 3}
          特定施設(三普)
        {/if}<br>
      </td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >送客実績送信</td>
      <td>
        {if $v->assign->hotel_control.checksheet_send == 0}
          送信しない
        {elseif $v->assign->hotel_control.checksheet_send == 1}
          送信する
        {/if}<br>
      </td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >金額切り捨て桁</td>
      <td>
        {if $v->assign->hotel_control.charge_round == 1 || is_empty($v->assign->hotel_control.charge_round)}
          1の位で丸める
        {elseif $v->assign->hotel_control.charge_round == 10}
          10の位で丸める
        {elseif $v->assign->hotel_control.charge_round == 100}
          100の位で丸める
        {/if}<br>
      </td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >連泊限界数</td>
      <td>
        {$v->helper->form->strip_tags($v->assign->hotel_control.stay_cap)}<br>
      </td>
    </tr>
    <tr>
      <td  bgcolor="#EEFFEE" >利用方法</td>
        {if $v->assign->hotel_control.management_status == 1}
          <td>ファックス管理 <br>
        {elseif $v->assign->hotel_control.management_status == 2}
          <td>インターネット管理<br>
        {elseif $v->assign->hotel_control.management_status == 3}
          <td>ファックス管理＋インターネット管理<br>
        {/if}
      </td>
    </tr>
    <tr>
      <td  bgcolor="#EEFFEE" >管理システムバージョン※</td>
      <td>
        {if     in_array(1, $v->assign->version) and in_array(2, $v->assign->version)}
          旧インターフェース / 新インターフェース
        {elseif in_array(1, $v->assign->version)}
          旧インターフェース
        {elseif in_array(2, $v->assign->version)}
          新インターフェース
        {/if}
      </td>
    </tr>
    <tr>
      <td  bgcolor="#EEFFEE" >日本旅行在庫連携</td>
      <td>
        {if $v->assign->hotel_control.akafu_status == 1}利用する
        {else}利用しない
        {/if}
      </td>
    </tr>
    <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
  </table>