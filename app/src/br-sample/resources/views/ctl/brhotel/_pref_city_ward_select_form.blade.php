@inject('service', 'App\Http\Controllers\ctl\BrhotelController')

<tr>
    <td bgcolor="#EEFFEE">都道府県※</td>
    <td nowrap>
        <script language="javascript" type="text/javascript">
            <!--
            $(document).ready(function() {
                $('select[name="Hotel[pref_id]"]').change(function() {
                    var uri_city = "{{ route('ctl.brhotel.searchcity') }}"
                    uri_city += '?pref_id=' + encodeURI($('select[name="Hotel[pref_id]"]').val());
                    $.get(uri_city, function(html) {
                        $('#res_city').html(html);
                    });
                    $("#res_ward").empty(); // 区をクリア
                });
                // 再構成された市フォームにもscriptが必要
                $('select[name="Hotel[city_id]"]').change(function() {
                    var uri_ward = "{{ route('ctl.brhotel.searchward') }}"
                    uri_ward += '?city_id=' + encodeURI($('select[name="Hotel[city_id]"]').val());
                    alert(uri_ward);
                    $.get(uri_ward, function(html) {
                        $('#res_ward').html(html);
                    });
                });
            });
            //-->
        </script>

        {{-- 都道府県表示 --}}
        <select size="1" name="Hotel[pref_id]">
            @foreach ($mast_prefs['values'] as $mast_pref)
                <option value="{{ strip_tags($mast_pref['pref_id']) }}" {{ $mast_pref['pref_id'] == $hotel['pref_id'] ? 'selected' : '' }}>
                    {{ strip_tags($mast_pref['pref_nm']) }}
                </option>
            @endforeach
        </select>

        {{-- 市表示 --}}
        <span id="res_city">
            @include('ctl.brhotel._city_select_form', [
                // "hotel" => $hotel,
                // "mast_cities" => $mast_cities
            ])
        </span>

        {{-- 別ファイル 区表示 --}}
        <span id="res_ward">
            @include('ctl.brhotel._ward_select_form', [
                // "hotel" => $hotel,
                // "mast_wards" => $mast_wards
            ])
        </span>
    </td>
    <td>
        <small>選択</small><br>
        <small>
            <font color="#339933">都道府県、市、区</font>
            <font color="#0000ff">（都道府県、市必須）</font>
        </small>
    </td>
</tr>
