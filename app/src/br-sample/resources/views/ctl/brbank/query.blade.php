@extends('ctl.common.base')
@section('title', '銀行支店検索')
@inject('service', 'App\Http\Controllers\ctl\BrbankController')

@section('page_blade')
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message', $messages)

{{-- 検索             --}}<p>
{{-- フォーム         --}}{!! Form::open(['route' => ['ctl.brbank.query'], 'method' => 'post']) !!}
{{-- 引数             --}}<input type="hidden" name="next" value="{{$views->next}}">
{{--                  --}}<table border="1" cellspacing="0" cellpadding="4">
{{-- 銀行・支店名称   --}}  <tr><td nowrap bgcolor="#EEFFEE">銀行・支店名称</td>
{{-- キーワード入力   --}}  <td><input type="text" name="keyword" size="50" MAXLENGTH="100" value="{{$views->keyword ?? null}}"></td> {{--  ?? null 追記    --}}
{{-- サブミット       --}}  <td><input type="submit" value="検索"></td>
{{--                  --}}  </tr>
{{--                  --}}</table>
{{--                  --}}<div style="margin-top:0.5em;">複数条件のキーワードを設定する場合は、スペースで区切ってください。<br />
{{--                  --}}各のキーワード毎に部分一致で銀行・支店名称の漢字・カナを検索します。</div>
{{--                  --}}{!! Form::close() !!}
{{--                  --}}</p>

{{-- 選択             --}}<p>
	{{--以下フォームの書き換えあっているか？ <form action="{$v->env.source_path}{$v->env.module}/{$v->assign->next}" method="POST"> --}}
{{--   フォーム       --}}{!! Form::open(['url' => $views->next, 'method' => 'post']) !!}
{{-- 検索一覧         --}}
{{--  検索結果ある    --}}@if (!$service->is_empty($views->banks ?? [])) {{--  ?? [] 追記,['valuesは削除']    --}}
{{--    テーブル      --}}<table border="0" cellspacing="0" cellpadding="4">
{{-- /検索結果ある    --}}@endif
{{-- 銀行一覧         --}}@foreach ($views->banks ?? [] as $bank) {{--  ?? [] 追記,['valuesは削除']    --}}
{{-- 区切り線         --}}<tr><td colspan="5"><hr size="1"></td></tr>
{{-- 銀行コードと名称 --}}<tr><td>{{$bank['bank']['bank_cd']}}</td><td>:</td><td>{{$bank['bank']['bank_nm']}}</td>
{{-- 名称カナ         --}}<td>:</td><td>{{$bank['bank']['bank_kn']}}</td>
{{--                  --}}</tr>
{{-- 支店ある         --}}@if (!$service->is_empty($bank['branch']))
{{-- 支店             --}}<tr><td><br /></td><td colspan="4"><div>支店</div>
{{-- スクロール調整   --}}<div style="@if (count($bank['branch']) > 5)height:10em;@endif overflow:auto;">
{{--                  --}}<table border="0" cellpadding="0" cellspacing="4" width="100%">
{{--   支店一覧       --}} @foreach ($bank['branch'] as $branch)
{{--  ラジオボタン    --}}<tr><td><input type="radio" name="mast_bank_branch_cd" value="{{$bank['bank']['bank_cd']}},{{$branch['bank_branch_cd']}}" }<br /></td> {{--  $branch['bank_cd']はないので{{$bank['bank']['bank_cd']}}でいいか    --}}
{{-- 銀行コードと名称 --}}<td>{{$branch['bank_branch_cd']}}</td><td>:</td><td>{{$branch['bank_branch_nm'] ?? null}}</td> {{--  ?? null 追記    --}}
{{-- 名称カナ         --}}<td>:</td><td>{{$branch['bank_branch_kn']}}</td>
{{--                  --}}</tr>
{{--                  --}}@endforeach
{{--                  --}}</table>
{{--  /スクロール調整 --}}</dif>
{{--                  --}}</td></tr>
{{--  /支店ある       --}}@endif
{{-- /銀行一覧        --}}@endforeach
{{--  検索結果ある    --}}@if (!$service->is_empty($views->banks ?? [])) {{--  ?? [] 追記,['valuesは削除']    --}}
{{--    /テーブル     --}}</table>
{{--    セットボタン  --}}<input type="submit" name="getbank" value="銀行・支店コードをセットする。">
{{-- /検索結果ある    --}}@endif
{{-- /選択フォーム    --}}</form>
{{--                  --}}</p>

@endsection