{{-- MEMO: 移植元 public\app\ctl\view2\_common\_header_htl.tpl --}}

{strip}

	{*==============================================================================================*}
	{* 施設ヘッダー                                                                                 *}
	{*==============================================================================================*}
	<div id="hd-htl">

		<div id="hd-htl-nmargin">
			<div id="hd-htl-info">
				{*--------------------------------------------------------------------*}
				{* 施設名称                                                           *}
				{*--------------------------------------------------------------------*}
				<p><a href="{$v->env.source_path}{$v->env.module}/redirect/rsvhotel/?target_cd={$v->user->hotel.hotel_cd}">{$v->helper->form->strip_tags($v->user->hotel.hotel_nm)}</a>&nbsp;様</p>

				{*--------------------------------------------------------------------*}
				{* 旧施設名称                                                         *}
				{*--------------------------------------------------------------------*}
				{if (!is_empty($v->helper->form->strip_tags($v->user->hotel.hotel_old_nm)))}
					<p>（旧&nbsp;{$v->helper->form->strip_tags($v->user->hotel.hotel_old_nm)}）</p>
				{/if}
			</div>
		</div>

		<div id="hd-htl-act">
			{*--------------------------------------------------------------------*}
			{* 広告                                                               *}
			{*--------------------------------------------------------------------*}
			<div id="hd-htl-adbnr">
				{*          <a href="http://www.nihon-weekly.com/hotelask/"><img src="/images/intro/ayan/banner.gif" alt="aやん! ウィークリーホテルズ" height="56" width="300" /></a>*}
				{* <a href="http://www.nihon-weekly.com/hotelask/"><img src="/images/intro/ayan/banner.gif" alt="aやん! ウィークリーホテルズ" height="56" width="300" /></a> *}
				{if $smarty.now >= '2020-06-17 00:00:00'|strtotime and $smarty.now <= '2020-07-15 23:59:59'|strtotime}
					<a href="https://www.kanxashi.co.jp/cp/202006kanxashi/?key=brv" target="_blank"><img src="/images/intro/kanzashi/kanxashi_zenryoku.png" alt="かんざしクラウド" height="56" width="300" /></a>
				{/if}
			</div>

			{*--------------------------------------------------------------------*}
			{* 予約受付                                                           *}
			{*--------------------------------------------------------------------*}
			<form action="{$v->env.source_path}{$v->env.module}/htlacceptance/update/" method="post">
				<div id="hd-htl-accept">

					{* request値の取得 *}
					{assign var=request value=$v->helper->form->get_request()}
					{* hidden生成処理 *}
					{foreach from=$request->getParams() key=key item=value name=post}
						{if $key != 'error_handler'
						and $key != 'module'
						and $key != 'controller'
						and $key != 'action'}
							{if is_array($value)}
								{* 配列のhidden値生成 *}
								{foreach from=$value key=key2 item=value2 name=post2}
									<input type="hidden" name="{$key}[{$key2}]" value="{$v->helper->form->strip_tags($value2)}" />
								{/foreach}
							{else}
								{* hidden値生成 *}
								<input type="hidden" name="{$key}" value="{$v->helper->form->strip_tags($value)}" />
							{/if}
						{/if}
					{/foreach}
					<input type="hidden" name="base_controller"      value="{$v->helper->form->strip_tags($v->env.controller)}"   />
					<input type="hidden" name="base_action"          value="{$v->helper->form->strip_tags($v->env.action)}"       />
					<input type="hidden" name="hotel[accept_status]" value="{if $v->user->hotel.accept_status == 0}1{else}0{/if}" />

					{if $v->user->hotel.accept_status == 1}

						{* 予約受付中 *}
						<p class="msg-text-info">予約受付中</p>
						<input type="submit" value="停止中にする" />

					{else}

						{* 予約受付停止中 *}
						<p class="msg-text-error">予約受付停止中</p>
						<input type="submit" value="受付中にする" />

					{/if}

				</div>
			</form>

			{*--------------------------------------------------------------------*}
			{* 操作メニュー                                                       *}
			{*--------------------------------------------------------------------*}
			<form action="{$v->env.source_path}{$v->env.module}/htltop/" method="post">
				<div id="hd-htl-menu">
					<input type="hidden" name="target_cd" value="{$v->assign->request_params.target_cd}" />
					<input type="submit"    value="メニュー" />
				</div>
			</form>

			<div class="clear"></div>

		</div>
	</div>

	<div class="clear"></div>

{/strip}