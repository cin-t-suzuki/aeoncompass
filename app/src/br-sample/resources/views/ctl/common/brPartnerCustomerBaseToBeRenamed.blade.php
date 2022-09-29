{{-- ここから svn_trunk/public/app/ctl/view2/_common/_br_header2.tpl --}}
{{-- TODO: なぞ xml <?xml version="1.0" encoding="UTF-8"?> --}}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">{{-- TODO: なぞ doctype 宣言 --}}
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Cache-Control" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
        <meta name="robots" content="none" />
        {{-- TODO: jquery 存在確認 --}}<script type="text/javascript" src="/scripts/jquery.js"></script>
        <title>
            STREAM社内管理 @yield('title')
        </title>
        
        {{-- TODO: css link <link type="text/css" rel="stylesheet" href="{{ $v->env['path_base_module'] }}/css/style_base.css" /> --}}
        {{-- TODO: 外部ファイル化 svn_trunk\public\app\ctl\statics\css\style_base.css --}}
        <style type="text/css">
            @charset "UTF-8";

            /* html */
            html {
            overflow-y:scroll;
            }

            /* body */
            body {
            margin:  0;
            padding: 0;
            line-height: 18px;
            font-size: 12px;
            font-family: "メイリオ", Meiryo, "ＭＳ Ｐゴシック", sans-serif;
            height: 100%;
            }

            /* form */
            form {
            margin: 0;
            padding: 0;
            display: inline;
            }

            .form-button-container {
            display: inline-block;
            *display: inline;
            *zoom: 1;
            margin: 0 12px;
            }

            /* Clear-fix */
            .clear {
            clear:both;
            }

            /* 実行環境表示 */
            .envproduct {
            display:none;
            }

            .envdevelopment, .envtest, .env {
            text-align: left;
            color:#ffffff;
            font-weight:bold;
            padding:2px;
            width: 60px;
            white-space: nowrap;
            }

            .envdevelopment {
            background-color: #36A;
            }

            .envtest {
            background-color: #297;
            }

            .env {
            background-color: #A63;
            }

            .envdevelopment, .envtest, .env span{
            text-align: center;
            }

            .wrap, .wrapproduct, .wrapdevelopment, .wraptest {
            width: 100%;
            min-width: 1024px;
            width: expression(document.body.clientWidth < 1024 ? "1024px" : "auto");
            display: inline-box;
            }

            .wrapproduct {
            border-left: 0;
            padding: 0;
            margin:  0;
            }

            .wrap {
            border: 1px solid #ffffff;
            border-left: 4px solid #A63;
            }

            .wrapdevelopment {
            border: 1px solid #ffffff;
            border-left: 4px solid #36A;
            }

            .wraptest {
            border: 1px solid #ffffff;
            border-left: 4px solid #297;
            }


            /* メイン枠 */
            .active-contents {
            width: 100%;
            min-width: 1024px;
            max-width: 1024px;
            width:expression(document.body.clientWidth < 1024 ? "1024px" : "auto");
            text-align: center;
            margin: auto;
            padding: 0;
            }


            /* メッセージボックス - 基本 */
            .msg-box {
            width: 100%;
            margin: 0 auto;
            }

            .msg-box .msg-box-back {
            margin: 15px 0px;
            }

            .msg-box .msg-box-back .msg-box-contents {
            padding: 15px;
            border: 1px solid;
            text-align: left;
            font-weight: bold;
            border-radius: 3px;
            }

            /* メッセージボックス - 通知 */
            .msg-box-info {
            color: #00529B;
            background-color: #BDE5F8;
            }

            /* メッセージボックス - 成功 */
            .msg-box-success {
            color: #4F8A10;
            background-color: #DFF2BF;
            }

            /* メッセージボックス - 警告 */
            .msg-box-warning {
            color: #9F6000;
            background-color: #FEEFB3;
            }

            /* メッセージボックス - エラー */
            .msg-box-error {
            color: #D8000C;
            background-color: #FFBABA;
            }

            /* タグ風テキスト */
            .tag-text, .tag-text-info, .tag-text-error, .tag-text-warning, .tag-text-success, .tag-text-deactive {
            color: #ffffff;
            padding: 1px 4px;
            white-space: nowrap;
            font-weight: bold;
            border-radius: 3px;
            }

            /* タグ風テキスト - 通知 */
            .tag-text-info {
            background-color: #0066ff;
            }

            /* タグ風テキスト - エラー */
            .tag-text-error {
            background-color: #ED2502;
            }

            /* タグ風テキスト - 警告 */
            .tag-text-warning {
            background-color: #ff6600;
            }

            /* タグ風テキスト - 成功 */
            .tag-text-success {
            background-color: #2d9124;
            }

            /* タグ風テキスト - グレーアウト */
            .tag-text-deactive {
            background-color: #999999;
            }

            /* テキスト - 通知 */
            .msg-text-info {
            color: #0066ff;
            }

            /* テキスト - 成功 */
            .msg-text-success {
            color: #2d9124;
            }

            /* テキスト - エラー */
            .msg-text-error {
            color: red;
            }

            /* テキスト - 警告 */
            .msg-text-warning {
            color: #9F6000;
            }

            /* テキスト - グレーアウト */
            .msg-text-deactive {
            color: #999999;
            }

            /* リスト - 汎用 */
            .gen-list {
            margin: 0;
            padding: 0;
            list-style-type: none;
            list-style-position: outside;
            }

            /* 非表示 */
            .default-hide {
            display: none;
            }

            /* 区切り余白 */
            hr.bound-line {
            width: 100%;
            color: #ffffff;
            border-style: solid;
            }

            /* 区切り余白（大） */
            hr.bound-line-l {
            width: 100%;
            color: #ffffff;
            border-style: solid;
            margin: 12px 0;
            }


            /* 汎用コンテナ */
            .gen-container {
            width: 1024px;
            padding: 0;
            margin: 0 auto;
            }

            /* DEBUG(1024pxの幅チェック用) */
            .chk-w {
            width: 1024px;
            padding: 0;
            margin: 10px 0;
            background-color: #0000FF;
            color: #ffffff;
            text-align: right;
            }

            /* 汎用リスト（左揃え） */
            .list-l {
            text-align: left;
            margin: 0;
            padding: 0;
            list-style-type: none;
            list-style-position: outside;
            }

            .list-l li {
            padding: 0;
            margin: 0;
            text-align: left;
            }

            /* ページ上部メニュー */
            .page-top-menu {
            padding: 12px;
            }

            hr.line {
            width: 100%;
            color: #cccccc;
            border-style: solid;
            }

            /* ページ下部メニュー */
            .page-under-menu {
            text-align: right;
            }


            /* リンクボタン */
            a.link-btn {
            margin: 2px;
            text-decoration: none;
            color: #9c9c9c;
            padding: 6px 12px;
            background-color: #d11c13;
            border: 1px solid #cdcdcd;
            box-shadow: 0px 1px 3px #9c9c9c;
            text-shadow: 1px 1px 3px #9c9c9c;
            }


            a.link-btn:hover {
            background-color: #f81c13;
            }

            /* モーダルウィンドウ */
            #modal-layer-back-full {
            display:none;
            position:fixed;
            left:0;
            top:0;
            height:100%;
            width:100%;
            background-color: rgba(0, 0, 0, 0.6);
            filter: progid:DXImageTransform.Microsoft.Gradient(GradientType=0,StartColorStr=#66000000,EndColorStr=#66000000);
            }

            * html #modal-layer-back-full {
                position:absolute;
            }

            #modal-layer-over-full {
            display: none;
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            position: fixed;
            overflow-y: scroll;
            }

            #modal-loading {
            top: 50%;
            left: 50%;
            display: none;
            background-color: #f5f5f5;
            position: fixed;
            padding: 8px;
            border: 4px solid #cdcdcd;
            border-radius: 3px;
            width: 100px;
            }

            * html #modal-layer-over-full, * html #modal-loading {
                position: absolute;
            }

            #modal-close-menu {
            text-align: right;
            }

            #modal-next-menu {
            text-align: center;
            }

            a.modal-close-btn {
            color: #ffffff;
            background-color: #d11c13;
            border-radius: 3px;
            border: 1px solid #7d0000;
            }


            a.modal-close-btn:hover {
            background-color: #f81c13;
            }

            .item-name {
            text-decoration: underline;
            font-weight: bold;
            margin-bottom: 0;
            }

            /* プログレストラッカー */
            #progress-tracker {
            width: 100%;
            display: inline-block;
            }

            #progress-tracker #list-step {
            overflow:hidden;
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
            padding: 4px;
            }

            #progress-tracker #list-step li {
            display: inline-block;
            padding: 0;
            margin: 0;
            float: left;
            width: 340px;
            font-weight: bold;
            }

            #progress-tracker #list-step li div {
            background-color: #fefefe;
            padding: 4px;
            margin-right: 4px;
            text-align: left;
            border: 1px solid #cdcdcd;
            color: #696969;
            }

            #progress-tracker #list-step li div#current {
            background-color: #ffffcc;
            border: 1px solid #FFCC66;
            color: #FF6633;
            }

            #progress-tracker #list-step li div.complete {
            background-color: #fafafa;
            border: 1px solid #cdcdcd;
            color: #bbbbbb;
            }

            #progress-tracker #list-step li p {
            margin: 0;
            padding: 0;
            }
        </style>


        {{-- TODO: css link <link type="text/css" rel="stylesheet" href="{{ $v->env['path_base_module'] }}/css/style_br.css" /> --}}
        {{-- TODO: 外部ファイル化 svn_trunk\public\app\ctl\statics\css\style_br.css --}}
        <style type="text/css">
            @charset "UTF-8";

            /*------------------------------------------------------------------------------
            社内管理
            ------------------------------------------------------------------------------*/

            /* ページタイトル */
            .page-title-base {
            float: left;
            margin-top: 12px;
            }

            .page-title {
            background-color: #eeffee;
            border-style: solid;
            border-width: 4px;
            border-color: #CCFFCC #99CC99 #99CC99 #CCFFCC;
            display: inline;
            padding: 2px 4px;
            font-size: 120%;
            color: #3d3d3d;
            border-radius: 3px;
            }

            /* 社内管理：ヘッダー */
            .header-br {
            width: 100%;
            min-width: 1024px;
            width:expression(document.body.clientWidth < 1024 ? "1024px" : "auto");
            padding: 0;
            margin: 0;
            text-align: left;
            
            }

            .header-br .header-br-back {
            background-color: #eeffee;
            padding:10px;
            }

            .header-br .header-br-back .header-br-contents {
            font-weight: bold;
            color: #3c3c3c;

            }

            .header-br .header-br-back .header-br-contents div#system-name {
            float: left;
            }

            .header-br .header-br-back .header-br-contents div#main-menu {
            float: right;
            }


            /* 社内管理：フッター（旧） */
            .footer-br {
            width: 100%;
            min-width: 1024px;
            width:expression(document.body.clientWidth < 1024 ? "1024px" : "auto");
            padding: 0;
            margin: 0;
            }

            .footer-br .footer-br-back {
            background-color: #eeffee;
            color: #3c3c3c;
            font-weight: bold;
            }

            .footer-br .footer-br-back .footer-br-contents {
            padding: 10px;
            }

            .footer-br .footer-br-back .footer-br-contents div#dtm {
            float: right;
            }

            .footer-br .footer-br-back .footer-br-contents div#logout {
            float: left;
            }

            /* 社内管理フッター（新） */
            .ft-back {
            padding: 4px;
            }

            #ft-htl {
            background-color: #eeffee;
            }

            #ft-dtm {
            float: right;
            }

            /* 戻るボタンメニュー */
            .br-back-main-menu-form {
            text-align: right;
            margin: 0;
            }

            /* 社内管理：汎用フォーム */
            .form-br-title, .form-br-box {
            background-color: #f8f8f8;
            margin: 0 auto;
            }

            .form-br-title .form-br-title-back, .form-br-box .form-br-box-back {
            margin: 0 auto;
            }

            .form-br-title .form-br-title-back .form-br-title-conntents {
            padding: 10px;
            border: 1px solid #7FD875;
            background-color: #7FD875;
            text-align: left;
            color: #3c3c3c;
            border-radius: 3px 3px 0px 0px;
            font-weight: bold;
            }

            .form-br-box .form-br-box-back .form-br-box-contents {
            padding: 10px;
            border: 2px solid #7FD875;
            text-align: left;
            border-radius: 0px 0px 3px 3px;
            box-shadow: 0 8px 6px -6px #c9c9c9;
            }

            .form-br-box .item-name {
            margin: 0;
            }

            .form-br-box .item-comment {
            font-size: 90%;
            }

            .form-br-box .item-value {
            color: #3c3c3c;
            margin: 0.25em 0.5em;
            font-weight: bold;
            }

            .form-br-box .item-value#explain {
            border: 1px solid #cdcdcd;
            padding: 0.5em;
            border-radius: 3px;
            }

            .form-br-box hr.item-margin {
            color: #f8f8f8;
            background-color: #f8f8f8;
            border :none;
            border-top: 1px #f8f8f8;
            height: 1em;
            }

            .form-br-box hr {
            color: #ffffff;
            background-color: #ffffff;
            border :none;
            border-top: 1px dashed #ccc;
            }

            .form-br-box p {
            color: #616360;
            }

            .form-br-box .hr {
            border: 0;
            border-bottom: 1px dashed #ccc;
            background: #999;
            }

            .form-br-box .menu {
            text-align: center;
            }

            /* 社内管理：検索汎用フォーム */
            .br-search-box {
            background-color: #f8f8f8;
            border: 1px solid #cdcdcd;
            padding: 10px;
            border-radius: 3px;
            box-shadow: 0 8px 6px -6px #e9e9e9;
            }

            .br-search-box hr {
            color: #ffffff;
            background-color: #ffffff;
            border :none;
            border-top: 1px dashed #ccc;
            }

            table.br-search-field {
            width: 100%;
            }

            table.br-search-field th {
            background-color: #7FD875;
            color: #3c3c3c;
            padding: 0.25em;
            white-space: nowrap;
            }

            table.br-search-field td {
            text-align: left;
            padding: 0.25em;
            }

            hr.contents-margin {
            color: #ffffff;
            background-color: #ffffff;
            border :none;
            border-top: 1px #ffffff;
            height: 1em;
            }

            /* 社内管理：所属団体フォーム */
            .box-section-form {
            width: 40%;
            margin: auto;
            }

            /* 社内管理：DenyList検索条件フォーム */
            .br-hotel-deny-form-search {
            width: 40%;
            margin: auto;
            float: left;
            }

            /* 社内管理：DenyList一覧 */
            .br-hotel-deny-form {
            width: 100%;
            text-align: left;
            margin: auto;
            }

            .br-search-box .item-name {
            background-color: #FFDD99;
            float: left;
            width: 10em;
            display: table-cell;
            padding: 0.25em;
            }

            /* 社内管理：汎用リスト */
            table.br-list {
            width: 100%;
            border-collapse:separate;
            border-spacing:0;
            }

            .br-list tr.odd {
            background-color: #f8f8f8;
            }

            .br-list tr.even {
            background-color: #DFF9D1;
            }

            .br-list tr.active {
            background-color: #FFFF66;
            }

            table.br-list th {
            text-align: left;
            background-color: #7FD875;
            color: #3c3c3c;
            padding: 0.75em 0.5em;
            white-space: nowrap;
            }

            table.br-list th.fc {
            border-radius:3px 0px 0px 0px;
            }

            table.br-list th.action-menu, table.br-list td.action-menu {
            text-align: right;
            }

            table.br-list th.lc {
            border-radius:0px 3px 0px 0px;
            }

            table.br-list td {
            text-align: left;
            padding: 0.5em;
            margin: 0;
            }

            .br-list-tail {
            background-color: #7FD875;
            color: #3c3c3c;
            padding: 0.25em;
            border-radius: 0px 0px 3px 3px;
            text-align: right;
            box-shadow: 0 8px 6px -6px #cdcdcd;
            }

            .pager {
            list-style: none;
            text-align: right;
            }

            .pager li {
            display: inline;
            margin: 0.25em;
            padding: 2px;
            }

            .pager li#current {
            color:#ff0000;
            }

            .pager li a {
            text-decoration: none;
            }

            .form-menu-l {
            float: left;
            margin-left: 0.5em;
            }

            .form-menu-r {
            float: right;
            margin-left: 0.5em;
            }

            /* パートナー管理：キーワード設定フォーム */
            .box-keyword-form {
            width: 40%;
            text-align: left;
            margin: auto;
            }
        </style>

        @yield('headScript')

        {{-- Googleアナリティクス --}}
            {{-- TODO: {{ include file=$v->env['module_root']|cat:'/views/_common/_google_analytics.tpl' }} --}}
        {{-- /Googleアナリティクス --}}
    </head>
    <body>
        {{-- 環境表示 --}}
        {{-- TODO: {{ include file=$v->env['module_root']|cat:'/view2/_common/_env_info.tpl' }} --}}
        <div class="wrap{{ $v->config->environment->status }}">{{-- TODO: class属性 --}}
            {{-- パートナー管理ヘッダー --}}
            <div class="header-br">
                <div class="header-br-back">
                    <div class="header-br-contents">
                        <div id="system-name">STREAM社内管理</div>
                        <div id="main-menu">
                            {{-- TODO: route() --}}
                            <form action="{{ $v->env['source_path'] }}{{ $v->env['module'] }}/brtop/" method="post">
                                <div>
                                    <input type="submit" value="メインメニュー" />担当：{{ $v->user->operator->staff_nm }}
                                </div>
                            </form>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>{{-- /パートナー管理ヘッダー --}}
            {{-- コンテンツ --}}
            <div class="active-contents">
                {{-- ここまで svn_trunk/public/app/ctl/view2/_common/_br_header2.tpl --}}

                @yield('content')

                {{-- ここから svn_trunk/public/app/ctl/view2/_common/_br_footer.tpl --}}
            </div>
            {{-- /コンテンツ --}}
        </div>
        {{-- /環境表示 --}}
        {{-- 提携先管理フッター --}}
        
        <div class="footer-br">
            <div class="footer-br-back">
                <div class="footer-br-contents">
                    <div id="logout">
                        <div>
                            @if ('$v->user->operator->is_login()' and '$v->user->operator->is_staff()') {{-- TODO オブジェクトが実行されたら修正（単なる文字列は true判定） --}}
                                @if ($v->env['controller'] === "brtop" and $v->env['action'] === "index")
                                    <form method="post" action="{{ $v->env['source_path'] }}{{ $v->env['module'] }}/logout/">
                                        <div><input type="submit" value="ログアウト" /></div>
                                    </form>
                                @else
                                    <div>&nbsp;</div>
                                @endif
                            @else
                                &nbsp;
                            @endif
                        </div>
                    </div>
                    <div id="dtm">
                        {{-- TODO: <div>{{ $smarty['now']|date_format:'%Y-%m-%d %T' }}</div> --}}
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        {{-- /提携先管理フッター --}}
    </body>
</html>
{{-- ここまで svn_trunk/public/app/ctl/view2/_common/_br_footer.tpl --}}
