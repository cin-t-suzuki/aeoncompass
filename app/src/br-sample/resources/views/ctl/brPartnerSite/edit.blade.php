{*  css  *}
{include file='./_css.tpl'}
{strip}
  {* 提携先管理ヘッダー *}
  {include file='../_common/_br_header2.tpl' title="精算サイト情報"}

  {* エラーメッセージ *}
  {include file='../_common/_message.tpl'}

    <hr class="contents-margin" />

  {* 入力フォーム *}
  <form action="{$v->env.source_path}{$v->env.module}/brpartnersite/modify/" method="POST">

    {* 精算先内容 *}
    {include file='./_input_site.tpl'}

    <hr class="contents-margin" />

    {* 引数 *}
    <input type="hidden" name="site_cd" value="{$v->helper->form->strip_tags($v->assign->form_params.site_cd)}" />
    {foreach from=$v->assign->search_params item=value key=key}
      <input type="hidden" name="{$key}" value="{$value}" />
    {/foreach}

    <input type="submit" value="更新">

  </form>

  {* 料率表示 *}
  {include file='./_info_rate.tpl'}

  <hr class="contents-margin" />

  {* 一覧へ戻る *}
  <form action="{$v->env.source_path}{$v->env.module}/brpartnersite/search/" method="POST">
    <small>
    {foreach from=$v->assign->search_params item=value key=key}
      <input type="hidden" name="{$key}" value="{$value}" />
    {/foreach}

      <input type="submit" value="精算サイト一覧へ">
    </small>
  </form>

  <hr class="contents-margin" />

  {* 提携先管理フッター *}
  {include file='../_common/_br_footer.tpl'}
  {* /提携先管理フッター *}
{/strip}