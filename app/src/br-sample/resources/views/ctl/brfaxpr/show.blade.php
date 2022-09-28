@section('title', '予約通知ＦＡＸ広告 掲載文章設定')
@include('ctl.common.base')

<br>
	{!! Form::open(['route' => ['ctl.brfaxPr.edit'], 'method' => 'post']) !!}
		<table border="0" cellpadding="4" cellspacing="0">
			<tr>
				<td>
					@section('message')
					{{-- メッセージbladeの読込 --}}
					@include('ctl.common.message', $messages)
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" value="戻る">
				</td>
			</tr>
		</table>
	{!! Form::close() !!}
<br>

@section('title', 'footer')
@include('ctl.common.footer')
