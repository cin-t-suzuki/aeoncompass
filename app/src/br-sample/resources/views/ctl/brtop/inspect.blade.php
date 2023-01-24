@section('title', '会員情報閲覧')
@include('ctl.common.base')

@inject('service', 'App\Http\Controllers\ctl\BrtopController')

<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td valign="top">
      <table border="1" cellspacing="0" cellpadding="4" width="100%">
        <tr>
          {!! Form::open(['route' => ['ctl.brdisplayinfo'], 'method' => 'post']) !!}
            <td nowrap>
              会員検索<br />
              <small style="color:#336">会員情報の詳細とパスワードの閲覧</small>
            </td>
            <td nowrap align="center">
              <input type="submit" value="表示">
            </td>
          {!! Form::close() !!}
        </tr>
        <tr>
          {!! Form::open(['route' => ['ctl.brissuelicense.authorize'], 'method' => 'post']) !!}
            <td nowrap>
              ライセンス発行<br />
              <small style="color:#336">会員情報閲覧用ライセンス発行</small>
            </td>
            <td nowrap align="center">
              <input type="submit" value="表示">
            </td>
          {!! Form::close() !!}
        </tr>
      </table>
      <br />
       <table border="1" cellpadding="4" cellspacing="0" width="100%">
         <tr>
          {!! Form::open(['route' => ['ctl.brmodifymember.mailsearch'], 'method' => 'post']) !!}
             <td nowrap>
               メールマガジン受信状態変更<br />
               <small style="color:#336">メールアドレスによるメールマガジン受信状態の変更</small>
             </td>
             <td nowrap align="center">
               <input type="submit" value="表示">
             </td>
          {!! Form::close() !!}
         </tr>
      </table>
    </td>
    <td valign="top">
      <table border="1" cellpadding="4" cellspacing="0" width="100%">
        <tr>
          {!! Form::open(['route' => ['ctl.brmodifymember.mailremove'], 'method' => 'post']) !!}
            <td nowrap>
              会員登録メールアドレスをremoveへ変更<br />
              <small style="color:#336">会員登録メールアドレスをremove@bestrsv.comへ変更</small>
            </td>
            <td nowrap align="center">
              <input type="submit" value="表示">
            </td>
          {!! Form::close() !!}
        </tr>
      </table>
       <br />
       <table border="1" cellpadding="4" cellspacing="0" width="100%">
          <tr>
            {!! Form::open(['route' => ['ctl.brremindermember'], 'method' => 'post']) !!}
              <td nowrap>
                会員コード・パスワードの案内<br />
                <small style="color:#336">会員コード・パスワードの案内メールを通知します。</small>
              </td>
              <td nowrap align="center">
                <input type="submit" value="表示">
              </td>
            {!! Form::close() !!}
          </tr>
        </table>
       <br />
       <table border="1" cellpadding="4" cellspacing="0" width="100%">
          <tr>
            {!! Form::open(['route' => ['ctl.brmemberinfo'], 'method' => 'post']) !!}
              <td nowrap>
                お客様情報検索<br />
                <small style="color:#336">メアドや氏名からお客様情報を検索します。</small>
              </td>
              <td nowrap align="center">
                <input type="submit" value="表示">
              </td>
            {!! Form::close() !!}
          </tr>
        </table>
    </td>
  </tr>
</table>

@section('title', 'footer')
@include('ctl.common.footer')