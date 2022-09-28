{{--TODO 削除予定--}}
@section('title', '料率マスタ')
@include('ctl.common.base')

{{--TODO サブメニュー --}}
<a href="{$v->env.source_path}{$v->env.module}/brhotel/show/target_cd/{$views->target_cd}">詳細変更</a>&nbsp;&gt;&nbsp;料率一覧
<br>
@section('message')
@include('ctl.common.message', $messages)
<br>

{{-- 施設情報詳細 --}}
{include file=$v->env.module_root|cat:'/views/brhotel/_hotel_info.tpl'}
<br>

{{-- 料率 新規登録--}}
<!--form action="{$v->env.source_path}{$v->env.module}/brhotelrate/new/" method="post"-->
{!! Form::open(['route' => ['ctl.brhotelRate.new'], 'method' => 'post']) !!}
	<input type="submit" value="新規登録" name="sinki">
	<input type="hidden" value="{strip_tags($views->target_cd)}" name="target_cd">
{!! Form::close() !!}

{{-- 料率一覧 --}}
<table border="1" cellpadding="4" cellspacing="0">
	<tr class="{cycle values='odd,even'}">
		<td bgcolor="#EEFFEE" >&nbsp;<big>料率適用開始日</big>&nbsp;</td>{{-- 料率適用開始日--}}
		<td bgcolor="#EEFFEE" >&nbsp;<big>ベストリザーブサイト料率</big>&nbsp;</td>{{-- BRサイトシステム利用料率--}}
		<td bgcolor="#EEFFEE" >&nbsp;<big>その他サイト料率</big>&nbsp;</td>{{-- 他サイトシステム利用料率--}}
		<td bgcolor="#EEFFEE" >編集</td>
	</tr>
	@foreach ($views->hotelrates as $hotelrates)
		<tr @if ($hotelrates['hotel_cd'] == $views->hotel_cd) bgcolor="#CCCCCC"@endif>
			<td align="center">
				{include file=$v->env.module_root|cat:'/views/_common/_date.tpl' timestamp=$hotelrates['accept_s_ymd format='ymd'}
			</td>
			<td align="right">{{strip_tags($hotelrates['system_rate'])}}%</td>{{-- BRサイトシステム利用料率--}}
			<td align="right">{{strip_tags($hotelrates['system_rate_out'])}}%</td>{{-- 他サイトシステム利用料率--}}
			<td>
			{{--if $hotelrates['accept_s_ymd']|date_format:"%Y-%m-%d" >= $smarty.now|date_format:"%Y-%m-%d" --}}
			@if ( \Carbon\Carbon::createFromTimeString($hotelrates['accept_s_ymd'])->format('Y-m-d')  >= (\Carbon\Carbon::now()->format('Y-m-d'))  )
			<table><tr><td>
				<!--form action="{$v->env.source_path}{$v->env.module}/brhotelrate/edit/" method="post"-->
				{!! Form::open(['route' => ['ctl.brhotelRate.edit'], 'method' => 'post']) !!}
					<input type="submit" name="kousin" value="編集">
					<input type="hidden" name="hotel_cd" value="{strip_tags($hotelrates['hotel_cd'])}">
					<input type="hidden" name="target_cd" value="{strip_tags($hotelrates['hotel_cd'])}">
					<input type="hidden" name="branch_no" value="{strip_tags($hotelrates['branch_no'])}">
				{!! Form::close() !!}
				</td>
				@if (count($views->hotelrates) > 1)
					<td>
					<!--form action="{$v->env.source_path}{$v->env.module}/brhotelrate/destroy/" method="post"-->
					{!! Form::open(['route' => ['ctl.brhotelRate.destroy'], 'method' => 'post']) !!}
						<input type="submit" name="kousin" value="削除">
						<input type="hidden" name="hotel_cd" value="{strip_tags($hotelrates['hotel_cd'])}">
						<input type="hidden" name="target_cd" value="{strip_tags($hotelrates['hotel_cd'])}">
						<input type="hidden" name="branch_no" value="{strip_tags($hotelrates['branch_no'])}">
					{!! Form::close() !!}
				</td>
				@endif
			</td></tr></table>
			@else <span style="color:#ccc">編集</span>
			@endif
			</td>
		</tr>

	{{--    debug:{{$hotelrates['accept_s_ymd']}}<br>
	--}}
	@endforeach
</table>
<div style="margin-top:1em;">
　※ベストリザーブサイト料率：提携先コード「0000000000」の予約に適用<br />
　※その他サイト料率：提携先コード「0000000000」以外の予約に適用<br />
</div>

@section('title', 'footer')
@include('ctl.common.footer')