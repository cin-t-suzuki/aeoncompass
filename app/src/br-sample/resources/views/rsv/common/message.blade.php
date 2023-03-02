{{-- MEMO: 移植元 public\app\rsv\view2\_common\_message.tpl --}}

{{-- エラーメッセージ --}}
@if (!empty($errorMessages) && count($errorMessages) > 0)
    <div class="alart-box alart-error">
        @foreach ($errorMessages as $error)
            <p class="align-left">
                {{ strip_tags($error, '<br>') }}
            </p>
        @endforeach
    </div>
@endif

{{-- withErrors で渡されたエラーメッセージ --}}
{{-- 
    MEMO: HACK: Laravel では、バリデーション失敗の際に $errors 変数に自動的にエラーメッセージを定義するが、
    既存の独自実装で controller から $errors 変数を渡している箇所のために、衝突を避けるための実装になっている。
 --}}
{{-- TODO: view() の第2引数で渡される $errors をすべて消したら、条件の前項を消す --}}
@if ($errors instanceof \Illuminate\Support\ViewErrorBag && $errors->any())
    <div class="alart-box alart-error">
        @foreach ($errors->all() as $error)
            <p class="align-left">
                {{ strip_tags($error, '<br>') }}
            </p>
        @endforeach
    </div>
@endif

@if (!empty($guides) && count($guides) > 0)
    <div class="alart-box alart-guide">
        @foreach ($guides as $guide)
            <p class="align-left">
                {{ strip_tags($guide, '<br>') }}
            </p>
        @endforeach
    </div>
@endif
