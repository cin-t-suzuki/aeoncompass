#!/bin/bash

# README
# smarty で書かれたビューテンプレートを、機械的にできる範囲で blade に置換する

# smarty document: https://www.smarty.net/docs/ja/
#     バージョンが合ってるかは不明

# 注意
#     試作版です。完全な変換を保証するものではありません。
#     特定のテンプレートをたたき台として作っているので、これに特化したスクリプトが紛れています。
#     基本的に各スクリプトは「うまくいく」or「なにもしない」場合がほとんどですが、変な書き換えが発生することがあります。
#     不要な箇所は適宜コメントアウトしてから実行するなど、注意して使ってください。

# 使用方法
#     このファイルが convert.sh として laravel プロジェクト直下に保存しているとする。
#     実行権限を付与するために、以下のコマンドを実行
#         $ chmod +x ./convert.sh
#     変換対象ファイルと出力先ファイルを変数に設定
#         $ in=./resources/views/ctl/htl/media/editplan.blade.php
#         $ out=./out.blade.php
#     スクリプトを実行（パラメータに、変換対象と、出力先を指定）
#         $ ./convert.sh $in $out
#     確認して、問題なければ変換結果を元ファイルにマージ
#         $ cp $out $in
#     必要なければ出力ファイルを削除
#         $ rm $out
#     ※ 出力先に変換対象を指定すれば、直接変換できる（非推奨）
#         $ ./convert.sh $in $in

# 未対応
#     埋め込みの css, js
#         {} を一括で {{}} に変換するため、 css, js も巻き添えを食らいます
#         たぶん、 blade 部分を {} -> {{}} と変換するより、 css, js を {{}} -> {} と変換するほうが楽そう
#     {if}
#         チェックボックスの selected やラジオボタンの checked の制御に使われている行内の {if} だとバグるかも
#     {section} (for)
#         カウンタ変数の開始が 0 出ない場合、 $smarty.section.loop_name.index などの特別な変数の置換が不適当
#         $smarty.section.loop_name.last: bool に対応していない（手動で書き換え）
#     form 関連のパーツ
#         {{ Form::xxx() }} という記法に変換する機能も入れたい
#         open, close, text, hidden, checkbox, radio, select, password, submit など

# 開発用メモ
#     MEMO: sed コマンドの正規表現では \d, \w, \s は使えないので、以下で代用する
#         \d -> [0-9]
#         \w -> [a-zA-Z_0-9]
#         \s -> [ \f\n\r\t]
#     MEMO: [ ] 内で角括弧を使う場合、[ はエスケープ無し、] は冒頭に置く


# コマンド引数を受け取る
in=${1}
out=${2}
echo $in
echo $out

# たたき台としてコピー
cp $in $out

# {} の空白を消す
sed -i -e "s/{[ \f\n\r\t]\+/{/g" $out
sed -i -e "s/[ \f\n\r\t]\+}/}/g" $out


# {if} を置換
sed -i -e "s/{if \(.*\)}/@if (\1) /" $out
sed -i -e "s/{elseif \(.*\)}/@elseif (\1) /" $out
sed -i -e "s/{else}/@else /" $out
sed -i -e "s/{\/if}/@endif /" $out

# {foreach} を置換
sed -i -e "s/{foreach from=\(\$[a-zA-Z_\.>-]\+\) \+item=\([a-zA-Z_]\+\)}/@foreach (\1 as $\2) /" $out
sed -i -e "s/{foreach from=\(\$[a-zA-Z_\.>-]\+\) \+item=\([a-zA-Z_]\+\) \+key=\([a-z_]\+\)}/@foreach (\1 as $\3 => $\2) /" $out
sed -i -e "s/{\/foreach}/@endforeach /" $out

# {section} を置換 (smarty の for)
sed -i -e "s/{section name=\(.*\) start=\(0\) loop=\(.*\)}/@for (\$i = \2; \$i < \3; \$i++) /g" $out
# sed -i -e "s/{section name=\(.*\) start=\(.*\) loop=\(.*\)}/@for (\$i = \2; \$i < \2 + \3; \$i++) /g" $out
sed -i -e "s/{\/section}/@endfor /" $out

    # smarty のループで使える特別な変数 (index, first, last, iteration)
    # これらの変換は、ループの開始が 0 であることを前提としている
    sed -i -e "s/\$smarty\.section\.[a-zA-Z_]\+\.index/\$i/g" $out
    sed -i -e "s/\$smarty\.section\.[a-zA-Z_]\+\.first/\$i == 0/g" $out
    sed -i -e "s/\$smarty\.section\.[a-zA-Z_]\+\.iteration/\$i + 1/g" $out
    # TODO: $smarty.loop.{loop_name}.last には対応していません。手動で書き換えます。

# {assign} を置換
# {assign} は、テンプレート内で使える変数の宣言と値の割り当てを行う
sed -i -e "s/\( *\){assign var=\([a-zA-Z_]\+\) \+value=\(true\|false\)}/\1@php\n\1    $\2 = \3;\n\1@endphp /g" $out
sed -i -e "s/\( *\){assign var=\([a-zA-Z_]\+\) \+value=\([0-9]\+\)}/\1@php\n\1    $\2 = \3;\n\1@endphp /g" $out
sed -i -e "s/\( *\){assign var=\"*\([a-zA-Z_]\+\)\"* \+value=\"\`\(\$[a-zA-Z_]\+\) \?\(+\) \?\([0-9]\+\)\`\"}/\1@php\n\1    $\2 = \3 \4 \5;\n\1@endphp /g" $out
sed -i -z -e "s/@endphp *\n *@php *\n */    /g" $out # 複数連続する場合、1つにまとめる

# パスを変換
sed -i -e "s/{\$v->env\.source_path}//g" $out
sed -i -e "s/{\$v->env\.module}/\/ctl/g" $out


# smarty の連想配列のドット記法を角括弧に変換
# sed -i -e "s/\(\$[a-zA-Z_\.>-]\+\)\.\([a-zA-Z_]\+\)/\1['\2']/g" $out
sed -i -e "s/\.\([a-z_]\+\)/['\1']/g" $out
sed -i -e "s/\['tpl'\]/.tpl/g" $out # .tpl ファイルは戻す

# include 変換 
# MEMO: @include ディレクティブはドット(.) を使うので、 smarty の連想配列記法の跡で実行する
# MEMO: include は、基本的に、旧ソースの対応するファイルを辿りやすくするため、元の記述をコメントアウトしておく
    # header, footer
    sed -i -e "s/\({include file=.*\/views\/_common\/\(_htl_\)header.tpl.*title='\(.\+\)'}\)/{{-- \1 --}} \n@extends('ctl.common.\2base')\n@section('title', '\3')\n/g" $out
    sed -i -e "s/\({include file=.*\/views\/_common\/_htl_\(footer\).tpl.*}\)/{{-- \1 --}} \n@endsection/g" $out
        # コメントアウト行を消す
        sed -i -e "/<!-- \/\?\(Header\|Footer\) -->/d" $out

    # message
    sed -i -e "s/{include .*\/views\/_common\/\(_message.*\)\.tpl.*}/@include('ctl.common.\1') /g" $out

    # css, js
    # MEMO: <!-- CSS --><!-- /CSS --> のようなコメントが付いているファイルで作ったため、これがない場合は手動で書き換えます。
    # MEMO: @section('content') ディレクティブを挿入するスクリプトも一緒に書かれています。
    sed -i -e "/<!-- \/CSS -->\n<!-- JS -->/d" $out
    sed -i -e "s/<!-- \(CSS\|JS\) -->/@section('headScript')/" $out
    sed -i -e "s/<!-- \/\(CSS\|JS\) -->/@endsection\n\n@section('content')/" $out # content 本体の @section をここに追加（妙案あれば）
    sed -i -e "s/{include .*\.\/\([a-zA-Z_]\+\)\.tpl'}/    @include('ctl\.htl\.media\.\1')/" $out

    # 各個撃破
    sed -i -e "s/\({include .*\/view2\/htlsmedia\/\([a-zA-Z_]\+\)\.tpl'}\)/{{-- \1 --}}\n@include('ctl\.htl\.media\.\2')/" $out
    sed -i -e "s/\( *\)\({include .*\/view2\/_common\/\([a-zA-Z_]\+\)\.tpl' \([a-zA-Z_]\+\)=\(\$[]a-zA-Z_>['-]\+\)}\)/\1{{-- \2 --}}\n\1@include('ctl\.common\.\3', ['\4' => \5])/g" $out

# $v->assign->hoge -> $hoge
sed -i -e "s/\$v->assign->/$/g" $out

# {} を {{}} に
# include のコメントアウトは、先に退避して後で戻す
# 埋め込みの css, js がバグる場合は、どちらかを手動で修正します
sed -i -e "s/{\(include[^}]*\)}/~~~\1~~~/" $out # 旧ソースの include を退避
sed -i -e "s/\([^{]\){\([^{]\)/\1{{ \2/g" $out
sed -i -e "s/^{\([^{]\)/{{ \1/g" $out
sed -i -e "s/\([^}]\)}\([^}]\)/\1 }}\2/g" $out
sed -i -e "s/\([^}]\)}$/\1 }}/g" $out
sed -i -e "s/~~~\(.*\)~~~/{\1}/" $out # 旧ソースの include を戻す

# or -> ||, and -> &&
sed -i -e "s/ or / || /g" $out
sed -i -e "s/ and / \&\& /g" $out

# is_empty は、とりあえず is_null で代用
sed -i -e "s/^\( *\)\(.*\)is_empty\(.*\)$/\1{{-- MEMO: ↓ もとは is_empty() --}}\n\1\2is_null\3/g" $out

# 行末の空白文字を削除
sed -i -e "s/[ \f\n\r\t]*$//" $out
