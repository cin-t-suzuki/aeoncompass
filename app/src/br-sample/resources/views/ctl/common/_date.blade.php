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
//       y/m/d H:M:S: 2008/08/05 00:00:00   :y/m/d H:M:S
//           ym     : 2008年08月            : Y年m月
//           d      : 05                    : d
//           J      : 火曜日                : J
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
{{-- TODO  不要？ @if ( $v->helper->date->set($timestamp*1) ) @endif --}}

@php
	//{{-- 日付関連の クラスを設定 --}}
	$dateUtil = new \App\Common\DateUtil($timestamp);

	//{{-- 曜日クラス設定(Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, Saturday) --}}
	$class_week = $dateUtil->to_week('E');

	//{{-- 祝日クラスの設定 --}}
	$day = $timestamp;

	if ($dateUtil->is_holiday())
	{
		$class_holiday = ' holiday';
		$holiday_nm = $dateUtil->to_holiday_nm();
	}else{
		$holiday_nm = '';
	}

	// formatで指定した形式で日付と曜日を文字列で取得 
	// TODO 使用箇所があったときにコメントアウトし実装
	// Y年m月d日（j）  
	if ( (!isset($format) || $format == 'ymd(w)') )
	{
		//assign var='day' value=$v->helper->date->to_format('Y年m月d日')|cat:'（'|cat:$v->helper->date->to_week('j')|cat:'）'
		$day = $dateUtil->to_format('Y年m月d日') .'（'. $dateUtil->to_week('j'). '）';
	}
	// Y年m月d日 
	elseif ( $format == 'ymd' )
	{
	  //assign var='day' value=$v->helper->date->to_format('Y年m月d日')
		$day = $dateUtil->to_format('Y年m月d日');
	}
	// y/m/d H:M:S  
	elseif ( $format == 'y/m/d H:M:S' )
	{
		$day = $dateUtil->to_format('Y/m/d H:i:s');
	}
	// Y年m月
	elseif ( $format == 'ym' )
	{
		$day = $dateUtil->to_format('Y年m月');
	
		/* Y年 
		elseif ( $format == 'y' )
		  assign var='day' value=$v->helper->date->to_format('Y年')
		 日付：0～31 
		elseif ( ($format == 'j') )
		  assign var='day' value=$v->helper->date->to_format('j')
		 日付：00～31 
		elseif ( $format == 'd' )
		  assign var='day' value=$v->helper->date->to_format('d')
		 G時i分 
		elseif ( $format == 'Gi' )
		  assign var='day' value=$v->helper->date->to_format('G時i分')
		 月曜日：3文字日本語  
		elseif ( ($format == 'J') )
		  assign var='day' value=$v->helper->date->to_week('J')
		 月曜日：1文字日本語  
		elseif ( ($format == '(j)') )
		  assign var='day' value=$v->helper->date->to_week('j')
		// その他指定は、デフォルトと一緒のフォーマットで表示 
		else
		  assign var='day' value=$v->helper->date->to_format('Y年m月d日')|cat:'（'|cat:$v->helper->date->to_week('j')|cat:'）'
		*/
	}
	//{{-- TODO 実装箇所のみコメントアウト

	//{{-- 色指定の設定 --}
	if  ( isset($color_on)) {
		$class_color =' color';
	}

	// 指定formatの文字列取得
	// font_color の設定  
	if ( isset($color_on) ) {
		//  祝日 --
		if ( isset($holiday_nm) )		{
				$font_color = 'red' ;
		//   日曜日 
		}elseif ( $dateUtil->to_week('n') == 1 )		{
			$font_color='red';
		//   土曜日 
		}elseif ( $dateUtil->to_week('n') == 7 )		{
			$font_color='blue';
		}
	}

@endphp

{{-- 日付を表示 --}}
<span class="date"><span class="{$class_week}{$class_holiday}{$class_color}" title="{{ $holiday_nm }}">
	@if ( isset($font_color) )
		<font color="{$font_color}">
	@endif
	{{ $day }}
	@if ( isset($font_color) )
		</font>
	@endif
</span></span>
