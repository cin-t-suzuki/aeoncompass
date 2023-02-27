<!-- {* 部屋のelements *} -->
@foreach($plan_elements as $plan_element)
  <tr>
    <td nowrap  bgcolor="#EEEEFF" >{{ $plan_element->element_nm }}</td>
    <td>
      <table border="0" cellpadding="2" cellspacing="0">
        <tr>
        @foreach($plan_element->element_value as $element)
        <td>
          <input
           type="radio" 
           id="bath_{{$element->element_value_id}}_{{$plan_element->element_id}}"
           value="{{$element->element_value_id}}"
           name="element_value_id[{{$plan_element->element_id}}]" 
           @if($element->element_value_id == 0) checked @endif
          >
        </td>
        <td>
          <label for="bath_{{$element->element_value_id}}_{{$plan_element->element_id}}">
            {{ $element->element_value_text }}
          </label>
        </td>
        @endforeach
        </tr>
      </table>
    </td>
    <td><small>選択</small></td>
  </tr>
@endforeach
<!-- {* 部屋のelements *} -->