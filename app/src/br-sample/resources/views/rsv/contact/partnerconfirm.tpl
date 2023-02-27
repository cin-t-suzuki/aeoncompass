{include file='../_common/_header.tpl' title='業務提携について'}
{include file='../_common/_pgh1.tpl' pgh1_mnv=1}

<div id="pgh2v2">
  <div class="pg">
    <div class="pgh2-inner">
    </div>
{include file='../_common/_pgh2_inner.tpl'}
  </div>
</div>

<div id="pgc1v2">
  <div class="pg">
    <div class="pgc1-inner">
{include file='./_pgc1_breadcrumbs.tpl'}
{include file='./_snv_text.tpl' current='partner'}
{$v->helper->store->add('step', '連絡先および質問などの入力')}
{$v->helper->store->add('step', '入力内容の確認')}
{$v->helper->store->add('step', '受け付け完了')}
{$v->helper->store->add('step', '後日担当者より連絡')}
{include file='../_common/_pgc1_steps.tpl' pgc1_steps_current=2}
    </div>
  </div>
</div>

<div id="pgc2">
  <div class="pg">
    <div class="pgc2-inner">
      <div style="text-align:center;">
      <div style="width:700px; margin:0 auto;text-align:left;">
      <div style="padding:1em 0">
         <h1 style="font-size:150%;font-weight:bold;border-left:4px solid #666;padding:4px;">業務提携について</h1>
      </div>

      <div style="margin:1em 0;text-align:center;">
下記、内容でよろしいでしょうか？<br />
      <div style="width:200px; margin:0 auto;">
<table border="0" cellpadding="2" cellspacing="0" width="200">
  <tr>
    <td>
      <form action="{$v->env.path_base_module}/contact/partnercomplete/" method="post">
        <input type="hidden" name="site_nm"        value="{$v->helper->form->strip_tags($v->assign->params.site_nm)}" />
        <input type="hidden" name="company_nm"     value="{$v->helper->form->strip_tags($v->assign->params.company_nm)}" />
        <input type="hidden" name="person_nm"      value="{$v->helper->form->strip_tags($v->assign->params.person_nm)}" />
        <input type="hidden" name="person_nm_kana" value="{$v->helper->form->strip_tags($v->assign->params.person_nm_kana)}" />
        <input type="hidden" name="group"          value="{$v->helper->form->strip_tags($v->assign->params.group)}" />
        <input type="hidden" name="post"           value="{$v->helper->form->strip_tags($v->assign->params.post)}" />
        <input type="hidden" name="tel"            value="{$v->helper->form->strip_tags($v->assign->params.tel)}" />
        <input type="hidden" name="fax"            value="{$v->helper->form->strip_tags($v->assign->params.fax)}" />
        <input type="hidden" name="postal_cd"      value="{$v->helper->form->strip_tags($v->assign->params.postal_cd)}" />
        <input type="hidden" name="pref_id"        value="{$v->helper->form->strip_tags($v->assign->params.pref_id)}" />
        <input type="hidden" name="address"        value="{$v->helper->form->strip_tags($v->assign->params.address)}" />
        <input type="hidden" name="email"          value="{$v->helper->form->strip_tags($v->assign->params.email)}" />
        <input type="hidden" name="url"            value="{$v->helper->form->strip_tags($v->assign->params.url)}" />
        <input type="hidden" name="note"           value="{$v->helper->form->strip_tags($v->assign->params.note)}" />
        <input type="submit" value="はい（実行)">
      </form>
    </td>
    <td>
      <form action="{$v->env.path_base_module}/contact/partnerinput/" method="post">
        <input type="hidden" name="site_nm"        value="{$v->helper->form->strip_tags($v->assign->params.site_nm)}" />
        <input type="hidden" name="company_nm"     value="{$v->helper->form->strip_tags($v->assign->params.company_nm)}" />
        <input type="hidden" name="person_nm"      value="{$v->helper->form->strip_tags($v->assign->params.person_nm)}" />
        <input type="hidden" name="person_nm_kana" value="{$v->helper->form->strip_tags($v->assign->params.person_nm_kana)}" />
        <input type="hidden" name="group"          value="{$v->helper->form->strip_tags($v->assign->params.group)}" />
        <input type="hidden" name="post"           value="{$v->helper->form->strip_tags($v->assign->params.post)}" />
        <input type="hidden" name="tel"            value="{$v->helper->form->strip_tags($v->assign->params.tel)}" />
        <input type="hidden" name="fax"            value="{$v->helper->form->strip_tags($v->assign->params.fax)}" />
        <input type="hidden" name="postal_cd"      value="{$v->helper->form->strip_tags($v->assign->params.postal_cd)}" />
        <input type="hidden" name="pref_id"        value="{$v->helper->form->strip_tags($v->assign->params.pref_id)}" />
        <input type="hidden" name="address"        value="{$v->helper->form->strip_tags($v->assign->params.address)}" />
        <input type="hidden" name="email"          value="{$v->helper->form->strip_tags($v->assign->params.email)}" />
        <input type="hidden" name="url"            value="{$v->helper->form->strip_tags($v->assign->params.url)}" />
        <input type="hidden" name="note"           value="{$v->helper->form->strip_tags($v->assign->params.note)}" />
        <input type="submit" value="いいえ（もどる)">
      </form>
    </td>
  </tr>
</table>
</div>
</div>
      <div style="margin:1em 0;text-align:center;">
      <div style="width:550px; margin:0 auto;text-align:left;">

<table border="1" cellpadding="1" cellspacing="1" width="550" bordercolor="#c0c0c0">
  <tr>
    <td>
      <table border="0" cellpadding="4" cellspacing="1" width="100%">
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center"><font color="#ffffff">サイト名</font></td>
          <td width="380" bgcolor="#ffffcc">{$v->helper->form->strip_tags($v->assign->params.site_nm)}</td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center"><font color="#ffffff">会社名</font></td>
          <td width="380" bgcolor="#ffffcc">{$v->helper->form->strip_tags($v->assign->params.company_nm)}</td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center"><font color="#ffffff">担当者名</font></td>
          <td width="380" bgcolor="#ffffcc">{$v->helper->form->strip_tags($v->assign->params.person_nm)}</td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center"><font color="#ffffff">担当者名<br>(ふりがな)</font></td>
          <td width="380" bgcolor="#ffffcc">{$v->helper->form->strip_tags($v->assign->params.person_nm_kana)}</td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center"><font color="#ffffff">所属</font></td>
          <td width="380" bgcolor="#ffffcc">{$v->helper->form->strip_tags($v->assign->params.group)}</td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center"><font color="#ffffff">役職</font></td>
          <td width="380" bgcolor="#ffffcc">{$v->helper->form->strip_tags($v->assign->params.post)}</td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center"><font color="#ffffff">ｔｅｌ</font></td>
          <td width="380" bgcolor="#ffffcc">{$v->helper->form->strip_tags($v->assign->params.tel)}</td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center"><font color="#ffffff">ｆａｘ</font></td>
          <td width="380" bgcolor="#ffffcc">{$v->helper->form->strip_tags($v->assign->params.fax)}</td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center"><font color="#ffffff">住所</font></td>
          <td width="380" bgcolor="#ffffcc">〒{$v->helper->form->strip_tags($v->assign->params.postal_cd)}
            <br>
            {$v->helper->form->strip_tags($v->assign->pref_data.pref_nm)}{$v->helper->form->strip_tags($v->assign->params.address)}
          </td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center"><font color="#ffffff">e-mail</font></td>
          <td width="380" bgcolor="#ffffcc">{$v->helper->form->strip_tags($v->assign->params.email)}</td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center"><font color="#ffffff">ホームページ</font></td>
          <td width="380" bgcolor="#ffffcc">{$v->helper->form->strip_tags($v->assign->params.url)}</td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center"><font color="#ffffff">質問等</font></td>
          <td width="380" bgcolor="#ffffcc">{$v->helper->form->strip_tags($v->assign->params.note)|nl2br}</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</div>
</div>
      </div>
      </div>


    </div>
  </div>
</div>


{include file='../_common/_footer.tpl'}