{{-- TODO: --}}

{{-- MEMO: 移植元 public\app\rsv\view2\_common\_form_select_place.tpl --}}

<select name="place_p" size="1">
    @foreach ($search_condition['form']['prefs'] as $prefs)
        <option value="{{ $prefs['place'] }}" {{ $prefs['current_status'] ? 'selected' : '' }}>
            {{ $prefs['place_nm'] }}
        </option>
    @endforeach
</select>
@if (is_null($search_condition['form']['cws']))
    {{-- MEMO: ↑↑↑ もとは is_empty --}}
    <br />
    <span>
        <select name="place_ms" style="width:200px;" size="1">
            @foreach ($search_condition['form']['areas'] as $area)
                <option value="{{ $area['place'] }}" {{ $area['current_status'] ? 'selected' : '' }}>
                    {{ $area['place_nm'] }}
                </option>
            @endforeach
        </select>
    </span>
@else
    <span>
        <select name="place_ms" style="width:140px;" size="1">
            @foreach ($search_condition['form']['areas'] as $area)
                <option value="{{ $area['place'] }}" {{ $area['current_status'] ? 'selected' : '' }}>
                    {{ $area['place_nm'] }}
                </option>
            @endforeach
        </select>
    </span>
    <span>
        <select name="place_cw" style="width:140px;" size="1">
            @foreach ($search_condition['form']['cws'] as $cws)
                <option value="{{ $cws['place'] }}" {{ $cws['current_status'] ? 'selected' : '' }}>
                    {{ $cws['place_nm'] }}
                </option>
            @endforeach
        </select>
    </span>
@endif
