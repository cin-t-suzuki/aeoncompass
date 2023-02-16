@inject('service', 'App\Http\Controllers\ctl\BrhotelController')

@forelse ($views->hotel_list['values'] as $hotel_list)

	@if ($loop->first)
		<table cellspacing="0" cellpadding="2" border="1">
			<tr>
				<td bgcolor="#EEFFEE" nowrap rowspan="2">登録状態</td>
				<td bgcolor="#EEFFEE" nowrap rowspan="2">施設コード<br>施設名</td>
				<td bgcolor="#EEFFEE" nowrap rowspan="2">登録状態</td>
				<td bgcolor="#EEFFEE" nowrap rowspan="2">詳細変更</td>
				<!--td bgcolor="#EEFFEE" nowrap rowspan="2">旧部屋総合</td-->
				<td bgcolor="#EEFFEE" nowrap rowspan="2">部屋総合</td>
				<td bgcolor="#EEFFEE" nowrap rowspan="2">新部屋総合</td>
				<td bgcolor="#EEFFEE" nowrap rowspan="2">簡単増返室</td>
				<td bgcolor="#EEFFEE" nowrap colspan="3" align="center">ストリーム</td>
				<td bgcolor="#EEFFEE" nowrap rowspan="2">キャンペーン</td>
				<td bgcolor="#EEFFEE" nowrap rowspan="2">分析レポート<br>実績一覧</td>
				<td bgcolor="#EEFFEE" nowrap rowspan="2">ホテル管理</td>
			</tr>
			<tr>
				<td bgcolor="#EEFFEE" nowrap>部屋</td>
				<td bgcolor="#EEFFEE" nowrap>条件</td>
				<td bgcolor="#EEFFEE" nowrap>販売</td>
			</tr>
	@endif

	{{--supervisor_cd要素自体ない可能性がある為is_emptyは使えない--}}
	@if(!isset($hotel_list->supervisor_cd))
		@if ($hotel_list->entry_status == 2)
			<tr bgcolor="cfcfcf">
		@else
			<tr>
		@endif
	@else
		@if ($hotel_list->entry_status == 2)
			<tr bgcolor="c5c56a">
		@else
			<tr bgcolor="ffffee">
		@endif
	@endif

	@if($service->is_empty($hotel_list->entry_status))
		<td nowrap>新規発番中<br>
	@else
		@if ($hotel_list->entry_status == 0)
			<td nowrap>公開中<br>
		@elseif ($hotel_list->entry_status == 1)
			<td nowrap>登録作業中<br>
		@else
			<td nowrap>解約<br>
		@endif
	@endif

	@if ($hotel_list->accept_status == 1)
		[受付中]</td>
	@else
		<font color="#ff0000">[停止中]</font></td>
	@endif

	@if ($hotel_list->entry_status == 0 && $hotel_list->accept_status == 1)
		@if (!$service->is_empty( strip_tags($hotel_list->hotel_old_nm)))
			<td nowrap>{{$hotel_list->hotel_cd}}<br>
				<a href="{{ route( 'ctl.htl_top.index' , ['hotel_cd'=>$hotel_list->hotel_cd]  ) }}"
					target="_blank" style="text-decoration: none; color:#000066;">{{strip_tags($hotel_list->hotel_nm)}}( 旧{{strip_tags($hotel_list->hotel_old_nm)}})</a>
		@else
			<td nowrap>{{$hotel_list->hotel_cd}}<br>
				<a href="{{ route( 'ctl.htl_top.index' , ['hotel_cd'=>$hotel_list->hotel_cd]  ) }}"
					target="_blank" style="text-decoration: none; color:#000066;">{{strip_tags($hotel_list->hotel_nm)}}</a>
		@endif

		@if ($hotel_list->stock_type == 1)
			<font color="#0000ff">[買]</font>
		@endif

		@if ($hotel_list->stock_type == 3)
			<font color="#0000ff">[三普]</font>
		@endif
	 （{{$hotel_list->pref_nm}}）</td>

	@else

		@if(!$service->is_empty(strip_tags($hotel_list->hotel_old_nm)))
			<td nowrap><font color="#996666">{{$hotel_list->hotel_cd}}<br><a href="{{ route( 'ctl.htl_top.index' , ['hotel_cd'=>$hotel_list->hotel_cd]  ) }}" target="_blank" style="text-decoration: none; color:#000066;">{{strip_tags($hotel_list->hotel_nm)}}( 旧{{strip_tags($hotel_list->hotel_old_nm)}})</a>
		@else
			<td nowrap><font color="#996666">{{$hotel_list->hotel_cd}}<br><a href="{{ route( 'ctl.htl_top.index' , ['hotel_cd'=>$hotel_list->hotel_cd]  ) }}" target="_blank" style="text-decoration: none; color:#000066;">{{strip_tags($hotel_list->hotel_nm)}}</a>
		@endif

		@if ($hotel_list->stock_type == 1)
			<font color="#0000ff">[買]</font>
		@endif

		@if ($hotel_list->stock_type == 3)
			<font color="#0000ff">[三普]</font>
		@endif
		（{{$hotel_list->pref_nm}}）</font></td>

	@endif
{{--TODO  ボタン遷移--}}
		<td nowrap align="middle">
			<form action="{$v->env.source_path}{$v->env.module}/brhotelstatus/" method="post" target="_blank">
				<input type="submit" value="設定">
				<input type="hidden" name="target_cd" value="{{$hotel_list->hotel_cd}}" />
			</form>
		</td>
		<td nowrap align="middle">{{--詳細変更 施設各情報ハブ--}}
			{!! Form::open(['route' => ['ctl.brhotel.show'], 'method' => 'post']) !!}
				<input type="submit" value="設定">
				<input type="hidden" name="target_cd" value="{{$hotel_list->hotel_cd}}" />
			{!! Form::close() !!}
		</td>
		<!--旧レイアウトを除去-->
		<!--td nowrap align="middle">
			<form action="{$v->env.source_path}{$v->env.module}/htlstock/" method="post" target="_blank">
				<input type="submit" value="設定">
				<input type="hidden" name="target_cd" value="{{$hotel_list->hotel_cd}}" />
			</form>
		</td-->
		<td nowrap align="middle">
			<form action="{$v->env.source_path}{$v->env.module}/htlsroomoffer/" method="post" target="_blank">
				<input type="submit" value="設定">
				<input type="hidden" name="target_cd" value="{{$hotel_list->hotel_cd}}" />
			</form>
		</td>
		<td nowrap align="middle">
			{!! Form::open(['route' => ['ctl.htlsroomplan2.index'], 'method' => 'post']) !!}
				<input type="submit" value="設定">
				<input type="hidden" name="target_cd" value="{{$hotel_list->hotel_cd}}" />
			{!! Form::close() !!}
		</td>
		<td nowrap align="middle">
			<form action="{$v->env.source_path}{$v->env.module}/htlreroom2/" method="post" target="_blank">
				<input type="submit" value="設定">
				<input type="hidden" name="target_cd" value="{{$hotel_list->hotel_cd}}" />
			</form>
		</td>
		<td nowrap align="middle">
			<form action="{$v->env.source_path}{$v->env.module}/htlstock/" method="post" target="_blank">
				<input type="submit" value="設定">
				<input type="hidden" name="target_cd" value="{{$hotel_list->hotel_cd}}" />
				<input type="hidden" name="disp" value="stream" />
			</form>
		</td>
		<td nowrap align="middle">
			<form action="{$v->env.source_path}{$v->env.module}/htlstock/dispcondition/" method="post" target="_blank">
				<input type="submit" value="設定">
				<input type="hidden" name="target_cd" value="{{$hotel_list->hotel_cd}}" />
			</form>
		</td>
		<td nowrap align="middle">
			<form action="{$v->env.source_path}{$v->env.module}/htldenylist/" method="post" target="_blank">
				<input type="submit" value="設定">
				<input type="hidden" name="target_cd" value="{{$hotel_list->hotel_cd}}" />
			</form>
		</td>
		<td nowrap align="middle">
			<form action="{$v->env.source_path}{$v->env.module}/htlcamp/" method="post" target="_blank">
				<input type="submit" value="設定">
				<input type="hidden" name="target_cd" value="{{$hotel_list->hotel_cd}}" />
			</form>
		</td>
		<td nowrap align="middle">
			<form action="{$v->env.source_path}{$v->env.module}/htlreport/result" method="post" target="_blank">
				<input type="submit" value="設定">
				<input type="hidden" name="target_cd" value="{{$hotel_list->hotel_cd}}" />
			</form>
		</td>
		<td nowrap align="middle">
			<form action="{$v->env.source_path}{$v->env.module}/htltop/" method="post" target="_blank">
				<input type="submit" value="設定">
				<input type="hidden" name="target_cd" value="{{$hotel_list->hotel_cd}}" />
			</form>
		</td>
	</tr>

	@if ($loop->last)
		</table>
	@endif
@empty
		<div style="border-style:solid;border-color:#f00;border-width:1px;padding:6px;background-color:#fee; margin-top:1em;">条件に該当する施設はありませんでした。</div>
@endforelse


