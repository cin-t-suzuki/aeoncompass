{{-- 移植元: svn_trunk\public\app\ctl\view2\brpartnersite\_js.tpl --}}
<script language="JavaScript" type="text/javascript">
<!--
// ↑ 意味があるのか不明なコメントアウト (js 内では効果なさそう)
function helpForm() {
  var f = document.getElementById('help');
  if (f.style.display == 'none') {
    f.style.display = 'block';
  } else {
    f.style.display = 'none';
  }
}
//-->
// ↑ 意味があるのか不明なコメントアウト (js 内では効果なさそう)
const help_buttons = document.getElementsByClassName('toggle_form_help');
for (let i = 0; i < help_buttons.length; i++) {
    help_buttons[i].addEventListener('click', helpForm);
}
</script>