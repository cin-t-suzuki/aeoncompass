{{--
    TODO: 判断保留
    移植元で view と view2 で分かれてたものの view2 のほう。
    ./message.blade.php が view のほうのもの。
    構造は同じだが、装飾が異なる。
    view のベースとなるテンプレートファイルでも、 view2 のメッセージで統一する場合、
    view2 で読み込んでいる css を読み込む必要がある。
--}}

@if (!empty($errors) && count($errors) > 0)
    <div class="msg-box">
        <div class="msg-box-back">
            <div class="msg-box-contents msg-box-error">
                @foreach ($errors as $error)
                    <div>{{ strip_tags($error, '<br>') }}</div>
                @endforeach
            </div>
        </div>
    </div>
@endif

{{-- ガイドメッセージの表示 --}}
@if (!empty($guides) && count($guides) > 0)
    <div class="msg-box">
        <div class="msg-box-back">
            <div class="msg-box-contents msg-box-info">
                @foreach ($guides as $guide)
                    <div>{{ strip_tags($guide, '<br>') }}</div>
                @endforeach
            </div>
        </div>
    </div>
@endif