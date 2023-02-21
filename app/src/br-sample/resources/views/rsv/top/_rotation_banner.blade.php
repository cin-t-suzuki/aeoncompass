{{-- TODO: --}}

{{-- MEMO: 移植元 public\app\rsv\view2\top\_rotation_banner.tpl --}}
{{--
    MEMO: 日付を条件に表示・非表示を制御している箇所は、適宜削除している。
        例：
            {過去の日付} よりも過去の場合に表示 → すべて削除
            {過去の日付} よりも未来の場合に表示 → 中身を表示して、条件分岐を削除
        他、移植元ソースでコメントアウトされている箇所は削除。
--}}
<link href="{{ asset('css/slider-pro.css') }}" rel="stylesheet">
<script src="{{ asset('js/jquery.sliderPro.min.js') }}"></script>
<div class=" pgc-600-sc slider-pro" id="jqs-slider">

    {{-- バナー画像 --}}


    <div class="sp-slides">

    {{-- 都道府県民限定プラン特集 --}}
    <div class="sp-slide">
        <a href="/feature/arealimited2022/A01/">
            <img class="sp-image" src="{{ asset('img/tpc/slider/kenmin-br.jpg') }}" alt="都道府県民限定プラン特集" />
        </a>
    </div>

    {{-- ベストセレクション --}}
    <div class="sp-slide">
        <a href="/feature/bselection2021/A01/">
            <img class="sp-image" src="{{ asset('img/tpc/slider/2020-1-bc.gif') }}" alt="ベストセレクション" />
        </a>
    </div>

    {{-- MOTHER化粧品付きプラン特集 --}}
    <div class="sp-slide">
        <a href="/feature/2017mother/A02/">
            <img class="sp-image" src="{{ asset('img/tpc/slider/2018-3-mother.gif') }}" alt="MOTHER化粧品付きプラン特集" />
        </a>
    </div>

    {{-- お天気保険付きプラン --}}
    <div class="sp-slide">
        <a href="/guide/weatherplan/">
            <img class="sp-image" src="{{ asset('img/tpc/slider/2017-4-weather.gif') }}" alt="お天気保険付きプラン" />
        </a>
    </div>

    {{-- ＪＲコレクション --}}
    {{-- ＪＲおでかけネットから遷移したときは表示しない。代替えとしてお天気保険を表示する。 --}}
    <div class="sp-slide">
        <a id="a-ch-jr-rentacar" href="/feature/jrc/">
            <img class="sp-image ch-jr-rentacar" src="{{ asset('img/tpc/slider/95-4-jrc.gif') }}" alt="JR＋宿泊のお得なセット" />
        </a>
    </div>

</div>

<div class="sp-thumbnails">
    {{-- 都道府県民限定プラン特集 サムネイル画像 --}}
    <img class="sp-thumbnail" src="{{ asset('img/tpc/slider/kenmin-br.jpg') }}" />

    {{-- ベストセレクション サムネイル画像 --}}
    <img class="sp-thumbnail" src="{{ asset('img/tpc/slider/2020-1-bc.gif') }}" />

    {{-- MOTHER化粧品付きプラン特集 サムネイル画像 --}}
    <img class="sp-thumbnail" src="{{ asset('img/tpc/slider/2018-3-mother.gif') }}" />

    {{-- お天気保険付きプラン サムネイル画像 --}}
    <img class="sp-thumbnail" src="{{ asset('img/tpc/slider/2017-4-weather.gif') }}" />

    {{-- ＪＲコレクション サムネイル画像 ：ＪＲおでかけネットから遷移したときは表示しない。代替えとしてお天気保険を表示する。 --}}
    <img class="sp-thumbnail ch-jr-rentacar" src="{{ asset('img/tpc/slider/95-4-jrc.gif') }}" />

</div>
</div>
</div>
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
            //{{-- ＪＲおでかけネットから遷移したときは表示しない。代替えとしてお天気保険の画像、リンクを表示する。 --}}
            //$("a.ch-jr-rentacar").attr("href","/rentacar/");
            $("#a-ch-jr-rentacar").attr("href", "/guide/weatherplan/");
            $("img.ch-jr-rentacar").attr("src", "/img/tpc/slider/2017-4-weather.gif"); {
                // * 以下はローテーションバナー下の大バナーの制御 *
            }
            $("#sfm-extra-atag-id").attr("href", "/guide/weatherplan/");
            $(".sfm-extra img").attr("src", "/img/tpc/banner-weatherplan-306-159.gif");
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
