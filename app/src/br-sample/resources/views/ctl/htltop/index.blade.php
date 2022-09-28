{* header start *}
{include file=$v->env.module_root|cat:'/views/_common/_htl_header.tpl' title='メインメニュー' service_info_flg=false}
{* header end *}
{literal}
<style type="text/css">
	<!--
	table.info_system_rate_point_rate a:hover{
		opacity: 0.6;
	}
		table.info_account_transfer a:hover{
		opacity: 0.6;
	}
	-->
</style>
{/literal}

{*==================================================================================================*}
{* 受託販売の施設にのみ表示するメニュー                                                             *}
{*==================================================================================================*}
{if $v->assign->stock_type == 0}
	{*----------------------------------------------------------------------------*}
	{* 年末年始営業に関する告知                                                   *}
	{*----------------------------------------------------------------------------*}
	<div style="width: 800px; margin: auto; text-align: center;">
	{include file=$v->env.module_root|cat:'/view2/_common/_business_new_years_holiday.tpl'}
	</div>
	<div style="width: 800px; margin: auto; text-align: center;">

				{foreach from=$v->assign->broadcast_messages.values item=value key=key}
				{if !empty($value.header_message) and ($value.accept_header_s_dtm < $smarty.now and $smarty.now < $value.accept_header_e_dtm)}
					<div style="border:1px solid #EF0000; width: 700px; padding:5px;  margin: 0 auto;">
						<table class="info_account_transfer" border="0" cellspacing="0" cellpadding="4" style="width: 700px;">
							<tr>
							<td style="text-align: center;">
							{$value.header_message}
							</td>
							</tr>
						</table>
					</div>
					<br />
				{/if}
				{/foreach}


		<div style="text-align: left; margin-left: 50px;">
			{*----------------------------------------------------------------------------*}
			{* 2014/4 消費税に関する告知                                                  *}
			{*----------------------------------------------------------------------------*}
			{include file=$v->env.module_root|cat:'/view2/_common/_consumption_tax_201404.tpl' type='pdf'}

			{*============================================================================*}
			{* JRコレクションに関する告知案内                                             *}
			{* ※JRコレクション対応施設のみ表示 (2018/03/18削除）                           *}
			{*============================================================================*}

			{if $v->assign->is_jrset}
			{/if}

			{*----------------------------------------------------------------------------*}
			{* マイグレーションに関する告知案内                                           *}
			{* ※旧管理画面利用施設のみ表示                                               *}
			{*----------------------------------------------------------------------------*}
			{if in_array(1, nvl($v->assign->is_disp_room_plan_list, array()))}
				{* 旧画面利用施設 *}
				{include file=$v->env.module_root|cat:'/views/htltop/_renew_info.tpl'}
			{/if}
		</div>

	</div>
{/if}

{*=======================================================================*}
{*       施設のランキング(2017-08-03 コメントアウト)                       *}
{*=======================================================================*}
{*include file=$v->env.module_root|cat:'/views/htltop/_info_rank_data.tpl'*}

<div align="center">

{if $v->assign->stock_type == 0}

{*=======================================================================*}
{*       機能リリース案内(2018-07-05 コメントアウト)                     *}
{*=======================================================================*}
{*	<div style="border:solid #666 1px; padding:10px 1px 3px 1px; width:600px; margin-bottom: 10px;">
	<table style="margin: 0;">
		<tbody>
					{if $v->assign->is_disp_rate_info and ($v->assign->pdf_suffix == '3' or $v->assign->pdf_suffix == 'n3' or $v->assign->pdf_suffix == '33')}
					{else}
					{/if}

					{if $v->assign->is_disp_rate_info and empty($v->assign->pdf_suffix)}
					{/if}
					<tr style="height: 30px;">
							<td><span style="color:#313131; font-size:13px;">2016年11月15日更新</span></td>
							<td><span style="color:#f00; font-size:14px;">画像アップロード機能を改善致しました！</span></td>
					</tr>
					<tr style="height: 30px;">
							<td style="padding-bottom: 10px;"><span style="color:#313131; font-size:13px;">2016年06月27日更新</span></td>
							<td style="padding-bottom: 10px;"><span style="color:#f00; font-size:14px;">かんざしクラウド対応しました！</span></td>
					</tr>
		</tbody>
	</table>
		<p style="margin:10px 5px 5px 5px; font-size:15px; color:#313131;">
		詳しくは<a href="#infomation"><span style="font-weight: bold; font-size: 17px;">↓お知らせ</span></a>をご覧下さい
		</p>
	</div>
*}

{/if}

{* bfsの紹介を表示する。 *}
    <div style="width: 800px; margin: auto; text-align: center;">
      <div style="border:1px solid #38a1db; width: 700px; padding:5px;  margin: 0 auto;">
      <table class="info_account_transfer" border="0" cellspacing="0" cellpadding="4" style="width: 700px;">
        <tr><td style="text-align: center;">
                <div style="background-color:a0d8ef; padding: 5px">
                    <b>可視化で3密対策！<br>
                    簡単・安心・低コストのホテル・旅館向け３密対策システム<br>
                    <div style="background-color:a0d8ef; padding: 5px; font-size:200%;">Best Facility Signal<br>（bfs）</div>
                    <div style="padding: 0px 0px 10px 0px;">をリリース致しました。</div>
                    <div style="background-color:DD2222; color:FFFFFF; padding:8px 50px">ただ今無料で31日間お試し頂けます。</div>
                    <div style="padding: 10px 0px 2px 0px;">詳細は<a href="https://www.bestrsv.com/hs/manual/pdf/bfs_overview.pdf" target="_blank" style="color:#0B0080; text-decoration:underline; text-decoration-color:#0B0080;">コチラ</a>からご確認下さい。</div></b>
                </div>
        </td></tr>
      </table>
      </div>
    </div>
    <br>

{* GoToキャンペーン画面の表示 回答済の場合表示する。 *}
{if $v->assign->hotel_goto_registed > 0 }
    <div style="width: 800px; margin: auto; text-align: center;">
      <div style="border:1px solid #EF0000; width: 700px; padding:5px;  margin: 0 auto;">
      <table class="info_account_transfer" border="0" cellspacing="0" cellpadding="4" style="width: 700px;">
        <tr><td style="text-align: center;">
                <p style="background-color:ffe5ff; padding: 5px"><b>＜GoToトラベルキャンペーン再開時のクーポン適用について＞</b><br><br>
                    <b><a href="https://www.bestrsv.com/hs/manual/pdf/goto_info_20210301.pdf" target="_blank" style="color:#0B0080; text-decoration:underline; text-decoration-color:#0B0080;">コチラ</a>からご確認下さい。</b><br>
                </p>
        </td></tr>
      </table>
      </div>
    </div>
    <br>
    {* 2021/12/17  神代さんの依頼で一旦非表示する。
    <div style="width: 800px; margin: auto; text-align: center;">
      <div style="border:1px solid #EF0000; width: 700px; padding:5px;  margin: 0 auto;">
      <table class="info_account_transfer" border="0" cellspacing="0" cellpadding="4" style="width: 700px;">
        <tr><td style="text-align: center;">
                <p style="background-color:faebd7; padding: 5px"><b>＜地域共通クーポンの管理について＞</b><br><br>
                    地域共通クーポンの管理において、10月19日（月）よりＱＲコードでの対応が可能になりました。<br> 
                    デンソーウェーブ社の提供するアプリをダウンロードの上、ご利用ください。<br>
                    利用方法は他の予約サイトと同様になります。<br>
                    操作方法等、詳細はこちらから　→　<b><a href="https://www.bestrsv.com/hs/manual/pdf/denso_manual_2_1013.pdf" target="_blank" style="color:#0B0080; text-decoration:underline; text-decoration-color:#0B0080;">マニュアル</a></b><br><br>
                    ※現時点で操作マニュアルにはベストリザーブの記載がありませんがご利用いただけます。<br>（11月中旬に記載予定）<br>
                    ※引き続き、<b><a href="https://biz.goto.jata-net.or.jp/info/2020093001.html" target="_blank" style="color:#0B0080; text-decoration:underline; text-decoration-color:#0B0080;">地域共通券番管理シート</a></b>をご利用頂きクーポン券番号の管理をすることも可能です。<br><br>
                    以前に入ったご予約にもＱＲコードを掲載しております。管理画面よりご確認頂けます。<br><br>
                    <b>※電子クーポンの対応ではありません。引き続き紙クーポンでの対応になります。</b><br><br>
                </p>
        </td></tr>
      </table>
      </div>
    </div>
    <br>
    *}
    <div style="width: 800px; margin: auto; text-align: center;">
      <div style="border:1px solid #EF0000; width: 700px; padding:5px;  margin: 0 auto;">
      <table class="info_account_transfer" border="0" cellspacing="0" cellpadding="4" style="width: 700px;">
        <tr><td style="text-align: center;">
                <p style="background-color:#f0ffff;">＜GoToトラベルキャンペーンについて＞<br><br>
                   下記の「GoToトラベルキャンペーン」ページより、設定をお願いします。
                </p>
        </td></tr>
      </table>
      </div>
    </div>
    <br>
    
    <table border="1" cellspacing="0" cellpadding="3"  width="600">
    <tr>
      <td  bgcolor="#FFFF99"  colspan="2" align="center">
        <strong>GoToトラベルキャンペーン</strong>
      </td>
    </tr>
    <tr>
      <td>
          事業者登録状態の変更と<br>対象プランの選択
      </td>
      <td>
        <div style="padding: 5px">
        {if in_array(1, nvl($v->assign->is_disp_room_plan_list, array()))}{* ＜ー新旧管理画面の判定 *}
          <form action="{$v->env.source_path}{$v->env.module}/htlcampgoto/" method="POST">
        {else}
          <form action="{$v->env.source_path}{$v->env.module}/htlcampgoto2/" method="POST">
        {/if}
          <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
          <input type="hidden" name="plan_input_flg" value="1" />
          <input type="submit" value="詳細">
          {if $v->assign->hotel_camp_goto }
              <small>（現在の状態は <font color="#0000FF">事業者登録済</font> です）</small>
          {else}
              <small>（現在の状態は <font color="#EF0000">事業者登録なし</font> です）</small>
          {/if}
        </form>
        </div>
        {if $v->assign->hotel_camp_goto }
          <div style="background-color: #EEEEEE; padding: 2px"><font color="#333333" size="-1">
          <b>※紙の地域共通クーポンでの対応となります。</b>
          ご対応頂けない場合はキャンペーンに参画頂くことができません。
          対応不可の施設様は以下のボタンをクリックください。<br>
          </font>
          </div>
          <div style="background-color: #EEEEEE; padding: 2px"><font color="#333333" size="-2">
          {if in_array(1, nvl($v->assign->is_disp_room_plan_list, array()))}{* ＜ー新旧管理画面の判定 *}
            <form method="POST" action="{$v->env.source_path}{$v->env.module}/htlcampgoto/delete/">
          {else}
            <form method="POST" action="{$v->env.source_path}{$v->env.module}/htlcampgoto2/delete/">
          {/if}
              <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
              <input type="hidden" name="camp_cd" value="{$v->helper->form->strip_tags($v->assign->goto_camp_cd)}">
              <input type="hidden" name="plan_input_flg" value="{$v->helper->form->strip_tags($v->assign->plan_input_flg)}">
              <input type="submit" value="GoToトラベルキャンペーンへの参加を取り下げる" style="display: block; margin: 0 auto;">
          </form>
          </div>
        {/if}
      </td>
    </tr>
          <tr>
            <td>予約通知サンプル</td>
            <td style="padding: 0px 0px 0px 8px"><form action="//{$v->config->system->rsv_host_name}/hs/manual/pdf/goto_notify.pdf" method="GET" target="_blank">
                <input type="submit" value="表示"><font  size="-1">　GoToトラベルキャンペーン用</font>
                </form>
            </td>
          </tr>
         {* 2021/12/17  神代さんの依頼で一旦非表示する。
          <tr>
            <td  bgcolor="#90ee90"  colspan="2" align="center" ><b>GoToトラベルキャンペーン　配布クーポン提出管理画面</b></td>
          <tr>
          </tr>
            <td>エクセルファイルアップロード</td>
            <td style="padding: 5px 5px 5px 8px">
                <font color="#333333" size="-1">紙の地域共通クーポン管理用の「地域共通券番管理シート」をベストリザーブへご提出頂くための画面です。　<b>翌月7日までにアップロードをお願いします。</b></font>
              <form action="{$v->env.source_path}{$v->env.module}/htlsgotoexcel/list/" method="POST">
                <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
                <input type="submit" value="表示">
                <font color="#EE0000" size="-1">（※アプリで管理頂いている施設は対応不要になります。）</font>
              </form>
            </td>
          </tr>
          *}
    </table>
    <br>
{/if}
  
{* お知らせ表示 *}
{foreach from=$v->assign->twitters.values name=twitter item=value key=key}
	{if $smarty.foreach.twitter.first}
		<br>
		<div align="center">
		<table border="0" cellpadding="0" cellspacing="1" bgcolor="#0000ff"><tr><td>
		<table border="0" cellpadding="2" cellspacing="0">
	{/if}

	<tr>
		<td bgcolor="#ffffff">【 {$v->helper->form->strip_tags($value.alert_system_nm)} 】{$v->helper->form->strip_tags($value.title, '<font>', false)}</td>
		{if !(is_empty($value.description))}<td>{$v->helper->form->strip_tags($value.description, '<br><div><font><img><li><small><span><strong><ul>', false)}</td>{/if}
	</tr>

	{if $smarty.foreach.twitter.last == true}
		</table>
		</td></tr></table>
		</div>
	{/if}
{/foreach}


{if $v->assign->stock_type == 0 or $v->assign->stock_type == 3}
	{* 部屋の管理 *}
	{include file=$v->env.module_root|cat:'/views/htltop/_stock.tpl'}
	{* 部屋の管理 *}
	<br>
{/if}


{if $v->assign->stock_type == 0}
	{* 宿泊データ管理 *}
	{include file=$v->env.module_root|cat:'/views/htltop/_form_staying_data.tpl'}
	{* 宿泊データ管理 *}
	<br>
{/if}

{if !in_array(2, $v->assign->is_disp_room_plan_list) and !in_array(3, $v->assign->is_disp_room_plan_list)}
	{if $v->assign->stock_type == 0}
		{* ストリーム用部屋の管理 *}
		{include file=$v->env.module_root|cat:'/views/htltop/_stream_stock.tpl'}
		{* ストリーム用部屋の管理 *}
		<br>
	{/if}
{/if}

{* 予約の管理と会員へのサポート *}
{include file=$v->env.module_root|cat:'/views/htltop/_support.tpl'}
{* 予約の管理と会員へのサポート *}
<br>

{* 基本情報の管理 *}
{include file=$v->env.module_root|cat:'/views/htltop/_basis_info.tpl'}
{* 基本情報の管理 *}
<br>

{if $v->assign->stock_type == 0}
<table border="1" cellspacing="0" cellpadding="3" width="600">
	<tr>
		<td  bgcolor="#EEEEFF"  colspan="2" align="center">
<strong>その他</strong></td></tr>
	<tr>
		<td width="40%">ベストリザーブ宿ぷらざ利用約款</td>
<form action="//{$v->config->system->rsv_host_name}/hs/manual/pdf/BR_YDP_clause.pdf" method="GET" target="_blank">
		<td><input type="submit" value="表示"></td>

		</form>
	</tr>
	<tr>
		<td width="40%">管理画面操作マニュアル</td>
                {if $v->user->hotel_system_version.version == 1}
<form action="//{$v->config->system->rsv_host_name}/hs/manual/" method="GET" target="_blank">
                {else}
<form action="//{$v->config->system->rsv_host_name}/hs/manual/pdf/instruction.pdf?{0|rand:999}" method="GET" target="_blank">
                {/if}
		<td><input type="submit" value="移動"></td>

	</form>
	</tr>
{if $v->assign->is_jrset}
	<tr>
		<td width="40%">JRコレクションマニュアル</td>
		<form action="//{$v->config->system->rsv_host_name}/hs/manual/index-jrc.html" method="GET" target="_blank">
			<td><input type="submit" value="移動"></td>
		</form>
	</tr>
{/if}
{if $v->assign->is_jetstar}
	<tr>
		<td width="40%">ジェットスター・ダイナミックパッケージマニュアル</td>
		<form action="//{$v->config->system->rsv_host_name}/hs/manual/index-jetstar.html" method="GET" target="_blank">
			<td><input type="submit" value="移動"></td>
		</form>
	</tr>
{/if}
</table>
<br>
{/if}

{if $v->assign->stock_type == 1}
	{if !$v->assign->is_migration_complete and $v->user->hotel_system_version.version == 1}
		{if $v->user->operator->staff_id == 19}
			<div align="center">
				<table border="1" cellpadding="2" cellspacing="0" width="600">
					<tr>
						<td bgcolor="#eeeeff" align="center"><b>マイグレーション</b></td>
					</tr>
					<tr>
						<td align="center">
							<form method="post" action="{$v->env.source_path}{$v->env.module}/htlmigration/" style="display:inline;">
								<input type="submit" value="プラン・部屋登録方式移行ツール" style="width:14em;" />
								<input type="hidden" name="ctl_nm" value="{$v->env.controller}" />
								<input type="hidden" name="act_nm" value="{$v->env.action}" />
								<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
							</form>
						</td>
					</tr>
				</table>
			</div>
			<br>
		{/if}
	{/if}
{/if}

{* サービスのお問い合わせ先 *}
	{include file=$v->env.module_root|cat:'/views/htltop/_info.tpl'}
{* サービスのお問い合わせ先 *}

				<br />
				<br />
		<div style="border:1px solid #EF0000; width: 700px; padding:5px;  margin: 0 auto;">
			<table class="info_account_transfer" border="0" cellspacing="0" cellpadding="4" style="width: 700px;">
					<tr>
					<td style="text-align: center;">
						<p style="margin: 0; line-height: 1.5em; font-size:17px;">
							<a href="http://{$v->config->system->rsv_host_name}/hs/manual/pdf/account_transfer.pdf" style="color:#EF0000; text-decoration: underline;" target="_blank">
							ご精算における口座振替に関するご案内
							</a>
						</p>
						<p style="margin: 0; font-size:15px; line-height: 1.5em;">
							<a href="http://{$v->config->system->rsv_host_name}/hs/manual/pdf/account_transfer.pdf" style="color:#EF0000;" target="_blank">
								クリックしていただくと案内の文書に遷移いたします。
							</a>
						</p>
					</td>
					</tr>
			</table>
		</div>
		<div style="width: 800px; padding:3px 5px 5px 5px;">
			<p style="margin: 0; font-size: 15px;">※「預金口座振替依頼書」の表示は<a href="http://{$v->config->system->rsv_host_name}/hs/manual/pdf/RequestForm.pdf
" style="font-weight: bold; text-decoration: underline" target="_blank">こちらのリンク</a>をクリック下さい。</p>
		</div>
	{if $v->assign->is_disp_rate_info and (empty($v->assign->pdf_suffix) or $v->assign->pdf_suffix == '3' or $v->assign->pdf_suffix == 'n3' or $v->assign->pdf_suffix == '33') }
	{*=======================================================================*}
	{* システム料率・ポイント料率変更連絡                                    *}
	{*=======================================================================*}
	{*<br><br>
	<div style="border:1px solid #000000; width: 600px; margin-top: 15px; padding:5px;">
	<table class="info_system_rate_point_rate" border="0" cellspacing="0" cellpadding="4" style="width: 600px;">
		<tr>
		<td style="text-align: center;">
		{if $v->assign->hotel_system_rate == 5 }
			<a href="http://{$v->config->system->rsv_host_name}/hs/manual/pdf/BRY_Notice_PointRate_5{$v->assign->pdf_suffix}.pdf" style="color:#000000; target="_blank">2016年11月　システム手数料、ポイント料および提携先手数料についてご連絡</a>
		{elseif $v->assign->hotel_system_rate == 6 }
			<a href="http://{$v->config->system->rsv_host_name}/hs/manual/pdf/BRY_Notice_PointRate_6{$v->assign->pdf_suffix}.pdf" style="color:#000000; target="_blank">2016年11月　システム手数料、ポイント料および提携先手数料についてご連絡</a>
		{elseif $v->assign->hotel_system_rate == 8 }
			<a href="http://{$v->config->system->rsv_host_name}/hs/manual/pdf/BRY_Notice_PointRate_8{$v->assign->pdf_suffix}.pdf" style="color:#000000; target="_blank">2016年11月　システム手数料、ポイント料および提携先手数料についてご連絡</a>
		{/if}
		</td>
		</tr>
	</table>
	</div>
	<br />*}
	{/if}

{if $v->assign->stock_type == 0}
	<br><br>

{* ジェットスター表示対応 ここから *}
{if $v->assign->is_disp_Jetstar_phase1 }
<a name="jetstar" ></a>
<table border="1" cellspacing="0" cellpadding="4" >
<tr>
	<td>


	{*=======================================================================*}
	{* アークスリー連携施設数 増加対応のお知らせ（指定された施設のみ/北海道・沖縄）                                           *}
	{*=======================================================================*}
	<div style="text-align: left; margin: 10px 25px 10px 25px; width: 626px;">
	<div style="text-align: center; padding-bottom: 15px;">
	<font color="#EF0000" size="+1">
	【ジェットスター・ダイナミックパッケージへの在庫連動について】<br>
	（販売を開始いたしました、貴施設様の運用に特にかわりはございません）<br>
	</font>
	</div>
平素より「ベストリザーブ・宿ぷらざ」をご利用頂き誠に有難うございます。<br>
すでにご報告いたしておりました「募集型企画旅行ジェットスター・ダイナミック
パッケージ」を取り扱う「株式会社アーク・スリーインターナショル」を通じ、
貴施設様在庫との連動について、11月25日（金）より開始いたしました事をご連絡申し上げます。<br>
これにより更なるご送客を図って参ります。<br>
<br>
貴施設様の運用に特にかわりはございません。<br>
また、システム利用料に関しましても従来とおりでございます。<br>
<br>
「ジェットスター・ダイナミックパッケージ」の概要について下記よりご確認下さい。<br>
<ul style="list-style:none">
<li style="margin-bottom:10px">
<a href="http://{$v->config->system->rsv_host_name}/hs/manual/pdf/jetstarDynamicPackaging-Manual2016-3.pdf" target="_blank">
「ジェットスター・ダイナミックパッケージ」の概要（PDFへリンク）</a></li>
<li style="margin-bottom:10px">
<a href="http://{$v->config->system->rsv_host_name}/hs/manual/pdf/jetstarDynamicPackaging-faxmailSample.pdf" target="_blank">
「ジェットスター・ダイナミックパッケージ」を通して入った予約<br>
の施設様向けFAX・メールサンプル（PDFへリンク）</a></li>
</ul>

「連動を希望されない」場合は、お手数ですが下記連絡先までご連絡をお願い申し上げます。<br>
<br>
【連絡先】<br>
ベストリザーブ・宿ぷらざ事務局<br>
MAIL : <a href="mailto:opc@bestrsv.com">opc@bestrsv.com</a><br>
TEL  : 03-5751-8243<br>
FAX  : 03-5751-8242<br>
営業時間: 月～金 9:30～18:30<br>
（土曜・日曜・祝祭日・弊社休日は除く）<br>
	</div>
	</td></tr></table>
<br><br>
{/if}

{if $v->assign->is_disp_Jetstar_phase2 }
<a name="jetstar" ></a>
<table border="1" cellspacing="0" cellpadding="4" >
<tr>
	<td>


	{*=======================================================================*}
	{* アークスリー連携施設数 増加対応のお知らせ（指定された施設のみ/関東圏、近畿圏、福岡）                                            *}
	{*=======================================================================*}
	<div style="text-align: left; margin: 10px 25px 10px 25px; width: 626px;">
	<div style="text-align: center; padding-bottom: 15px;">
	<font color="#EF0000" size="+1">
	【ジェットスター・ダイナミックパッケージへの在庫連動について】<br>
	（販売を開始いたしました、貴施設様の運用に特にかわりはございません）<br>
	</font>
	</div>
平素より「ベストリザーブ・宿ぷらざ」をご利用頂き誠に有難うございます。<br>
すでにご報告いたしておりました「募集型企画旅行ジェットスター・ダイナミック
パッケージ」を取り扱う「株式会社アーク・スリーインターナショル」を通じ、
貴施設様在庫との連動について、2017年3月10日（金）より開始いたしました事をご連絡申し上げます。<br>
これにより更なるご送客を図って参ります。<br>
<br>
貴施設様の運用に特にかわりはございません。<br>
また、システム利用料に関しましても従来とおりでございます。<br>
<br>
「ジェットスター・ダイナミックパッケージ」の概要について下記よりご確認下さい。<br>
<ul style="list-style:none">
<li style="margin-bottom:10px">
<a href="http://{$v->config->system->rsv_host_name}/hs/manual/pdf/jetstarDynamicPackaging-Manual2016-3.pdf" target="_blank">
「ジェットスター・ダイナミックパッケージ」の概要（PDFへリンク）</a></li>
<li style="margin-bottom:10px">
<a href="http://{$v->config->system->rsv_host_name}/hs/manual/pdf/jetstarDynamicPackaging-faxmailSample.pdf" target="_blank">
「ジェットスター・ダイナミックパッケージ」を通して入った予約<br>
の施設様向けFAX・メールサンプル（PDFへリンク）</a></li>
</ul>
「連動を希望されない」場合は、お手数ですが下記連絡先までご連絡をお願い申し上げます。<br>
<br>
【連絡先】<br>
ベストリザーブ・宿ぷらざ事務局<br>
MAIL : <a href="mailto:opc@bestrsv.com">opc@bestrsv.com</a><br>
TEL  : 03-5751-8243<br>
FAX  : 03-5751-8242<br>
営業時間: 月～金 9:30～18:30<br>
（土曜・日曜・祝祭日・弊社休日は除く）<br>
	</div>
	</td></tr></table>
<br><br>
{/if}

{if $v->assign->is_disp_Jetstar_phase3 }
<a name="jetstar" ></a>
<table border="1" cellspacing="0" cellpadding="4" >
<tr>
	<td>


	{*=======================================================================*}
	{* アークスリー連携施設数 増加対応のお知らせ// (カード決済プラン販売施設 各府県914軒)                                            *}
	{*=======================================================================*}
	<div style="text-align: left; margin: 10px 25px 10px 25px; width: 626px;">
	<div style="text-align: right; padding-bottom: 15px;">
	2017年6月15日<br>
	株式会社ベストリザーブ<br>
	</div>
	<div style="text-align: center; padding-bottom: 15px;">
	<font color="#EF0000" size="+1">
	【ジェットスター・ダイナミックパッケージへの在庫連動について】<br>
	（貴施設様の運用に特にかわりはございません）<br>
	</font>
	</div>
平素より「ベストリザーブ・宿ぷらざ」をご利用頂き誠に有難うございます。<br>
<br>
「ベストリザーブ・宿ぷらざ」では、以前より各サイトや企業様との提携をすすめ、<br>
宿泊施設様へのご送客拡大を図っております。<br>
<br>
5月29日にご連絡いたしました通り、すでに提携しております「募集型企画旅行ジェットスター・ダイナミックパッケージ」を
取り扱う「株式会社アーク・スリーインターナショル」を通じて、<br>
提携或を更に広め、貴施設様の在庫を連動致します事、ご連絡申し上げます。<br>
これにより更なるご送客を図ります。<br>
<br>
5月29日からの「お知らせ」掲載においてご連絡させていただきました、6月9日までのお問い合わせ期間を経て、現在連携に向けて準備を行っており、随時連携を開始予定でございます。<br>
<br>
貴施設様の運用に特にかわりはございません。<br>
また、システム利用料に関しましても従来とおりでございます。<br>
<br>
「ジェットスター・ダイナミックパッケージ」の概要について下記よりご確認下さい。<br>
<ul style="list-style:none">
<li style="margin-bottom:10px">
<a href="http://{$v->config->system->rsv_host_name}/hs/manual/pdf/jetstarDynamicPackaging-Manual2016-3.pdf" target="_blank">
「ジェットスター・ダイナミックパッケージ」の概要（PDFへリンク）</a></li>
<li style="margin-bottom:10px">
<a href="http://{$v->config->system->rsv_host_name}/hs/manual/pdf/jetstarDynamicPackaging-faxmailSample.pdf" target="_blank">
「ジェットスター・ダイナミックパッケージ」を通して入った予約<br>
の施設様向けFAX・メールサンプル（PDFへリンク）</a></li>
</ul>
<br>
【連絡先】<br>
ベストリザーブ・宿ぷらざ事務局<br>
MAIL : <a href="mailto:opc@bestrsv.com">opc@bestrsv.com</a><br>
TEL  : 03-5751-8243<br>
FAX  : 03-5751-8242<br>
営業時間: 月～金 9:30～18:30<br>
（土曜・日曜・祝祭日・弊社休日は除く）<br>
	</div>
	</td></tr></table>
<br><br>
{/if}

{* ジェットスター表示対応 ここまで *}


	{* お知らせ表示 *}
	{include file=$v->env.module_root|cat:'/views/htltop/_broadcast_messages.tpl'}
	{* お知らせ表示 *}
{/if}

</div>

{literal}
<script language="javascript"  type="text/javascript">
<!--
	if (window.focus){
		window.focus();
	}
//-->
</script>
{/literal}

<br>

{* 担当者情報確認ダイアログ *}
{if $v->assign->a_confirm_hotel_person.confirm_dtm_check        ||
	$v->assign->a_confirm_hotel_person.hotel_person_email_check ||
	$v->assign->a_confirm_hotel_person.customer_email_check  ||
	$v->assign->confirm_hotel_person_force }
<link rel="stylesheet" href="/scripts/Remodal-master/remodal.css">
<link rel="stylesheet" href="/scripts/Remodal-master/remodal-default-theme.css">
<script src="/scripts/jquery1.11.js"></script>
<script src="/scripts/Remodal-master/remodal.min.js"></script>

<script>
{literal}
$(function(){
	$('[data-remodal-id=modal_confirm_info]').remodal().open();
});
{/literal}
</script>

{if $v->assign->confirm_hotel_person_force }
	<div class="remodal" data-remodal-id="modal_confirm_info" data-remodal-options="hashTracking: false, closeOnOutsideClick: false,closeOnEscape:false">
{else}
	<div class="remodal" data-remodal-id="modal_confirm_info" data-remodal-options="hashTracking: false">
{/if}
<div class="pop_confirm-header">
	<!-- button data-remodal-action="close" class="remodal-close"></button-->

	<p>ご担当者に変更はございませんか？</p>
{if $v->assign->confirm_hotel_person_force }
	<p style="font-size:14px;color: #900;margin-top: -20px;">ご登録のない施設にご案内させて頂いております。</p>
{else}
	<p style="font-size:14px;color: #900;margin-top: -20px;">定期的にご確認させていただいております。</p>
{/if}
	<hr size=1>
</div>


	<div class="pop_confirm" style="width:100%;">
			<h4>施設ご担当者様</h4>
			<ul>
			<li class="li-title">[氏名]</li>
			<li class="li-name">
			{if empty($v->assign->hotel_person.person_nm) }
				<font color="red">※氏名のご登録をお願いします。</font>
			{else}
				{$v->assign->hotel_person.person_nm}<span class="li-name-sama">様</span>
			{/if}
			</li>
			<li class="li-title">[電話番号]</li>
			<li class="li-tel" >
			{if empty($v->assign->hotel_person.person_tel) }
				<font color="red">※電話番号のご登録をお願いします。</font>
			{else}
				{$v->assign->hotel_person.person_tel}
			{/if}
			</li>
			<li class="li-title">[メールアドレス]</li>
			<li class="li-mail">
				{if empty($v->assign->hotel_person.person_email) }
					<font color="red">※メールアドレスのご登録をお願いします。</font>
				{elseif $v->assign->a_confirm_hotel_person.hotel_person_email_check }
					<font color="red">{$v->assign->hotel_person.person_email}<br>
					※メールアドレスが正しくない可能性があります。
					</font>
				{else}
					{$v->assign->hotel_person.person_email}
				{/if}
			</li>
			</ul>
	</div>

	<div class="pop_confirm" style="width:100%;">
			<h4>請求ご担当者様</h4>
			<ul>
			<li class="li-title">[氏名]</li>
			<li class="li-name">
			{if empty($v->assign->customer.person_nm) }
				<font color="red">※氏名のご登録をお願いします。</font>
			{else}
				{$v->assign->customer.person_nm}<span class="li-name-sama">様</span>
			{/if}
			</li>
			<li class="li-title">[電話番号]</li>
			<li class="li-tel">
			{if empty($v->assign->customer.tel) }
				<font color="red">※電話番号のご登録をお願いします。</font>
			{else}
				{$v->assign->customer.tel}
			{/if}
			</li>
			<li class="li-title">[メールアドレス]
			<li class="li-mail">
			{if empty($v->assign->customer.email) }
				<font color="red">※メールアドレスのご登録をお願いします。</font>
			{elseif $v->assign->a_confirm_hotel_person.customer_email_check }
				<font color="red">{$v->assign->customer.email}<br>
				※メールアドレスが正しくない可能性があります。
				</font>
			{else}
				{$v->assign->customer.email}
			{/if}
			</li>
			</ul>
	</div>

<hr size=1>

	<div class='mt20'>
		{if $v->assign->confirm_hotel_person_force }
		<form action="{$v->env.source_path}{$v->env.module}/htlmaillist/list" method="POST">
		<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
		<input type="submit" name="genreupload"  value="登録する"  class="remodal-confirm btn btn-success"  style=" width: 450px;margin: 0 100px 0 100px;"/>
		</form>
		{else}
		<form action="{$v->env.source_path}{$v->env.module}/htlmaillist/list" method="POST">
		<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
		<input type="submit" name="genreupload"  value="変更が必要"  class="remodal-confirm btn btn-success"  style="float: left;margin: 0 50px 0 100px;"/>
		</form>
		<button data-remodal-action="cancel" class="btn btn-danger remodal-cancel">変更しない</button>
		{/if}
	</div>

	<table border="1" cellpadding="6" cellspacing="0"  style=" position: absolute; bottom: -110px; right: 30px; background-color: #fff;">
		<tbody><tr>
		<td nowrap="" style="font-size:10pt;">
			ベストリザーブ・宿ぷらざ事務局<br>
			MAIL:<a href="mailto:{$v->config->environment->mail->from->opc}">{$v->config->environment->mail->from->opc}<br>
			</a>TEL : 03-5751-8243<br>
			FAX : 03-5751-8242<br>
			受付: 月～金 9:30～18:30<br>
			（土曜・日曜・祝祭日・弊社休日は除く）
		</td>
		</tr>
	</tbody></table>

</div>
{/if}
{* 担当者情報確認ダイアログ ここまで *}

{* GoTo事業者登録確認ダイアログ *}
{if $v->assign->confirm_hotel_goto_regist }
<link rel="stylesheet" href="/scripts/Remodal-master/remodal.css">
<link rel="stylesheet" href="/scripts/Remodal-master/remodal-default-theme.css">
<script src="/scripts/jquery1.11.js"></script>
<script src="/scripts/Remodal-master/remodal.min.js"></script>

<script>
{literal}
$(function(){
	$('[data-remodal-id=modal_confirm_info]').remodal().open();
});
{/literal}
</script>

{if 1==1 }
	<div class="remodal" data-remodal-id="modal_confirm_info" data-remodal-options="hashTracking: false, closeOnOutsideClick: false,closeOnEscape:false">
{else}
	<div class="remodal" data-remodal-id="modal_confirm_info" data-remodal-options="hashTracking: false">
{/if}
<div class="pop_confirm-header">
	<!-- button data-remodal-action="close" class="remodal-close"></button-->

	<p>GoToトラベルキャンペーンの事務局への<br>事業者登録状況についてご回答願います。</p>
        <p style="font-size: small;">※ベストリザーブのシステムでの対応は2020年10月1日を予定しています。</p>
	<hr size=1>
</div>

	<p>以下の1～4の該当するボタンをクリックしてご回答ください。</p>
	<div class='mt20'  style="margin: 10px 0 0 0;">
		<form action="{$v->env.source_path}{$v->env.module}/htlgotoregist/selected" method="POST">
		<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
		<input type="hidden" name="status" value="1" />
		<input type="submit" name="genreupload"  value="1.  GoToトラベルキャンペーンの事業者登録を完了し、参加を希望する"  style="text-align: left; width: 450px;margin: 0 100px 0 100px;"/>
		</form>
	</div>
	<div class='mt20'  style="margin: 10px 0 0 0;">
		<form action="{$v->env.source_path}{$v->env.module}/htlgotoregist/selected" method="POST">
		<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
		<input type="hidden" name="status" value="2" />
                <input type="submit" name="genreupload"  value="2. 現在審査中で回答待ちのため確定後に別途回答する　"   style="text-align: left; width: 450px;margin: 0 100px 0 100px;"/>
		</form>
	</div>
	<div class='mt20'  style="margin: 10px 0 0 0;">
		<form action="{$v->env.source_path}{$v->env.module}/htlgotoregist/selected" method="POST">
		<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
		<input type="hidden" name="status" value="3" />
                <input type="submit" name="genreupload"  value="3. 参加/不参加等を検討中のため確認後に別途回答する"   style="text-align: left; width: 450px;margin: 0 100px 0 100px;"/>
		</form>
	</div>
        <div class='mt20'  style="margin: 10px 0 0 0;">
		<form action="{$v->env.source_path}{$v->env.module}/htlgotoregist/selected" method="POST">
		<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
		<input type="hidden" name="status" value="4" />
                <input type="submit" name="genreupload"  value="4. GoToトラベルキャンペーンに参加しない"   style="text-align: left; width: 450px;margin: 0 100px 0 100px;"/>
		</form>
	</div>
        <p style="font-size: small;">※選択した回答は管理画面にて修正可能です。</p>

	<table border="1" cellpadding="6" cellspacing="0"  style=" position: absolute; bottom: -110px; right: 30px; background-color: #fff;">
		<tbody><tr>
		<td nowrap="" style="font-size:10pt;">
			ベストリザーブ・宿ぷらざ事務局<br>
			MAIL:<a href="mailto:{$v->config->environment->mail->from->opc}">{$v->config->environment->mail->from->opc}<br>
			</a>TEL : 03-5751-8243<br>
			FAX : 03-5751-8242<br>
			受付: 月～金 9:30～18:30<br>
			（土曜・日曜・祝祭日・弊社休日は除く）
		</td>
		</tr>
	</tbody></table>

</div>
{/if}
{* GoTo事業者登録確認ダイアログ ここまで *}

		{*----------------------------------------------------------------------------*}
		{* プライスコンシェルジュの案内  2018/07/05 ページ上部からここへ位置変更      *}
		{*----------------------------------------------------------------------------*}
		<div style="width: 800px; margin: auto; text-align: center;">
		<p><a href="http://price.bestrsv.com/" target="_blank">日本最大級の競合料金分析サービス！「プライスコンシェルジュ」はこちら</a></p>
		</div>

{* footer start *}
	{include file=$v->env.module_root|cat:'/views/_common/_htl_footer.tpl'}
{* footer end *}
