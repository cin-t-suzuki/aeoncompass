{{-- MEMO: 移植元 public\app\rsv\view2\top\_link_advert_hotels.tpl --}}

<div style="float:left;">
    <img src="{{ asset('img/tpc/tpc-pt-title.gif') }}" alt="ポイントで得しちゃおう！ポイントが２倍！朝食バイキングが大人気のホテル特集" width="546" height="21" />
</div>

{{--
    TODO: smarty -> blade の書き換え
        使用しない可能性があるので、保留
        実装する場合、$advert_hotels 変数はコントローラから渡す
--}}
@php
    $advert_hotels = [
        [
            'hotel_nm' => 'hotel name',
            'pref_nm' => '東京都',
            'city_nm' => null,
            'info' => '',
        ]
    ];
@endphp
<div style="clear:both;">
    @foreach ($advert_hotels as $key_hotels => $hotel)
        <div class="tpc-h2cl">
            <div class="tpc-h2cl-photo">
                <a href="{$v->env['path_base']}/hotel/{$hotel['hotel_cd']}/" title="{strip_tags($hotel['hotel_nm'])} ホテル詳細">
                    <img src="/img/hotel/{$hotel['hotel_cd']}/trim_100/{$hotel['hotel_media']['values'][0]['file_nm']}" alt="画像" width="68" height="68" />
                </a>
            </div>
            <div class="tpc-h2cl-area">
                【
                @if (is_null($hotel['city_nm']))
                    {{$hotel['pref_nm']}}
                @elseif ($hotel['city_nm'] == '東京２３区')
                    {$hotel['pref_nm']}
                    {' '|cat:$hotel['ward_nm']|rtrim}
                @elseif (mb_strpos($hotel['city_nm'], $hotel['pref_ns']) === 0)
                    {$hotel['city_nm']}
                    {' '|cat:$hotel['ward_nm']|rtrim}
                @else
                    {$hotel['pref_nm']}
                    {' '|cat:$hotel['city_nm']|rtrim}
                @endif
                】
            </div>
            <div class="tpc-h2cl-hotel">
                <a href="{$v->env['path_base']}/hotel/{$hotel['hotel_cd']}/" title="{strip_tags($hotel['hotel_nm'])} ホテル詳細">
                    {{$hotel['hotel_nm']}}
                </a>
            </div>
            <div class="tpc-h2cl-info">
                @if (strip_tags($hotel['info']) != '')
                    {capture name=link_sequel}...
                    <a href="{$v->env['path_base']}/hotel/{$hotel['hotel_cd']}/" title="{strip_tags($hotel['hotel_nm'])} ホテル詳細">
                        続きを読む
                    </a>
                    {/capture}
                    @if (mb_strlen($hotel['hotel_nm']) >= 19)
                        {$v->helper->string->left(strip_tags($hotel['info']), 17, $smarty['capture']['link_sequel'])}
                    @else
                        {$v->helper->string->left(strip_tags($hotel['info']), 40, $smarty['capture']['link_sequel'])}
                    @endif
                @endif
            </div>
            <div class="tpc-h2cl-link">
                【
                <a href="{$v->env['path_base']}/plan/{$hotel['hotel_cd']}/">
                    部屋情報
                </a>
                |
                <a href="{$v->env['path_base']}/voice/{$hotel['hotel_cd']}/">
                    クチコミ
                </a>
                】
            </div>
            <div style="clear:both;">
            </div>
        </div>
    @endforeach
</div>
