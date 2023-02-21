{{-- TODO: --}}

{{-- MEMO: 移植元: public\app\rsv\view2\top\_form_keyword.tpl --}}

<div class="sfm-keyword">
    <script src="{{ asset('/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('/js/keyword_suggest.js') }}"></script>
    <link type="text/css" href="/css/jquery-ui.css" rel="stylesheet" />

    <div class="sfm-keyword-inner">
        {{ Form::open(['route' => 'rsv.keywords.search', 'method' => 'get']) }}
        <input id="f_query" name="keywords" type="text" value="" style="width:216px; margin:-2px 0 0 0; padding:4px;" maxlength="40" placeholder="入力例：県名 ホテル名">
        <div class="btn-b01-068-s" style="margin:-8px 16px 0 0; float:right; padding:5px;">
            <input class="btnimg collectBtn" src="{{ asset('img/btn/b01-search3.gif') }}" type="image" alt="キーワード検索" />
        </div>
        {{ Form::close() }}

        {{--
            TODO: キーワードの実装
            コントローラが継承した親クラスの init() メソッドの中で、変数に値を入れている。
            public\app\rsv\lib\Controllers\Action2.php の L.730 行あたりで、駅、ランドマークとともに処理されている。
         --}}
        {{-- {foreach from=$v->user->partner->keyword_example.keyword item=keyword name=keyword} --}}
        @if (count($keywords) > 0)
            <ul>
                <li class="title">☆彡 人気のおすすめキーワード</li>
                @foreach ($keywords as $keyword)
                    <li>
                        {{--
                            MEMO:
                                $keyword['value'] には、null でなければ、 '/rentacar/', '/feature/ninki/' などのように
                                url が入っているらしい。
                            HACK: キーワード設定機能で検討
                                url はルーティング定義内で完結させたい。DBには url ではなくルート名で保存するのがよいか。
                                そもそも、この機能必要？
                        --}}
                        {{-- <a href="@if (!is_null($keyword['value'])) {$v->env.path_base}{$keyword.value} @else {$v->env.path_base}/keywords/?keywords={$keyword.word|urlencode} @endif ">
                            {{ $keyword['word'] }}
                        </a> --}}
                        <a href="{{ $keyword['value'] ?? route('rsv.keywords.search', ['keywords' => $keyword['word']]) }}">
                            {{ $keyword['word'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
