{{-- MEMO: 移植元 public\app\rsv\view2\error\accesses.tpl --}}

{include file='../_common/_header.tpl' type=$smarty.const.HEADER_RESERVE sub_header = 'ご確認依頼'}
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

<script language="JavaScript" type="text/javascript">
<!--
document.write('<a href="JavaScript:history.back();">- 戻る -</a>');
// -->
</script>
<noscript>
- ブラウザの戻るを押してください。-
</noscript>

      </div>

    </div>
  </div>
</div>

{* footer *}
{include file='../_common/_footer.tpl' google_analytics='off'}
{* footer *}