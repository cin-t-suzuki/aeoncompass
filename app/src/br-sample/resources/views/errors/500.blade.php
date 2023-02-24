{{-- MEMO: 移植元 public\app\rsv\view2\error\500.tpl --}}

<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Content-Language" content="ja">
    <meta http-equiv="Pragma" content="no-cache">
    <title>株式会社イオンコンパス</title>
    <script language="JavaScript" type="text/javascript">
        <!--
        if (navigator.platform) {
            if (navigator.platform.charAt(0) == 'W') {
                if (navigator.userAgent.indexOf("MSIE") > -1) {
                    document.write('<style type="text/css"><!-- body, td, th { font-size:80% } --></style>')
                } else if (navigator.userAgent.indexOf("Netscape6") > -1) {
                    document.write('<style type="text/css"><!-- body, td, th { font-size:73%; font-family: sans-serif; } --></style>')
                }
            }
        }
        // -- >
    </script>
</head>

<body text="#000000" bgcolor="#FFFFFF">
    &nbsp;
    <center>
        <table border=0 cellspacing=0 cellpadding=4 width="540">
            <tr>
                <td>
                    <a href="{{ route('rsv.top') }}">
                        <img src="/img/ac_logo.png" alt="イオンコンパス" border="0" width="136" height="52" hspace="8">
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <br>
                    <big>ただいまメンテナンス中です。しばらくお待ち下さい。</big>
                    <br>
                </td>
            </tr>
            <tr>
                <td align=right>株式会社イオンコンパス</td>
            </tr>
        </table>
    </center>

    {{-- 開発の場合エラーメッセージ表示  --}}
    {{-- {if $v->config->environment->status != 'product'} --}}
    @if (config('app.env') != 'product')
        <div style="background-color:#eee">
            @if ($errors->any())
                <br />
                PHP ERROR:
                <br />
                =============
                <br />
                @foreach ($errors->all() as $error)
                    {{ $error }}
                    <br />
                @endforeach
            @endif
        </div>
    @endif
</body>

</html>
