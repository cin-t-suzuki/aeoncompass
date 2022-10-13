{* TODO: 移植 *}
<table class="br-detail-list">
  <tr><th>精算先ID</th><td>{$v->assign->partner_customer.customer_id}</td></tr>
  <tr><th>精算先名称</th><td>{$v->helper->form->strip_tags($v->assign->partner_customer.customer_nm)}</td></tr>
  <tr><th>役職（部署名）</th><td>{$v->helper->form->strip_tags($v->assign->partner_customer.person_post)}</td></tr>
  <tr><th>担当者</th><td>{$v->helper->form->strip_tags($v->assign->partner_customer.person_nm)}</td></tr>
  <tr><th>郵便番号・都道府県</th><td>〒{$v->helper->form->strip_tags($v->assign->partner_customer.postal_cd)}
      {foreach from = $v->assign->mast_pref.values item = value}
        {if $v->assign->partner_customer.pref_id == $value.pref_id}{$value.pref_nm}{/if}
      {/foreach}
  </td>
  </tr>
  <tr><th>住所</th><td>{$v->helper->form->strip_tags($v->assign->partner_customer.address)}</td></tr>
  <tr><th>電話番号</th><td>{$v->helper->form->strip_tags($v->assign->partner_customer.tel)}</td></tr>
  <tr><th>ファックス番号</th><td>{$v->helper->form->strip_tags($v->assign->partner_customer.fax)}</td></tr>
  <tr><th>E-Mail</th><td>{$v->helper->form->strip_tags($v->assign->partner_customer.email)}</td></tr>
  <tr><th>通知方法</th>
      <td>
        {if $v->assign->partner_customer.mail_send === "0"}郵送（手動印刷）{/if}
        {if $v->assign->partner_customer.mail_send === "1"}メールで通知する{/if}
      </td>
  </tr>
  <tr><th>手数料キャンセル対象状態</th>
      <td>
        {if $v->assign->partner_customer.cancel_status === "0"}予約のみ（キャンセル料金精算対象外）{/if}
        {if $v->assign->partner_customer.cancel_status === "1"}キャンセル含む（キャンセル料金精算対象）{/if}
      </td>
  </tr>
  <tr><th>明細書の通知有無</th>
      <td>
        {if $v->assign->partner_customer.detail_status === "0"}通知不用{/if}
        {if $v->assign->partner_customer.detail_status === "1"}通知必要{/if}
        <br />※ 精算書確認画面下部にあります「予約明細ダウンロード」からCSVファイルをダウンロードして必要に応じて加工して通知してください。
      </td>
  </tr>
  <tr><th>精算日</th><td>{$v->assign->partner_customer.billpay_day}日</td>
  </tr>
  <tr><th>精算必須月</th>
    <td nowrap>
      {if ($v->assign->partner_customer.billpay_required_month|substr:3:1) == 1} 4月{/if}
      {if ($v->assign->partner_customer.billpay_required_month|substr:4:1) == 1} 5月{/if}
      {if ($v->assign->partner_customer.billpay_required_month|substr:5:1) == 1} 6月{/if}
      {if ($v->assign->partner_customer.billpay_required_month|substr:6:1) == 1} 7月{/if}
      {if ($v->assign->partner_customer.billpay_required_month|substr:7:1) == 1} 8月{/if}
      {if ($v->assign->partner_customer.billpay_required_month|substr:8:1) == 1} 9月{/if}
      {if ($v->assign->partner_customer.billpay_required_month|substr:9:1) == 1} 10月{/if}
      {if ($v->assign->partner_customer.billpay_required_month|substr:10:1)== 1} 11月{/if}
      {if ($v->assign->partner_customer.billpay_required_month|substr:11:1)== 1} 12月{/if}
      {if ($v->assign->partner_customer.billpay_required_month|substr:0:1) == 1} 1月{/if}
      {if ($v->assign->partner_customer.billpay_required_month|substr:1:1) == 1} 2月{/if}
      {if ($v->assign->partner_customer.billpay_required_month|substr:2:1) == 1} 3月{/if}
    </td>
  </tr>
  <tr><th>精算最低金額</th><td>{$v->assign->partner_customer.billpay_charge_min|number_format}円</td></tr>
</table>
