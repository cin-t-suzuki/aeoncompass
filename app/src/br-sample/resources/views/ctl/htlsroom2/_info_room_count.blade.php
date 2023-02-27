<tr>
	<td  bgcolor="#EEEEFF" >基本提供室数の設定</td>
	<td colspan="2">
    期間の設定<br />
    {{$room->from_year}}年{{$room->from_month}}月{{$room->from_day}}日
	～
	{{$room->to_year}}年{{$room->to_month}}月{{$room->to_day}}日
		<table border="0" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
			<tr>
				<td>
					<table border="0" cellpadding="4" cellspacing="1" width="100%">
						<tr>
							<td bgcolor="#FFFFFF"><font color="#FF0000">日曜</font></td>
							<td bgcolor="#FFFFFF">月曜</td>
							<td bgcolor="#FFFFFF">火曜</td>
							<td bgcolor="#FFFFFF">水曜</td>
							<td bgcolor="#FFFFFF">木曜</td>
							<td bgcolor="#FFFFFF">金曜</td>
							<td bgcolor="#FFFFFF"><font color="#0000FF">土曜</font></td>
							<td bgcolor="#EEEEEE"><font color="#FF0000">祝日</font></td>
							<td bgcolor="#EEEEEE">休前日</td>
						</tr>
						<tr>
							<td bgcolor="#FFFFFF" align="right" nowrap>
                {{$room->rooms_1}}
							</td>
							<td bgcolor="#FFFFFF" align="right" nowrap>
                {{$room->rooms_2}}
							</td>
							<td bgcolor="#FFFFFF" align="right" nowrap>
                {{$room->rooms_3}}
							</td>
							<td bgcolor="#FFFFFF" align="right" nowrap>
                {{$room->rooms_4}}
							</td>
							<td bgcolor="#FFFFFF" align="right" nowrap>
                {{$room->rooms_5}}
							</td>
							<td bgcolor="#FFFFFF" align="right" nowrap>
                {{$room->rooms_6}}
							</td>
							<td bgcolor="#FFFFFF" align="right" nowrap>
                {{$room->rooms_7}}
							</td>
							<td bgcolor="#FFFFFF" align="right" nowrap>
                {{$room->rooms_8}}
							</td>
							<td bgcolor="#FFFFFF" align="right" nowrap>
                {{$room->rooms_9}}
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</td>
</tr>