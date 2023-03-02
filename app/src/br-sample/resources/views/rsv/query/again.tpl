{include file='../_common/_header.tpl'}
{include file='../_common/_pgh1.tpl' pgh1_mnv=1}

<div id="pgh2">
  <div class="pg">
    <div class="pgh2-inner">
    </div>
{include file='../_common/_pgh2_inner.tpl'}
  </div>
</div>

<div id="pgc1">
  <div class="pg">
    <div class="pgc1-inner">


        <!-- 条件変更 -->
        {include file='./_form_search.tpl'}
        <!-- / 条件変更 -->
    </div>
  </div>
</div>

<div id="pgc2">
  <div class="pg">
    <div class="pgc2-inner">

        {* メッセージ表示 *}
        {include file='../_common/_message.tpl'}

    </div>
  </div>
</div>


{include file='../_common/_footer.tpl'}

