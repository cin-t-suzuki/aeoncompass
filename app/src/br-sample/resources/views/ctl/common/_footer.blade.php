<hr class="pause-line" />
<div class="align-l" style="float:left;">
  {* ログインしていれば *}
  {if $v->user->operator->is_login() == true}
    {if !$v->user->operator->is_staff()}<span style="font-size:smaller;"><a href="{$v->env.source_path}{$v->env.module}/logout/">ログアウト</a></span>{/if}
  {/if}
</div>
<div class="align-r nowrap">画面更新日時({$smarty.now|date_format:'%Y-%m-%d %T'})</div>
<div class="clear"></div>
<div>(c)Copyright 2012 BestReserve Co.,Ltd. All Rights Reserved.</div>