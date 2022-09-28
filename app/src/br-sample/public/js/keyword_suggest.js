$(document).ready( function() {
  $("#f_query").autocomplete({
        minLength: 2,
        source: function(req, resp){
        $.ajax({
            url: "/rsv/suggesthotel/index/",
            type: "GET",
            cache: true,
            dataType: "json",
            delay: 200,
            autoFocus: true,
            data: {
            keyword: req.term
            },
            success: function(o){
            resp(o);
            },
            error: function(xhr, ts, err){
            resp(['']);
            },
        });
    },
    select: function(event, ui) {
        $('#f_query').val(ui.item.value);
        $('#form-keywords').submit();
    },
  });
});
