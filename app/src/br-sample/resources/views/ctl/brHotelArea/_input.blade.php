{{-- MEMO: 移植元 svn_trunk\public\app\ctl\view2\brhotelarea\_input.tpl --}}
{{--
  引数
    $title: ページのタイトルを指定します。
    $action_type: 実行されるアクションを指定します。（create：新規作成, update：更新）
--}}
{{-- ヘッダーのテンプレート読み込み --}}
{{-- MEMO: file = $v->env.module_root|cat:'/view2/_common/_header2.tpl' --}}
@extends('ctl.common.base3', [
    'title' => $title,
    'screen_type' => 'br',
])
{{-- @section('title', $title) --}}

{{-- JavaScript指定 --}}
@section('headScript')
    <script type="text/javascript" src="{{ asset('/js/brj.ctl.js') }}"></script>

    <script type="text/javascript">
        <!--
        $(document).ready(function() {
            $('#jqs-hotel-area').loadHotelArea({
                uri: '/ctl/brHotelArea/json',
                // uri         : '{$v->env.source_path}{$v->env.module}/brhotelarea/json/',
                area_large  : '{{ $request_params['area_large'] }}',
                area_pref   : '{{ $request_params['area_pref'] }}',
                area_middle : '{{ $request_params['area_middle'] }}',
                area_small  : '{{ $request_params['area_small'] }}'
            });
        });
        //-->
    </script>
@endsection

@section('content')
    {{-- 余白 --}}
    <hr class="bound-line-l" />

    @include('ctl.brHotelArea._hotel_info')

    {{-- 余白 --}}
    <hr class="bound-line-l" />

    {{-- メッセージ --}}
    @include('ctl.common.message2')

    {{-- 余白 --}}
    <hr class="bound-line" />

    {{ Form::open(['route' => 'ctl.br_hotel_area.' . $action_type, 'method' => 'post']) }}
        <div>
            <input type="hidden" name="target_cd" value="{{ $target_cd }}" />
            <input type="hidden" name="is_submit" value="true" />

            @if ($action_type === 'update')
                <input type="hidden" name="entry_no" value="{{ $entry_no }}" />
            @endif

            <table class="br-list" id="jqs-hotel-area">
                <tr>
                    <th class="fc">大エリア</th>
                    <th>都道府県</th>
                    <th>中エリア</th>
                    <th>小エリア</th>
                    <th class="lc">&nbsp;</th>
                </tr>
                <tr>
                    <td>
                        <select name="area_large" id="jqs-area-l-list">
                            <option value="">未選択</option>
                        </select>
                    </td>
                    <td>
                        <select name="area_pref" id="jqs-area-p-list">
                            <option value="">未選択</option>
                        </select>
                    </td>
                    <td>
                        <select name="area_middle" id="jqs-area-m-list">
                            <option value="">未選択</option>
                        </select>
                    </td>
                    <td>
                        <select name="area_small" id="jqs-area-s-list">
                            <option value="">未選択</option>
                        </select>
                    </td>
                    <td>
                        <input type="submit" value="{{ $action_type === 'create' ? '追加' : ($action_type === 'update' ? '変更' : '') }}" />
                    </td>
                </tr>
            </table>
            <div class="br-list-tail">&nbsp;</div>
        </div>
    {{ Form::close() }}

    {{-- 余白 --}}
    <hr class="bound-line-l" />

    {{ Form::open(['route' => 'ctl.br_hotel_area.index', 'method' => 'get']) }}
        <div class="br-back-main-menu-form">
            <input type="hidden" name="target_cd" value="{{ $target_cd }}" />
            <input type="submit" value="施設と地域の関連付け【一覧】へ" />
        </div>
    {{ Form::close() }}

    {{-- 余白 --}}
    <hr class="bound-line-l" />
@endsection
