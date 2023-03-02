{strip}
{*=============================================================================*}
{* 改ページ処理                                                                *}
{*=============================================================================*}
{assign var="total_page" value="7"}
{assign var="lr_page" value="3"}

{if $v->assign->index.page.total_page > 1}

  <div class="sch-page">
  
    &lt;&nbsp;
    
    {if $v->assign->index.page.total_page <= $total_page || $v->assign->index.page.current_page-$lr_page < 1                                                 }{assign var="start_page" value=1}
    {elseif $v->assign->index.page.total_page > $total_page && $v->assign->index.page.current_page > $v->assign->index.page.total_page-$lr_page              }{assign var="start_page" value=$v->assign->index.page.total_page-$total_page+1}
    {elseif $v->assign->index.page.total_page > $total_page && $v->assign->index.page.current_page-$lr_page > $v->assign->index.page.total_page-$total_page-1}{assign var="start_page" value=$v->assign->index.page.current_page-$lr_page}
    {else                                                                                                                                                    }{assign var="start_page" value=$v->assign->index.page.current_page-$lr_page}
    {/if}
    
    {section name="page_list" start=$start_page max=$v->assign->index.page.total_page-$start_page+1 loop=$start_page+$total_page}
      
      {* 最初、前へ *}
      {if $smarty.section.page_list.first}
        {if 1 < $v->assign->index.page.current_page}
          <a href="{$v->env.path_base}{$v->env.path_x_uri}/?{$v->helper->form->to_query_correct('page', false)}" title="最初のページへ">最初&nbsp;</a>&nbsp;|&nbsp;
          <a href="{$v->env.path_base}{$v->env.path_x_uri}/?{$v->helper->form->to_query_correct('page', false)}
          {if $v->assign->index.page.current_page-1 == 1}{* ページに関するパラメータはなし *}
          {else}&page={$v->assign->index.page.current_page|cat:'p'}
          {/if}
          "  title="前のページへ">前</a>&nbsp;|&nbsp;
        {else}
          <span class="void">最初</span>&nbsp;|&nbsp;<span class="void">前</span>&nbsp;|&nbsp;
        {/if}
      {/if}
      
      {* ページ部分 *}
      {if $smarty.section.page_list.index == $v->assign->index.page.current_page}
        <span class="current">&nbsp;{$smarty.section.page_list.index}&nbsp;</span>
      {else}
        <a href="{$v->env.path_base}{$v->env.path_x_uri}/?{$v->helper->form->to_query_correct('page', false)}
        {if $smarty.section.page_list.index == 1}{* ページに関するパラメータはなし *}
        {elseif $smarty.section.page_list.index == $v->assign->index.page.total_page}&page=l
        {else}&page={$smarty.section.page_list.index}
        {/if}
        " title="{$smarty.section.page_list.index}ページへ">&nbsp;{$smarty.section.page_list.index}&nbsp;</a>
      {/if}
      
      {if !$smarty.section.page_list.last}
        &nbsp;|&nbsp;
      {/if}
      
    {/section}
    
    &nbsp;|&nbsp;
    
    {* 次、最後部分 *}
    {if $v->assign->index.page.total_page > $v->assign->index.page.current_page}
      <a href="{$v->env.path_base}{$v->env.path_x_uri}/?{$v->helper->form->to_query_correct('page', false)}
      {if $v->assign->index.page.current_page+1 == $v->assign->index.page.total_page}&page=l
      {else}&page={$v->assign->index.page.current_page|cat:'n'}
      {/if}
      " title="次のページへ">次</a>&nbsp;|&nbsp;
      <a href="{$v->env.path_base}{$v->env.path_x_uri}/?{$v->helper->form->to_query_correct('page', false)}&page=l" title="最後のページへ">&nbsp;最後</a>&nbsp;&gt;
    {else}
      <span class="void">次</span>&nbsp;|&nbsp;
      <span class="void">&nbsp;最後</span>
    {/if}
    
  </div>
  
{/if}

{/strip}
