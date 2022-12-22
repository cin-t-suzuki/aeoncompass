<table border="0" cellpadding="0" cellspacing="0" width="700">
  <tr>
    <td style="vertical-align:top;">
      <table border="1" cellpadding="4" cellspacing="0">
        <tr><td  bgcolor="#EEEEFF"  nowrap align="center"><strong>【　「ベストリザーブ」新管理画面への移行に関するご案内　】</strong></td></tr>
        <tr>
          <td>
            2011年12月の日本旅行との提携に向けて、「ベストリザーブ」の大幅な機能追加を実施いたします。追加機能は新しい管理画面をご利用の宿泊施設様に限らせていただいております。以下、ご覧のうえ新画面への切替ならびに新しいプランの造成などご協力の程お願い申上げます。
            <br />
            <br />
            <table border="0" align="center">
              <tr>
                <td colspan="2" align="center">【「ベストリザーブ」新管理画面　追加機能について】</td>
              </tr>
              <tr>
                <td><strong>・「プラン 部屋」構造への切替</strong><br />（新プラン造成時の簡便化）</td>
                <td><strong>・マンチャージ方式での価格設定</strong><br />（ルームチャージも引続き可能）</td>
              </tr>
              <tr>
                <td>・子供料金登録への対応</td>
                <td>・プランの販売期間の設定</td>
              </tr>
              <tr>
                <td>・コーポレート価格設定の機能強化　　</td>
                <td>など</td>
              </tr>
            </table>
            <br />
            新管理画面の<a href="http://{$v->config->system->rsv_host_name}/hs/manual/">操作マニュアルはこちら</a>になります。
            <br />
            <br />
            新管理画面では「部屋・プラン」から「プラン・部屋」による管理となります。
            <br />
            また、プランの利用人数も６名までの単価設定に対応しております。
            {{-- {if !$v->assign->is_migration_complete and !($v->user->is_open_adjournment_ctl)} --}}
            @if (!$views->is_migration_complete && !($views->is_open_adjournment_ctl))
              <br />
              従来のプランを新しい体系に変換いただく為「プラン・部屋登録方式移行ツール」を用意しました。
              <br />
              以降ツールでの処理完了いただくと新機能のご利用が可能になります。
              <br />
              新管理画面へ切替をご希望の宿泊施設様は下記ボタンより進めてください。
              <br />
              <br />
              <div align="center">
                <form method="post" action="{$v->env.source_path}{$v->env.module}/htlmigration/">
                  <input type="submit" value="プラン・部屋登録方式移行ツール" style="width:14em;" />
                  <input type="hidden" name="ctl_nm" value="{$v->env.controller}" />
                  <input type="hidden" name="act_nm" value="{$v->env.action}" />
                  <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
                </form>
              </div>
              <br />
              ※旧管理画面へ戻る事は出来ませんのでご注意願います。
              <br />
              ※「プラン・部屋登録方式移行ツール」のご利用は一度限りとなります。
            @endif
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br />