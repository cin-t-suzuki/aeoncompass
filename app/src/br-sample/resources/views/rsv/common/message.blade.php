{{-- MEMO: 移植元 public\app\rsv\view2\_common\_message_org.tpl --}}

{* エラーメッセージ *}
{if $v->error->has()}
{literal}
      <style type="text/css">
.ei { margin: 1em 0; padding: 0.8em 1.2em; border: 2px solid #900; color: #900; background-color: #FFF; line-height: 1.25em; }
.ei ul, .ei ol { margin: 0 1em; padding:0 1em; }
      </style>
{/literal}
{foreach from=$v->error->gets() item=error name=error}
{if $smarty.foreach.error.first}<div class="ei">{/if}
{$v->helper->form->strip_tags($error, '<br>', false)}<br />
{if $smarty.foreach.error.last}</div>{/if}
{/foreach}
{/if}
{* ガイドメッセージ *}
{if $v->guide->has()}
{literal}
      <style type="text/css">
.gi { margin: 1em 0; padding: 0.8em 1.2em; border: 2px solid #009; color: #009; background-color: #FFF; line-height: 1.25em; }
.gi ul, .gi ol { margin: 0 1em; padding:0 1em; }
      </style>
{/literal}
{foreach from=$v->guide->gets() item=guide name=guide}
{if $smarty.foreach.guide.first}<div class="gi">{/if}
{$v->helper->form->strip_tags($guide, '<br>', false)}<br />
{if $smarty.foreach.guide.last}</div>{/if}
{/foreach}
{/if}
