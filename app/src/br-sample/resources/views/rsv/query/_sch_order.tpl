{strip}
  <div class="sch-order">
    | {* strip対策 *}
    {if nvl($v->assign->params.sort, 'trust') == "trust"}
      <span class="current">おまかせ順</span>
    {else}
      <a href="{$v->env.path_base}{$v->env.path_x_uri}/?sort=trust&{$v->helper->form->to_query_correct('sort,page', false)}">おまかせ順</a>
    {/if}
    {* strip対策 *} | {* strip対策 *}
    {if $v->assign->params.sort == "rank"}
      <span class="current">お客様人気順</span>
    {else}
      <a href="{$v->env.path_base}{$v->env.path_x_uri}/?sort=rank&{$v->helper->form->to_query_correct('sort,page', false)}">お客様人気順</a>
    {/if}
    {* strip対策 *} | {* strip対策 *}
    {if $v->assign->params.sort == "charge"}
      <span class="current">料金が安い順</span>
    {else}
      <a href="{$v->env.path_base}{$v->env.path_x_uri}/?sort=charge&{$v->helper->form->to_query_correct('sort,page', false)}">料金が安い順</a>
    {/if}
    {* strip対策 *} | {* strip対策 *}
    {if $v->assign->params.sort == "charge_desc"}
      <span class="current">料金が高い順</span>
    {else}
      <a href="{$v->env.path_base}{$v->env.path_x_uri}/?sort=charge_desc&{$v->helper->form->to_query_correct('sort,page', false)}">料金が高い順</a>
    {/if}
    {* strip対策 *} | {* strip対策 *}
    {if $v->assign->params.sort == "rate"}
      <span class="current">お得感順</span>
    {else}
      <a href="{$v->env.path_base}{$v->env.path_x_uri}/?sort=rate&{$v->helper->form->to_query_correct('sort,page', false)}">お得感順</a>
    {/if}
    {* strip対策 *} | {* strip対策 *}
  </div>
{/strip}
