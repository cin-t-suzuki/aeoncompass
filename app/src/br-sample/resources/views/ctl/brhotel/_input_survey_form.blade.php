{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\brhotel\_input_survey_form.tpl --}}

<script type="text/javascript" src="{$v->env.path_base_module}/js/jquery.js"></script>
<script type="text/javascript">
    // ↓ MEMO: js を理解できないブラウザに対する配慮（？）
    <!--
    $(document).ready(function() {
        $('#input-auto').click(function() {

            function decimal_to_sexagesimal(af_value_decimal) {
                var a_values = [];

                a_values[0] = Math.floor(af_value_decimal);
                a_values[1] = Math.floor((af_value_decimal - a_values[0]) * 60);
                a_values[2] = (((af_value_decimal - a_values[0]) * 60) - a_values[1]) * 60;

                return a_values.join('.').substring(0, 16);
            }

            var f_wgs_lat_d = $('input[name^="Hotel_Survey[wgs_lat_d]"]').val();
            var f_wgs_lng_d = $('input[name^="Hotel_Survey[wgs_lng_d]"]').val();

            f_wgs_lat_d = f_wgs_lat_d.replace(/\s+/g, '');
            f_wgs_lng_d = f_wgs_lng_d.replace(/\s+/g, '');

            if (String(f_wgs_lat_d).length == 0 || String(f_wgs_lng_d).length == 0) {
                return true;
            }

            if (isNaN(f_wgs_lat_d) || isNaN(f_wgs_lng_d)) {
                return true;
            }

            $('#jqs-wgs-lat').val(decimal_to_sexagesimal(f_wgs_lat_d));

            $('#jqs-wgs-lng').val(decimal_to_sexagesimal(f_wgs_lng_d));

            f_td_lat_d = (f_wgs_lat_d * 1.000106961) - (f_wgs_lng_d * 0.000017467) - 0.004602017;
            s_td_lat_d = String(f_td_lat_d);
            $('#jqs-td-lat-d').val(s_td_lat_d.substring(0, 16));

            f_td_lng_d = (f_wgs_lng_d * 1.000083049) + (f_wgs_lat_d * 0.000046047) - 0.010041046;
            s_td_lng_d = String(f_td_lng_d);
            $('#jqs-td-lng-d').val(s_td_lng_d.substring(0, 16));

            $('#jqs-td-lat').val(decimal_to_sexagesimal(s_td_lat_d));

            $('#jqs-td-lng').val(decimal_to_sexagesimal(s_td_lng_d));
        });
    });
    -->
    // ↑ MEMO: 同上
</script>

<table border="1" cellspacing="0" cellpadding="3">
    <tr>
        <td bgcolor="#EEFFEE">世界測地系-度-緯度</td>
        <td>
            {{ Form::text('Hotel_Survey[wgs_lat_d]', strip_tags($hotel_survey->wgs_lat_d), ['size' => '20', 'maxlength' => '16',]) }}
        </td>
        <td><small>16桁</small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">世界測地系-度-経度</td>
        <td>
            {{ Form::text('Hotel_Survey[wgs_lng_d]', strip_tags($hotel_survey->wgs_lng_d), ['size' => '20', 'maxlength' => '16',]) }}
        </td>
        <td><small>16桁</small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">世界測地系-度分秒-緯度</td>
        <td>
            {{ Form::text('Hotel_Survey[wgs_lat]', strip_tags($hotel_survey->wgs_lat), ['size' => '20', 'maxlength' => '16', 'id' => 'jqs-wgs-lat',]) }}
        </td>
        <td><small>16桁</small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">世界測地系-度分秒-経度</td>
        <td>
            {{ Form::text('Hotel_Survey[wgs_lng]', strip_tags($hotel_survey->wgs_lng), ['size' => '20', 'maxlength' => '16', 'id' => 'jqs-wgs-lng',]) }}
        </td>
        <td><small>16桁</small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">東京測地系-度-緯度</td>
        <td>
            {{ Form::text('Hotel_Survey[td_lat_d]', strip_tags($hotel_survey->td_lat_d), ['size' => '20', 'maxlength' => '16', 'id' => 'jqs-td-lat-d',]) }}
        </td>
        <td><small>16桁</small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">東京測地系-度-経度</td>
        <td>
            {{ Form::text('Hotel_Survey[td_lng_d]', strip_tags($hotel_survey->td_lng_d), ['size' => '20', 'maxlength' => '16', 'id' => 'jqs-td-lng-d',]) }}
        </td>
        <td><small>16桁</small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">東京測地系-度分秒-緯度</td>
        <td>
            {{ Form::text('Hotel_Survey[td_lat]', strip_tags($hotel_survey->td_lat), ['size' => '20', 'maxlength' => '16', 'id' => 'jqs-td-lat',]) }}
        </td>
        <td><small>16桁</small></td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">東京測地系-度分秒-経度</td>
        <td>
            {{ Form::text('Hotel_Survey[td_lng]', strip_tags($hotel_survey->td_lng), ['size' => '20', 'maxlength' => '16', 'id' => 'jqs-td-lng',]) }}
        </td>
        <td><small>16桁</small></td>
    </tr>

    {{ Form::hidden('target_cd', $target_cd) }}
</table>

<p>
    <input id="input-auto" type="button" value="自動入力" />&nbsp;※世界測地系-度-緯度・経度から残りの項目を自動計算します。
</p>
