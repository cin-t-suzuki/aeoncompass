
@extends('ctl.common.brPartnerCustomerBaseToBeRenamed')

@section('title', 'パートナー精算一覧')


@section('headScript')
    {{-- TODO: css 外部ファイル化 --}}
    {{-- ここから svn_trunk\public\app\ctl\view2\brpartnercustomer\_css.tpl --}}
        <style type="text/css">
            /* <!-- TODO: コメントアウトっぽいの書かれてるけど、CSS内でコメントアウトされてんのかな。*/
            /* 社内向け管理：明細一覧 */
            table.br-detail-list {
            text-align: left;
            border: 2px ridge;
            border-collapse: collapse;
            }
            
            table.br-detail-list th {
            text-align: left;
            background-color: #EEFFEE;
            border: 2px ridge;
            padding: 3px;
            white-space: nowrap;
            }
            
            
            table.br-detail-list td {
            text-align: left;
            color: #3c3c3c;
            border: 2px ridge;
            padding: 3px;
            }
            
            table.br-detail-list td.nowrap {
            text-align: left;
            color: #3c3c3c;
            border: 2px ridge;
            padding: 3px;
            white-space: nowrap;
            }
            
            table.br-detail-list td hr {
            color: #ffffff;
            background-color: #ffffff;
            border :none;
            border-top: 1px dashed #ccc;
            }
            
            table.br-detail-list .charge {
            text-align: right;
            white-space: nowrap;
            }
            
            /* --> */
        </style>
    {{-- ここまで svn_trunk\public\app\ctl\view2\brpartnercustomer\_css.tpl --}}
    
    {{-- TODO: js 外部ファイル化 --}}
    {{-- ここから \svn_trunk\public\app\ctl\view2\brpartnercustomer\_js.tpl --}}
        <script language="JavaScript" type="text/javascript">
            // <!-- TODO: コメントアウトしようとしてる感あるけど、できてない。これは必要なコードなの？要らないコードなの？
            function helpForm() {
                var f = document.getElementById('help');
                if (f.style.display == 'none') {
                    f.style.display = 'block';
                } else {
                    f.style.display = 'none';
                }
            }
            //-->
        </script>
    {{-- ここまで \svn_trunk\public\app\ctl\view2\brpartnercustomer\_js.tpl --}}
@endsection


@section('content')
<hr class="contents-margin" />

<h1>
    パートナー＆アフィリエイト 精算先一覧
</h1>
<p>
    この画面はパートナーおよびアフィリエイトの精算先の登録と<br>精算先に紐づく提携先コードやアフィリエイトコード毎の情報である「サイト」を登録する画面です。<br>サイトの登録時にそのサイトに紐づける提携先コードまたはアフィリエイトコードを指定することで<br>精算先と提携先コードまたはアフィリエイトコードが紐付された状態になります。
</p>

{{-- はりぼてオブジェクト --}}
@php
    $v = new stdClass;
    $v->assign = new stdClass;
    $v->assign->search_params = [
        'key1' => 'item1',
        'key2' => 'item2',
        'customer_id' => 'non_output',
    ];
    $v->assign->customers = [
        [
            'customer_id' => '1',
            'customer_nm' => '株式会社日本旅行',
            'person_post' => '', // 空も用
            'person_nm' => '辻井　康一', // 空も用意
            'mail_send' => '1', // 0も用意
            'tel' => '0357193741',
            'fax' => '',
            'email_decrypt' => 'hideyuki_akiyama@nta.co.jp,coichi_tsujii@nta.co.jp',
            'billpay_day' => '10',
            'billpay_required_month' => '111111111111', // 月ごとに 0/1
            'billpay_charge_min' => 50000,
            'site_cd' => 'site_cd_val', // 空も用意
            'site_nm' => 'BestReserve',
            'partner_cd' => '0000000000', // 空も用意
            'affiliate_cd' => 'affiliate_cd_val', // 空も用意
            'sales_cnt' => '1', // DB から取得するとしたら、文字列型になりそう
            'stock_cnt' => '716',
        ],
        [
            'customer_id' => '3',
            'customer_nm' => '株式会社ベストリザーブ',
            'person_post' => '企画・開発部', // 空も用
            'person_nm' => '嶋田　至', // 空も用意
            'mail_send' => '0', // 0も用意
            'tel' => '06-6253-3800',
            'fax' => '06-6253-3801',
            'email_decrypt' => 'dev@bestrsv.com',
            'billpay_day' => '8',
            'billpay_required_month' => '100100001000', // 月ごとに 0/1
            'billpay_charge_min' => 50000,
            'site_cd' => '1', // 空も用意
            'site_nm' => 'BestReserve',
            'partner_cd' => '0000000000', // 空も用意
            'affiliate_cd' => 'affiliate_cd_val', // 空も用意
            'sales_cnt' => '1', // DB から取得するとしたら、文字列型になりそう
            'stock_cnt' => '0',
        ],
        [
            'customer_id' => '405',
            'customer_nm' => '株式会社カカクコム',
            'person_post' => 'サービス事業本部　サービスマーケティング1部', // 空も用
            'person_nm' => '登坂温美', // 空も用意
            'mail_send' => '0', // 0も用意
            'tel' => '03-4530-6412',
            'fax' => '',
            'email_decrypt' => 'koichi.ami@bestrsv.com',
            'billpay_day' => '8',
            'billpay_required_month' => '111111111111', // 月ごとに 0/1
            'billpay_charge_min' => 0,
            'site_cd' => 'a', // 空も用意
            'site_nm' => '旅行の口コミサイト「フォートラベル」',
            'partner_cd' => '', // 空も用意
            'affiliate_cd' => '0A30000003', // 空も用意
            'sales_cnt' => '1', // DB から取得するとしたら、文字列型になりそう
            'stock_cnt' => '0',
        ],
        [
            'customer_id' => 'customer_id_val',
            'customer_nm' => 'customer_nm_val',
            'person_post' => 'person_post_val', // 空も用
            'person_nm' => 'person_nm_val', // 空も用意
            'mail_send' => '1', // 0も用意
            'tel' => '080-1234-5678',
            'fax' => '080-9876-5432',
            'email_decrypt' => 'abc@sample.com,def@sample.co.jp',
            'billpay_day' => '25',
            'billpay_required_month' => '000000000000', // 月ごとに 0/1
            'billpay_charge_min' => 10000,
            'site_cd' => 'site_cd_val', // 空も用意
            'site_nm' => 'site_num_val',
            'partner_cd' => 'partner_cd_val', // 空も用意
            'affiliate_cd' => 'affiliate_cd_val', // 空も用意
            'sales_cnt' => '1', // DB から取得するとしたら、文字列型になりそう
            'stock_cnt' => '3',
        ],
        [
            'customer_id' => 'customer_id_val2',
            'customer_nm' => 'customer_nm_val2',
            'person_post' => '', // 空も用
            'person_nm' => '', // 空も用意
            'mail_send' => '0', // 0も用意
            'tel' => '080-1234-5611',
            'fax' => '080-9876-5422',
            'email_decrypt' => 'abc2@sample.com,def2@sample.co.jp',
            'billpay_day' => '10',
            'billpay_required_month' => '110100011100', // 月ごとに 0/1
            'billpay_charge_min' => 20000,
            'site_cd' => '', // 空も用意
            'site_nm' => 'site_num_val2',
            'partner_cd' => '', // 空も用意
            'affiliate_cd' => '', // 空も用意
            'sales_cnt' => 5,
            'stock_cnt' => 8,
        ],
    ];
    $v->assign->search_params = [
        'key1' => 'value1',
        'key2' => 'value2',
    ];
    $v->user = new stdClass;
    $v->user->operator = new stdClass;
    $v->user->operator->is_login = true;
    $v->user->operator->is_staff = true;
    $v->user->operator->staff_nm = 'staff_name_val';

    $v->env = [
        'controller' => "brtop",
        'action' => "index",
        'source_path' => 'source_path_val',
        'module' => 'module_val',
        'path_base_module' => 'path_base_module_val',
    ];
    $v->config = new stdClass;
    $v->config->environment = new stdClass;
    $v->config->environment->status = 'value';
@endphp

<div style="text-align:left;">

    {{-- TODO: 検索フォーム 外部テンプレ化 --}}{{-- svn_trunk\public\app\ctl\view2\brpartnercustomer\_form.tpl --}}
        <form action="{{ $v->env['path_base_module'] }}/brpartnercustomer/search/" method="post">
            <p>
                <table class="br-detail-list">
                    <tr>
                        <th>キーワード</th>
                        <td>
                            {{-- TODO: オブジェクトが実装されたら修正 --}}
                            <input type="text" name="keywords" size="50" maxlength="20" value="{{ '$v->helper->form->strip_tags($v->assign->form_params.keywords)' }}" />
                            <br /><a href="" onclick="helpForm(); return false;">キーワードのヘルプ</a>{{-- TODO: onlcick は見逃してよい？ --}}
                        </td>
                    </tr>
                </table>
            </p>
            <p>
                <input type="submit" value="　検索　" />
            </p>
        </form>
        {{-- TODO: キーワード検索のヘルプ 外部テンプレ化 --}}{{-- svn_trunk\public\app\ctl\view2\brpartnercustomer\_form_help.tpl --}}
            <div id="help" style="border: 1px solid rgb(0, 0, 0); display: none; position: absolute; background-color: rgb(255, 255, 255);" align="left">
                <div style="margin: 2px 4px; text-align: right;"><a href="" onclick="helpForm();return false;"><nobr>×閉じる</nobr></a></div>
                <div style="font-size:10px;margin-top:8px">
                    半角スペース区切りで 複数キーワード設定可能です。
                </div>
                <div style="font-size:10px;margin-top:8px">
                    数値は、下記での検索を完全一致にて行います。
                    <ul style="margin-top:0px">
                        <li>精算先ID</li>
                    </ul>
                </div>
                <div style="font-size:10px;margin-top:8px">
                    文字列は、下記いずれかでの検索を部分一致にて行います。
                    <ul style="margin-top:0px">
                        <li>精算先名称</li>
                        <li>精算サイト名称</li>
                    </ul>
                </div>
                <div style="font-size:10px;margin-top:8px">
                    10桁のアルファベットを含む英数字の文字列は、下記いずれかでの検索を完全一致にて行います。
                    <ul style="margin-top:0px">
                        <li>パートナーコード</li>
                        <li>アフィリエイトコード</li>
                    </ul>
                </div>
                <div style="font-size:10px;margin-top:8px">
                    10桁の数字の文字列は、下記いずれかでの検索を完全一致にて行います。
                    <ul style="margin-top:0px">
                        <li>パートナーコード</li>
                        <li>アフィリエイトコード</li>
                        <li>精算サイトコード</li>
                    </ul>
                </div>
            </div>
        {{-- / キーワード検索のヘルプ --}}
    {{-- / 検索フォームテンプレ --}}

    <hr class="contents-margin" />

    {{-- TODO: Formファサードで書き換える --}}
    {{-- TODO: route() に変更 --}}
    <form action="ctl/brpartnercustomer/edit/" method="POST">
        <small>
            <input type="submit" value="新規登録">

            @foreach ($v->assign->search_params as $key => $value)
                @if ($key != 'customer_id')
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                @endif
            @endforeach
        </small>
    </form>
</div>

{{-- 一覧表示 --}}
<table class="br-detail-list">
    <tr>
        <th>精算先ID</th>
        <th>精算先</th>
        <th>通知方法<br />連絡先</th>
        <th>精算パターン</th>
        <th>対象サイト</th>
        <th></th>
    </tr>
    @foreach ($v->assign->customers as $customer)
        <tr>
            <td class="charge">{{ $customer['customer_id'] }}</td>
            <td>
                {{ $customer['customer_nm'] }}
                @if (!empty($customer['person_post']))
                    <br />{{ $customer['person_post'] }}
                @endif
                @if (!empty($customer['person_nm']))
                    <br />{{ $customer['person_nm'] }} 様
                @endif
            </td>
            <td>
                通知方法：
                @if ($customer['mail_send'] == '1')
                    メールで通知する
                @else
                    郵送（手動印刷）
                @endif
                <br />
                tel:{{ $customer['tel'] }} fax:{{ $customer['fax'] }}<br />

                email:
                @foreach (explode(',', $customer['email_decrypt']) as $email)
                    {{ $email }}<br />
                @endforeach
            </td>
            <td>
                締め日：{{ $customer['billpay_day'] }}日<br />
                @if ($customer['billpay_required_month'] == '000000000000')
                    指定なし
                @elseif ($customer['billpay_required_month'] == '111111111111')
                    必須月：毎月
                @else
                    @for ($m = 1; $m <= 12; $m++)
                        @if ($customer['billpay_required_month'][$m-1] == '1')
                            {{ $m }}{{ "月 " }}
                        @endif
                    @endfor
                @endif
                <br />
                最低金額：{{ number_format($customer['billpay_charge_min']) }}
                {{-- TODO: ビューにわたってきた時点で、カンマ区切りの文字列になっている可能性？（カンマ区切りにするのは、ビューの仕事にしたい。） --}}
            </td>

            <td>
                @if (empty($customer['site_cd']))
                    対象サイトを設定してください。
                    {{-- TODO: action を route() で置き換える --}}
                    <form action="brpartnersite/search/" method="post">
                        <input type="submit" value=" 設定 ">
                        <input type="hidden" name="customer_id" value="{{ $customer['customer_id'] }}" />

                        @foreach ($v->assign->search_params as $key => $value)
                            @if ($key != 'customer_id' && $key != 'customer_off')
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                            @endif
                        @endforeach
                    </form>
                @else
                    @if (!empty($customer['partner_cd']))
                        パートナー: {{ $customer['site_nm'] }}（{{ $customer['partner_cd'] }}）
                    @elseif (!empty($customer['affiliate_cd']))
                        アフィリエイト: {{ $customer['site_nm'] }}（{{ $customer['affiliate_cd'] }}）
                    @endif
                    <br >
                    @if ($customer['sales_cnt'] + $customer['stock_cnt'] > 1)
                        その他サイト {{ $customer['sales_cnt'] + $customer['stock_cnt'] - 1 }}件<br />
              @endif
                    {{-- TODO: route() で書き換える --}}
                    <form action="brpartnersite/search/" method="post">
                        <input type="submit" value=" サイト表示 ">
                        <input type="hidden" name="customer_id"      value="{{ $customer['customer_id'] }}" />
                        @foreach ($v->assign->search_params as $key => $value)
                            @if ($key != 'customer_id')
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                            @endif
                        @endforeach
                    </form>
                @endif
            </td>
            <td style="text-align:center;">
                <form action="brpartnercustomer/edit/" method="post">
                    <input type="submit" value=" 表示 ">
                    <input type="hidden" name="customer_id" value="{{ $customer['customer_id'] }}" />
                    @foreach ($v->assign->search_params as $key => $value)
                        @if ($key != 'customer_id')
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                        @endif
                    @endforeach
                </form>
            </td>
        </tr>
    @endforeach
</table>

<hr class="contents-margin" />
@endsection