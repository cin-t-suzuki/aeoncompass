{* header start *}
  {include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="アフィリエイト支払管理"}
{* header end *}
<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <form method="POST" action="{$v->env.source_path}{$v->env.module}/brtop/payment/">
      <td>
        <input type="submit" value="データ確認・データ登録へ戻る">
      </td>
    </form>
  </tr>
</table>
<br />

<form method="POST" action="{$v->env.source_path}{$v->env.module}/braffiliate/paymentmanagement/">
  {* アフィリエイト情報検索フォーム *}
  {include file=$v->env.module_root|cat:'/views/braffiliate/_affiliate_info_search_form.tpl'}
</form>

<br />
{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}

{if $v->assign->claim_lists.values|@count != 0}
  {* アフィリエイト情報一覧 *}
  {include file=$v->env.module_root|cat:'/views/braffiliate/_affiliate_info_list.tpl'}
{/if}

<ul style="line-height: 130%">
  <li><small>宿泊日ベースでの情報表示です。</small></li>
  <li><small>実際に宿泊となったものだけを対象とします。<br>

    キャンセル、電話キャンセル、不泊設定は除かれます。</small></li>
  <li><small>宿泊料金合計は、施設の登録料金を税区分を無視し単純に合計する<br>
    本来は税別サ込料金に変換後、合計するところですが、パートナー向け管理画面内「送客実績の確認」において実績表示画面下部に表示される「料金合計」が税区分を無視した料金の合計となっているため、今回の一覧表示でも税区分を無視した合計にて表示するものとします。<br>
    今後、パートナー管理画面での表記が変更された際は、今回の一覧表示でも税区分を考慮した合計となるよう修正が必要です。</small></li>
</ul>
<br>
{* footer start *}
  {include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'}
{* footer end *}