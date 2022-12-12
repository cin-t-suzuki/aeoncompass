{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\brhotel\_log_hotel_person_form.tpl --}}
{{-- MEMO: strip_tags() は移植元に従って実装しているが、過剰なものが含まれている可能性はありそう？ --}}

<table border="1" cellspacing="0" cellpadding="3">
    <tr>
        <td bgcolor="#EEFFEE">施設コード</td>
        <td>
            {{ strip_tags($target_cd) }}
        </td>
        <td><br /></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">アカウントID※</td>
        <td>
            {{ Form::text('Hotel_Account[account_id_begin]', strip_tags($hotel_account->account_id_begin), ['size' => '15', 'maxlength' => '10',]) }}
        </td>
        <td><small>半角英数字で10文字以内<font color="#0000ff">（必須）</font></small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">パスワード※</td>
        <td>
            @if ($service->is_empty($disp))
                {{ Form::text('Hotel_Account[password]', strip_tags($hotel_account->password), ['size' => '15', 'maxlength' => '10',]) }}
            @else
                <span nowrap style="float: left;">**********</span>
                <span nowrap style="float: right;">
                    {{-- TODO: 施設ログインパスワードの閲覧画面　実装後、リンクを張る --}}
                    {{-- <a href="{{ $v->env.source_path }}{{ $v->env.module }}/brhotel/seeingnotes/target_cd/{{ strip_tags($target_cd) }}"> --}}
                    <a href="#">
                        施設ログインパスワードの閲覧
                    </a>
                </span>
            @endif
        </td>
        <td><small>大文字半角英字・半角数字で10文字以内<font color="#0000ff">（必須）</font></small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">ステータス</td>
        <td>
            {{ Form::radio('Hotel_Account[accept_status]', '1', $hotel_account->accept_status == 1 || $service->is_empty($hotel_account->accept_status), ['id' => 'i2',]) }}
            {{ Form::label('i2', '利用可') }}
            {{ Form::radio('Hotel_Account[accept_status]', '0', $hotel_account->accept_status == 0 && !$service->is_empty($hotel_account->accept_status), ['id' => 'i1',]) }}
            {{ Form::label('i1', '利用不可') }}
        </td>
        <td><small>選択</small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">担当者役職</td>
        <td>
            {{ Form::text('Hotel_Person[person_post]', strip_tags($hotel_person->person_post), ['size' => '50', 'maxlength' => '32',]) }}
        </td>
        <td><small>32文字</small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">担当者名称※</td>
        <td>
            {{ Form::text('Hotel_Person[person_nm]', strip_tags($hotel_person->person_nm), ['size' => '50', 'maxlength' => '32',]) }}
        </td>
        <td><small>32文字<font color="#0000ff">（必須）</font></small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">担当者電話番号※</td>
        <td>
            {{ Form::text('Hotel_Person[person_tel]', ($status == 'new' && $service->is_empty(strip_tags($hotel_person->person_tel))) ? strip_tags($hotel->tel) : strip_tags($hotel_person->person_tel), ['size' => '20', 'maxlength' => '15',]) }}
        </td>
        <td><small>xxxx-xxxx-xxxx<font color="#0000ff">（必須）</font></small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">担当者ファックス番号</td>
        <td>
            {{ Form::text('Hotel_Person[person_fax]', ($status == 'new' && $service->is_empty(strip_tags($hotel_person->person_fax))) ? strip_tags($hotel->fax) : strip_tags($hotel_person->person_fax), ['size' => '20', 'maxlength' => '15',]) }}
        </td>
        <td><small>xxxx-xxxx-xxxx</small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">担当者電子メールアドレス</td>
        <td>
            {{ Form::text('Hotel_Person[person_email]', strip_tags($hotel_person->person_email), ['size' => '50', 'maxlength' => '50',]) }}
        </td>
        <td><br /></td>
    </tr>


    <tr>
        <td bgcolor="#EEFFEE">登録状態</td>
        @if ($new_flg)
            <td>
                {{ Form::hidden('Hotel_Status[entry_status]', 1) }}
                登録作業中
            </td>
        @else
            <td>
                {{ Form::radio('Hotel_Status[entry_status]', '0', $hotel_status->entry_status == 0, ['id' => 'k1', 'disabled' => !$rate_chk,]) }}
                {{ Form::label('k1', '公開中') }}

                {{ Form::radio('Hotel_Status[entry_status]', '1', $hotel_status->entry_status == 1, ['id' => 'k2',]) }}
                {{ Form::label('k2', '登録作業中') }}

                {{ Form::radio('Hotel_Status[entry_status]', '2', $hotel_status->entry_status == 2, ['id' => 'k3',]) }}
                {{ Form::label('k3', '解約') }}
            </td>
        @endif
        <td>
            <small>施設(買取以外)の料率情報が存在していない場合、公開中は選択できません。</small>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">契約日</td>
        <td>
            {{ Form::text('Hotel_Status[contract_ymd]', strip_tags($hotel_status->contract_ymd), ['size' => '20', 'maxlength' => '15',]) }}
        </td>
        <td>YYYY/MM/DD <small>又は</small> YYYY-MM-DD</td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">公開日</td>
        <td>
            {{ Form::text('Hotel_Status[open_ymd]', strip_tags($hotel_status->open_ymd), ['size' => '20', 'maxlength' => '15',]) }}
        </td>
        <td>YYYY/MM/DD <small>又は</small> YYYY-MM-DD</td>
    </tr>

    @if (!$service->is_empty($disp))
        <tr>
            <td bgcolor="#EEFFEE">解約日時</td>
            <td>
                {{ strip_tags(date('Y/m/d H:i:s', $hotel_status->close_dtm)) }}<br />
            </td>
            <td><br /></td>
        </tr>
    @endif

    {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
    {{ Form::hidden('target_stock_type', strip_tags($target_stock_type)) }}
</table>
