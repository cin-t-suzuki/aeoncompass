{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\brhotel\_info_management_form.tpl --}}

<table border="1" cellspacing="0" cellpadding="3">
    <tr>
        <td bgcolor="#EEFFEE">施設コード</td>
        <td>
            {$v->helper->form->strip_tags($v->assign->target_cd)}
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">アカウントID</td>
        <td>
            {$v->helper->form->strip_tags($v->assign->hotel_account.account_id_begin)}<br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">パスワード</td>
        <td>
            <span nowrap style="float: left;">**********</span>&emsp;
            <span nowrap style="text-align: right;">
                <a href="{$v->env.source_path}{$v->env.module}/brhotel/seeingnotes/target_cd/{$v->helper->form->strip_tags($v->assign->target_cd)}">
                    施設ログインパスワードの閲覧
                </a>
            </span>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">ステータス</td>
        <td>
            {if $v->assign->hotel_account.accept_status == 0}
                利用不可
            {elseif $v->assign->hotel_account.accept_status == 1}
                利用可
            {/if}<br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">担当者役職</td>
        <td>
            {$v->helper->form->strip_tags($v->assign->hotel_person.person_post)}<br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">担当者名称</td>
        <td>
            {$v->helper->form->strip_tags($v->assign->hotel_person.person_nm)}<br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">担当者電話番号</td>
        <td>
            {$v->helper->form->strip_tags($v->assign->hotel_person.person_tel)}<br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">担当者ファックス番号</td>
        <td>
            {$v->helper->form->strip_tags($v->assign->hotel_person.person_fax)}<br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">担当者電子メールアドレス</td>
        <td>
            {$v->helper->form->strip_tags($v->assign->hotel_person.person_email)}<br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">登録状態</td>
        <td>
            {if $v->assign->hotel_status.entry_status == 0}
                公開中
            {elseif $v->assign->hotel_status.entry_status == 1}
                登録作業中
            {elseif $v->assign->hotel_status.entry_status == 2}
                解約
            {/if}<br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">契約日</td>
        <td>
            {$v->helper->form->strip_tags($v->assign->hotel_status.contract_ymd)}<br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">公開日</td>
        <td>
            {$v->helper->form->strip_tags($v->assign->hotel_status.open_ymd)}<br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">解約日時</td>
        <td>
            {$v->helper->form->strip_tags($v->assign->hotel_status.close_dtm)}<br>
        </td>
    </tr>

    <input type="hidden" name="target_cd" value="{{ strip_tags($target_cd) }}">
    <input type="hidden" name="target_stock_type" value="{$v->helper->form->strip_tags($v->assign->target_stock_type)}">
</table>
