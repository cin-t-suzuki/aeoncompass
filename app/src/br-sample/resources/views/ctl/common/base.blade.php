<HTML>
<head>
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Expires" content="0">
    <meta name="robots" content="none">
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/jquery.cookies.js"></script>
    <title>STREAM社内管理 @yield('title')</title>
    <!--TODO デザイン link type="text/css" rel="stylesheet" href="/css/base.css"-->
    {{-- 印刷用スタイルシート --}}
    @if(isset($print_flg) && $print_flg)
    <link type="text/css" rel="stylesheet" href="/styles/print.css" media="print">
    <link type="text/css" rel="stylesheet" href="/styles/screen.css" media="screen">
    @endif
    <script language="JavaScript" type="text/javascript">
    <!--
        if(navigator.platform){
            if(navigator.platform.charAt(0)=='W'){
                u = navigator.userAgent;
                if(u.indexOf("MSIE") > -1){
                    document.write('<style type="text/css"> body, td, th { font-size:80% } <'+'/style>');
                }
                else if(u.indexOf("Netscape6") > -1 || u.indexOf("Netscape/7") > -1 || u.indexOf("Firefox") > -1){
                    document.write('<style type="text/css"> body, td, th { font-size:10pt; font-family: sans-serif; } <'+'/style>');
                }
            }
        }
    // -->
    </script>
    {{--  Googleアナリティクス  --}}
    {{-- include file=$v->env.module_root|cat:'/views/_common/_google_analytics.tpl' --}}
</head>
<body topmargin="0" marginheight="0"
    @if(config('app.env') == 'test') style="margin-top:0;margin-left:0;padding-left:8px;border-left:4px solid #297;" @endif
    @if(config('app.env') == 'development') style="margin-top:0;margin-left:0;padding-left:8px;border-left:4px solid #36A;" @endif
    @if(config('app.env') == 'product') style="margin-top:0;margin-left:0;padding-left:8px;border-left:4px solid #A63;" @endif
>
@if(config('app.env') == 'test')
    <div style="margin-left:-12px;padding:0.25em 0;background-color:#297;color:#fff;font-weight:bold;width:6em;text-align:center;">検証環境</div>        
@elseif(config('app.env') == 'development')
    <div style="margin-left:-12px;padding:0.25em 0;background-color:#36A;color:#fff;font-weight:bold;width:6em;text-align:center;">開発環境</div>    
@elseif(config('app.env') != 'product')
    <div style="margin-left:-12px;padding:0.25em 0;background-color:#A63;color:#fff;font-weight:bold;width:6em;text-align:center;">環境不明</div>
@endif


@if(isset($no_print) && $no_print)
    <div class="noprint">
@endif

    <table border="0" WIDTH="100%" cellspacing="0" cellpadding="6" bgcolor="#EEFFEE" >
        <tr>
            <td nowrap></td>
            <td nowrap WIDTH="20%"><B>STREAM社内管理</B></td>
            {{-- ログインしていれば --}}
            @if(isset($isLogin) && $isLogin)
            <td align="right" WIDTH="70%">
                <small>
                    {!! Form::open(['route' => ['ctl.index'], 'method' => 'get']) !!}
                        <input type="submit" value="メインメニュー">
                        担当：{{ staff_nm }}
                    {!! Form::close() !!}
                </small>
            </td>
            @endif
        </tr>
    </table>

@if(isset($no_print) && $no_print)
    </div>
@endif

@if(isset($no_print_title) && $no_print_title)
    <div class="noprint">
@endif
{{-- ログインしていれば --}}
@if(isset($isLogin) && $isLogin)
    <br />
    <br />
    @if(isset($menu_title) || isset($title))
    <table border="3" cellpadding="2" cellspacing="0">
        <tr>
            <td align="center" bgcolor="#EEFFEE">
                <big>
                    {{ !isset($menu_title) ? $title : $menu_title }}
                </big>
            </td>
        </tr>
    </table>
    @endif
    <br />
@endif
@if(isset($no_print_title) && $no_print_title)
    </div>
@endif


{{-- blade --}}
@yield('page_blade')


@if(isset($no_print) && $no_print)
<div class="noprint">
@endif
<br>
{{-- ログインしていれば --}}
@if(isset($isLogin) && $isLogin)
<table border="0" WIDTH="100%" cellspacing="0" cellpadding="0" bgcolor="#EEFFEE">
    <tr>
        <td bgcolor="#EEFFEE">
        @if(isset($isStaff) && $isStaff)
            <small>
            操作者変更（<a href="">Logout</a>）
            </small>
        @else
            @if(\Route::currentRouteName() == "ctl.index")
            <small>
                操作者変更（<a href="">Logout</a>）
            </small>
            @endif
        @endif
        </td>
        <td bgcolor="#EEFFEE" ALIGN="right">
            <small>
                画面更新日時({{ date('Y-m-d H:i:s') }})
            </small>
        </td>
    </tr>
</table>
@endif

@if(isset($no_print) && $no_print)
    </div>
@endif

</body>
</HTML>