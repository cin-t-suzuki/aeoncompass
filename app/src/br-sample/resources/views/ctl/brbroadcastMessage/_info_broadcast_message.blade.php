<table class="br-detail-list">
	<tr>
		<th style="height: 50px;">ページ上部表示文言</th>
		<td>{{ $form_params["header_message"] }}</td>
	</tr>

	<tr>
		<th>ページ上部表示期間</th>
		<td>
			<table border="0" cellspacing="0" cellpadding="0">
				<tbody>
					<tr>
						<td style="border:0;">
							{{-- 対象期間開始日 --}}
							@if (isset($form_params["header_message"]))
								{{  sprintf('%04d',$form_params["accept_header_s_year"]) }}年
								{{  sprintf('%0d',$form_params["accept_header_s_month"]) }}月
								{{  sprintf('%0d',$form_params["accept_header_s_day"])   }}日
								{{  $form_params["accept_header_s_time"]  }}
								&nbsp;～&nbsp;
								{{-- 対象期間終了日 --}}
								{{  sprintf('%04d',$form_params["accept_header_e_year"])  }}年
								{{  sprintf('%02d',$form_params["accept_header_e_month"]) }}月
								{{  sprintf('%02d',$form_params["accept_header_e_day"])   }}日
								{{  $form_params["accept_header_e_time"]  }}
							@endif
						</td>
					</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<th style="height: 50px;">お知らせ欄タイトル</th>
		<td>
			{{  $form_params["title"]  }}
		</td>
	</tr>
	<tr>
		<th style="height: 50px;">お知らせ詳細</th>
		<td>
			{!!  nl2br(  e(  $form_params["description"] ) )  !!}
		</td>
	</tr>
	<tr>
		<th>お知らせ欄表示期間</th>
		<td>
			<table border="0" cellspacing="0" cellpadding="0">
				<tbody>
					<tr>
						<td style="border:0;">
							{{-- 対象期間開始日 --}}
							{{  sprintf('%04d',$form_params["accept_s_year"]) }}年
							{{  sprintf('%02d',$form_params["accept_s_month"])  }}月
							{{  sprintf('%02d',$form_params["accept_s_day"])  }}日
							{{  $form_params["accept_s_time"]  }}
							&nbsp;～&nbsp;
							{{-- 対象期間終了日 --}}
							{{  sprintf('%04d',$form_params["accept_e_year"]) }}年
							{{  sprintf('%02d',$form_params["accept_e_month"])  }}月
							{{  sprintf('%02d',$form_params["accept_e_day"])  }}日
							{{  $form_params["accept_e_time"]  }}
						</td>
					</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<th style="height: 50px;">特定施設指定</th>
		<td>
			@if (isset($form_params["target_hotels"]))
					{!!  nl2br(  e(  $form_params["target_hotels"] ) ) !!}
			@else
				全施設
			@endif
		</td>
	</tr>
</table>
