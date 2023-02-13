{{-- HACK: refactoring
    timestamp として渡される値が不明瞭
        Unix タイムスタンプを想定しているのであれば、2038年問題対策のために、
        PHP の DateTime オブジェクトをベースにしたオブジェクトに置き換えたい
        Carbon ライブラリ (DateTime オブジェクトのラッパー) を使うのが、
        柔軟性、ネット上の情報の充実度の点で適していると思われる。
--}}

{{-- * -----------------------------------------------------------------------------
//
// 日付フォーマット用テンプレート（詳しくはBr_Models_Date参照
//
// timestamp timestampの時刻
// format    日付のフォーマット（デフォルト：ymd(j)）
//
//           [フォーマットパターン]
//           ------- ----------------------- ---------------
//           指定   : 例                    : Br_Models_Date
//           ------- ----------------------- ---------------
//           ymd(w) : 2008年08月05日（火）  : Y年m月d日（j）
//           ymd    : 2008年08月05日        : Y年m月d日
//　　       y-m-d  : 2008-08-05            : Y-m-d
//           ym     : 2008年08月            : Y年m月
//           d      : 05                    : d
//           J      : 火曜日                : J
//	         ymdhi  : 2008-08-05 12:59
//           ------- ----------------------- ---------------
//          ※ 必要に応じてパターンの追加する。
//
// color_on  色の設定（デフォルト：false）
//            true  :あり
//            false :なし
//
//
// 例：2008年07月18日 (金) の形で取得できる  ※日曜、祝日は赤　土曜日は青で表示
// {include file=$v->env.module_root|cat:'/views/_common/_date.tpl' timestamp=$timestamp format='ymd(w)'}
//
// 例：2008年07月18日 (金) から２日後を表示
// @if ( $v->helper->date->set('2008-07-18')}@endif
// @if ( $v->helper->date->add('d', 2)}@endif
// {include file=$v->env.module_root|cat:'/views/_common/_date.tpl' timestamp=$v->helper->date->get() format='ymd(w)'}
//
//
----------------------------------------------------------------------------- --}}
{{-- 取得した日付をセット ※掛けているのは数値として認識させる為 --}}
{{-- TODO: 不要？ @if ( $v->helper->date->set($timestamp*1) ) @endif --}}

@php
    //{{-- 日付関連の クラスを設定 --}}
    $dateUtil = new \App\Common\DateUtil($timestamp);

    //{{-- 曜日クラス設定(Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, Saturday) --}}
    $class_week = $dateUtil->to_week('E');

    //{{-- 祝日クラスの設定 --}}
    $day = $timestamp;

    if ($dateUtil->is_holiday()) {
        $class_holiday = ' holiday';
        $holiday_nm = $dateUtil->to_holiday_nm();
    } else {
        $class_holiday = '';
        $holiday_nm = '';
    }

    if (!isset($format)) {
        $format = 'ymd(w)';
    }

    // formatで指定した形式で日付と曜日を文字列で取得
    // TODO: 使用箇所があったときにコメントアウトし実装
    if ($format == 'ymd(w)') {
        // Y年m月d日（j）
        //assign var='day' value=$v->helper->date->to_format('Y年m月d日')|cat:'（'|cat:$v->helper->date->to_week('j')|cat:'）'
        $day = $dateUtil->to_format('Y年m月d日') . '（'. $dateUtil->to_week('j') . '）';
    } elseif ($format == 'ymd') {
        // Y年m月d日
          //assign var='day' value=$v->helper->date->to_format('Y年m月d日')
        $day = $dateUtil->to_format('Y年m月d日');
    } elseif ($format == 'ym') {
        // Y年m月
        $day = $dateUtil->to_format('Y年m月');
    } elseif ( $format == 'ymdhi' ) {
        // Y年m月d日 
		$day = $dateUtil->to_format('Y-m-d H:i');
    } elseif ($format == 'y-m-d') {
        // Y-m-d
        $day = $dateUtil->to_format('Y-m-d');
    }
     /* elseif ( $format == 'y' )
        // Y年
        assign var='day' value=$v->helper->date->to_format('Y年')

        elseif ( ($format == 'j') )
        // 日付：0～31
        assign var='day' value=$v->helper->date->to_format('j')

        elseif ( $format == 'd' )
        // 日付：00～31
        assign var='day' value=$v->helper->date->to_format('d')

        elseif ( $format == 'Gi' )
        // G時i分
        assign var='day' value=$v->helper->date->to_format('G時i分')

        elseif ( ($format == 'J') )
        月曜日：3文字日本語
        assign var='day' value=$v->helper->date->to_week('J')

        elseif ( ($format == '(j)') )
        月曜日：1文字日本語
        assign var='day' value=$v->helper->date->to_week('j')

        else
        // その他指定は、デフォルトと一緒のフォーマットで表示
        assign var='day' value=$v->helper->date->to_format('Y年m月d日')|cat:'（'|cat:$v->helper->date->to_week('j')|cat:'）'
    }
    */
    //{{-- TODO 実装箇所のみコメントアウト

    if (!isset($color_on)) {
        $color_on = false;
    }

    //{{-- 色指定の設定 --}
    if  ($color_on) {
        $class_color = ' color';
    } else {
        $class_color = '';
    }

    // 指定formatの文字列取得
    // font_color の設定
    if ($color_on) {
        //  祝日 --
        if ($holiday_nm != '') {
                $font_color = 'red' ;
        //   日曜日
        } elseif ($dateUtil->to_week('n') == 1) {
            $font_color='red';
        //   土曜日
        } elseif ($dateUtil->to_week('n') == 7) {
            $font_color='blue';
        }
    }
@endphp

{{-- 日付を表示 --}}
<span class="date">
    <span class="{{ $class_week . $class_holiday . $class_color }}" title="{{ $holiday_nm }}">
        <span style="color: {{ isset($font_color) ? $font_color : '' }};">
            {{ $day }}
        </span>
    </span>
</span>
