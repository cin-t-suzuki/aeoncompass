{{-- エラーメッセージの表示 --}}
@if (!empty($errors) && is_array($errors) && count($errors) > 0)
    <div style="border-style:solid;border-color:#f00;border-width:1px;padding:6px;background-color:#fee;">
        @foreach ($errors as $error)
            <div>{{ strip_tags($error, '<br>') }}</div>
        @endforeach
    </div>
@endif

{{-- withErrors で渡されたエラーメッセージ --}}
{{-- 
    MEMO: HACK: Laravel では、バリデーション失敗の際に $errors 変数に自動的にエラーメッセージを定義するが、
    既存の独自実装で controller から $errors 変数を渡しているために、このような筋の悪い実装になっている。
 --}}
{{-- TODO: view() の第2引数で渡される $errors をすべて消したら、条件の前項を消す --}}
@if ($errors instanceof \Illuminate\Support\ViewErrorBag && $errors->any())
    <div style="border-style:solid;border-color:#f00;border-width:1px;padding:6px;background-color:#fee;">
        @foreach ($errors->all() as $error)
            <div>{{ strip_tags($error, '<br>') }}</div>
        @endforeach
    </div>
@endif

{{-- ガイドメッセージの表示 --}}
@if (!empty($guides) && count($guides) > 0)
    <div style="border-style:solid;border-color:#00f;border-width:1px;padding:6px;background-color:#eef;">
        @foreach ($guides as $guide)
            <div>{{ strip_tags($guide, '<br>') }}</div>
        @endforeach
    </div>
@endif
