{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/_script.tpl --}}

{literal}
<script type="text/javascript"> 
<!-- 

function is_confirm(){

	if(window.confirm('削除してよろしいですか？')){ // 確認ダイアログを表示

		return true; // 「OK」時は送信を実行

  } else {

    return false;

	}

}
$(function(){
    $("a[href^=#page-bottom]").click(function(){
        $('body').animate({
          scrollTop: $(document).height()
        },1200);
        return false;
    });
    $("a[href^=#page-top]").click(function(){
        $('body').animate({
          scrollTop: $('#page_top_symbol').offset().top
        },1200);
        return false;
    });
});
// -->
</script>
{/literal}