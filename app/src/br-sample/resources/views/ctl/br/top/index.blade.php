{{-- MEMO: 移植元 public\app\ctl\views\brtop\index.tpl --}}

{{-- {include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="メインメニュー"} --}}
@extends('ctl.common.base')
@section('title', 'メインメニュー')

@section('headScript')
    <style type="text/css">
        .today {
            background-color: #FFCCFF;
        }
    </style>
@endsection

@section('page_blade')
    <br>
    {{-- メインメニュー --}}
    {{-- HACK: HTTP 動詞 POST になっている
        リソースの生成を伴わないリクエストは GET で済ませられないか検討。
        ボタンの形にしたいのであれば、 css で装飾できる。
    --}}
    <table border="0" cellspacing="12" cellpadding="8">
        <tr>
            <td style="background-color: #FFF9FF;" valign="top">
                <table border="1" cellspacing="0" cellpadding="4">
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.reserve', 'method' => 'post']) }}
                        <td nowrap width="100%">予約の検索</td>
                        <td nowrap><input type="submit" value=" 検索 "></td>
                        {{ Form::close() }}
                    </tr>
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.reserve.check', 'method' => 'post']) }}
                        <td nowrap width="100%">送客実績・料金変更</td>
                        <td nowrap><input type="submit" value=" 確認 "></td>
                        {{ Form::close() }}
                    </tr>
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.demand.result.list', 'method' => 'post']) }}
                        <td width="100%" nowrap="nowrap">送客請求実績</td>
                        <td nowrap="nowrap"><input value=" 確認 " type="submit"></td>
                        {{ Form::close() }}
                    </tr>
                </table>
                <br>
                <table border="1" cellspacing="0" cellpadding="4">
                    <tr>
                        {{ Form::open(['route' => 'ctl.brhotel.index', 'method' => 'get']) }}
                        <td nowrap width="100%">施設の登録・変更</td>
                        <td nowrap><input type="submit" value=" 施設 "></td>
                        {{ Form::close() }}
                    </tr>
                </table>
                <br>
                <table border="1" cellspacing="0" cellpadding="4">
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.partner', 'method' => 'post']) }}
                        <td nowrap width="100%">パートナー設定</td>
                        <td nowrap><input type="submit" value=" 設定 "></td>
                        {{ Form::close() }}
                    </tr>
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.affiliate', 'method' => 'post']) }}
                        <td nowrap width="100%">アフィリエイト設定</td>
                        <td nowrap><input type="submit" value=" 設定 "></td>
                        {{ Form::close() }}
                    </tr>
                    <tr>
                        {{ Form::open(['url' => '/ctl/brpartnercustomer/', 'method' => 'get']) }}
                        <td nowrap width="100%">精算先設定</td>
                        <td nowrap><input type="submit" value=" 設定 "></td>
                        {{ Form::close() }}
                    </tr>
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.top.payment', 'method' => 'post']) }}
                        <td nowrap width="100%">支払</td>
                        <td nowrap><input type="submit" value=" 表示 "></td>
                        {{ Form::close() }}
                    </tr>
                </table>
                <br>
                <table border="1" cellspacing="0" cellpadding="4">
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.top.confirmation', 'method' => 'post']) }}
                        <td nowrap width="100%">確認</td>
                        <td nowrap><input type="submit" value=" 表示 "></td>
                        {{ Form::close() }}
                    </tr>
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.top.registration', 'method' => 'post']) }}
                        <td nowrap width="100%">登録</td>
                        <td nowrap><input type="submit" value=" 表示 "></td>
                        {{ Form::close() }}
                    </tr>
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.top.offer', 'method' => 'post']) }}
                        <td nowrap width="100%">提供</td>
                        <td nowrap><input type="submit" value=" 表示 "></td>
                        {{ Form::close() }}
                    </tr>
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.top.stock', 'method' => 'post']) }}
                        <td nowrap width="100%">仕入</td>
                        <td nowrap><input type="submit" value=" 表示 "></td>
                        {{ Form::close() }}
                    </tr>
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.top.claim', 'method' => 'post']) }}
                        <td nowrap width="100%">請求書・支払書</td>
                        <td nowrap><input type="submit" value=" 表示 "></td>
                        {{ Form::close() }}
                    </tr>
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.group.buying.deals', 'method' => 'post']) }}
                        <td nowrap width="100%">ベストク（クーポン）</td>
                        <td nowrap><input type="submit" value=" 表示 "></td>
                        {{ Form::close() }}
                    </tr>
                </table>
            </td>

            <td style="background-color: #FFFFEF;" valign="top">
                <table border="1" cellspacing="0" cellpadding="4">
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.top.inspect', 'method' => 'post']) }}
                        <td nowrap width="100%">会員情報の確認・変更</td>
                        <td nowrap><input type="submit" value=" 確認 "></td>
                        {{ Form::close() }}
                    </tr>
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.voice', 'method' => 'post']) }}
                        <td nowrap width="100%">クチコミ投稿表示・返信</td>
                        <td nowrap><input type="submit" value=" 確認 "></td>
                        {{ Form::close() }}
                    </tr>
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.point', 'method' => 'post']) }}
                        <td nowrap width="100%">ＢＲポイント・ギフト・サービスの管理</td>
                        <td><input type="submit" value=" 確認 "></td>
                        {{ Form::close() }}
                    </tr>
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.mail.magazine', 'method' => 'post']) }}
                        <td nowrap width="100%"><span style="color: #bfbfbf;">メールマガジン 差し込み可</span></td>
                        <td nowrap>
                            <input type="hidden" name="send_system" value="reserve">
                            <input type="submit" value=" 設定 ">
                        </td>
                        {{ Form::close() }}
                    </tr>
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.mail.magazine2', 'method' => 'post']) }}
                        <td nowrap width="100%">メールマガジン 差し込み<s>不</s>可</td>
                        <td nowrap><input type="submit" value=" 設定 "></td>
                        {{ Form::close() }}
                    </tr>
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.mail.magazine2', 'method' => 'post']) }}
                        <td nowrap width="100%">メール一括送信プログラムについて</td>
                        <td nowrap>
                            <a href="javascript:void(0);" onclick="openWin()">説明</a>
                            <script language="JavaScript" type="text/javascript">
                                function openWin() {
                                    newWin = window.open(
                                        'http://logbook.bestrsv.com/index_tsv_sendmail.html',
                                        'tsv_sendmail',
                                        'width=1200,height=900,scrollbars=no,status=no,toolbar=no,location=no,menubar=no,resizable=yes'
                                    );
                                    newWin.focus();
                                }
                            </script>
                        </td>
                        {{ Form::close() }}
                    </tr>
                </table>
                <br>
                <table border="1" cellspacing="0" cellpadding="4">
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.change.pass', 'method' => 'post']) }}
                        <td nowrap width="100%">管理画面用パスワード変更</td>
                        <td nowrap><input type="submit" value=" 確認 "></td>
                        {{ Form::close() }}
                    </tr>
                    <tr>
                        {{ Form::open(['route' => 'ctl.br.top.kbs.brv.tool.member.touroku', 'method' => 'post']) }}
                        <td nowrap width="100%">管理画面操作者登録</td>
                        <td nowrap><input type="submit" value=" 登録 "></td>
                        {{ Form::close() }}
                    </tr>
                </table>
            </td>

            {{-- スケージュールの表示 --}}
            <td valign="top">
                <strong>-- スケジュール --</strong>
                <br>
                <br>

                {{-- 先月 --}}
                @include('ctl.common._date', [
                    'timestamp' => $last_month->format('Y-m-d'),
                    'format' => 'ym',
                ])
                <table border="0" cellpadding="4" cellspacing="0">
                    @foreach ($schedules['last_month'] as $schedule)
                        <tr>
                            <td>{{ $schedule->schedule_nm }}</td>
                            @if (is_null($schedule->date_ymd))
                                <td>
                                    {{-- TODO: 実装時確認 ym の形式, day は 1 で固定にする？ --}}
                                    <a
                                        href="{{ route('ctl.br.money.schedule.new', [
                                            'Money_Schedule' => [
                                                'money_schedule_id' => $schedule->money_schedule_id,
                                                'ym' => $last_month->format('Y-m-01'),
                                            ],
                                        ]) }}">
                                        登録する
                                    </a>
                                </td>
                            @else
                                <td class="{{ $schedule->date_ymd == date('Y-m-d') ? 'today' : '' }}">
                                    @include('ctl.common._date', [
                                        'timestamp' => $schedule->date_ymd,
                                        'format' => 'ymd(w)',
                                        'color_on' => true,
                                    ])
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </table>
                <br>

                {{-- 当月 --}}
                @include('ctl.common._date', [
                    'timestamp' => $this_month->format('Y-m-d'),
                    'format' => 'ym',
                ])
                <table border="0" cellpadding="4" cellspacing="0">
                    @foreach ($schedules['this_month'] as $schedule)
                        <tr>
                            <td>{{ $schedule->schedule_nm }}</td>
                            @if (is_null($schedule->date_ymd))
                                <td>
                                    {{-- TODO: 実装時確認 ym の形式, day は 1 で固定にする？ --}}
                                    <a
                                        href="{{ route('ctl.br.money.schedule.new', [
                                            'Money_Schedule' => [
                                                'money_schedule_id' => $schedule->money_schedule_id,
                                                'ym' => $this_month->format('Y-m-01'),
                                            ],
                                        ]) }}">
                                        登録する
                                    </a>
                                </td>
                            @else
                                <td class="{{ $schedule->date_ymd == date('Y-m-d') ? 'today' : '' }}">
                                    @include('ctl.common._date', [
                                        'timestamp' => $schedule->date_ymd,
                                        'format' => 'ymd(w)',
                                        'color_on' => true,
                                    ])
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </table>
                <br>

                {{-- 翌月 --}}
                @include('ctl.common._date', [
                    'timestamp' => $next_month->format('Y-m-d'),
                    'format' => 'ym',
                ])
                {{ $next_month->format('Y年m月') }}
                <table border="0" cellpadding="4" cellspacing="0">
                    @foreach ($schedules['next_month'] as $schedule)
                        <tr>
                            <td>
                                {{ $schedule->schedule_nm }}
                            </td>
                            @if (is_null($schedule->date_ymd))
                                <td>
                                    {{-- TODO: 実装時確認 ym の形式, day は 1 で固定にする？ --}}
                                    <a
                                        href="{{ route('ctl.br.money.schedule.new', [
                                            'Money_Schedule' => [
                                                'money_schedule_id' => $schedule->money_schedule_id,
                                                'ym' => $next_month->format('Y-m-01'),
                                            ],
                                        ]) }}">
                                        登録する
                                    </a>
                                </td>
                            @else
                                <td class="{{ $schedule->date_ymd == date('Y-m-d') ? 'today' : '' }}">
                                    @include('ctl.common._date', [
                                        'timestamp' => $schedule->date_ymd,
                                        'format' => 'ymd(w)',
                                        'color_on' => true,
                                    ])
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </table>

            </td>
        </tr>
    </table>
    <div style="text-align: left;">
        <ul>
            @foreach ($licenses as $license)
                <li style="padding:0; margin:0;">{{ $license->license_token }}</li>
            @endforeach
        </ul>
    </div>
    {{-- {include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'} --}}
@endsection
