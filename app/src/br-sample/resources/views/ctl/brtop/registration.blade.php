@section('title', '登録')
@include('ctl.common.base')

@inject('service', 'App\Http\Controllers\ctl\BrtopController')

<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td>
      <table border="1" cellspacing="0" cellpadding="4" width="100%">
<!--
        <form action="kbs_brv_room_plan_priority.html" method="post">
        <tr>
          <td width="100%" nowrap>管理画面お知らせ設定<br>
             　<small style="color:#336">施設画面メニューに表示するお知らせの設定</small>
          </td>
          <td><input type="submit" value=" 登録 " /></td>
        </tr>
        </form>
-->
        {!! Form::open(['route' => ['ctl.brfaxpr.conf'], 'method' => 'post']) !!}
        <tr>
          <td width="100%" nowrap>予約通知ＦＡＸ広告 掲載文章設定<br>
             <small style="color:#336">予約通知FAXの下部に掲載する文章の設定</small>
          </td>
          <td><input type="submit" value=" 登録 " /></td>
        </tr>
        {!! Form::close() !!}

        {!! Form::open(['route' => ['ctl.brroomplanpriority2'], 'method' => 'post']) !!}
        <tr>
          <td width="100%" nowrap>空室検索おすすめプラン<br>
             <small style="color:#336">空室検索にて <img src="/images/qi/star.gif" border="0" width="38" height="11" alt="おすすめプラン"> が付与されるプランの設定</small>
          </td>
          <td><input type="submit" value=" 登録 " /></td>
        </tr>
        {!! Form::close() !!}

        {!! Form::open(['route' => ['ctl.brpremium'], 'method' => 'post']) !!}
        <tr>
          <td width="100%" nowrap>プレミアム施設<br>
             <small style="color:#336">プレミアムパッケージの導入施設の登録</small>
          </td>
          <td><input type="submit" value=" 登録 " /></td>
        </tr>
        {!! Form::close() !!}

        {!! Form::open(['route' => ['ctl.bralert'], 'method' => 'post']) !!}
        <tr>
          <td>アラート通知機能<br>
             　<small style="color:#336">社内向けアラート通知を管理します。</small>
          </td>
          <td><input type="submit" value=" 登録 " /></td>
        </tr>
        {!! Form::close() !!}

        {!! Form::open(['route' => ['ctl.brsupervisor'], 'method' => 'post']) !!}
        <tr>
          <td width="100%" nowrap>グループホテルの追加<br>
             　<small style="color:#336">グループホテルの一覧から追加、削除を行います。</small>
          </td>
          <td><input type="submit" value=" 登録 " /></td>
        </tr>
        {!! Form::close() !!}

        {!! Form::open(['route' => ['ctl.brbank'], 'method' => 'post']) !!}
        <tr>
          <td width="100%" nowrap>銀行・支店の追加<br>
             　<small style="color:#336">銀行・支店の追加、更新を行います。</small>
          </td>
          <td><input type="submit" value=" 登録 " /></td>
        </tr>
        {!! Form::close() !!}
      </table>
    </td>
  </tr>

  <tr>
    <td><strong>広告掲載</strong>
      <table border="1" cellpadding="4" cellspacing="0">
        {!! Form::open(['route' => ['ctl.brhoteladvert2009000400'], 'method' => 'post']) !!}
        <tr>
          <td width="100%" nowrap>迷わずここ！(2009000400 )<br>
             　<small style="color:#336">トップページ左下</small>
          </td>
          <td><input type="submit" value=" 登録 " /></td>
        </tr>
        {!! Form::close() !!}
      </table>
    </td>
  </tr>
  <tr>
    <td><strong>お天気保険</strong>
      <table border="1" cellpadding="4" cellspacing="0">
      {!! Form::open(['route' => ['ctl.brinsuranceweather.index'], 'method' => 'post']) !!}
        <tr>
          <td width="100%">お天気保険成立処理<br>
             　<small style="color:#336">毎月１日に成立と成立者へのメールを送信するための準備として お天気保険成立待ち状態への更新処理を行います。</small>
          </td>
          <td><input type="submit" value=" 更新 " /></td>
        </tr>
        {!! Form::close() !!}
      </table>
    </td>
  </tr>
  <tr>
    <td><strong>ポイント加算情報</strong>
      <table border="1" cellpadding="4" cellspacing="0">
        <tr>
          {!! Form::open(['route' => ['ctl.bryahoopointplus.index'], 'method' => 'post']) !!}
          <form action="{$v->env.source_path}{$v->env.module}/bryahoopointplus/index/" method="post">
            <td width="100%">Yahoo!ポイント加算情報一覧・新規登録<br>
              <span style="font-size: 15px; color: red;">Yahoo!で販売する際にのみ追加で付与するポイントの情報を登録できます。</span>
             　<small style="color:#336">
               <br />
               <div onclick="obj=document.getElementById('openyp').style; obj.display=(obj.display=='none')?'block':'none';">
               <a style="cursor:pointer;">▼ クリックで展開</a>
               </div>
               <div id="openyp" style="display:none;clear:both;">
               ・<span style="font-weight: bold;">このポイントはBRの負担になります。</span>施設には請求しません。<br /><br />
              ・施設負担のポイントとこのポイントが合計がYahoo!で販売される際のポイントになります。<br /><br />
              ・ポイントの付与は予約やキャンセル時にリアルタイムで行われます。<br />
              　お客様は予約確認画面でこのポイントを含めた付与ポイントを確認することになります。<br /><br />
              ・施設に送信される予約通知には施設負担分も含めて付与ポイントの情報はありません。<br /><br />
              ・施設用のプラン管理画面にはこのポイント付与について指定された文言が情報として表示されます。<br /><br />
              ・施設用の送客実績・料金変更画面と送客請求実績画面には<br />
              　このポイントの情報を含まない施設負担分の付与ポイントのみが表示されます。<br />
               </div>
              </small>
            </td>
            <td><input type="submit" value="登録" /></td>
          {!! Form::close() !!}
        </tr>
        <tr>
          {!! Form::open(['route' => ['ctl.brbrpointplus.index'], 'method' => 'post']) !!}
          <form action="{$v->env.source_path}{$v->env.module}/brbrpointplus/index/" method="post">
            <td width="100%">BRポイント加算情報一覧・新規登録<br>
              <span style="font-size: 15px; color: red;">BRで販売する際にのみ追加で付与するポイントの情報を登録できます。</span>
             　<small style="color:#336">
               <br />
               <div onclick="obj=document.getElementById('openbrp').style; obj.display=(obj.display=='none')?'block':'none';">
               <a style="cursor:pointer;">▼ クリックで展開</a>
               </div>
               <div id="openbrp" style="display:none;clear:both;">
                ・<span style="font-weight: bold;">このポイントはBRの負担になります。</span>施設には請求しません。<br /><br />
                ・ポイントの付与はバッチ処理で後付けされます。<br />
                　お客様は<span style="font-weight: bold;">「BRポイント付与状況」</span>画面でこのポイントを含めた付与ポイントを確認することになります。<br />
                　<span style="font-weight: bold;">「BRポイント付与状況」</span>画面に表示されるタイミングは以下になります。<br /><br />
                　&nbsp;&nbsp;<span  style="color: #222; display: inline-block; line-height: 1.2em;"><span style="font-weight: bold;">①&nbsp;施設が付けたポイント</span>　・・・・予約の時点で仮ポイントが付与される。</span><br />
                　&nbsp;&nbsp;<span  style="color: #222; display: inline-block; line-height: 1.2em;"><span style="font-weight: bold;">②&nbsp;ここで登録したポイント</span>・・・・予約の時点で仮ポイントが付与されない。バッチ完了後に付与される。</span ><br />
                　&nbsp;&nbsp;<span style="display: inline-block; line-height: 2em;">（参考）BR予約確認画面には予約毎の付与ポイントは表示されません。</span><br /><br />
                ・お客様向けの予約確認メールには施設が付与したポイントのみが表示されます。<br />
                　ここで登録したポイントの付与は後日バッチで処理され、お客様には付与したことを通知しません。<br /><br />
              ・施設に送信される予約通知には施設負担分も含めて付与ポイントの情報はありません。<br /><br />
              ・施設用のプラン管理画面にはこのポイント付与について指定された文言が情報として表示されます。<br /><br />
              ・施設用の送客実績・料金変更画面と送客請求実績画面には<br />
              　このポイントの情報を含まない施設負担分の付与ポイントのみが表示されます。<br /><br />
              ・対象の期間は短くすることはできません。長くすることはできます。<br />
              　長くした場合、レコードを修正した翌日のバッチ処理で新たに対象になった予約にポイントが付与されます。<br /><br />
              ■開発向け<br />
              ・バッチが処理対象とするBR_POINT_PLUS_INFOのレコードは宿泊日の終了日＋60日間です。（キャンセルを取消せるよう）<br />
               </div>
              </small>
            </td>
            <td><input type="submit" value="登録" /></td>
          {!! Form::close() !!}
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td><strong>施設管理TOPお知らせ情報画面</strong>
      <table border="1" cellpadding="4" cellspacing="0">
        <tr>
          {!! Form::open(['route' => ['ctl.brbroadcastmessage.index'], 'method' => 'post']) !!}
              <td width="100%">施設管理TOPお知らせ情報一覧画面・新規登録<br />
                <span style="font-size: 15px; color: red;">施設管理TOP画面のお知らせ情報を登録する画面です。</span>
              </td>
              <td><input type="submit" value="登録" /></td>
          {!! Form::close() !!}
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td><strong>BRサイトTOP注目変更画面</strong>
      <table border="1" cellpadding="4" cellspacing="0">
        <tr>
          {!! Form::open(['route' => ['ctl.brattention.index'], 'method' => 'post']) !!}
              <td width="100%">BRサイトTOP注目変更画面・新規登録・編集<br />
                <span style="font-size: 15px; color: red;">BRサイトTOP画面の注目情報を登録する画面です。</span>
              </td>
              <td><input type="submit" value="登録" /></td>
          {!! Form::close() !!}
        </tr>
      </table>
    </td>
  </tr>
</table>

@section('title', 'footer')
@include('ctl.common.footer')