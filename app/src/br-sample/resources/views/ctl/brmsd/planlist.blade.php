

@extends('ctl.common.base')
@section('title', 'MSD専用プラン一覧')

@section('page_blade')


合計：{{count($plan_list)}}プラン
<table border="1" cellpadding="4" cellspacing="0">
  <tr>
    <td bgcolor="#EEFFEE" nowrap rowspan="2">都道府県</td>
    <td bgcolor="#EEFFEE" nowrap rowspan="2">施設コード</td>
    <td bgcolor="#EEFFEE" nowrap rowspan="2">施設名称</td>
    <td bgcolor="#EEFFEE" nowrap rowspan="2">プランコード</td>
    <td bgcolor="#EEFFEE" nowrap rowspan="2">プラン名称</td>
    <td bgcolor="#EEFFEE" nowrap rowspan="2">部屋コード</td>
    <td bgcolor="#EEFFEE" nowrap rowspan="2">部屋名称</td>
    <td bgcolor="#EEFFEE" nowrap colspan="6">利用人数</td>
  </tr>
  <tr>
    <td bgcolor="#EEFFEE" nowrap>1名</td>
    <td bgcolor="#EEFFEE" nowrap>2名</td>
    <td bgcolor="#EEFFEE" nowrap>3名</td>
    <td bgcolor="#EEFFEE" nowrap>4名</td>
    <td bgcolor="#EEFFEE" nowrap>5名</td>
    <td bgcolor="#EEFFEE" nowrap>6名</td>
  </tr>

  @foreach ($plan_list as $value)
  <tr>
    <td nowrap>{{$value->pref_nm}}</td>
    <td nowrap>{{$value->hotel_cd}}</td>
    <td nowrap>{{$value->hotel_nm}}</td>
    <td>{{$value->plan_cd}}</td>
    <td>{{$value->plan_nm}}</td>
    <td>{{$value->room_cd}}</td>
    <td>{{$value->room_nm}}</td>
    <td>@if($value->capacity == 1) {{$value->capacity_value}} @else <br /> @endif</td>
    <td>@if($value->capacity == 2) {{$value->capacity_value}} @else <br /> @endif</td>
    <td>@if($value->capacity == 3) {{$value->capacity_value}} @else <br /> @endif</td>
    <td>@if($value->capacity == 4) {{$value->capacity_value}} @else <br /> @endif</td>
    <td>@if($value->capacity == 5) {{$value->capacity_value}} @else <br /> @endif</td>
    <td>@if($value->capacity == 6) {{$value->capacity_value}} @else <br /> @endif</td> 
  </tr>
@endforeach
</table>

{{ Form::open(['route' => 'ctl.brmsd.planlistcsv', 'method' => 'post']) }}
<input type="submit" value="CSVダウンロード" />
{!! Form::close() !!}
<br />
※CSVファイルをエクセルで開いた際には前ゼロが自動的に消されてしまいますのでご注意ください。<br />
<br />
前ゼロを残すためのエクセルの開き方
<ol>
  <li>CSVファイルを一旦保存します。</li>
  <li>スタートメニューからエクセルを起動</li>
  <li>「データ」->「外部データ取り込み」->「テキストファイル」を選択</li>
  <li>ダウンロードしたCSVファイルを選択</li>
  <li>ウィザードが表示されますので「カンマやタブなどの・・・」を選択し次へ</li>
  <li>「区切り文字」の「タブ」を外しを「カンマ」を設定し次へ</li>
  <li>「プランコード」「部屋コード」の欄が「G/標準」となっているので「文字列」を指定し「完了」ボタンを押下。</li>
  <li>インポートする開始位置を指定されますので「=$A$1」を指定して「OK」ボタンを押下</li>
</ol>

@endsection