{*  css  *}
{include file='./_css.tpl'}
{strip}
  {* 提携先管理ヘッダー *}
  {include file='../_common/_br_header2.tpl' title="パートナー精算実績の予約明細"}

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
        <tr>
          <th>サイト名</th>
          <td>{$v->assign->customer.site_nm}</td>
        </tr>
        {if !is_empty($v->assign->form_params.stock_type)}
        <tr>
          <th>属性</th>
          <td style="text-align:left;">{if $v->assign->form_params.stock_type == 1}一般ネット在庫
              {elseif $v->assign->form_params.stock_type == 2}連動在庫
              {elseif $v->assign->form_params.stock_type == 3}東横イン在庫
              {/if}
          </td>
        </tr>
        {/if}
        <tr>
          <th>内容</th>
          <td>{if $v->assign->form_params.billpay == 1}
                {assign var=target_ym value=$v->assign->form_params.target_ym|cat:'-01'}
                {if $v->helper->date->set($target_ym)}{/if}{$v->helper->date->add('m', -1)}{$v->helper->date->to_format('Y年m月')}分
              {else}精算分
              {/if}
          </td>
        </tr>
        {if $v->assign->customer.document_type == 2 or $v->assign->customer.document_type == 3}
        <tr>
          <th>率（%）</th>
          <td>{if $v->assign->form_params.msd_rate==0}{$v->assign->form_params.rate|number_format:2}{else}{$v->assign->form_params.msd_rate|number_format:2}{/if}%</td>
        </tr>
        {/if}
      </table>

  <div class="clear"></div>

  <hr class="contents-margin" />

  {* メッセージボックス *}
  {include file='../_common/_message.tpl'}


  <hr class="contents-margin" />

  {* 予約データ *}
  {if !is_empty($v->assign->detail.values)}
    <table class="br-detail-list">
      {include file='../_common/_billpayptn_detail.tpl'}
    </table>
  {/if}
  {* /予約データ *}

  <hr class="contents-margin" />

  {* ページャー *}
  {include file='../_common/_pager.tpl' pager=$v->assign->pager params=$v->assign->search_params}
  {* /ページャー *}

  <hr class="contents-margin" />

  <form style="margin:0;" method="post" action="{$v->env.source_path}{$v->env.module}/brbillpayptn/csv/" target="_blank">
    <input type="submit" value="ＣＳＶデータダウンロード" />
    {* Getパラメータ作成 *}
    {assign var=get_params value=''}
    {foreach from=$v->assign->search_params item=value key=key}
      <input type="hidden" name="{$key}" value="{$value}" />
    {/foreach}
  </form>

  <hr class="contents-margin" />

  {* 精算実績の確認への遷移 *}
  <form action="{$v->env.source_path}{$v->env.module}/brbillpayptn/customer/" method="post">
    <div class="ptn-back-main-menu-form">
      <input type="hidden" name="billpay_ym"     value="{$v->assign->form_params.billpay_ym}" />
      <input type="hidden" name="customer_id"     value="{$v->assign->form_params.customer_id}" />
      <input type="submit" value="精算実績の確認へ" />
    </div>
  </form>
  {* /精算実績の確認への遷移 *}

  <hr class="contents-margin" />

  {* 提携先管理フッター *}
  {include file='../_common/_br_footer.tpl'}
  {* /提携先管理フッター *}
{/strip}