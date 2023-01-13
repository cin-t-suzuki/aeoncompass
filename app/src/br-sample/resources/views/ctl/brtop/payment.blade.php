@section('title', '支払')
@include('ctl.common.base')

@inject('service', 'App\Http\Controllers\ctl\BrtopController')

<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td>
      <table border="1" cellspacing="0" cellpadding="4" width="100%">
      {!! Form::open(['route' => ['ctl.braffiliate.paymentmanagement'], 'method' => 'post']) !!}
        <tr>
          <td width="100%" nowrap>アフィリエイト支払管理<br>
            　<small style="color:#336">アフィリエイトの支払データ参照します。</small>
          </td>
          <td><input type="submit" value=" 確認 "></td>
        </tr>
      {!! Form::close() !!}
      {!! Form::open(['route' => ['ctl.brpartner.paymentmanagement'], 'method' => 'post']) !!}
        <tr>
          <td width="100%" nowrap>パートナー請求管理<br>
            　<small style="color:#336">パートナーの請求データ参照します。</small>
          </td>
          <td><input type="submit" value=" 確認 "></td>
        </tr>
      {!! Form::close() !!}
      </table>
      
    </td>
  </tr>
</table>
@section('title', 'footer')
@include('ctl.common.footer')