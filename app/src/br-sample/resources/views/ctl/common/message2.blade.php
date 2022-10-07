{{-- 
    TODO: 判断保留
    移植元で view と view2 で分かれてたものの view2 のほう。
    ./message.blade.php が view のほうのもの。
    構造は同じだが、装飾が異なる。
--}}

{foreach from=$v->error->gets() item=error name=error}
  {if $smarty.foreach.error.first}
    <div class="msg-box">
      <div class="msg-box-back">
        <div class="msg-box-contents msg-box-error">
  {/if}
  {$v->helper->form->strip_tags($error, '<br>', false)}<br />
  {if $smarty.foreach.error.last}
        </div>
      </div>
    </div>
  {/if}
{/foreach}
{foreach from=$v->guide->gets() item=guide name=guide}
  {if $smarty.foreach.guide.first}
    <div class="msg-box">
      <div class="msg-box-back">
        <div class="msg-box-contents msg-box-info">
  {/if}
  {* この対応は一時的な対応のため、外部から許容するタグを指定することはしてはいけません。 *}
  {assign var='tags' value='<br>'|cat:$allowable_tags}
  {$v->helper->form->strip_tags($guide, $tags, false)}<br />
  {if $smarty.foreach.guide.last}
        </div>
      </div>
    </div>
  {/if}
{/foreach}
