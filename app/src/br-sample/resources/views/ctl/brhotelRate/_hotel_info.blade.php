{{-- TODO 削除、移動予定 --}}

施設情報詳細
		<table border="2" cellspacing="0" cellpadding="4">
			<tr>
				<td bgcolor="#EEFFEE">
				施設コード
				</td>
				<td>
					{strip_tags($views->hotel.hotel_cd)}<br />
				</td>
			</tr>
			<tr>
				<td bgcolor="#EEFFEE">
				施設名
				</td>
				<td>
					{strip_tags($views->hotel.hotel_nm)}<br />
				</td>
			</tr>
			<tr>
				<td bgcolor="#EEFFEE">
				都道府県
				</td>
				<td>
					{strip_tags($views->mast_pref.pref_nm)}  {strip_tags($views->mast_city.city_nm)} {strip_tags($views->mast_ward.ward_nm)}<br />
				</td>
			</tr>
		</table>