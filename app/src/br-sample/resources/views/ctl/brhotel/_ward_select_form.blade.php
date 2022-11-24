@inject('service', 'App\Http\Controllers\ctl\BrhotelController')

{{-- 区表示 --}}
@if (isset($mast_wards['values']) && count($mast_wards['values']) != 0)
    <select size="1" name="Hotel[ward_id]">
        <option value="">未選択</option>
        @foreach ($mast_wards['values'] as $mast_ward)
            <option value="{{ strip_tags($mast_ward['ward_id']) }}" @if (isset($hotel) && !$service->is_empty($hotel['ward_id']) && $mast_ward['ward_id'] == $hotel['ward_id']) selected @endif>
                {{ strip_tags($mast_ward['ward_nm']) }}
            </option>
        @endforeach
    </select>
@endif
