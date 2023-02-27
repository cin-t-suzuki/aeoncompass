<form action="{$v->env.path_base_module}/contact/partnerconfirm/" method="post">
<table border="1" cellspacing="1" width="550" bordercolor="#c0c0c0">
  <tr>
    <td>
      <table border="0" cellpadding="2" cellspacing="1" width="550">
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center">
            <font color="#ffffff">サイト名</font>
          </td>
          <td width="380" bgcolor="#ffffcc">
            <input type="text" name="site_nm" size="40" maxlength="50" value="{$v->helper->form->strip_tags($v->assign->params.site_nm)}">
          </td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center">
            <font color="#ffffff">会社名</font>
          </td>
          <td width="380" bgcolor="#ffffcc">
            <input type="text" name="company_nm" size="35" maxlength="50" value="{$v->helper->form->strip_tags($v->assign->params.company_nm)}">
            <font color="#0000ff">《全角文字》</font>
          </td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center">
            <font color="#ffffff">担当者名</font>
          </td>
          <td width="380" bgcolor="#ffffcc">
            <input type="text" name="person_nm" size="20" maxlength="20" value="{$v->helper->form->strip_tags($v->assign->params.person_nm)}" >
            <font color="#0000ff">《全角文字》</font>
          </td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center">
            <font color="#ffffff">担当者名<br>(ふりがな)</font>
          </td>
          <td width="380" bgcolor="#ffffcc">
            <input type="text" name="person_nm_kana" size="20" maxlength="20" value="{$v->helper->form->strip_tags($v->assign->params.person_nm_kana)}">
            <font color="#0000ff">《全角文字》</font>
          </td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center">
            <font color="#ffffff">所属</font>
          </td>
          <td width="380" bgcolor="#ffffcc">
            <input type="text" name="group" size="20" maxlength="50" value="{$v->helper->form->strip_tags($v->assign->params.group)}">
            <font color="#0000ff">《全角文字》</font>
          </td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center">
            <font color="#ffffff">役職</font>
          </td>
          <td width="380" bgcolor="#ffffcc">
            <input type="text" name="post" size="20" maxlength="20" value="{$v->helper->form->strip_tags($v->assign->params.post)}">
            <font color="#0000ff">《全角文字》</font>
          </td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center">
            <font color="#ffffff">ｔｅｌ</font>
          </td>
          <td width="380" bgcolor="#ffffcc">
            <input type="text" name="tel" size="20" maxlength="20" value="{$v->helper->form->strip_tags($v->assign->params.tel)}">
            <font color="#0000ff">《例:9999-9999-9999》</font>
          </td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center">
            <font color="#ffffff">ｆａｘ</font>
          </td>
          <td width="380" bgcolor="#ffffcc">
            <input type="text" name="fax" size="20" maxlength="20" value="{$v->helper->form->strip_tags($v->assign->params.fax)}">
            <font color="#0000ff">《例:9999-9999-9999》</font>
          </td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center" >
            <font color="#ffffff">住所</font>
          </td>
          <td width="380" bgcolor="#ffffcc">
               〒<input type="text" name="postal_cd" size="9" maxlength="8" value="{$v->helper->form->strip_tags($v->assign->params.postal_cd)}">
            <font color="#0000ff">《例:541-0054》</font>
            <br>
            <select name="pref_id" size="1">
              {foreach name=pref_data from=$v->assign->pref_data.values item=pref_data}
                <option value="{$v->helper->form->strip_tags($pref_data.pref_id)}" {if $pref_data.pref_id == $v->assign->params.pref_id} selected{/if}>{$v->helper->form->strip_tags($pref_data.pref_nm)}</option>
              {/foreach}
            </select>
            <input type="text" name="address" size="50" maxlength="50" value="{$v->helper->form->strip_tags($v->assign->params.address)}">
          </td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center">
            <font color="#ffffff">e-mail</font>
          </td>
          <td width="380" bgcolor="#ffffcc">
            <input type="text" name="email" size="40" maxlength="50" value="{$v->helper->form->strip_tags($v->assign->params.email)}">
            <br>※e-mailをお持ちの場合<font color="#0000ff">《半角 例:xxxx@xxx.xx.xx》</font>
          </td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center">
            <font color="#ffffff">ホームページ</font>
          </td>
          <td width="380" bgcolor="#ffffcc">
            <input type="text" name="url" size="50" maxlength="50" value="{$v->helper->form->strip_tags($v->assign->params.url)}">
            <br>※ホームページをお持ちの場合
          </td>
        </tr>
        <tr>
          <td width="120" bgcolor="#808080" nowrap align="center">
            <font color="#ffffff">質問等</font>
          </td>
          <td width="380" bgcolor="#ffffcc">
            <textarea rows="6" cols="72" name="note" wrap="hard">{$v->helper->form->strip_tags($v->assign->params.note)}</textarea>
            <br><br>※ご質問に対するご回答は、後日弊社担当よりご連絡いたします。
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br />
<div style="margin:1em 0;text-align:center;"><input type="submit" value="プライバシーポリシーに同意して次へ"></div>
</form>