@section('title', '確認')
@include('ctl.common.base')

@inject('service', 'App\Http\Controllers\ctl\BrtopController')

<!-- 
{* header start *}
	{include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="確認"}
{* header end *} -->


<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td>
      <table border="1" cellspacing="0" cellpadding="4" width="100%">
         {{ Form::open(['route' => 'ctl.brsecurity.search', 'method' => 'post']) }}
       
        <tr>
          <td>セキュリティログ一覧</td>
          <td><input type="submit" style="margin: 0px 10px;" value=" 確認 "></td>
        </tr>
        
        {!! Form::close() !!}
        {{ Form::open(['route' => 'ctl.brnotify.search', 'method' => 'post']) }}
        
        <tr>
          <td>予約通知ログ</td>
          <td><input type="submit" style="margin: 0px 10px;" value=" 確認 "></td>
        </tr>
    
        {!! Form::close() !!}
<!--旧コードのコメントアウト　念のため保管
        <form action="kbs_brv_check_rate.html" method="POST">
        <tr>
          <td>料率確認リスト<br>
            　<small style="color:#336">不正な料率が設定されているかどうかを一覧で確認できます。</small>
          <td><input type="submit" value=" 確認 "></td>
        </tr>
        </form>
        <FORM ACTION="kbs_com_mail.hyoji" METHOD="POST">
        <input type="hidden" name="i_kubun" value="brv">
        <tr>
          <td>施設のメール送信履歴<br>
            　<small style="color:#336">ホテル管理画面から会員へ送信したメールを検索</small>
          </td>
          <td><input type="submit" style="margin: 0px 10px;" value=" 確認 "></td>
        </tr>
        </form>
-->
        {{ Form::open(['route' => 'ctl.brmailbuffer.search', 'method' => 'post']) }}
        <tr>
          <td>メール送信ログ<br>
            <small style="color:#336">メール送信ログの一覧や詳細等が確認できます。</small>
          <td><input type="submit" style="margin: 0px 10px;" value=" 確認 "></td>
        </tr>
      
        {!! Form::close() !!}
<!--旧コードのコメントアウト　念のため保管
        <form action="{$v->env.source_path}{$v->env.module}/brreservepower/search/" method="POST">
        <tr>
          <td>売り上げ料金確認画面<br>
            　<small style="color:#336">パワーオーソリ状況の確認や変更、ログの一覧等が確認できます。</small>
          <td><input type="submit" style="margin: 0px 10px;" value=" 確認 "></td>
        </tr>
        </form>

        <form action="{$v->env.source_path}{$v->env.module}/brdatum/stream/" method="POST">
        <tr>
          <td>ストリーム日別実績一覧<br>
            　<small style="color:#336">ストリームの日別実績一覧（７日分）が確認できます。</small>
          <td><input type="submit" style="margin: 0px 10px;" value=" 確認 "></td>
        </tr>
        <input type="hidden" name = "first_flg" value="true" />
        </form>
-->
        {{ Form::open(['route' => 'ctl.brpartnertotal.search', 'method' => 'post']) }}
       
        <tr>
          <td>提携先別専用料金登録プラン一覧<br>
            <small style="color:#336">提携先別専用料金登録プラン一覧（HS007）が確認できます。</small>
          <td><input type="submit" style="margin: 0px 10px;" value=" 確認 "></td>
        </tr>
       
        {!! Form::close() !!}

        <tr>
          <td>部屋登録状況一覧<br>
            <small style="color:#336">部屋登録状況一覧が確認できます。<br />　抽出には１・２分かかりますので表示されるまでしばらくお待ちください。</small>
          <td>
          {{ Form::open(['route' => 'ctl.brroomdemand.search', 'method' => 'post']) }}
          	
          		<input type="submit" style="margin: 0px 10px;" value=" 確認 "><br>
          	
          {!! Form::close() !!}
          {{ Form::open(['route' => 'ctl.brroomdemand.download', 'method' => 'post']) }}
          	<input type="submit" title="部屋登録状況一覧をcsvでダウンロードできます。" style="margin: 10px 0px;" value="CSV出力">
	
          {!! Form::close() !!}

          </td>
        </tr>
        
        {{ Form::open(['route' => 'ctl.brroomplaninfo.index', 'method' => 'post']) }}
       <tr>
          <td>部屋プラン情報一覧<br>
            <small style="color:#336">ホテル単位毎の部屋プラン情報が確認できます。</small>
          <td><input type="submit" style="margin: 0px 10px;" value=" 確認 "></td>
        </tr>
       
        {!! Form::close() !!}
        {{ Form::open(['route' => 'ctl.brmsd.planlist', 'method' => 'post']) }}
     
        <tr>
          <td>MSD専用プラン一覧<br>
            <small style="color:#336">MSD専用プランの一覧が確認できます。</small>
          <td><input type="submit" style="margin: 0px 10px;" value=" 確認 "></td>
        </tr>
    
        {!! Form::close() !!}
      </table>

<div style="margin-top:1em">
  <table border="1" cellspacing="0" cellpadding="4" width="100%">
    <tr>
      <td width="100%" nowrap>監視対象者予約状況<br></td>
      <td>
        {{ Form::open(['route' => 'ctl.brblacklist', 'method' => 'post']) }}
       
            <input type="submit" value=" 確認 ">
        
        {!! Form::close() !!}
    </td>
    </tr>
  </table>
</div>

<div style="margin-top:1em">
      <table border="1" cellspacing="0" cellpadding="4" width="100%">
        <tr>
          <td width="100%" nowrap>基礎集計（ベータ）<br>
            <small style="color:#336">基礎となる予約数、会員数、施設数、訪問数の集計結果</small>
          </td>
          <td>
            {{ Form::open(['route' => 'ctl.brrecord', 'method' => 'post']) }}
            
                <input type="submit" value=" 確認 ">
          
            {!! Form::close() !!}
        </td>
        </tr>
<!--旧コードのコメントアウト　念のため保管
        <form action="kbs_brv_addr_hotel.main" method="POST">
        <tr>
          <td width="100%" nowrap>住所別ホテルリスト<br>
            　<small style="color:#336">年月、都道府県別のホテルリスト（空室数、宿泊数付）</small>
          </td>
          <td><input type="submit" value=" 確認 "></td>
        </tr>
        </form>
        <form action="kbs_brv_hs_ranking_hotel.html" method="POST">
        <tr>
          <td>都道府県別　ホテルランキング検索<br>
            　<small style="color:#336">ホテル管理画面のランキング部分をホテル別に表示</small>
          <td><input type="submit" value=" 確認 "></td>
        </tr>
        </form>
        <form action="kbs_brv_report_extend.html" method="post">
        <tr>
          <td>自動延長機能<br>
            　<small style="color:#336">自動延長機能の登録状態および実行状態を把握するための統計資料</small>
          </td>
          <td><input type="submit" value=" 確認 " /></td>
        </tr>
        </form>
-->
      </table>
</div>

<div style="margin-top:1em">
      <table border="1" cellspacing="0" cellpadding="4" width="100%">
      {{ Form::open(['route' => 'ctl.braccounting.salespowerhotels', 'method' => 'post']) }}
    
        <tr>
          <td width="100%">パワーホテル売上表（ベータ）<br>
            <small style="color:#336">買取・事前カード決済対象施設の売上実績表が確認できます。</small>
          </td>
          <td><input type="submit" value=" 確認 " /></td>
        </tr>
     
        {!! Form::close() !!}
      </table>
</div>



    </td>
  </tr>
</table>
<br>

@include('ctl.common.footer')