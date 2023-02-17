@extends('ctl.common.base')
@section('title', '口座振替　追加処理')

@section('page_blade')
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message')

{{--削除でいいか {literal} --}}
<script language="JavaScript" type="text/javascript">
// <!--は削除？コメントアウトにする？
<!--
function help1Form() {
  var f = document.getElementById('help1');
  if (f.style.display == 'none') {
    f.style.display = 'block';
  } else {
    f.style.display = 'none';
  }
  var g = document.getElementById('help2');
  g.style.display = 'none';
}
function help2Form() {
  var f = document.getElementById('help2');
  if (f.style.display == 'none') {
    f.style.display = 'block';
  } else {
    f.style.display = 'none';
  }
  var g = document.getElementById('help1');
  g.style.display = 'none';
}
//-->
</script>
{{-- {/literal} --}}



<div style="line-height:150%" style="margin:1em 1em">



{{--削除でいいか {literal} --}}
<style type="text/css">
/*　コメントアウトでいいか？
 <!--
 form {margin:0px}
-->
 */
</style>
<script language="javascript"  type="text/javascript">
// <!--は削除？コメントアウトにする？
<!--
  $(document).ready(function () {

    $('input[name="query"]').click(function () {
      if ($('input[name="entry_status"]').is(':checked')) {
        // var uri = '{/literal}{$v->env.source_path}{$v->env.module}{literal}/bradditionalzengin/searchhotel?keywords=' + encodeURI($('input[name="keyword"]').val()) + '&pref_id=' + $('select[name="pref_id"]').val() + '&entry_status=0';
        var uri = '{{ route('ctl.brAdditionalZengin.searchHotel')}}?keywords=' + encodeURI($('input[name="keyword"]').val()) + '&pref_id=' + $('select[name="pref_id"]').val() + '&entry_status=0';
      } else {
        // var uri = '{/literal}{$v->env.source_path}{$v->env.module}{literal}/bradditionalzengin/searchhotel?keywords=' + encodeURI($('input[name="keyword"]').val()) + '&pref_id=' + $('select[name="pref_id"]').val();
        var uri = '{{ route('ctl.brAdditionalZengin.searchHotel')}}?keywords=' + encodeURI($('input[name="keyword"]').val()) + '&pref_id=' + $('select[name="pref_id"]').val();
      }

      //window.location.href =uri;
      $.get(uri, function(html){
        $('#hotel').html(html);
      });
    });

  });
//-->
</script>
 {{-- {/literal}  --}}

  <strong>対象施設を選択</strong><br>
 口座振替の金額追加を行う対象の施設を選択する。
  <table border="1" cellspacing="0" cellpadding="4" class="search">
    <tr>
      <td bgcolor="#EEFFEE">宿泊施設</td>
      <td nowrap><input type="checkbox" value="0" name="entry_status" id="entry_status_0" /><label for="entry_status_0">公開中のみ</label></td>
      <td nowrap>
	   <select size="1" name="pref_id">
      @foreach ($mast_prefs['values'] as $mast_pref)
        @if (strip_tags($mast_pref['pref_id']) == 0)
          <option value="">
        @else
          <option value="{{strip_tags($mast_pref['pref_id'])}}">
        @endif
          {{strip_tags($mast_pref['pref_nm'])}}
        </option>
      @endforeach
      </select>
      </td>
      <td nowrap><input maxlength="20" name="keyword" size="20"></td>
      <td nowrap><input type="submit" name="query" value=" 検索 "></td>
    </tr>
  </table>

<span id="hotel"></span>
{{-- 検索のヘルプ --}}
{{--削除でいいか {literal} --}}
<script language="JavaScript" type="text/javascript">
// <!--は削除？コメントアウトにする？
<!--
if(document.getElementById){
  // ↓<a href="" の中身は#でいいか？（元は空）
  document.write('<div style="float:left"><a href="#" onclick="help2Form(); return false;">宿泊施設検索のヘルプ</a></div>');}
//-->
</script>
{{-- {/literal} --}}
</div>



{{-- 検索のヘルプ --}}
  <div id="help2" style="border: 1px solid rgb(0, 0, 0); display: none; position: absolute; background-color: rgb(255, 255, 255);" align="left">
    {{-- ↓<a href="" の中身は#でいいか？（元は空） --}}
  <div style="margin: 2px 4px; text-align: right;"><a href="#" onclick="help2Form();return false;"><nobr>×閉じる</nobr></a></div>
     <div style="font-size:10px;margin-top:8px">
      下記での検索を前方一致にて行います。
      <ul style="margin-top:0px">
        <li>ホテルコード</li>
        <li>郵便番号</li>
        <li>住所</li>
        <li>電話番号</li>
        <li>FAX番号</li>
      </ul>
    </div>
    <div style="font-size:10px">
      下記での検索を前後方一致にて行います。
      <ul style="margin-top:0px">
        <li>施設名称</li>
        <li>施設名称かな</li>
        <li>グループ名称（※黄色の背景色で表示されます。）</li>
      </ul>
    </div>
    <div style="font-size:10px">
      下記での検索を完全一致にて行います。
      <ul style="margin-top:0px">
        <li>担当者メールアドレス</li>
      </ul>
    </div>
  </div>

@endsection
