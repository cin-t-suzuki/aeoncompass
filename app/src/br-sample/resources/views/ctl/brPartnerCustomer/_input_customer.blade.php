{{-- 移植元: svn_trunk\public\app\ctl\view2\brpartnercustomer\_input_customer.tpl --}}

<h1>
    読み込めてる。
</h1>

{{-- TODO: smarty -> blade --}}
<table class="br-detail-list">
    <tr><th>精算先ID</th><td>{$v->assign->partner_customer.customer_id}<input type="hidden" name="partner_customer[customer_id]" value="{$v->helper->form->strip_tags($v->assign->partner_customer.customer_id)}"></td></tr>
    <tr><th>精算先名称</th><td><input type="text" name="partner_customer[customer_nm]" SIZE="50" MAXLENGTH="150" value="{$v->helper->form->strip_tags($v->assign->partner_customer.customer_nm)}"></td></tr>
    <tr><th>役職（部署名）</th><td><input type="text" name="partner_customer[person_post]" SIZE="50" MAXLENGTH="50" value="{$v->helper->form->strip_tags($v->assign->partner_customer.person_post)}"></td></tr>
    <tr><th>担当者</th><td><input type="text" name="partner_customer[person_nm]" SIZE="20" MAXLENGTH="20" value="{$v->helper->form->strip_tags($v->assign->partner_customer.person_nm)}"></td></tr>
    <tr><th>郵便番号・都道府県</th><td>〒<input type="text" name="partner_customer[postal_cd]" SIZE="9" MAXLENGTH="8" value="{$v->helper->form->strip_tags($v->assign->partner_customer.postal_cd)}">
      <select size="1" name="partner_customer[pref_id]">
        {foreach from = $v->assign->mast_pref.values item = value}
          <option value="{$value.pref_id}"{if $v->assign->partner_customer.pref_id == $value.pref_id}selected{/if}>{$value.pref_nm}</option>
        {/foreach}
      </select>
    </td>
    </tr>
    <tr><th>住所</th><td><input type="text" name="partner_customer[address]" SIZE="50" MAXLENGTH="200" value="{$v->helper->form->strip_tags($v->assign->partner_customer.address)}"></td></tr>
    <tr><th>電話番号</th><td><input type="text" name="partner_customer[tel]" SIZE="15" MAXLENGTH="15" value="{$v->helper->form->strip_tags($v->assign->partner_customer.tel)}"></td></tr>
    <tr><th>ファックス番号</th><td><input type="text" name="partner_customer[fax]" SIZE="15" MAXLENGTH="15" value="{$v->helper->form->strip_tags($v->assign->partner_customer.fax)}"></td></tr>
    <tr><th>E-Mail</th><td><input type="text" name="partner_customer[email]" SIZE="50" MAXLENGTH="50" value="{$v->helper->form->strip_tags($v->assign->partner_customer.email_decrypt)}"></td></tr>
    <tr><th>通知方法</th>
        <td>
          <label for="mail_send_1"><input type="radio" id="mail_send_1" name="partner_customer[mail_send]" value="0" {if nvl($v->assign->partner_customer.mail_send, "0") === "0"}checked{/if} />郵送（手動印刷）</label><label for="mail_send_0"><input type="radio" id="mail_send_0" name="partner_customer[mail_send]" value="1" {if $v->assign->partner_customer.mail_send === "1"}checked{/if} />メールで通知する</label>
        </td>
    </tr>
    <tr><th>手数料キャンセル対象状態</th>
        <td>
          <label for="cancel_status_0"><input type="radio" id="cancel_status_0" name="partner_customer[cancel_status]" value="0" {if nvl($v->assign->partner_customer.cancel_status, "0") === "0"}checked{/if} />予約のみ（キャンセル料金精算対象外）</label>
          <label for="cancel_status_1"><input type="radio" id="cancel_status_1" name="partner_customer[cancel_status]" value="1" {if $v->assign->partner_customer.cancel_status === "1"}checked{/if} />キャンセル含む（キャンセル料金精算対象）</label>
        </td>
    </tr>
    <tr><th>明細書の通知有無</th>
        <td>
          <label for="detail_status_0"><input type="radio" id="detail_status_0" name="partner_customer[detail_status]" value="0" {if nvl($v->assign->partner_customer.detail_status, "0") === "0"}checked{/if} />通知不用</label>
          <label for="detail_status_1"><input type="radio" id="detail_status_1" name="partner_customer[detail_status]" value="1" {if $v->assign->partner_customer.detail_status === "1"}checked{/if} />通知必要</label>
          <br />※ 精算書確認画面下部にあります「予約明細ダウンロード」からCSVファイルをダウンロードして必要に応じて加工して通知してください。
        </td>
    </tr>
    <tr><th>精算日</th>
      <td>
         <select size="1" name="partner_customer[billpay_day]">
           {section name = billpay_day start = 1 loop = 31}
              <option value="{$smarty.section.billpay_day.index}" {if $smarty.section.billpay_day.index == nvl($v->assign->partner_customer.billpay_day, "8")} selected="selected" {/if}>{$smarty.section.billpay_day.index}日</option>
           {/section}
         </select>
      </td>
    </tr>
    <tr><th>精算必須月</th>
      <td nowrap>
        <label for="billpay_month04"><input type="checkbox" name="partner_customer[billpay_month04]" id="billpay_month04" value="1"{if ($v->assign->partner_customer.billpay_required_month|substr:3:1) == 1} checked{/if}>4月</label>
        <label for="billpay_month05"><input type="checkbox" name="partner_customer[billpay_month05]" id="billpay_month05" value="1"{if ($v->assign->partner_customer.billpay_required_month|substr:4:1) == 1} checked{/if}>5月</label>
        <label for="billpay_month06"><input type="checkbox" name="partner_customer[billpay_month06]" id="billpay_month06" value="1"{if ($v->assign->partner_customer.billpay_required_month|substr:5:1) == 1} checked{/if}>6月</label>
        <label for="billpay_month07"><input type="checkbox" name="partner_customer[billpay_month07]" id="billpay_month07" value="1"{if ($v->assign->partner_customer.billpay_required_month|substr:6:1) == 1} checked{/if}>7月</label>
        <label for="billpay_month08"><input type="checkbox" name="partner_customer[billpay_month08]" id="billpay_month08" value="1"{if ($v->assign->partner_customer.billpay_required_month|substr:7:1) == 1} checked{/if}>8月</label>
        <label for="billpay_month09"><input type="checkbox" name="partner_customer[billpay_month09]" id="billpay_month09" value="1"{if ($v->assign->partner_customer.billpay_required_month|substr:8:1) == 1} checked{/if}>9月</label>
        <label for="billpay_month10"><input type="checkbox" name="partner_customer[billpay_month10]" id="billpay_month10" value="1"{if ($v->assign->partner_customer.billpay_required_month|substr:9:1) == 1} checked{/if}>10月</label>
        <label for="billpay_month11"><input type="checkbox" name="partner_customer[billpay_month11]" id="billpay_month11" value="1"{if ($v->assign->partner_customer.billpay_required_month|substr:10:1) == 1} checked{/if}>11月</label>
        <label for="billpay_month12"><input type="checkbox" name="partner_customer[billpay_month12]" id="billpay_month12" value="1"{if ($v->assign->partner_customer.billpay_required_month|substr:11:1) == 1} checked{/if}>12月</label>
        <label for="billpay_month01"><input type="checkbox" name="partner_customer[billpay_month01]" id="billpay_month01" value="1"{if ($v->assign->partner_customer.billpay_required_month|substr:0:1) == 1} checked{/if}>1月</label>
        <label for="billpay_month02"><input type="checkbox" name="partner_customer[billpay_month02]" id="billpay_month02" value="1"{if ($v->assign->partner_customer.billpay_required_month|substr:1:1) == 1} checked{/if}>2月</label>
        <label for="billpay_month03"><input type="checkbox" name="partner_customer[billpay_month03]" id="billpay_month03" value="1"{if ($v->assign->partner_customer.billpay_required_month|substr:2:1) == 1} checked{/if}>3月</label>
      </td>
    </tr>
    <tr><th>精算最低金額</th><td><input type="text" name="partner_customer[billpay_charge_min]" SIZE="5" MAXLENGTH="5" value="{$v->helper->form->strip_tags($v->assign->partner_customer.billpay_charge_min)}" class="charge" /> 円 空欄にすると精算必須月欄指定月のみ処理されます。</td></tr>
  </table>
  {* 消費税単位 *}
  <input type="hidden" name="partner_customer[tax_unit]" value="{if ($v->assign->partner_customer.custmer_id == "1")}2{else}1{/if}" />{* 1:料率毎（一般） 2:在庫種類単位（NTA） *}
