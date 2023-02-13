<hr size="1">
<!--form method="post" action="{$v->env.source_path}{$v->env.module}/brhotel/show/"-->
{!! Form::open(['route' => ['ctl.brhotel.show'], 'method' => 'post']) !!}
	<small>
	<input type="submit" value="詳細変更へ">
	<input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
</small>
{!! Form::close() !!}