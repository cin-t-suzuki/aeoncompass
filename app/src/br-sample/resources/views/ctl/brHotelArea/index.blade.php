{{-- MEMO: 移植元 svn_trunk\public\app\ctl\view2\brhotelarea\index.tpl --}}

{{-- ヘッダーのテンプレート読み込み --}}
{{-- TODO: 読込ファイルは、別の新たに発見されたもの --}}
{{-- TODO: パラメータ --}}
{{-- {include
  file  = $v->env.module_root|cat:'/view2/_common/_header2.tpl'
  title = '施設と地域の関連付け【一覧】'
  screen_type  = 'br'
  js_action    = $smarty.capture.js_action
} --}}
@extends('ctl.common.base3', [
    'title' => '施設と地域の関連付け【一覧】',
])
{{-- @section('title', '施設と地域の関連付け【一覧】') --}}

{{-- JavaScript指定 --}}
@section('headScript')
    <script type="text/javascript">
        <!--
        $(document).ready(function() {
            $('input.jqs-area-delete').click(function() {
                return confirm(
                    $('.jqs-area-nm').eq($('input.jqs-area-delete').index(this)).val()
                    + '\n\nこの地域情報を削除します。\nよろしいですか？'
                );
            });
        });
        // -->
    </script>
@endsection

@section('content')

    {{-- 余白 --}}
    <hr class="bound-line-l" />

    {{-- メッセージ --}}
    @include('ctl.common.message2')

    {{-- 余白 --}}
    <hr class="bound-line-l" />

    @include('ctl.brHotelArea._hotel_info')

    {{-- 余白 --}}
    <hr class="bound-line-l" />

    <div>
        <table class="br-list">
            <tr>
                <th class="fc">大エリア</th>
                <th>都道府県</th>
                <th>中エリア</th>
                <th>小エリア</th>
                <th colspan="2" class="lc">
                    {{ Form::open(['route' => 'ctl.br_hotel_area.new', 'method' => 'get']) }}
                        <div>
                            <input type="submit" value="新規追加" />
                            <input type="hidden" name="target_cd" value="{{ request()->input('target_cd') }}" />
                        </div>
                    {{ Form::close() }}
                </th>
            </tr>
            @forelse ($hotel_areas as $hotel_area)
                <tr class="
                    @if (request()->input('target_no') === $hotel_area['entry_no'])
                        {{ 'active' }}
                    @else
                        {{ $loop->odd ? 'odd' : 'even' }}
                    @endif
                ">
                    <td>{{ $hotel_area['area_nm_l'] }}</td>
                    <td>{{ $hotel_area['area_nm_p'] }}</td>
                    <td>{{ $hotel_area['area_nm_m'] }}</td>
                    {{-- TODO: null 合体を削除 --}}
                    <td>{{ $hotel_area['area_nm_s'] }}</td>
                    <td>
                        {{ Form::open(['route' => 'ctl.br_hotel_area.edit', 'method' => 'get']) }}
                            <div>
                                <input type="hidden" name="target_cd" value="{{ $hotel_area['hotel_cd'] }}" />
                                <input type="hidden" name="entry_no" value="{{ $hotel_area['entry_no'] }}" />
                                <input type="submit" value="編集" />
                            </div>
                        {{ Form::close() }}
                    </td>
                    <td>
                        {{ Form::open(['route' => 'ctl.br_hotel_area.delete', 'method' => 'post']) }}
                            <div>
                                <input type="hidden" name="area_pattern" class="jqs-area-nm" value="{{ $hotel_area['area_nm_l'] . ' ' . $hotel_area['area_nm_p'] . ' ' . $hotel_area['area_nm_m'] . ' ' . $hotel_area['area_nm_s'] }}" />
                                <input type="hidden" name="target_cd" value="{{ $hotel_area['hotel_cd'] }}" />
                                <input type="hidden" name="entry_no" value="{{ $hotel_area['entry_no'] }}" />
                                <input type="submit" value="削除" class="jqs-area-delete" />
                            </div>
                        {{ Form::close() }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <p class="msg-text-error">現在登録されている地域はありません</p>
                    </td>
                </tr>
            @endforelse
        </table>
        <div class="br-list-tail">&nbsp;</div>
    </div>

    {{-- 余白 --}}
    <hr class="bound-line-l" />

    {{ Form::open(['route' => 'ctl.brhotel.show', 'method' => 'get']) }}
        <div class="br-back-main-menu-form">
            {{-- <input type="hidden" name="target_cd" value="{$hotel_area.hotel_cd}" /> --}}
            <input type="hidden" name="target_cd" value="{{ request()->input('target_cd') }}" />
            <input type="submit" value="詳細変更へ" />
        </div>
    {{ Form::close() }}

    {{-- 余白 --}}
    <hr class="bound-line-l" />

@endsection
