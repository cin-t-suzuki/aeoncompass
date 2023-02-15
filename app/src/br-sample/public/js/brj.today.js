$(document).ready(function () {
var w = new Array("日", "月", "火", "水", "木", "金", "土");
var d = new Date();
$(".today").text(d.getFullYear() + "年"+ (d.getMonth() + 1) + "月" + d.getDate() + "日（" + w[d.getDay()] + "）");
});
