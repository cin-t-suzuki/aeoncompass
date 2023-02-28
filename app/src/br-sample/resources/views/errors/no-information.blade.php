{{-- MEMO: 移植元 public\app\rsv\view2\error\noinformation.tpl --}}

{include file='../_common/_header.tpl' title="確認のご案内"}
{include file='../_common/_pgh1.tpl' pgh1_mnv=1}
<div id="pgh2">
  <div class="pg">
    <div class="pgh2-inner">
    </div>
  </div>
</div>

<div id="pgc1">
  <div class="pg">
    <div class="pgc1-inner">
    </div>
  </div>
</div>

<div id="pgc2">
  <div class="pg">
    <div class="pgc2-inner">

      <div style="width:400px; margin:0 auto;text-align:center;">
      {* エラーメッセージ *}
      {include file='../_common/_message.tpl'}
      </div>

    </div>
  </div>
</div>

{* footer *}
{include file='../_common/_footer.tpl' google_analytics='off'}
{* footer *}