<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<meta name="robots" content="none" />
    <script type="text/javascript" src="/scripts/jquery.js"></script>
    <script type="text/javascript">
      <!--
        $(document).ready(function () {
          //===================================================================
          // 外部リンクは別タブで開くように設定
          //===================================================================
          $('a[href^="http://"]').attr('target', '_blank');

          //===================================================================
          // 別タブ/ウインドウで開く
          //===================================================================
          $('.blank').click(function() {
            window.open(this.href, '_blank');
            return false;
          });

        });
      //-->
    </script>
<title>
  [ストリーム]予約受付管理&nbsp;
  [{{$v->helper->form->strip_tags($title)}}]
</title>
<link type="text/css" rel="stylesheet" href="{$v->env.path_base_module}/css/style.css" />
<!-- Googleアナリティクス -->
@include('ctl.common._google_analytics')