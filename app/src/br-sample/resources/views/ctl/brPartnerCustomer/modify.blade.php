hello, modify blade
{{-- TODO: smarty -> blade --}}

{*  css  *}
{include file='./_css.tpl'}
{strip}
  {* 提携先管理ヘッダー *}
  {include file='../_common/_br_header2.tpl' title="精算先情報"}

  <hr class="contents-margin" />

  {include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}

  <hr class="contents-margin" />

  {* 精算先情報表示 *}
  {include file='./_info_customer.tpl'}

  <hr class="contents-margin" />

  {* 一覧に戻る *}
  <form action="{$v->env.source_path}{$v->env.module}/brpartnercustomer/search/" method="POST">
    <small>
      {foreach from=$v->assign->search_params item=value key=key}
        <input type="hidden" name="{$key}" value="{$value}" />
      {/foreach}
      <input type="submit" value="精算先請求先一覧へ">
    </small>
  </form>

  {* 提携先管理フッター *}
  {include file='../_common/_br_footer.tpl'}
  {* /提携先管理フッター *}
{/strip}