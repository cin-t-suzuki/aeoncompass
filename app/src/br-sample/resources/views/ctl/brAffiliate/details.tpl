{* header start *}
  {include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="アフィリエイター詳細"}
{* header end *}
<table>
  <tr>
    <form method="POST" action="{$v->env.source_path}{$v->env.module}/braffiliate/">
        <input type="submit" value="アフィリエイト管理TOPへ戻る">
    </form>
    <form method="POST" action="{$v->env.source_path}{$v->env.module}/braffiliate/affiliateredit/">
        <input type="submit" value=" アフィリエイター情報を編集 ">
        <input type="hidden" name="affiliater_cd" value={$v->helper->form->strip_tags($v->assign->affiliater_value.affiliater_cd)} />
    </form>
  </tr>
</table>

<table border="1" cellpadding="4" cellspacing="0">
  <tr>
    <td nowrap colspan="2" bgcolor="#EEFFEE" >アフィリエイター詳細情報</td>
  </tr>
  <tr>
    <td nowrap bgcolor="#EEFFEE" >名称</td>
    <td nowrap><small>{$v->helper->form->strip_tags($v->assign->affiliater_value.affiliater_cd)}</small><br>
    {$v->helper->form->strip_tags($v->assign->affiliater_value.affiliater_nm)}<br></td>
  </tr>
  <tr>
    <td nowrap  bgcolor="#EEFFEE" >担当者氏名</td>
    <td nowrap>{$v->helper->form->strip_tags($v->assign->affiliater_value.person_nm)}<br></td>
  </tr>
  <tr>
    <td nowrap  bgcolor="#EEFFEE" >担当者ふりがな</td>
    <td nowrap>{$v->helper->form->strip_tags($v->assign->affiliater_value.person_kn)}<br></td>
  </tr>
  <tr>
    <td nowrap  bgcolor="#EEFFEE" >担当者役職</td>
    <td nowrap>{$v->helper->form->strip_tags($v->assign->affiliater_value.person_post)}<br></td>
  </tr>
  <tr>
    <td nowrap  bgcolor="#EEFFEE" >メールアドレス</td>
    <td nowrap>{$v->helper->form->strip_tags($v->assign->affiliater_value.person_email)}<br></td>
  </tr>
  <tr>
    <td nowrap  bgcolor="#EEFFEE" >電話番号</td>
    <td nowrap>{$v->helper->form->strip_tags($v->assign->affiliater_value.tel)}<br></td>
  </tr>
  <tr>
    <td nowrap  bgcolor="#EEFFEE" >FAX番号</td>
    <td nowrap>{$v->helper->form->strip_tags($v->assign->affiliater_value.fax)}<br></td>
  </tr>
  <tr>
    <td nowrap  bgcolor="#EEFFEE" >郵便番号</td>
    <td nowrap>{$v->helper->form->strip_tags($v->assign->affiliater_value.postal_cd)}<br></td>
  </tr>
  <tr>
    <td nowrap  bgcolor="#EEFFEE" >住所</td>
    <td nowrap>{$v->helper->form->strip_tags($v->assign->affiliater_value.address)}<br></td>
  </tr>
  <tr>
    <td nowrap  bgcolor="#EEFFEE" >ログインID</td>
    <td nowrap>{$v->helper->form->strip_tags($v->assign->affiliater_value.account_id)}<br></td>
  </tr>
  <tr>
    <td nowrap  bgcolor="#EEFFEE" >パスワード</td>
    <td nowrap>{$v->helper->form->strip_tags($v->assign->affiliater_value.password)}<br></td>
  </tr>
  <tr>
    <td nowrap  bgcolor="#EEFFEE" >URL</td>
    <td nowrap><a href={$v->assign->affiliater_value.url} target="_blank">{$v->assign->affiliater_value.url}</a></td>
  </tr>
  <tr>
    <td nowrap  bgcolor="#EEFFEE" >サービス開始日</td>
    <td nowrap>
      {if $v->assign->affiliater_value.open_ymd != ""}
        {include file=$v->env.module_root|cat:'/views/_common/_date.tpl' timestamp=$v->assign->affiliater_value.open_ymd format='ymd'}
      {/if}
    </td>
  </tr>
</table>
{if count($v->assign->affiliate.values) != 0}
  {foreach from=$v->assign->affiliate.values item=affiliate}
<br>
<form method="POST" action="{$v->env.source_path}{$v->env.module}/afttop/" target="_blank">
  <table border="1" cellpadding="4" cellspacing="0">
    <input type="submit" value="『{$v->helper->form->strip_tags($affiliate.program_nm)}({$v->helper->form->strip_tags($affiliate.affiliate_cd)})』  のアフィリエイト管理画面に 成りすましログイン ">
    <input type="hidden" name="affiliate_cd" value={$v->helper->form->strip_tags($affiliate.affiliate_cd)} />
  </table>
</form>
{/foreach}
{/if}
<br>
<form method="POST" action="{$v->env.source_path}{$v->env.module}/braffiliate/programnew/">
  <table border="1" cellpadding="4" cellspacing="0">
    <input type="submit" value=" プログラム情報を登録 ">
    <input type="hidden" name="affiliater_cd" value={$v->helper->form->strip_tags($v->assign->affiliater_value.affiliater_cd)}>
  </table>
</form>
<table border="1" cellpadding="4" cellspacing="0" width="100%">
  <tr>
    <td nowrap colspan="7"  bgcolor="#EEFFEE" >プログラム詳細情報</td>
  </tr>
  <tr>
    <td nowrap  bgcolor="#EEFFEE" >編集</td>
    <td nowrap  bgcolor="#EEFFEE" >プログラム</td>
    <td nowrap  bgcolor="#EEFFEE" >予約システム</td>
    <td nowrap  bgcolor="#EEFFEE" >COOKIE<br />有効期限</td>
    <td nowrap  bgcolor="#EEFFEE" >COOKIE<br />上書き可否</td>
    <td nowrap  bgcolor="#EEFFEE" >U : アフィリエイトＵＲＬ<br />R : リダイレクト先<br />T : タグ</td>
    <td nowrap  bgcolor="#EEFFEE" >開始日時<br />終了日時</td>
  </tr>
  {if count($v->assign->affiliate.values) != 0}
    {foreach from=$v->assign->affiliate.values item=affiliate}

    <form method="POST" action="{$v->env.source_path}{$v->env.module}/braffiliate/programedit/">
      <tr>
        <td nowrap >
          <input type="submit" value="編集">
          <input type="hidden" name="affiliater_cd" value={$v->helper->form->strip_tags($affiliate.affiliater_cd)} />
          <input type="hidden" name="affiliate_cd" value={$v->helper->form->strip_tags($affiliate.affiliate_cd)} />
          <input type="hidden" name="reserve_system" value={$v->helper->form->strip_tags($affiliate.reserve_system)} />
        </td>
        <td nowrap >
          {$v->helper->form->strip_tags($affiliate.affiliate_cd)}<br>
          {$v->helper->form->strip_tags($affiliate.program_nm)}
        </td>
        <td nowrap >
          {$v->helper->form->strip_tags($affiliate.reserve_system)}
        </td>
        <td nowrap align="right">
         {if is_empty($affiliate.limit_cookie)}
           セッション単位
         {else}
           {$v->helper->form->strip_tags($affiliate.limit_cookie)}日
         {/if}
        </td>
        <td nowrap align="center">
          {if $affiliate.overwrite_status == 0    }
            ×
          {elseif $affiliate.overwrite_status == 1}
            ○
          {/if}
        </td>
        <td  nowrap>
          {if $affiliate.reserve_system == "reserve"}
            U : <a target="blank" href="http://{$v->config->system->rsv_host_name}/ac/{$affiliate.affiliate_cd}/">http://{$v->config->system->rsv_host_name}/ac/{$affiliate.affiliate_cd}/</a><br>
          {elseif $affiliate.reserve_system == "biztrip"}
            U : <a target="blank" href="http://biztrip.livedoor.com/ad/"{$affiliate.affiliate_cd}"/">http://biztrip.livedoor.com/ad/{$affiliate.affiliate_cd}/</a><br>
          {/if}
          R : <a target="blank" href=""{$affiliate.redirect}"">{$affiliate.redirect}</a><br>
          T : {$affiliate.tag|escape:html}
        </td>
        <td>
          {if !is_empty($affiliate.accept_s_dtm)}
            {if $v->helper->date->set($affiliate.accept_s_dtm*1)}{/if}
              {$v->helper->date->to_format('Y/m/d H:i:s')}<br>
            {else}
            <br>
          {/if}
          {if !is_empty($affiliate.accept_e_dtm)}
            {if $v->helper->date->set($affiliate.accept_e_dtm*1)}{/if}
              {$v->helper->date->to_format('Y/m/d H:i:s')}<br>
            {else}
            <br>
          {/if}
        </td>
      </tr>
    </form>
    {/foreach}
  {else}
    <tr>
      <td nowrap colspan="7">
        プログラムは登録されていません。
      </td>
    </tr>
  {/if}
</table>
<br>
※アフィリエイトを登録したら 精算先の登録画面から精算先を登録してサイトの設定でアフィリエイト先と結び付けを行ってください。<br>
 そうしないとNTAの精算から漏れることになります。
<br>
{* footer start *}
  {include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'}
{* footer end *}
