{{-- 元ファイル: svn_trunk\public\app\ctl\view2\brpartnercustomer\_form.tpl --}}

{{-- 検索フォーム --}}
<form action="{{ $v->env['path_base_module'] }}/brpartnercustomer/search/" method="post">
    <p>
        <table class="br-detail-list">
            <tr>
                <th>キーワード</th>
                <td>
                    <input type="text" name="keywords" size="50" maxlength="20" value="{{ 'TODO: オブジェクトが実装されたら修正' . '$v->helper->form->strip_tags($v->assign->form_params.keywords)' }}" />
                    <br /><a href="" onclick="helpForm(); return false;">キーワードのヘルプ</a>{{-- TODO: onlcick は見逃してよい？ --}}
                </td>
            </tr>
        </table>
    </p>
    <p>
        <input type="submit" value="　検索　" />
    </p>
</form>
{{-- キーワード検索のヘルプ --}}
@include('ctl.brPartnerCustomer._form_help')
