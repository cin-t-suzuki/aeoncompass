{{-- 元ファイル: svn_trunk\public\app\ctl\view2\brpartnercustomer\_js.tpl --}}

{{-- TODO? .blade.php ではなく、.css にして　public/ に配置したほうがよい？ --}}

<script language="JavaScript" type="text/javascript">
    <!-- // 謎の html コメントアウト 疑わしきは罰せず
    function helpForm() {
        var f = document.getElementById('help');
        if (f.style.display == 'none') {
            f.style.display = 'block';
        } else {
            f.style.display = 'none';
        }
    }
    //-->
    const help_buttons = document.getElementsByClassName('toggle_form_help');
    for (let i = 0; i < help_buttons.length; i++) {
        help_buttons[i].addEventListener('click', helpForm);
    }
</script>
