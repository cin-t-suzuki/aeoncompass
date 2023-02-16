{{-- TODO: --}}

{{-- MEMO: 移植元 public\app\rsv\view2\top\_rotation_banner.tpl --}}

<link href="{$v->env.path_base}/css/slider-pro.css" rel="stylesheet">
<script src="{$v->env.path_base}/js/jquery.sliderPro.min.js"></script>
<div class=" pgc-600-sc slider-pro" id="jqs-slider">

    {{-- バナー画像 --}}


    <div class="sp-slides">
        {{-- Go To トラベル キャンペーン --}}
        {{-- <div class="sp-slide">
            <a href="{$v->env.path_base}/campaign/goto/">
                <img class="sp-image" src="{$v->env.path_img}/tpc/slider/2020-goto.gif" alt="Go To トラベル キャンペーン" />
            </a>
        </div> --}}

        {{-- 新規会員登録＆宿泊で1000プレゼントキャンペーン --}}
        {if $smarty.now >= '2020-07-28 00:00:00'|strtotime}
        {if $smarty.now < '2020-08-31 23:59:59' |strtotime} <div class="sp-slide">
            <a href="{$v->env.path_base}/campaign/b20071/">
                <img class="sp-image" src="{$v->env.path_img}/tpc/slider/2020-2-cp.gif" alt="新規会員登録＆宿泊で1000ポイントプレゼントキャンペーン" />
            </a>
    </div>
    {/if}
    {/if}

    {{-- 都道府県民限定プラン特集 --}}
    <div class="sp-slide">
        <a href="{$v->env.path_base}/feature/arealimited2022/A01/">
            <img class="sp-image" src="{$v->env.path_img}/tpc/slider/kenmin-br.jpg" alt="都道府県民限定プラン特集" />
        </a>
    </div>

    {{-- ベストセレクション --}}
    <div class="sp-slide">
        <a href="{$v->env.path_base}/feature/bselection2021/A01/">
            <img class="sp-image" src="{$v->env.path_img}/tpc/slider/2020-1-bc.gif" alt="ベストセレクション" />
        </a>
    </div>

    {{-- MOTHER化粧品付きプラン特集 --}}
    <div class="sp-slide">
        <a href="{$v->env.path_base}/feature/2017mother/A02/">
            <img class="sp-image" src="{$v->env.path_img}/tpc/slider/2018-3-mother.gif" alt="MOTHER化粧品付きプラン特集" />
        </a>
    </div>

    {{-- お天気保険付きプラン --}}
    <div class="sp-slide">
        <a href="{$v->env.path_base}/guide/weatherplan/">
            <img class="sp-image" src="{$v->env.path_img}/tpc/slider/2017-4-weather.gif" alt="お天気保険付きプラン" />
        </a>
    </div>

    {if $smarty.now >= '2020-04-25 00:00:00'|strtotime}
    {{-- ＪＲコレクション --}}
    {{-- ＪＲおでかけネットから遷移したときは表示しない。代替えとしてお天気保険を表示する。 --}}
    <div class="sp-slide">
        <a id="a-ch-jr-rentacar" href="{$v->env.path_base}/feature/jrc/">
            <img class="sp-image ch-jr-rentacar" src="{$v->env.path_img}/tpc/slider/95-4-jrc.gif" alt="JR＋宿泊のお得なセット" />
        </a>
    </div>
    {/if}

    {if $smarty.now <= '2019-05-30 23:59:58' |strtotime} {{-- 【ポイント祭り】「EPARKトラベル」＆「ベストリザーブ・宿ぷらざ」連動企画！！ --}} <div class="sp-slide">
        <a href="{$v->env.path_base}/campaign/b19041/">
            <img class="sp-image" src="{$v->env.path_img}/tpc/slider/2019-1-cp.gif" alt="【ポイント祭り】「EPARKトラベル」＆「ベストリザーブ・宿ぷらざ」連動企画！！" />
        </a>
</div>
{/if}

{{-- 城崎温泉特集 --}}
{{-- <div class="sp-slide">
    <a href="{$v->env.path_base}/hotel/grouphotel/KINOSAKI.html">
        <img class="sp-image" src="{$v->env.path_img}/tpc/slider/2018-kinosaki.gif" alt="城崎温泉特集" />
    </a>
</div> --}}

{{-- ベストプライスルーム --}}
{{-- <div class="sp-slide">
    <a href="{$v->env.path_base}/hotel/highrank/">
        <img class="sp-image" src="{$v->env.path_img}/tpc/slider/125-4-bpr-3.gif" alt="ベストプライスルーム 厳選されたホテルを、特別価格でご提供！" />
    </a>
</div> --}}

{{-- レンタカー --}}
{{-- <div class="sp-slide">
    <a href="{$v->env.path_base}/rentacar/">
        <img class="sp-image" src="{$v->env.path_img}/tpc/slider/2017-5-car.gif" alt="レンタカー" />
    </a>
</div> --}}

</div>

<div class="sp-thumbnails">
    {{-- Go To トラベル キャンペーン サムネイル画像 --}}
    {{-- <img class="sp-thumbnail" src="{$v->env.path_img}/tpc/slider//2020-goto.gif" /> --}}

    {{-- 新規会員登録＆宿泊で1000ポイントプレゼントキャンペーン サムネイル画像 --}}
    {if $smarty.now >= '2020-07-28 00:00:00'|strtotime}
    {if $smarty.now
    < '2020-08-31 23:59:59' |strtotime} <img class="sp-thumbnail" src="{$v->env.path_img}/tpc/slider/2020-2-cp.gif" />
    {/if}
    {/if}

    {{-- 都道府県民限定プラン特集 サムネイル画像 --}}
    <img class="sp-thumbnail" src="{$v->env.path_img}/tpc/slider/kenmin-br.jpg" />

    {{-- ベストセレクション サムネイル画像 --}}
    <img class="sp-thumbnail" src="{$v->env.path_img}/tpc/slider/2020-1-bc.gif" />

    {{-- MOTHER化粧品付きプラン特集 サムネイル画像 --}}
    <img class="sp-thumbnail" src="{$v->env.path_img}/tpc/slider/2018-3-mother.gif" />

    {{-- お天気保険付きプラン サムネイル画像 --}}
    <img class="sp-thumbnail" src="{$v->env.path_img}/tpc/slider/2017-4-weather.gif" />

    {if $smarty.now >= '2020-04-25 00:00:00'|strtotime}
    {{-- ＪＲコレクション サムネイル画像 ：ＪＲおでかけネットから遷移したときは表示しない。代替えとしてお天気保険を表示する。 --}}
    <img class="sp-thumbnail ch-jr-rentacar" src="{$v->env.path_img}/tpc/slider/95-4-jrc.gif" />
    {/if}

    {{-- 城崎温泉特集 サムネイル画像 --}}
    {{-- <img class="sp-thumbnail" src="{$v->env.path_img}/tpc/slider/2018-kinosaki.gif" /> --}}

    {{-- ベストプライスルーム サムネイル画像 --}}
    {{-- <img class="sp-thumbnail" src="{$v->env.path_img}/tpc/slider/125-4-bpr-3.gif" /> --}}

    {{-- レンタカー サムネイル画像 --}}
    {{-- <img class="sp-thumbnail" src="{$v->env.path_img}/tpc/slider/2017-5-car.gif" /> --}}

    {if $smarty.now
    <= '2019-05-30 23:59:58' |strtotime} {{-- 【ポイント祭り】「EPARKトラベル」＆「ベストリザーブ・宿ぷらざ」連動企画！！ --}} <img class="sp-thumbnail" src="{$v->env.path_img}/tpc/slider/2019-1-cp.gif" />
    {/if}

</div>
</div>
</div>
{literal}
<script type="text/javascript">
    $(document).ready(function() {
        var thumbNum = $('.sp-thumbnails .sp-thumbnail').length;
        thumbHeight = 166;
        if (thumbNum > 1) {
            thumbHeight = (166 - (thumbNum - 1) * 4) / thumbNum;
        }

        $('#jqs-slider').sliderPro({
            width: 495, //横
            height: 166, //縦
            orientation: 'vertical', //スライド方向
            arrows: true, //左右矢印
            buttons: false, //ナビゲーションボタン
            loop: false, //ループ
            thumbnailsPosition: 'right', //サムネイルの位置
            thumbnailPointer: true, //アクティブなサムネイルにマークを付ける
            autoplay: true, //自動再生。初期値：true
            autoplayDelay: 5000, //自動再生の間隔。初期値：5000
            fade: true, //フェード処理。初期値：false
            thumbnailWidth: 115, //サムネイルの横幅
            thumbnailHeight: thumbHeight, //サムネイルの縦幅
            breakpoints: {
                600: { //表示方法を変えるサイズ
                    thumbnailsPosition: 'bottom',
                    thumbnailWidth: 200,
                    thumbnailHeight: 80
                },
                480: { //表示方法を変えるサイズ
                    thumbnailsPosition: 'bottom',
                    thumbnailWidth: 110,
                    thumbnailHeight: 60
                }
            }
        });

        // PC版Chromeのみスライダー画像のリンクが効かない為の対応。
        if ($(window).width() > 767) {
            $("#jqs-slider .sp-slide a").each(function() {
                $(this).addClass("sp-selectable").css("cursor", "pointer");
            });
        }

        if ($.cookies.get('CP') == '1169008784') {
            //{/literal}
            //{{-- ＪＲおでかけネットから遷移したときは表示しない。代替えとしてお天気保険の画像、リンクを表示する。 --}}
            //$("a.ch-jr-rentacar").attr("href","{$v->env.path_base}/rentacar/");
            $("#a-ch-jr-rentacar").attr("href", "{$v->env.path_base}/guide/weatherplan/");
            $("img.ch-jr-rentacar").attr("src", "{$v->env.path_img}/tpc/slider/2017-4-weather.gif"); {
                * 以下はローテーションバナー下の大バナーの制御 *
            }
            $("#sfm-extra-atag-id").attr("href", "{$v->env.path_base}/guide/weatherplan/");
            $(".sfm-extra img").attr("src", "{$v->env.path_img}/tpc/banner-weatherplan-306-159.gif");
            //{literal}
        }

        $('.sp-thumbnails .sp-thumbnail').each(function() {
            $(this).hover(
                function() {
                    // サムネイルのホバーイベント
                    var idx = $('.sp-thumbnails .sp-thumbnail').index(this);
                    $('#jqs-slider').sliderPro('gotoSlide', idx);
                },
                function() {
                    // フォーカスが外れたとき
                }
            );
        });

        $('.slider-pro').animate({
            opacity: '1'
        }, 1500);

    });
</script>
{/literal}
