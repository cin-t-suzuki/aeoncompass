<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>BestReserve宿泊予約</title>
	</head>
	<body>
		<a href="{{ route('ctl.brbank.index') }}">銀行支店マスタ</a> <br>
		<a href="{{ route('ctl.brbroadcastMessage.index') }}">施設管理TOPお知らせ情報管理</a><br>
		<a href="{{ route('ctl.top.index') }}">管理画面一覧</a><br>
		<a href="{{ route('ctl.brtop.index') }}">社内TOP</a><br>
		
		{!! Form::open(['route' => ['ctl.brfaxPr.edit'], 'method' => 'post']) !!}
			<input type="submit" value="予約通知ＦＡＸ広告 掲載文章"><br>
		{!! Form::close() !!}
		<a href="{{ route('ctl.htlhotelInfo.show', ['target_cd'=>'2015060001']) }}">施設情報 get&引数</a><br>
		{!! Form::open(['route' => ['ctl.htlhotelInfo.show'], 'method' => 'post']) !!}
			<input type="submit" value="施設情報 post"><br>
		{!! Form::close() !!}
		<a href="{{ route('ctl.brhotel.index') }}">施設情報メイン</a><br>
		<a href="{{ route('ctl.brhotel.edit', ['target_cd'=>'2015060001']) }}">施設情報更新</a><br>
		<a href="{{ route('ctl.brhotelStatus.index', ['target_cd'=>'2015060001']) }}">施設情報変更（登録状態変更）</a><br>

		<a href="{{ route('ctl.brhotelRate.index', ['target_cd'=>'2015060001']) }}">料率一覧</a><br>

		<a href="{{ route('ctl.brhotel.show', ['target_cd'=>'2015060001']) }}">詳細変更 施設各情報ハブ		</a><br>

		<a href="{{ route('ctl.br_hotel.new') }}">施設情報登録</a>
	</body>

</html>
