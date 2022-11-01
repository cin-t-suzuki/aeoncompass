{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\brhotel\_log_hotel_person_form.tpl --}}
<hr size="1">

<h4>施設担当者更新履歴</h4>
※施設管理画面側での更新履歴のみ保存されます。
<table border="1" cellspacing="0" cellpadding="3">
    <tr>
        <td bgcolor="#EEFFEE">
            担当者役職
        </td>
        <td bgcolor="#EEFFEE">
            担当者名称
        </td>
        <td bgcolor="#EEFFEE">
            電話番号
        </td>
        <td bgcolor="#EEFFEE">
            ファックス番号
        </td>
        <td bgcolor="#EEFFEE">
            電子メールアドレス
        </td>
        <td bgcolor="#EEFFEE">
            更新日時
        </td>
    </tr>
    {foreach from=$v->assign->log_hotel_person item=hotel_person}
        <tr>
            <td nowrap>
                {$v->helper->form->strip_tags($hotel_person.person_post)}
            </td>
            <td nowrap>
                {$v->helper->form->strip_tags($hotel_person.person_nm)}
            </td>
            <td nowrap>
                {$v->helper->form->strip_tags($hotel_person.person_tel)}
            </td>
            <td nowrap>
                {$v->helper->form->strip_tags($hotel_person.person_fax)}
            </td>
            <td nowrap>
                {$v->helper->form->strip_tags($hotel_person.person_email)}
            </td>
            <td nowrap>
                {$hotel_person.modify_ts|date_format:"%Y/%m/%d %H:%M:%S"}
            </td>
        </tr>
    {/foreach}
</table>
<br />
