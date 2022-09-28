<div class="hi-box">
    <div class="hi-hotel">
        <h1 class="hi-name">
            <div style="display: flex; align-items: center;">
                <a href="{{-- {$v->env.path_base}/hotel/{$v->hotel.hotel_cd}/ --}}">
                    {{-- {$v->helper->form->strip_tags($v->hotel.hotel_nm)} --}}
                    @if(!empty($hotel.hotel_old_nm))
                        <br />
                        <span class="before">（旧{{ RsvViewHelp::strip_tags($hotel->hotel_old_nm) }}）</span>
                    @endif
                </a>
                @if ($hotel.camp_goto == 1)
                    <img src="{{ asset('/img/spc/spc-p0-camp_goto_hotel.gif') }}" width="140" height="20" alt="GoToトラベルキャンペーン" />
                @endif
            </div>
        </h1>
        <table class="hi-summary" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td class="hi-photo" rowspan="2" valign="top">
                    {{-- 施設外観写真 --}}
                    @if(!empty($hotel.medias.values.1.0.url))
                        <img src="{{-- {$v->hotel.medias.values.1.0.url} --}}" width="138" height="138" alt="{{ RsvViewHelp::strip_tags($hotel.hotel_nm) }}" />
                    @elseif(!empty($hotel.medias.values.1.0.file_nm))
                        <img src="{{-- {$v->env.root_path}img/hotel/{$v->hotel.hotel_cd}/trim_138/{$v->hotel.medias.values.1.0.file_nm} --}}" width="138" height="138" alt="{{ RsvViewHelp::strip_tags($v->hotel.hotel_nm) }}" />
                    @else
                        <div class="hi-photo-none"></div>
                    @endif
                </td>
                <td valign="top">
                    <div class="hi-info">
                        {{ RsvViewHelp::left(RsvViewHelp::strip_tags($info), 80) }}
                        @if ($rel == "on")
                        ... <a href="{{-- {$v->env.base_path}hotel/{$v->hotel.hotel_cd}/#info --}}">続きを読む</a>
                        @else
                        ... <a href="{$v->env.base_path}hotel/{$v->hotel.hotel_cd}/">続きを読む</a>
                        @endif
                    </div>

                    {{-- 住所 --}}
                    <div class="hi-address">{{ $hotel.pref_nm }}{{ $hotel.address }}</div>

                    {{-- クチコミ --}}
                    @if($hotel.voices.values.0.total_count > 5)
                    <div class="hi-voice">{{-- {$v->hotel.hotel_review.values.0.review_cnt|number_format:1|mb_convert_kana:'N'} --}}</div>
                    <div class="hi-voice-star">
                        <img src="{{-- {$v->env.root_path}img/vic/vic-star-w-{$v->hotel.hotel_review.values.0.review_cnt*10}.gif --}}" width="100" height="17" alt="クチコミ総合{{-- {$v->hotel.hotel_review.values.0.review_cnt|number_format:1} --}}" />
                    </div>
                    @endif

                    {{-- 駐車場 性能向上のため共通テンプレートを利用しません --}}
                    @if( ($hotel.facilities.values.9.element_value_id == 1) || !empty($hotel.info.values.parking_info))
                    <div class="hi-parking">
                        @if( $hotel.facilities.values.9.element_value_id == 1 && !isset($hotel.is_agoda))
                            <strong> {$hotel.facilities.values.9.element_value_text}</strong>
                        @elseif(isset($hotel.is_agoda) && !empty($hotel.info.values.parking_info))
                            <strong>あり</strong>
                        @endif
                        @if(!empty($v->hotel.info.values.parking_info))
                            &nbsp;&nbsp;{{ RsvViewHelp::strip_tags($hotel.info.values.parking_info) }}
                        @endif
                    </div>
                    @endif

                    {{-- 最寄駅 --}}
                    @include('rsv.common.hotel_stations', ['hotel_stations' => $hotel.stations])
                </td>
            </tr>
            <tr>
                <td align="right" valign="bottom">
                    <div class="hi-cmd">
                        {if $b06_hotel}
                        <div class="btn-b06-098-s">
                            <a class="btnimg" href="{$v->env.root_path}hotel/{$v->hotel.hotel_cd}/" title="{$v->helper->form->strip_tags($v->hotel.hotel_nm)}の詳細を表示">
                                <img src="{$v->env.root_path}img/btn/b06-hotel.gif" width="98" height="23" alt="詳細を表示" />
                            </a>
                        </div>
                        {/if}

                        {if $b06_plan}
                        <div class="btn-b06-098-s">
                            <a class="btnimg" href="{$v->env.root_path}plan/{$v->hotel.hotel_cd}/" title="{$v->helper->form->strip_tags($v->hotel.hotel_nm)}のプランリストを表示">
                                <img src="{$v->env.root_path}img/btn/b06-plan.gif" width="98" height="23" alt="プランリストを表示" />
                            </a>
                        </div>
                        {/if}

                        {if $b06_clip}
                        <div class="btn-b06-clip2-s jqs-clip-has" style="display:none;">
                            <a class="btnimg jqs-clip jqs-clip-{$v->hotel.hotel_cd}" href="{$v->env.path_base}/clip/{$v->hotel.hotel_cd}/" title="クリップ削除">
                                <img src="{$v->env.root_path}img/btn/b06-clip2.gif" width="98" height="23" alt="クリップ中" title="「クリップ」とは、よく泊まる宿泊施設や、いつか泊まってみたい気になる宿泊施設を保存できる機能です。サイトに会員ログインいただき「クリップ」すると、その宿泊施設はベストリザーブ・宿ぷらざの画面を閉じても、あらためてサイトに会員ログインいただくと「クリップ」されたまま保存されています。「クリップ」された宿泊施設だけの空室を簡単に探すことができるようになります。" />
                            </a>
                        </div>
                        <div class="btn-b06-098-s">
                            <a class="btnimg jqs-clip jqs-clip-{$v->hotel.hotel_cd}" href="{$v->env.path_base}/clip/{$v->hotel.hotel_cd}/" title="クリップする">
                                <img src="{$v->env.root_path}img/btn/b06-clip1.gif" width="98" height="23" alt="クリップする" title="「クリップ」とは、よく泊まる宿泊施設や、いつか泊まってみたい気になる宿泊施設を保存できる機能です。サイトに会員ログインいただき「クリップ」すると、その宿泊施設はベストリザーブ・宿ぷらざの画面を閉じても、あらためてサイトに会員ログインいただくと「クリップ」されたまま保存されています。「クリップ」された宿泊施設だけの空室を簡単に探すことができるようになります。" />
                            </a>
                        </div>
                        {/if}

                        {if $b06_search}
                        <div class="btn-b06-138-s">
                            <a class="btnimg" href="{$v->env.path_base}/rsv/hotel/reserve/?hotel_cd={$v->hotel.hotel_cd}" title="チェックイン日などを指定して{$v->helper->form->strip_tags($v->hotel.hotel_nm)}の空室を検索します。">
                                <img src="{$v->env.root_path}img/btn/b06-search1.gif" width="138" height="23" alt="空室検索" />
                            </a>
                        </div>
                        {/if}
                        {if $v->hotel.is_jrc}
                        <div class="btn-b06-138-s jqs-jrc">
                            <a class="btnimg" href="{$v->env.root_path}jrc/?SiteCode=00574251&PageType=hotel&ListMode=Plan&HotelCD={$v->hotel.jrc_hotel_cd}{if $v->hotel.pref_id >= 8 and $v->hotel.pref_id <= 14}&Departure=56{/if}" title="出発日などを指定して{$v->helper->form->strip_tags($v->hotel.hotel_nm)}の「ＪＲ＋宿泊検索」へすすみます。" target="_blank">
                                <img src="{$v->env.root_path}img/btn/b06-jrc1.gif" width="138" height="23" alt="ＪＲ＋宿泊検索へすすむ" />
                            </a>
                        </div>
                        {/if}
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
