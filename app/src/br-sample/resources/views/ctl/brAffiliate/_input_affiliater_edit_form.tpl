    <tr>
      <td nowrap colspan="2" bgcolor="#EEFFEE" >アフィリエイター詳細情報　　<small>[※]は入力必須項目です。</small></td>
    </tr>
    <tr>
      <td nowrap  bgcolor="#EEFFEE" >名称 <small>※</small></td>
      <td nowrap><small>{$v->helper->form->strip_tags($v->assign->affiliater_value.affiliater_cd)}</small><br />
      <input type="text" name="affiliater[affiliater_nm]" value="{$v->helper->form->strip_tags($v->assign->affiliater_value.affiliater_nm)}" size="80" maxlength="64" ></td>
      <input type="hidden" name="affiliater[affiliater_cd]" value={$v->helper->form->strip_tags($v->assign->affiliater_value.affiliater_cd)}>
    </tr>
    <tr>
      <td nowrap  bgcolor="#EEFFEE" >担当者氏名</td>
      <td nowrap><input type="text" name="affiliater[person_nm]" value="{$v->helper->form->strip_tags($v->assign->affiliater_value.person_nm)}" size="50" maxlength="32"></td>
    </tr>
    <tr>
      <td nowrap  bgcolor="#EEFFEE" >担当者ふりがな</td>
      <td nowrap><input type="text" name="affiliater[person_kn]" value="{$v->helper->form->strip_tags($v->assign->affiliater_value.person_kn)}" size="50" maxlength="32"></td>
    </tr>
    <tr>
      <td nowrap  bgcolor="#EEFFEE" >担当者役職</td>
      <td nowrap><input type="text" name="affiliater[person_post]" value="{$v->helper->form->strip_tags($v->assign->affiliater_value.person_post)}" size="50" maxlength="32"></td>
    </tr>
    <tr>
      <td nowrap  bgcolor="#EEFFEE" >メールアドレス</td>
      <td nowrap><input type="text" name="affiliater[person_email]" value="{$v->helper->form->strip_tags($v->assign->affiliater_value.person_email)}" size="80" maxlength="128"></td>
    </tr>
    <tr>
      <td nowrap  bgcolor="#EEFFEE" >電話番号</td>
      <td nowrap><input type="text" name="affiliater[tel]" value="{$v->helper->form->strip_tags($v->assign->affiliater_value.tel)}" size="20" maxlength="15">　<small>(ハイフンあり)</small></td>
    </tr>
    <tr>
      <td nowrap  bgcolor="#EEFFEE" >FAX番号</td>
      <td nowrap><input type="text" name="affiliater[fax]" value="{$v->helper->form->strip_tags($v->assign->affiliater_value.fax)}" size="20" maxlength="15">　<small>(ハイフンあり)</small></td>
    </tr>
    <tr>
      <td nowrap  bgcolor="#EEFFEE" >郵便番号</td>
      <td nowrap><input type="text" name="affiliater[postal_cd]" value="{$v->helper->form->strip_tags($v->assign->affiliater_value.postal_cd)}" size="10" maxlength="8">　<small>(ハイフンあり)</small></td>
    </tr>
    <tr>
      <td nowrap  bgcolor="#EEFFEE" >住所</td>
      <td nowrap><input type="text" name="affiliater[address]" value="{$v->helper->form->strip_tags($v->assign->affiliater_value.address)}" size="80"  maxlength="300"></td>
    </tr>
    <tr>
      <td nowrap  bgcolor="#EEFFEE" >ログインID</td>
      <td nowrap><input type="text" name="affiliater[account_id]"	 value="{$v->helper->form->strip_tags($v->assign->affiliater_value.account_id)}" size="50" maxlength="32"></td>
    </tr>
    <tr>
      <td nowrap  bgcolor="#EEFFEE" >パスワード</td>
      <td nowrap><input type="password" name="affiliater[password]"  value="" size="50" maxlength="64"></td>
    <tr>
      <td nowrap  bgcolor="#EEFFEE" >URL <small>※</small></td>
      <td nowrap><input type="text" name="affiliater[url]" 	 value="{$v->assign->affiliater_value.url}" size="80" maxlength="128" ></td>
    </tr>
    <tr>
      <td nowrap  bgcolor="#EEFFEE" >サービス開始日 <small>※</small></td>
      <td nowrap><input type="text" name="affiliater[open_ymd]" value="{$v->helper->form->strip_tags($v->assign->affiliater_value.open_ymd)}" size="10" maxlength="10">　<small>YYYY/MM/DD <small>又は</small> YYYY-MM-DD</small></td>
    </tr>
    <tr>