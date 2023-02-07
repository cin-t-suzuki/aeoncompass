@section('title','請求')
@include('ctl.common.base')

@inject('service', 'App\Http\Controllers\ctl\BrtopController')

<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td>
      <table border="1" cellspacing="0" cellpadding="4" width="100%">
      {!! Form::open(['route' => ['ctl.br.moneyschedule'], 'method' => 'post']) !!}
        <tr>
          <td width="100%" nowrap>経理関係スケジュールの追加<br>
             <small style="color:#336">経理関係スケジュールのメンテナンスを行います。</small>
          </td>
          <td><input type="submit" value=" 登録 " /></td>
        </tr>
        {!! Form::close() !!}
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table border="1" cellspacing="0" cellpadding="4" width="100%">
      {!! Form::open(['route' => ['ctl.br.checksheet'], 'method' => 'post']) !!}
        <tr>
          <td width="100%" nowrap>送客リスト送付状況<br>
            <small style="color:#336">送客リストの送付状況と原稿の確認を行えます。</small>
          </td>
          <td><input type="submit" value=" 確認 "></td>
        </tr>
        {!! Form::close() !!}
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table border="1" cellspacing="0" cellpadding="4" width="100%">
      {!! Form::open(['route' => ['ctl.br.billpay'], 'method' => 'post']) !!}
        <tr>
          <td width="100%" nowrap>請求・支払処理<br />
            <small style="color:#336">請求・支払処理および請求書の印刷を行います。</small>
          </td>
          <td><input type="submit" value=" 表示 "></td>
        </tr>
        {!! Form::close() !!}
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table border="1" cellspacing="0" cellpadding="4" width="100%">
      {!! Form::open(['route' => ['ctl.br.billpayptn'], 'method' => 'post']) !!}
        <tr>
          <td width="100%" nowrap>パートナー精算確認<br />
            <small style="color:#336">パートナー精算の原稿確認を行えます。</small>
          </td>
          <td><input type="submit" value=" 表示 "></td>
        </tr>
        {!! Form::close() !!}
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table border="1" cellspacing="0" cellpadding="4" width="100%">
      {!! Form::open(['route' => ['ctl.br.additionalzengin'], 'method' => 'post']) !!}
        <tr>
          <td width="100%" nowrap>口座振替 追加処理<br />
            <small style="color:#336">施設の口座振替額に追加処理を行います。</small>
          </td>
          <td><input type="submit" value=" 表示 "></td>
        </tr>
        {!! Form::close() !!}
      </table
    </td>
  </tr>
  <tr>
    <td>
      <table border="1" cellspacing="0" cellpadding="4" width="100%">
      {!! Form::open(['route' => ['ctl.br.accounting'], 'method' => 'post']) !!}
        <tr>
          <td width="100%" nowrap>経理処理データ<br>
            <small style="color:#336">経理データのダウンロードを行えます。<br />
                                         ・システム利用料（送客データ）<br />
                                         ・売掛金リスト（請求先単位）<br />
                                         ・システム利用料（請求・支払データ）<br />
                                         ・クレジット決済(ホテル)<br />
                                         ・クレジット決済(クレジット会社)<br />
                                         ・ホテル（請求／支払データ）<br />
                                         ・など</small>
          </td>
          <td><input type="submit" value="ダウンロード"></td>
        </tr>
        {!! Form::close() !!}
      </table>
    </td>
  </tr>
</table>

@section('title', 'footer')
@include('ctl.common.footer')