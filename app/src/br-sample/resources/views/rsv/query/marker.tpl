{if $v->env.action == 'vacant'}
{include file='./_hotel_list_fix.tpl'}
{else}
{include file='./_hotel_list.tpl'}
{/if}