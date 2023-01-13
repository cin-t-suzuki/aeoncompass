{* 引数：$pager     ※ページャーオブジェクト             *}
{* 引数：$params    ※リンクパラメータ                   *}
{* 引数：$page_name ※ページ番号パラメータの名称         *}
{*------------------------------------------------------------------------------*}
{* ページ番号パラメータの名称設定 *}
{if is_empty($page_name)}
  {assign var=page_nm value='page'}
{else}
  {assign var=page_nm value=$page_name}
{/if}
{* /ページ番号パラメータの名称設定 *}
{* Getパラメータ作成 *}
{assign var=get_params value=''}
{foreach from=$params item=value key=key}
  {assign var=get_params value=$get_params|cat:'&amp;'|cat:$key|cat:'='|cat:$value}
{/foreach}
{* /Getパラメータ作成 *}
{*------------------------------------------------------------------------------*}
{* ページャー *}
{if $pager->pageCount > 1}
  <ul class="pager">
    <li>
      {* 最初のページへのリンク *}
      {if !is_empty($pager->previous)}
        <a href="{$v->env.source_path}{$v->env.module}/{$v->env.controller}/{$v->env.action}/?{$page_nm}={$pager->first}{$get_params}">&lt;&lt;</a>
      {else}
        &lt;&lt;
      {/if}
      {* /最初のページへのリンク *}
    </li>
    <li>
      {* 前のページへのリンク *}
      {if !is_empty($pager->previous)}
        <a href="{$v->env.source_path}{$v->env.module}/{$v->env.controller}/{$v->env.action}/?{$page_nm}={$pager->previous}{$get_params}">PREV</a>
      {else}
        PREV
      {/if}
      {* /前のページへのリンク *}
    </li>
    {* ページ番号へのリンク *}
    {foreach from=$pager->pagesInRange item=page}
      <li {if $page == $pager->current}id="current"{/if}>
        {if $page == $pager->current}
          {$page}
        {else}
          <a href="{$v->env.source_path}{$v->env.module}/{$v->env.controller}/{$v->env.action}/?{$page_nm}={$page}{$get_params}">{$page}</a>
        {/if}
      </li>
    {/foreach}
    {* /ページ番号へのリンク *}
    <li>
      {* 次のページへのリンク *}
      {if !is_empty($pager->next)}
        <a href="{$v->env.source_path}{$v->env.module}/{$v->env.controller}/{$v->env.action}/?{$page_nm}={$pager->next}{$get_params}">NEXT</a>
      {else}
        NEXT
      {/if}
      {* /次のページへのリンク *}
    </li>
    <li>
      {* 最後のページへのリンク *}
      {if !is_empty($pager->next)}
        <a href="{$v->env.source_path}{$v->env.module}/{$v->env.controller}/{$v->env.action}/?{$page_nm}={$pager->last}{$get_params}">&gt;&gt;</a>
      {else}
        &gt;&gt;
      {/if}
      {* /最後のページへのリンク *}
    </li>
  </ul>
{/if}
{* /ページャー *}