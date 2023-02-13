{{-- MEMO: 移植元 public\app\rsv\view2\_common\_message_org.tpl --}}
{{-- エラーメッセージ --}}
@if ($errors->any())
    <style type="text/css">
        .ei {
            margin: 1em 0;
            padding: 0.8em 1.2em;
            border: 2px solid #900;
            color: #900;
            background-color: #FFF;
            line-height: 1.25em;
        }

        .ei ul,
        .ei ol {
            margin: 0 1em;
            padding: 0 1em;
        }
    </style>
    <div class="ei">
        @foreach ($errors->all() as $error)
            {{ strip_tags($error, '<br>') }}
            <br />
        @endforeach
    </div>
@endif

{{-- ガイドメッセージ --}}
@if (isset($guides) && count($guides) > 0)
    <style type="text/css">
        .gi {
            margin: 1em 0;
            padding: 0.8em 1.2em;
            border: 2px solid #009;
            color: #009;
            background-color: #FFF;
            line-height: 1.25em;
        }

        .gi ul,
        .gi ol {
            margin: 0 1em;
            padding: 0 1em;
        }
    </style>
    <div class="gi">
        @foreach ($guides as $guide)
            {{ strip_tags($guide, '<br>') }}
            <br />
        @endforeach
    </div>
@endif
