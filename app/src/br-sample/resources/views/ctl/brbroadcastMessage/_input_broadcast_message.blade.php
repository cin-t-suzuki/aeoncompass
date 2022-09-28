<table class="br-detail-list">
	<tr>
			<th style="height: 50px;">ページ上部表示文言</th>
			<td colspan="2">
				<p>※ページ上部に表示したい場合のみ入力してください。</p>
				<p>※HTMLで入力を行ってください。使用可能なタグは以下に限ります。</p>
				<p>&lt;p&gt;&lt;a&gt;&lt;br&gt;&lt;font&gt;&lt;img&gt;&lt;small&gt;&lt;span&gt;&lt;strong&gt;</p>
				<textarea name="header_message"cols="60" rows="6">{{ $form_params['header_message'] }}</textarea>
			</td>
	</tr>
	<tr>
		<th>ページ上部表示期間</th>
		<td colspan="2">
				<p>※ページ上部表示文言が未入力の場合はこの期間は保存されません</p>　
				<table border="0" cellspacing="0" cellpadding="0">
						<tbody>
								<tr>
										<td style="border:0;">
											<input type="text" 
														name="accept_header_s_year"
														style="height: 30px; width:40px;" 
														value="{{ strip_tags($form_params['accept_header_s_year']) }}"" />
											年&nbsp;
											<select name="accept_header_s_month" style="height:30px;">
												@foreach ($accept_header_ymd_selecter['month'] as $accept_header_s_month)
													<option value={{ $accept_header_s_month }} @if ( $form_params['accept_header_s_month'] == $accept_header_s_month )
															selected="selected" 
														@endif > 
															{{$accept_header_s_month}}
													</option>
												@endforeach 
											</select>
											月&nbsp;
											<select name="accept_header_s_day" style="height:30px;">
												@foreach ($accept_header_ymd_selecter['day'] as $accept_header_s_day)
													<option value={{$accept_header_s_day}}   
																	@if ($form_params['accept_header_s_day'] == $accept_header_s_day) selected="selected" 
																	@endif > {{$accept_header_s_day}}  </option>
												@endforeach
											</select>
											日
											<select name="accept_header_s_time" style="height:30px;">
											 @foreach ($accept_header_time_selecter['time'] as $accept_header_s_time)
													<option value={{$accept_header_s_time}} 
																	@if ($form_params['accept_header_s_time'] == $accept_header_s_time) selected="selected"
																	@endif >{{$accept_header_s_time}}
													</option>
												@endforeach
											</select>
										</td>
								</tr>
								<tr>
										<td style="border:0;">
										{{-- 対象期間終了日(予約日) --}}
											<input type="text" 
														name="accept_header_e_year"
														style="height: 30px; width:40px;" 
														value="{{strip_tags($form_params['accept_header_e_year']) }}"" />
											年&nbsp;
											<select name="accept_header_e_month" style="height:30px;">
													@foreach ($accept_header_ymd_selecter['month'] as $accept_header_e_month)
													<option value={{$accept_header_e_month}} 
																	@if ($form_params['accept_header_e_month'] == $accept_header_e_month) selected="selected"
																	@endif >  {{$accept_header_e_month}}
													</option>
												@endforeach

											</select>
											月&nbsp;
										 <select name="accept_header_e_day" style="height:30px;">
												@foreach ($accept_header_ymd_selecter['day'] as $accept_header_e_day)
													<option value={{$accept_header_e_day}} @if ($form_params['accept_header_e_day'] == $accept_header_e_day) selected="selected" @endif > {{$accept_header_e_day}}</option>
												@endforeach
											</select>
											日
											<select name="accept_header_e_time" style="height:30px;">
												@foreach ($accept_header_time_selecter['time'] as $accept_header_e_time)
													<option value={{$accept_header_e_time}} 
																@if ($form_params['accept_header_e_time'] == $accept_header_e_time)selected="selected" 
																@endif > {{$accept_header_e_time}}
													</option>
												@endforeach
											</select>
										</td>
								</tr>
						</tbody>
				</table>
				<br />
			</td>
		</tr>
	<tr>
		<th style="height: 50px;">お知らせ欄タイトル<span class="required">*</span></th>
		<td colspan="2">
				<p>※HTMLで入力を行ってください。使用可能なタグは以下に限ります。</p>
				<p>&lt;font&gt;</p>
			<textarea name="title"cols="60" rows="3">{{ $form_params['title'] }}</textarea>
		</td>
	</tr>
 <tr>
			<th style="height: 180px;">お知らせ詳細<span class="required">*</span></th>
			<td style="width: 550px;" colspan="2">
				<p>※HTMLで入力を行ってください。使用可能なタグは以下に限ります。</p>
				<p>&lt;ul&gt;&lt;li&gt;&lt;a&gt;&lt;pre&gt;&lt;br&gt;&lt;font&gt;&lt;img&gt;&lt;small&gt;&lt;span&gt;&lt;strong&gt;</p>
				<textarea name="description" cols="60" rows="10">{{ $form_params['description'] }}</textarea>
			</td>
	</tr>
	<tr>
			<th>お知らせ欄表示期間<span class="required">*</span></th>
			<td colspan="2">
					<table border="0" cellspacing="0" cellpadding="0">
							<tbody>
									<tr>
											<td style="border:0;">
												<input type="text" 
														name="accept_s_year"
														style="height: 30px; width:40px;" 
														value="{{ strip_tags( $form_params['accept_s_year'] ) }}"" />
												年&nbsp;
												<select name="accept_s_month" style="height:30px;">
													@foreach ( $accept_ymd_selecter['month'] as $accept_s_month)
														<option value= {{ $accept_s_month }} 
																		@if ($form_params['accept_s_month'] == $accept_s_month) selected="selected" 
																		@endif > {{$accept_s_month}} </option>
													@endforeach
												</select>
												月&nbsp;
												<select name="accept_s_day" style="height:30px;">
													@foreach ($accept_ymd_selecter['day'] as $accept_s_day)
														<option value={{ $accept_s_day }} @if ($form_params['accept_s_day'] == $accept_s_day) selected="selected" @endif > {{$accept_s_day}} </option>
													@endforeach
												</select>
												日
												<select name="accept_s_time" style="height:30px;">
														@foreach ($accept_time_selecter['time'] as $accept_s_time)
															<option value={{$accept_s_time}} 
																		@if ($form_params['accept_s_time'] == $accept_s_time) selected="selected" 
																		@endif > {{$accept_s_time}}
															</option>
														@endforeach
												</select>
											</td>
									</tr>
									<tr>
											<td style="border:0;">
											<input type="text" 
														name="accept_e_year"
														style="height: 30px; width:40px;" 
														value="{{ strip_tags($form_params['accept_e_year']) }}"" />
												年&nbsp;
												<select name="accept_e_month" style="height:30px;">
													@foreach ($accept_ymd_selecter['month'] as $accept_e_month)
														<option value={{$accept_e_month}} 
																@if ($form_params['accept_e_month'] == $accept_e_month) selected="selected" 
																@endif > {{$accept_e_month}} </option>
													@endforeach
												</select>
												月&nbsp;
												<select name="accept_e_day" style="height:30px;">
													@foreach ($accept_ymd_selecter['day'] as $accept_e_day)
														<option value= {{$accept_e_day}} 
																		@if ($form_params['accept_e_day'] == $accept_e_day) selected="selected" 
																		@endif > {{$accept_e_day}} </option>
													@endforeach
												</select>
												日
												<select name="accept_e_time" style="height:30px;">
													@foreach ($accept_time_selecter['time'] as $accept_e_time)
														<option value= {{ $accept_e_time }} 
																		@if ($form_params['accept_e_time'] == $accept_e_time) selected="selected" 
																		@endif > {{$accept_e_time}} </option>
													@endforeach
												</select>
											</td>
									</tr>
							</tbody>
					</table>
					<br />
			</td>
	</tr>
	<tr>
		<th style="height: 50px;">特定施設指定</th>
		<td style="width: 550px;" colspan="2">
			<p>※全施設が対象になります</p>
			<input type="hidden" name="target_hotels" cols="60" rows="60" >{{ $form_params['target_hotels'] }}</input>
		</td>
	</tr>
</table>
