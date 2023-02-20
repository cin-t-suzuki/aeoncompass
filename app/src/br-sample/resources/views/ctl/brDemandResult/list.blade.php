@extends('ctl.common.base')
@section('title', '送客請求実績確認')

@section('page_blade')

<script language="javascript"  type="text/javascript">
function helpForm() {
  var f = document.getElementById('help');
  if (f.style.display == 'none') {
    f.style.display = 'block';
  } else {
    f.style.display = 'none';
  }
}
</script>

<br />
{!! Form::open(['route' => ['ctl.BrDemandResult.list'], 'method' => 'get']) !!}
  <table border="1" cellpadding="4" cellspacing="0">
    <tr>
      <td nowrap  bgcolor="#EEFFEE">キーワード</td>
      <td nowrap><input type="text" name="keyword" size="30" maxlength="500" value="{{strip_tags($keyword)}}">　<small>（必須）</small></td>
    </tr>
  </table>
  <input type="submit" name="hotel" value="検索する">
<a href="" onclick="helpForm(); return false;">キーワードのヘルプ</a>
  <br />
{!! Form::close() !!}

  <div id="help" style="border: 1px solid rgb(0, 0, 0); display: none; position: absolute; background-color: rgb(255, 255, 255);" align="left">
  <div style="margin: 2px 4px; text-align: right;"><a href="" onclick="helpForm();return false;"><nobr>×閉じる</nobr></a></div>
     <div style="font-size:10px;margin-top:8px">
      下記の項目を検索します。<br>（施設コード、請求支払先IDは完全一致です。）
      <ul style="margin-top:0px">
        <li>施設コード</li>
        <li>施設名称</li>
        <li>請求支払先ID</li>
        <li>請求支払先名称</li>
      </ul>
    </div>
  </div>
<hr size="1">
@if (count($errors) > 0 || count($search_customer ?? []) == 0) 
  {{-- メッセージbladeの読込 --}}
  @include('ctl.common.message')
@else
  <table border="1" cellspacing="0" cellpadding="4">
    <tr bgcolor="#EEFFEE" >
      <td nowrap>請求支払先ID</td>
      <td nowrap>請求支払先名称</td>
      <td nowrap>施設コード</td>
      <td nowrap>施設名称</td>
      <td nowrap><br></td>
    </tr>
    @foreach ($search_customer as $value)
      <tr>
        {{-- TODO：HTldemand実装後に修正 --}}
        <form action="{$v->env.source_path}{$v->env.module}/htldemand/" method="post">
        <td nowrap>{{strip_tags($value->customer_id)}}</td>
        <td nowrap>{{strip_tags($value->customer_nm)}}</td>
        <td nowrap>{{strip_tags($value->hotel_cd)}}</td>
        <td nowrap>{{strip_tags($value->hotel_nm)}}</td>
        <td nowrap align="center"><input type="submit" value=" 表示 " /><input type="hidden" name="target_cd" value="{{strip_tags($value->hotel_cd)}}" /></td>
        </form>
      </tr>
    @endforeach
  </table>
@endif
<br />
@endsection