{{-- TODO: --}}

{{-- MEMO: 移植元: public\app\rsv\view2\top\_form_keyword.tpl --}}

<div class="sfm-keyword">
<script src="/js/jquery-ui.js"></script>
<script src="/js/keyword_suggest.js"></script>
<link type="text/css" rel="stylesheet" href="/css/jquery-ui.css" />

<div class="sfm-keyword-inner">
    <form method="get" action="{$v->env.path_base}/keywords/" class="parseForm">
      <input name="keywords" id="f_query" type="text" maxlength="40" style="width:216px; margin:-2px 0 0 0; padding:4px;" placeholder="入力例：県名 ホテル名" value="">
      <div class="btn-b01-068-s" style="margin:-8px 16px 0 0; float:right; padding:5px;">
        <input class="btnimg collectBtn" type="image" src="{$v->env.path_img}/btn/b01-search3.gif" alt="キーワード検索" />
      </div>
    </form>
{foreach from=$v->user->partner->keyword_example.keyword item=keyword name=keyword}
  {if $smarty.foreach.keyword.first}
    <ul>
      <li class="title">☆彡 人気のおすすめキーワード</li>
  {/if}
    <li><a href="{if !is_empty($keyword.value)}{$v->env.path_base}{$keyword.value}{else}{$v->env.path_base}/keywords/?keywords={$keyword.word|urlencode}{/if}">{$keyword.word}</a></li>
  {if $smarty.foreach.keyword.last}
    </ul>
  {/if}
{/foreach}
  </div>
</div>