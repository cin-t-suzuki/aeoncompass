{*  css  *}
{include file='./_css.tpl'}
{strip}
  {* 提携先管理ヘッダー *}
  {include file='../_common/_br_header2.tpl' title="パートナー精算実績の内容"}

  <hr class="contents-margin" />

  {* メッセージボックス *}
  {include file='../_common/_message.tpl'}

  <hr class="contents-margin" />

  {* 検索条件 *}
    <table class="br-detail-list">
    <tr>
      <th>精算年月</th>
      <td>{$v->assign->form_params.billpay_ym|substr:0:4}年{$v->assign->form_params.billpay_ym|substr:5:2}月</td>
    </tr>
    <tr>
      <th>パートナー精算先</th>
      <td>{$v->assign->customer.customer_nm}</td>
    </tr>
  </table>

  <hr class="contents-margin" />

  {* サイト単位台帳 *}
  {if !is_empty($v->assign->book)}
    <table class="br-detail-list">
      {if ($v->assign->customer.customer_id == 1)}
        {include file='../_common/_billpayptn_book_nta.tpl'}
      {else}
        {include file='../_common/_billpayptn_book.tpl'}
      {/if}
    </table>
  {/if}
  {* /サイト単位台帳 *}


  {* サイト単位台帳 *}
  {if !is_empty($v->assign->book)}
    {if ($v->assign->customer.customer_id == 1)}

      <hr class="contents-margin" />

      <form style="margin:0;" method="post" action="{$v->env.path_base_module}/{$v->env.controller}/bookcsv/" target="_blank">
        <input type="submit" value="ＣＳＶダウンロード（属性・提携先サイト・料率単位）" />
        <input type="hidden" name="customer_id"    value="{$v->assign->customer.customer_id}" />
        <input type="hidden" name="billpay_ptn_cd" value="{$v->assign->customer.billpay_ptn_cd}" />
        <input type="hidden" name="billpay_ym"     value="{$v->assign->form_params.billpay_ym}" />
        <input type="hidden" name="key"            value="{$v->assign->customer.book_path_encrypt}" />
        {if $v->user->operator->is_staff() and !is_empty($v->user->partner.partner_cd)}<input type="hidden" name="partner_cd" value="{$v->user->partner.partner_cd}" />{/if}{* パートナーコードを持ち回すのは社内ログイン時のみ *}
      </form>
   {/if}
  {/if}
  {* /サイト単位CSV *}

  {* 予約明細ＣＳＶ *}
  {if !is_empty($v->assign->book)}
  <hr class="contents-margin" />

  <form style="margin:0;" method="post" action="{$v->env.source_path}{$v->env.module}/brbillpayptn/csv/" target="_blank">
    <input type="submit" value="ＣＳＶデータダウンロード（予約明細）" />
    <input type="hidden" name="customer_id"    value="{$v->assign->customer.customer_id}" />
    <input type="hidden" name="billpay_ptn_cd" value="{$v->assign->customer.billpay_ptn_cd}" />
    <input type="hidden" name="billpay_ym"     value="{$v->assign->form_params.billpay_ym}" />
    {if $v->user->operator->is_staff() and !is_empty($v->user->partner.partner_cd)}<input type="hidden" name="partner_cd" value="{$v->user->partner.partner_cd}" />{/if}{* パートナーコードを持ち回すのは社内ログイン時のみ *}
  </form>
  {/if}

  <hr class="contents-margin" />

  {* パートナー精算一覧への遷移 *}
  <form action="{$v->env.path_base_module}/brbillpayptn/list/" method="post">
    <div class="ptn-back-main-menu-form">
      <input type="hidden" name="year"     value="{$v->assign->form_params.billpay_ym|substr:0:4}" />
      <input type="hidden" name="month"    value="{$v->assign->form_params.billpay_ym|substr:5:2}" />
      <input type="submit" value="パートナー精算一覧へ" />
    </div>
  </form>
  {* /パートナー精算一覧への遷移 *}

  <hr class="contents-margin" />

  {* 提携先管理フッター *}
  {include file='../_common/_br_footer.tpl'}
  {* /提携先管理フッター *}
{/strip}