{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\brhotel\_log_hotel_person_form.tpl --}}
<table border="1" cellspacing="0" cellpadding="3">
    <tr>
        <td bgcolor="#EEFFEE">施設コード</td>
        <td>
            {$v->helper->form->strip_tags($v->assign->target_cd)}
        </td>
        <td><br /></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">アカウントID※</td>
        <td>
            <input type="text" name="Hotel_Account[account_id_begin]" value="{$v->helper->form->strip_tags($v->assign->hotel_account.account_id_begin)}" size="15" maxlength="10">
        </td>
        <td><small>10文字<font color="#0000ff">（必須）</font></small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">パスワード※</td>
        <td>
            {if is_empty($v->assign->disp)}
                <input type="text" name="Hotel_Account[password]" value="{$v->helper->form->strip_tags($v->assign->hotel_account.password)}" size="15" maxlength="10">
            {else}
            <span nowrap style="float: left;">**********</span>
            <span nowrap style="float: right;">
                <a href="{$v->env.source_path}{$v->env.module}/brhotel/seeingnotes/target_cd/{$v->helper->form->strip_tags($v->assign->target_cd)}">
                    施設ログインパスワードの閲覧
                </a>
            </span>
            {/if}
        </td>
        <td><small>10文字<font color="#0000ff">（必須）</font></small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">ステータス</td>
        <td>
            <input type="radio" name="Hotel_Account[accept_status]" value="1" {if $v->assign->hotel_account.accept_status == 1 || is_empty($v->assign->hotel_account.accept_status)} checked {/if} id="i2">
            <label for="i2">
                利用可
            </label>
            <input type="radio" name="Hotel_Account[accept_status]" value="0" {if $v->assign->hotel_account.accept_status == 0 && !is_empty($v->assign->hotel_account.accept_status)} checked {/if} id="i1">
            <label for="i1">
                利用不可
            </label>
        </td>
        <td><small>選択</small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">担当者役職</td>
        <td>
            <input type="text" name="Hotel_Person[person_post]" value="{$v->helper->form->strip_tags($v->assign->hotel_person.person_post)}" size="50" maxlength="32">
        </td>
        <td><small>32文字</small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">担当者名称※</td>
        <td>
            <input type="text" name="Hotel_Person[person_nm]" value="{$v->helper->form->strip_tags($v->assign->hotel_person.person_nm)}" size="50" maxlength="32">
        </td>
        <td><small>32文字<font color="#0000ff">（必須）</font></small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">担当者電話番号※</td>
        <td>
            <input type="text" name="Hotel_Person[person_tel]" value="{if $v->assign->status == 'new' && is_empty($v->helper->form->strip_tags($v->assign->hotel_person.person_tel))}{$v->helper->form->strip_tags($v->assign->hotel.tel)}{else}{$v->helper->form->strip_tags($v->assign->hotel_person.person_tel)}{/if}" size="20" maxlength="15">
        </td>
        <td><small>xxxx-xxxx-xxxx<font color="#0000ff">（必須）</font></small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">担当者ファックス番号</td>
        <td>
            <input type="text" name="Hotel_Person[person_fax]" value="{if $v->assign->status == 'new' && is_empty($v->helper->form->strip_tags($v->assign->hotel_person.person_fax))}{$v->helper->form->strip_tags($v->assign->hotel.fax)}{else}{$v->helper->form->strip_tags($v->assign->hotel_person.person_fax)}{/if}" size="20" maxlength="15">
        </td>
        <td><small>xxxx-xxxx-xxxx</small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">担当者電子メールアドレス</td>
        <td>
            <input type="text" name="Hotel_Person[person_email]" value="{$v->helper->form->strip_tags($v->assign->hotel_person.person_email)}" size="50" maxlength="50">
        </td>
        <td><br /></td>
    </tr>


    <tr>
        <td bgcolor="#EEFFEE">登録状態</td>
        {if $new_flg == 1}
            <td>
                <input type="hidden" name="Hotel_Status[entry_status]" value="1" />
                登録作業中
            </td>
        {else}
            <td>
                <label>
                    <input type="radio" name="Hotel_Status[entry_status]" value="0" {if $v->assign->hotel_status.entry_status == 0} checked {/if} id="k1" {if !$v->assign->rate_chk} disabled {/if}>
                    <label for="k1">
                        公開中
                    </label>
                </label>
                <input type="radio" name="Hotel_Status[entry_status]" value="1" {if $v->assign->hotel_status.entry_status == 1} checked {/if} id="k2">
                <label for="k2">
                    登録作業中
                </label>
                <input type="radio" name="Hotel_Status[entry_status]" value="2" {if $v->assign->hotel_status.entry_status == 2} checked {/if} id="k3">
                <label for="k3">
                    解約
                </label>
            </td>
        {/if}
        <td>
            <small>施設(買取以外)の料率情報が存在していない場合、公開中は選択できません。</small>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">契約日</td>
        <td>
            <input type="text" name="Hotel_Status[contract_ymd]" value="{$v->helper->form->strip_tags($v->assign->hotel_status.contract_ymd)}" size="20" maxlength="15">
        </td>
        <td>YYYY/MM/DD <small>又は</small> YYYY-MM-DD</td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">公開日</td>
        <td>
            <input type="text" name="Hotel_Status[open_ymd]" value="{$v->helper->form->strip_tags($v->assign->hotel_status.open_ymd)}" size="20" maxlength="15">
        </td>
        <td>YYYY/MM/DD <small>又は</small> YYYY-MM-DD</td>
    </tr>

    {if !is_empty($v->assign->disp)}
        <tr>
            <td bgcolor="#EEFFEE">解約日時</td>
            <td>
                {$v->helper->form->strip_tags($v->assign->hotel_status.close_dtm)|date_format:'%Y/%m/%d %H:%M:%S'}<br />
            </td>
            <td><br /></td>
        </tr>
    {/if}

    <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
    <input type="hidden" name="target_stock_type" value="{$v->helper->form->strip_tags($v->assign->target_stock_type)}">
</table>
