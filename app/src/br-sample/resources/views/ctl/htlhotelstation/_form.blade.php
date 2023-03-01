<table border="1" cellspacing="0" cellpadding="4">
  <tr>
    <td colspan = "2" bgcolor="#EEEEFF" align="center" style="min-width: 300px;">
      交通アクセス
    </td>
  </tr>
  <tr>
    <td>
      交通アクセス
    </td>
    <td>
        @if(isset($a_mast_route->route_id) && $a_mast_route->route_id == 'B2001')
      <table>
        <tr>
          <td>路線：航路</td>
        </tr>
      </table>
      @else
      <table>
        <tr>
          <td>
            路線：@if(isset($a_mast_station->station_id) && $a_mast_station->station_id != "" && $a_mast_route->route_id != ""){{strip_tags($a_mast_route->route_nm)}} @endif
          </td>
        </tr>
        <tr>
          <td>
            駅：@if(isset($a_mast_station->station_id) && $a_mast_station->station_id != "" && $a_mast_route->route_id != ""){{strip_tags($a_mast_station->station_nm)}}@endif
          </td>
        </tr>
      </table>
      @endif
    </td>
  </tr>
  <tr>
    <td>
      時間
    </td>
    <td>
      <table cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <input type="radio" name="HotelStation[traffic_way]" value="0" {{ old('HotelStation.traffic_way',$a_hotel_station['traffic_way']) == 0 ? 'checked' : '' }} id="hotelstation_traffic_way0"><label for="hotelstation_traffic_way0">徒歩</label>
            <input type="radio" name="HotelStation[traffic_way]" value="1" {{ old('HotelStation.traffic_way',$a_hotel_station['traffic_way']) == 1 ? 'checked' : '' }} id="hotelstation_traffic_way1"><label for="hotelstation_traffic_way1">車</label>
          </td>
        </tr>
        <tr>
          <td>
            <input type="text" name="HotelStation[minute]" size="5" value="{{old('HotelStation.minute', strip_tags($a_hotel_station['minute']))}}"> 分
          </td>
        </tr>
      </table>
    </td>
  </tr>