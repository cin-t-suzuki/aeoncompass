<script language="JavaScript" type="text/javascript">
<!--
 var yahoo_plus_point = yahoo_plus_point||{};
 var yahoo_plus_point = {
    ctl_new_page : function() {
        $('input[name=plus_target_type]:radio').change(function(){
             if($('input[name=plus_target_type]:eq(0)').attr('checked')) {
                 $('#target_hotel_area').css('display','none');
                 $('#target_plan_area').css('display','none');
                 $('[name=plus_point_rate] option').filter(function(index){
                     return $(this).val() === '-0.5'; // Strawberryを選択する場合
                 }).attr('disabled', true);
             }
             if($('input[name=plus_target_type]:eq(1)').attr('checked')) {
                 $('#target_hotel_area').css('display','');
                 $('#target_plan_area').css('display','none');
                 $('[name=plus_point_rate] option').filter(function(index){
                     return $(this).val() === '-0.5'; // Strawberryを選択する場合
                 }).attr('disabled', false);
             }
             if($('input[name=plus_target_type]:eq(2)').attr('checked')) {
                 $('#target_hotel_area').css('display','none');
                 $('#target_plan_area').css('display','');
                 $('[name=plus_point_rate] option').filter(function(index){
                     return $(this).val() === '-0.5'; // Strawberryを選択する場合
                 }).attr('disabled', true);
             }
         });
        if($('input[name="plus_target_type"]:eq(0)').attr('checked')) {
            $('#target_hotel_area').css('display','none');
            $('#target_plan_area').css('display','none');
            $('[name=plus_point_rate] option').filter(function(index){
                return $(this).val() === '-0.5'; // Strawberryを選択する場合
            }).attr('disabled', true);
        }
        if($('input[name="plus_target_type"]:eq(1)').attr('checked')) {
            $('#target_hotel_area').css('display','');
            $('#target_plan_area').css('display','none');
            $('[name=plus_point_rate] option').filter(function(index){
                return $(this).val() === '-0.5'; // Strawberryを選択する場合
            }).attr('disabled', false);
        }
        if($('input[name="plus_target_type"]:eq(2)').attr('checked')) {
            $('#target_hotel_area').css('display','none');
            $('#target_plan_area').css('display','');
            $('[name=plus_point_rate] option').filter(function(index){
                return $(this).val() === '-0.5'; // Strawberryを選択する場合
            }).attr('disabled', true);
        }
    },
    check_point_rate :function () {
        $('#submit-new').click(function(){
            console.log()
            if( $('[name=plus_point_rate]').val() == -0.5 && !($('input[name="plus_target_type"]:eq(1)').attr('checked'))) {
                 alert("加算ポイント率「-0.5%」は付与対象が特定施設の場合のみです。");
                 return false;
            }
         return true;
         });
    },
    reset_rsv_ymd : function() {
        $('[name=target_rsv_s_ymd_year]').change(function(){
          make_rsv_e_ymd();
        });
        $('[name=target_rsv_s_ymd_month]').change(function(){
          make_rsv_e_ymd();
        });
        $('[name=target_rsv_s_ymd_day]').change(function(){
          make_rsv_e_ymd();
        });
    }
};
$(function(){
    yahoo_plus_point.ctl_new_page();
    yahoo_plus_point.reset_rsv_ymd();
    yahoo_plus_point.check_point_rate();
});

function make_rsv_e_ymd() {
    if($('[name=target_rsv_s_ymd_month]').val() == '' || $('[name=target_rsv_s_ymd_day]').val() == ''){
        return;
    }
    var conv_month = $('[name=target_rsv_s_ymd_month]').val() - 1;
    var dt = new Date();
    dt.setFullYear($('[name=target_rsv_s_ymd_year]').val());
    dt.setMonth(conv_month);
    dt.setDate($('[name=target_rsv_s_ymd_day]').val());
    dt.setDate(dt.getDate() + 1);
    $('[name=target_rsv_e_ymd_year]').val(dt.getFullYear());
    $('[name=target_rsv_e_ymd_month]').val((dt.getMonth() + 1));
    $('[name=target_rsv_e_ymd_day]').val(dt.getDate());
}
//-->
</script>
