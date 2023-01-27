{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/_upload_form.tpl --}}

<script language="javascript" type="text/javascript">
    <!--
    $(document).ready(function() {
        if ($('input[name=select]:checked').val() == 'normal') {
            $('#el').show();
        } else {
            $('#el').hide();
        }

        $('input[name=select]').change(function() {
            if ($('input[name=select]:checked').val() == 'normal') {
                $('#el').show();
            } else {
                $('#el').hide();
            }
        });

        $('input[name=upload]').click(function() {
            if ($('input[name=select]:checked').val() == 'normal') {
                // 処理なし
            } else {
                // MEMO: バグってる？ checkbox が value="label_cd" になっている。
                // 想定動作としては、チェックを外したいのだと思われる。
                // $(':checkbox').val('label_cd').attr('checked', '');
                $('#upload_form checkbox').removeAttr('checked').prop('checked', false).change();

                $('#upload_form').append('<input type="hidden" name="label_cd[map]" value="1" />');
            }
            $('#upload_form').submit();
        });

        $('#inputFile').change(function() {
            var file = this.files[0];
            if (file != null) {
                var file_nm = file.name;
                var type = file_nm.split('.');
                if (type.length < 2) {
                    alert('画像ファイルは「.gif」・「.jpeg」・「.jpg」の拡張子を持つファイルのみアップロード可能です。');
                    return;
                }
                var extension = type[type.length - 1];
                if (
                    extension.toLowerCase() != 'gif' &&
                    extension.toLowerCase() != 'jpg' &&
                    extension.toLowerCase() != 'jpeg'
                ) {
                    alert('画像ファイルは「.gif」・「.jpeg」・「.jpg」の拡張子を持つファイルのみアップロード可能です。');
                    return;
                } else {
                    var regExp = new RegExp(extension, "g");
                    file_nm = file_nm.replace(regExp, "");
                    file_nm = file_nm.replace(/\.$/, "");
                    file_nm = file_nm.substr(0, 30);
                    $('#inputTitle').val(file_nm);
                }
            }
        });
    });
    // -->
</script>

<strong>画像の追加</strong>
<p style="color:#a81f42; font-size:12.5px; font-weight: bold; margin:1px 1px 10px 1px;">
    ※ファイル名称に全角文字を含むファイルのアップロードが可能です。
</p>
{{ Form::open(['route' => 'ctl.htl.media.upload', 'method' => 'post', 'id' => 'upload_form', 'enctype' => 'multipart/form-data']) }}
{{ Form::hidden('target_cd', $target_cd) }}

{{ Form::hidden('room_id', $room_id) }}
{{ Form::hidden('plan_id', $plan_id) }}

{{-- TODO: この $media はどこから来たものなのか？ --}}
{{-- {{ Form::hidden('media_no', $media->media_no) }} --}}

{{ Form::hidden('media_type', $media_type) }}
{{ Form::hidden('target_order_no', $target_order_no) }}
{{ Form::hidden('setting_media_no', $setting_media_no) }}
{{ Form::hidden('label_type', $label_type) }}

{{-- TODO: 適切に動作するよう修正 --}}
<script language="javascript" type="text/javascript">
    <!--
    function window_open() {
        window.open(
            '/ctl/htlmedia/rule/target_cd/{{ strip_tags($target_cd) }}/',
            '_blank', 'width=600,height=750,resizable=yes,scrollbars=yes,menubar=no'
        );
    }
    document.write('<a href="#" onclick="window_open()">－ ご利用について －</a>');
    // 
    -->
</script>
<noscript>
    <a href="/ctl/htlmedia/rule/target_cd/{{ strip_tags($target_cd) }}/" target="_blank">－ ご利用について －</a>
</noscript>

<table border="1" cellpadding="4" cellspacing="0" width="700">
    <tr>
        <td style="background-color: #EEEEFF;" nowrap>ファイル指定</td>
        <td width="100%">
            <input id="inputFile" name="file" type="file" size="40" accept="image/jpeg,image/gif,image/pjpeg">
        </td>
    </tr>
    <tr>
        <td style="background-color: #EEEEFF;" nowrap>画像タイトル</td>
        <td>
            {{-- <input id="inputTitle" type="text" size="60" name="title" maxlength="30" value=""> --}}
            {{ Form::text('title', old('title'), ['id' => 'inputTitle', 'size' => '60']) }}
            <br>
            <small>タイトルはお客様画面に表示されます。全角３０文字以内で必ず入力ください。</small>
        </td>
    </tr>
    <tr>
        <td style="background-color: #EEEEFF;" nowrap>画像ラベル</td>
        <td>
            <table border="0" cellpadding="4" cellspacing="1" width="100%">
                {{-- HACK: refactor (工数次第) 共通化したうえで、呼び出し元を条件に分岐するのは、筋が悪いように思われる。 --}}

                {{-- 「画像一覧画面」と「地図画像以外の編集画面」でのみ表示 --}}
                @if (\Route::CurrentRouteName() === 'ctl.htl.media.list' || $form_params['label_cd']['map'] !== '1')
                    <tr>
                        <td style="background-color: #FFFFFF;">
                            {{ Form::radio('select', 'normal', old('select') === 'normal' || $form_params['label_cd']['map'] !== '1', ['id' => 'normal']) }}
                            <label for="normal">地図以外</label>

                            {{-- HACK: (refactor, 工数次第) ただの横並びにテーブルレイアウトを使うのは違和感がある --}}
                            <table id="el" style="margin-left:50px" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr class="visible-normal">
                                    <td style="background-color: #FFFFFF;">
                                        {{ Form::checkbox('label_cd[outside]', '1', old('label_cd.outside', $form_params['label_cd']['outside']) === '1', ['id' => 'outside']) }}
                                        <label for="outside">
                                            <font color="#FF9999">■</font>外観
                                        </label>
                                    </td>
                                    <td style="background-color: #FFFFFF;">
                                        {{ Form::checkbox('label_cd[inside]', '1', old('label_cd.inside', $form_params['label_cd']['inside']) === '1', ['id' => 'inside']) }}
                                        <label for="inside">
                                            <font color="#99FF99">■</font>フォトギャラリー
                                        </label>
                                    </td>
                                    <td style="background-color: #FFFFFF;">
                                        {{ Form::checkbox('label_cd[room]', '1', old('label_cd.room', $form_params['label_cd']['room']) === '1', ['id' => 'room']) }}
                                        <label for="room">
                                            <font color="#66CCFF">■</font>客室
                                        </label>
                                    </td>
                                    <td style="background-color: #FFFFFF;">
                                        {{ Form::checkbox('label_cd[other]', '1', old('label_cd.other', $form_params['label_cd']['other']) === '1', ['id' => 'other']) }}
                                        <label for="other">
                                            <font color="#FF99FF">■</font>その他
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif

                {{-- 「画像一覧画面」と「地図画像の編集画面」でのみ表示 --}}
                @if (\Route::CurrentRouteName() === 'ctl.htl.media.list' || ($form_params['label_cd']['map'] === '1' && ($form_params['label_cd']['outside'] !== '1' && $form_params['label_cd']['inside'] !== '1' && $form_params['label_cd']['room'] !== '1' && $form_params['label_cd']['other'] !== '1')))
                    <tr>
                        <td style="background-color: #FFFFFF" colspan="5;">
                            {{ Form::radio('select', 'map', old('select') === 'map' || $form_params['label_cd']['map'] === '1', ['id' => 'map']) }}
                            <label for="map">
                                <font color="#FFCC66">■</font>地図
                            </label>
                        </td>
                    </tr>
                @endif
            </table>
            <small>ラベルを選択すると、たくさんの画像をアップロードした時の絞込表示に役立ちます。</small>
        </td>
    </tr>
    <tr>
        <td style="background-color: #EEEEFF;">　</td>
        <td>
            <input name="upload" type="button" value="アップロード">
        </td>
    </tr>
</table>
{{ Form::close() }}
