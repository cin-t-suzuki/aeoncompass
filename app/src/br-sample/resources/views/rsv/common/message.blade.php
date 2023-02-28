{{-- MEMO: 移植元 public\app\rsv\view2\_common\_message.tpl --}}

{* エラーメッセージ *}
{if ($v->error->has())}
  <div class="alart-box alart-error">
{foreach from=$v->error->gets() item=error name=error}
<p class="align-left">{$v->helper->form->strip_tags($error, '<br>', false)}</p>
{/foreach}
  </div>
{/if}

{if ($v->guide->has())}
  <div class="alart-box alart-guide">
{foreach from=$v->guide->gets() item=guide name=guide}
<p  class="align-left">{$v->helper->form->strip_tags($guide, '<br>', false)}</p>
{/foreach}
  </div>
{/if}