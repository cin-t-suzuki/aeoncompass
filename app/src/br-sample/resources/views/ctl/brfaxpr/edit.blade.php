@section('title', '予約通知ＦＡＸ広告 掲載文章設定')
@include('ctl.common.base')

@section('error message')
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message', $messages)

<!--form method="POST" action="{$v->env.source_path}{$v->env.module}/brfaxpr/upd/"-->
{!! Form::open(['route' => ['ctl.brfaxPr.update'], 'method' => 'post']) !!}
	<input type="hidden" name="fax_pr_id" value="{{ strip_tags( $views->faxPr["fax_pr_id"] ) }}" />
	<table border="1" cellpadding="4" cellspacing="0">
		<tr>
			<td  bgcolor="#EEFFEE" >
				タイトル
			</td>
			<td>
				<input type="text" name="title" size="40" maxlength="16" value="{{ strip_tags( $views->faxPr["title"] ) }}">
			</td>
		</tr>
		<tr>
			<td bgcolor="#EEFFEE" >
				広告文章
			</td>
			<td>
				<textarea rows="8" name="note" cols="100" wrap="soft">{{ strip_tags( $views->faxPr["note"] ) }}</textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" value="登録">
				<input type="hidden" name="fax_pr_id" value="1" />
			</td>
		</tr>
	</table>
<!--TODO /form -->
{!! Form::close() !!}

@section('title', 'footer')
@include('ctl.common.footer')