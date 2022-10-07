{{-- 元ファイル: svn_trunk\public\app\ctl\view2\brpartnercustomer\_form.tpl --}}

{{-- 検索フォーム --}}
{{ Form::open(['route' => 'brpartnercustomer.search', 'method' => 'post']) }}
    <p>
        <table class="br-detail-list">
            <tr>
                <th>キーワード</th>
                <td>
                    <input type="text" name="keywords" size="50" maxlength="20" value="{{ strip_tags($form_params['keywords'] ?? '') }}" />
                    <br /><a href="#" class="toggle_form_help">キーワードのヘルプ</a>
                </td>
            </tr>
        </table>
    </p>
    <p>
        <input type="submit" value="　検索　" />{{-- HACK: スペースでレイアウトするの変では？ --}}
    </p>
{{ Form::close() }}

{{-- キーワード検索のヘルプ --}}
@include('ctl.brPartnerCustomer._form_help')
