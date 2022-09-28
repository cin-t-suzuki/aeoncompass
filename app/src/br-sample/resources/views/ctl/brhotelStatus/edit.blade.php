@section('title', '施設情報変更(登録状態変更)')
@include('ctl.common.base')

{{-- メッセージ --}}
@section('message')
@include('ctl.common.message', $messages)

{!! Form::open(['route' => ['ctl.brhotelStatus.update'], 'method' => 'post']) !!}
	<table border="1" cellpadding="4" cellspacing="0">
		<tr>
			<td bgcolor="#EEFFEE" >登録状態</td>
			<td>
				<input type="radio" name="hotel_status[entry_status]" value="0" @if ($views->hotel_status['entry_status'] == 0) checked @endif id="entry_status_0" @if (!$views->rate_chk) disabled @endif /><label for="entry_status_0" >公開中</label>
				<input type="radio" name="hotel_status[entry_status]" value="1" @if ($views->hotel_status['entry_status'] == 1) checked @endif id="entry_status_1" /><label for="entry_status_1">登録作業中</label>
				<input type="radio" name="hotel_status[entry_status]" value="2" @if ($views->hotel_status['entry_status'] == 2) checked @endif id="entry_status_2" /><label for="entry_status_2">解約</label>
				<small>&nbsp;&nbsp;施設(買取以外)の料率情報が存在していない場合、公開中は選択できません。</small> 
			</td>
		</tr>
		<tr>
			<td bgcolor="#EEFFEE" >契約日</td>
			<td>
				<INPUT TYPE="text" NAME="hotel_status[contract_ymd]" SIZE="12" MAXLENGTH="10" value="{{strip_tags($views->hotel_status['contract_ymd'])}}">
				YYYY/MM/DD <small>又は</small> YYYY-MM-DD
			</td>
		</tr>
		<tr>
			<td bgcolor="#EEFFEE" >公開日</td>
			<td>
				<INPUT TYPE="text" NAME="hotel_status[open_ymd]" SIZE="12" MAXLENGTH="10" value="{{strip_tags($views->hotel_status['open_ymd'])}}">
				YYYY/MM/DD <small>又は</small> YYYY-MM-DD
			</td>
		</tr>
		<tr>
			<td bgcolor="#EEFFEE" >解約日</td>
			<td>
				@if(isset($views->hotel_status['close_dtm']))
					{{\Carbon\Carbon::createFromTimeString(strip_tags($views->hotel_status['close_dtm']))->format('Y-m-d H:i:s')}}
				@endif<br />
			</td>
		</tr>
		<tr>
			<td bgcolor="#EEFFEE" nowrap>登録日時</td>
			<td>{{\Carbon\Carbon::createFromTimeString(strip_tags($views->hotel_status['entry_ts']))->format('Y-m-d H:i:s')}}<br /></td>
		</tr>
	</TABLE>
	<input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
	<br />
	<INPUT TYPE="submit" VALUE="変　　　　　更">

{!! Form::close() !!}

<hr size="1">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<small>
				{!! Form::open(['route' => ['ctl.brhotel.show'], 'method' => 'post']) !!}
					<input type="hidden" name="target_cd" value="{strip_tags($views->target_cd)}" />
					<INPUT TYPE="submit" VALUE="施設情報変更へ">
				{!! Form::close() !!}
			</small>
		</td>
	</tr>
</TABLE>

@section('title', 'footer')
@include('ctl.common.footer')