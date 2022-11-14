{{-- MEMO: 移植 svn_trunk\public\app\ctl\views\_common\_htl_service_info.tpl --}}

<table align="right" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      {if $ad==true}
      {* 表示期間はリリース直後から 2010-05-22 17:00:00 まで *}
      {if $v->helper->date->set('2010-05-22 17:00:00')}{/if}

      {* 簡単増返室 *}
      {if $smarty.now <= $v->helper->date->get() and $v->env.controller == 'htlreroom2'}
        <div style="margin-bottom:0.5em;"><a href="https://{$v->config->system->rsv_host_name}/intro/funaisoken/page02.html" title="船井総合研究所×プライスコンシェルジュ セミナーのご案内「先行予約の取り込み強化でホテルはＶ字回復できる！」"  target="funaisoken"><img src="/intro/funaisoken/images/pcg002.gif" width="260" height="60" border="0" alt="船井総合研究所×プライスコンシェルジュ セミナーのご案内「先行予約の取り込み強化でホテルはＶ字回復できる！」" /></a></div>

      {* 部屋管理総合 *}
      {elseif $smarty.now <= $v->helper->date->get() and $v->env.controller == 'htlstock'}
        <div style="margin-bottom:0.5em;"><a href="https://{$v->config->system->rsv_host_name}/intro/funaisoken/page02.html" title="船井総合研究所×プライスコンシェルジュ セミナーのご案内「先行予約の取り込み強化でホテルはＶ字回復できる！」"  target="funaisoken"><img src="/intro/funaisoken/images/pcg002.gif" width="260" height="60" border="0" alt="船井総合研究所×プライスコンシェルジュ セミナーのご案内「先行予約の取り込み強化でホテルはＶ字回復できる！」" /></a></div>
      {/if}
      {/if}

      <table border="1" cellpadding="6" cellspacing="0">
        <tr>
          <td nowrap style="font-size:10pt;">
            お問い合わせ先<br>
             MAIL:<a href="mailto:{$v->config->environment->mail->from->opc}">{$v->config->environment->mail->from->opc}<br>
             </a>TEL : 03-5751-8243<br>
             FAX : 03-5751-8242<br>
             受付: 月～金 9:30～18:30<br />
            （土曜・日曜・祝祭日・弊社休日は除く）
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>