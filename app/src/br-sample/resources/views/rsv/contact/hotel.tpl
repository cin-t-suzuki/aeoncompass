{include file='../_common/_header.tpl' title='宿泊施設関係者の方へ・ベストリザーブ・宿ぷらざ 参画のご案内'}
{include file='../_common/_pgh1.tpl' pgh1_mnv=1}

{literal}
<script type="text/javascript">
$(function() {
$('#send_status').click(function() {
  if($(this).is(':checked')) {
    $('.style02').removeClass('grayout');
  } else {
    $('.style02').addClass('grayout');
  }
});
});
window.onload = function(){
document.getElementById("send_status").onclick = function(){
document.getElementById("postal_cd2").disabled = !this.checked;
document.getElementById("pref_id2").disabled = !this.checked;
document.getElementById("address2").disabled = !this.checked;
document.getElementById("hotel_nm2").disabled = !this.checked;
document.getElementById("person_post2").disabled = !this.checked;
document.getElementById("person_nm2").disabled = !this.checked;
document.getElementById("person_nm_kana2").disabled = !this.checked;
document.getElementById("tel2").disabled = !this.checked;
document.getElementById("email2").disabled = !this.checked;
}
}
</script>
{/literal}

<div id="pgh2v2">
  <div class="pg">
    <div class="pgh2-inner">
{include file='../_common/_pgh2_inner.tpl'}
    </div>
  </div>
</div>

<div id="pgc1v2">
  <div class="pg">
    <div class="pgc1-inner">
{include file='./_pgc1_breadcrumbs.tpl'}
{include file='./_snv_text.tpl' current='hotel'}
    </div>
  </div>
</div>

<div id="pgc2">
  <div class="pg">
    <div class="pgc2-inner advance">
      <div style="text-align:center;">
        <div style="width:700px; margin:0 auto;text-align:left;">
          <div style="padding:1em 0">
            <h2 id="h2_title">宿泊施設関係者様へ・ベストリザーブ・宿ぷらざ　参画のご案内</h2>
          </div>

          <div id="contact_container" class="clearfix">
            <div id="contact_contents">
              {if !$v->error->has()}
              <div class="section">
                <h3 class="title">ベストリザーブ・宿ぷらざとは</h3>
                <ul class="guide1">
                  <li><span>インターネットを介した国内宿泊予約サービスです。<br />
                  （株式会社ベストリザーブと株式会社日本旅行の共同運営となります。）</span></li>
                  <li><span>会員総数約2,000,000人！レジャー、ビジネスなど幅広いニーズのお客様にご利用いただいております。</span></li>
                  <li><span>宿泊実績に応じた手数料をご負担いただくシステムのため、施設様のご参画費用、年会費は無料です。</span></li>
                  <li><span style="color:red;"><b>送客にかかる基本手数料(システム利用料)は <font size="4.5px;">８％ !</font><br />
                            ベストリザーブ・宿ぷらざでは業界最安値水準のコストで送客のお手伝いをいたします。</b><br /></span></li>
                  <li><span>お部屋の増室・返室、料金の設定等インターネットの画面でいつでもご自由に調整できます。<br />
                            最低提供客室数（アロットメント数）等の規定はございません。</span></li>
                </ul>
              </div>

              <div class="section2">
                <h3 class="title">豊富な提携による集客と管理支援！ </h3>
                <dl class="guide2">
                  <dt>■法人出張請負（クローズドマーケット）での販売</dt>
                    <dd>ベストリザーブ・宿ぷらざ独自の法人契約により、一部上場企業や外資系企業など多くの法人・<br />企業様に対しクローズなマーケットでの販売が可能です。</dd>
                  <dt>■豊富な提携メディアからの送客</dt>
                    <dd>ベストリザーブ・宿ぷらざが契約する多くの横断検索サイト、提携サイトに同時販売が行われるため、<br />提携先からの送客もご期待いただけます！</dd>
                  <dt>■一元管理ツール連動</dt>
                    <dd>主要な予約一元管理ツールと直接システム連動！在庫コントロールにストレスがかかりません。</dd>
                </dl>
              </div>

              <div class="lp-img01"></div>

              {/if}

              <div class="section">
                <h3 class="title">資料請求</h3>

                <ul id="step">
                <li class="step-1-0">資料請求内容の入力</li>
                <li class="step-2-1">入力内容の確認</li>
                <li class="step-3-1">お手続き完了</li>
                </ul>

                {if !$v->error->has()}
                <p class="caution">詳細資料・参画書類のご請求ご希望の場合は下記よりお申し込み下さい。</p>
                <p>あらかじめ当社<a href="{$v->env.path_base}/about/policy/privacy/" target="_blank">プライバシーポリシー</a>に同意をお願いします。</p>
                {else}
                {* メッセージ *}
                {include file='../_common/_message_org.tpl'}

                {/if}

                {include file='../contact/_form_hotel.tpl'}

              </div>

              <div><p class="return"><a href="#top">↑ページのトップに戻る</a></p></div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


{include file='../_common/_footer.tpl'}
