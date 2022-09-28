BRJ.Gmap = {


  is_marker:  false,    // マーカーが作成されたことを示す
  is_draging: false,    // マウスドラッグ中であることを示す
  markersArray: [],  // マーカーオブジェクト退避用

  // クエリストリングを取得します。
  get_querystring: function (){
    var request = {};

    // クエリストリングより必要なパラメータを抽出
    if (location.search.length > 1) {
      var list = location.search.substr(1).split("&");
      for (i in list) {

        key  = list[i].split("=")[0];
        value = list[i].split("=")[1];

        if (key == 'adult[0]') {
          $key = 'senior';
        }


        if (key == 'year_month'              ||
            key == 'day'                     ||
            key == 'date_status'             ||
            key == 'stay'                    ||
            key == 'rooms'                   ||
            key == 'senior'                  ||
            key == 'child1'                  ||
            key == 'child2'                  ||
            key == 'child3'                  ||
            key == 'child4'                  ||
            key == 'child5'                  ||
            key == 'charge_min'              ||
            key == 'charge_max'              ||
            key == 'hotel_category_business' ||
            key == 'hotel_category_inn'      ||
            key == 'hotel_category_capsule'  ||
            key == 'hotel_cd'                ||
            key == 'clip_hotel'              ||
            key == 'plan_id'                 ||
            key == 'room_id'                 ||
            key == 'hotels_title'            ||
            key == 'landing_url'             ||
            key == 'keywords'                ||
            key == 'ocp'                     ||
            key == 'ort'                     ||
            key == 'oml'                     ||
            key == 'osm'                     ||
            key == 'opw'                     ||
            key == 'opt'                     ||
            key == 'onw'                     ||
            key == 'ocd'                     ||
            key == 'ohs'                     ||
            key == 'opc'                     ||
            key == 'ost'                     ||
            key == 'icp'                     ||
            key == 'irt'                     ||
            key == 'iml'                     ||
            key == 'ism'                     ||
            key == 'ipw'                     ||
            key == 'ipt'                     ||
            key == 'inw'                     ||
            key == 'icd'                     ||
            key == 'ihs'                     ||
            key == 'ipc'                     ||
            key == 'ist'                     ||
            key == 'sort'                    ||
            key == 'page'
        ) {

          request[key] = decodeURIComponent(value);
        }
      }
    }

    // 検索フォームの値を取得
    $('select, input', $('form[action^="/query/map/"]').first()).each (function () {
      if ($(this).attr('name') == 'year_month'              ||
          $(this).attr('name') == 'day'                     ||
          $(this).attr('name') == 'date_status'             ||
          $(this).attr('name') == 'stay'                    ||
          $(this).attr('name') == 'rooms'                   ||
          $(this).attr('name') == 'senior'                  ||
          $(this).attr('name') == 'child1'                  ||
          $(this).attr('name') == 'child2'                  ||
          $(this).attr('name') == 'child3'                  ||
          $(this).attr('name') == 'child4'                  ||
          $(this).attr('name') == 'child5'                  ||
          $(this).attr('name') == 'charge_min'              ||
          $(this).attr('name') == 'charge_max'              ||
          $(this).attr('name') == 'hotel_category_business' ||
          $(this).attr('name') == 'hotel_category_inn'      ||
          $(this).attr('name') == 'hotel_category_capsule'
      ) {
        if (typeof $(this).attr('checked') === 'undefined' || $(this).attr('type') == 'hidden') {
          request[$(this).attr('name')] = $(this).val();
        } else if ($(this).attr('checked') == true) {
          request[$(this).attr('name')] = $(this).val();
        } else {
          request[$(this).attr('name')] = '';
        }
      }
    });

    // クエリストリングを返却
    return jQuery.param(request);
  },

  // マーカーの情報を取得
  //
  // marker      マーカーオブジェクト
  marker_information: function(marker) {

    var icon = marker.getIcon();

    var latlng = marker.getPosition();

    // 施設コードを抽出
    for (var hotel_cd in BRJ.Gmap.markersArray){
      if (BRJ.Gmap.markersArray[hotel_cd].getTitle() == marker.getTitle()){
        break;
      }
    }

    // フィルタなどのクエリストリングを取得
    query = BRJ.Gmap.get_querystring();

    // 施設情報の取得
    $.get(BRJ.Env.pathBaseModule + '/query/?view=marker&hotel_cd=' + hotel_cd + '&' + query , function(html){

      // マーカークリック時の情報を表示
      var infowindow = new google.maps.InfoWindow(
        { content:  html
      });

      infowindow.open(map,marker);

    });
  },

  // マーカー作成
  //
  // map      マップオブジェクト
  // myLatlng マーカー表示位置
  // title    マーカータイトル
  createmarker: function(map, myLatlng, hotel_cd, title, type){

    if (BRJ.Gmap.markersArray) {
      for (i in BRJ.Gmap.markersArray) {
        if (hotel_cd == i){
          return;
        }
      }
    }


    if (type == 'has_plan') {
      var image = '/img/gmap/sale.png';

    } else {
      var image = '/img/gmap/soldout.png';
    }

    image.size = new google.maps.Size(20, 34);
    image.origin = new google.maps.Point(0, 34);
    image.anchor = new google.maps.Point(10, 34);

    // マーカー作成
    var marker = new google.maps.Marker({
      position: myLatlng,
      map:      map,
      title:    title,
      icon:     image
    });



   // マーカーを保持
    BRJ.Gmap.markersArray[hotel_cd] = marker;

    // クリックイベント
    google.maps.event.addListener(marker, 'click', function() {
      // マーカーの情報を取得
      BRJ.Gmap.marker_information(marker);
    });

  },

  // マーカーをマッピング
  //
  // map      マップオブジェクト
  // absolute 施設が特定されている場合:true 施設コードが特定されていない場合:false
  mapping_marker: function(map, absolute) {

    latlng = map.getCenter();
    latlngbound = map.getBounds();

    if (latlngbound == undefined ){
      return;
    }

    // 絶対位置の場合は最初のみマーカーを設定
    if (absolute && BRJ.Gmap.markersArray.length != 0){
      return;

    // 絶対位置でない場合は読み込み 且つ ズームインしすぎた場合は再取得しない
    } else if (!absolute && 17 <= map.getZoom()) {
      return;
    }

    ne = latlngbound.getNorthEast();
    sw = latlngbound.getSouthWest();

    // フィルタなどのクエリストリングを取得
    query = BRJ.Gmap.get_querystring();

    // 近隣の施設マッピング
    $.getJSON(BRJ.Env.pathBaseModule + '/query/?view=markup&lng=' + latlng.lng() + '&lat=' + latlng.lat() + '&ne_lng=' + ne.lng() + '&ne_lat=' + ne.lat() + '&sw_lng=' + sw.lng() + '&sw_lat=' + sw.lat() + '&' + query, function(json){

      // Filterの表示
      $('.jqs-filter').html(json.filters[0].html.replace(/&amp;/g, '&').replace(/&quot;/g, '"').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/%27/g, "'").replace(/%2A/g, '*'));

      for(var cnt = 0; cnt < json.hotels.length; cnt++){
        var myLatlng = new google.maps.LatLng(json.hotels[cnt].lat, json.hotels[cnt].lng);

        if (json.hotels[cnt].has_plan) {
          type = 'has_plan';
        } else {
          type = 'no_plan';
        }
        BRJ.Gmap.createmarker(map, myLatlng, json.hotels[cnt].hotel_cd, json.hotels[cnt].hotel_nm, type);
      }

    });
  },

  // マーカーをマッピング（クリップ、最近見た施設など）
  //
  // map      マップオブジェクト
  mapping_marker_hotel: function(map) {

    latlng = map.getCenter();
    latlngbound = map.getBounds();

    if (latlngbound == undefined ){
      return;
    }

    ne = latlngbound.getNorthEast();
    sw = latlngbound.getSouthWest();

    // フィルタなどのクエリストリングを取得
    query = BRJ.Gmap.get_querystring();

    // 近隣の施設マッピング
    $.getJSON(BRJ.Env.pathBaseModule + '/query/?view=markup&' + query, function(json){

      // Filterの表示
      $('.jqs-filter').html(json.filters[0].html.replace(/&amp;/g, '&').replace(/&quot;/g, '"').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/%27/g, "'").replace(/%2A/g, '*'));

      for(var cnt = 0; cnt < json.hotels.length; cnt++){
        var myLatlng = new google.maps.LatLng(json.hotels[cnt].lat, json.hotels[cnt].lng);

        if (json.hotels[cnt].has_plan) {
          type = 'has_plan';
        } else {
          type = 'no_plan';
        }
        BRJ.Gmap.createmarker(map, myLatlng, json.hotels[cnt].hotel_cd, json.hotels[cnt].hotel_nm, type);
      }

    });
  },

  // 位置情報の保持
  //
  // map      マップオブジェクト
  changeplace :function (map) {

    latlng = map.getCenter();
    latlngbound = map.getBounds();

    if (latlngbound == undefined ){
      return;
    }
    ne = latlngbound.getNorthEast();
    sw = latlngbound.getSouthWest();

    if (document.getElementById("place")!=null){
      document.getElementById("place").innerHTML = '緯度：'+latlng.lat()+'  経度：'+latlng.lng();

      $(':hidden[name="lat"]').val(latlng.lat());
      $(':hidden[name="lng"]').val(latlng.lng());
    }

    // 緯度経度をクッキーに保存
    $.cookies.set('zoomlevel', escape(map.getZoom()));
    $.cookies.set('center', escape(latlng.lat() + ',' + latlng.lng()));
    $.cookies.set('sw',     escape(sw.lat() + ',' + sw.lng()));
    $.cookies.set('ne',     escape(ne.lat() + ',' + ne.lng()));
    $.cookies.set('map',    escape('opened'));
  },


  // 中心点指定
  //
  // lat      緯度
  // lng      経度
  center: function(lat, lng, zoomlevel, absolute) {

    var LatLng = new google.maps.LatLng(lat, lng);

    var myOptions = {
      zoom: zoomlevel,
      center: LatLng,
      navigationControl: true,
      navigationControlOptions: {
        style: google.maps.NavigationControlStyle.SMALL
      } ,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById("map_canvas"),  myOptions);

    var stylez = [{
      featureType: 'all',
      elementType: 'all',
      stylers : [{'visibility': 'on'}]
    }];

    var styledMapOptions = {map: map, name: "br"}
    var brMapType =  new google.maps.StyledMapType(stylez, styledMapOptions);

    map.mapTypes.set('br', brMapType);
    map.setMapTypeId('br');

    google.maps.event.addListener(map, 'dragstart', function () {
      BRJ.Gmap.is_draging = true;
      BRJ.Gmap.is_marker  = false;
    });

    google.maps.event.addListener(map, 'dragend', function () {
      BRJ.Gmap.is_draging = false;

      // ドラッグ中にタイルがロードされた場合
      if (BRJ.Gmap.is_marker) {
        // 位置情報をクッキーに保存
        BRJ.Gmap.changeplace(map);

        // マーカーを設定
        BRJ.Gmap.mapping_marker(map, absolute);
      }
    });


    // タイルがロードされた時（初期表示、ドラッグ、ズームチェンジなど）
    google.maps.event.addListener(map, 'tilesloaded', function() {

      BRJ.Gmap.is_marker = true;

      // ドラッグ中は処理しない
      if (BRJ.Gmap.is_draging) {
        return;
      }

      // 位置情報をクッキーに保存
      BRJ.Gmap.changeplace(map);

      // マーカーを設定
      BRJ.Gmap.mapping_marker(map, absolute);

    });

    // ズームが変わったとき
    google.maps.event.addListener(map, 'zoom_changed', function() {

      // ズームレベルを固定
      if (map.getZoom() <= 11){
        map.setZoom(12);
        return;
      }

    });
  },

  // 範囲指定（施設固定）
  //
  // min_lat      最小緯度
  // max_lat      最大緯度
  // min_lng      最小経度
  // max_lng      最大経度
  bound: function(min_lat, max_lat, min_lng, max_lng) {


    var myOptions = {
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      navigationControlOptions: {
        style: google.maps.NavigationControlStyle.SMALL
      }
    };

    map = new google.maps.Map(document.getElementById("map_canvas"),  myOptions);

    var stylez = [{
      featureType: 'all',
      elementType: 'all',
      stylers : [{'visibility': 'on'}]
    }];

    var styledMapOptions = {map: map, name: "br"}
    var brMapType =  new google.maps.StyledMapType(stylez, styledMapOptions);

    map.mapTypes.set('br', brMapType);
    map.setMapTypeId('br');

    var min = new google.maps.LatLng(min_lat, min_lng);
    var max = new google.maps.LatLng(max_lat, max_lng);

    var latlng = new google.maps.LatLngBounds(min, max);
    map.fitBounds(latlng);

    // タイルがロードされた時（ドラッグ、ズームチェンジなど）
    google.maps.event.addListener(map, 'tilesloaded', function() {

      if (BRJ.Gmap.markersArray.length == 0){

        // ズームインしすぎた場合は再取得しない
        if (map.getZoom() <= 17){

          // マーカーを設定
          BRJ.Gmap.mapping_marker_hotel(map);

        } else {
          map.setZoom(17);
        }
      }

    });

  },

  // サムネイル地図を表示
  //
  // lat      緯度
  // lng      経度
  thumbnail: function(lat, lng, zoomlevel) {

    var LatLng = new google.maps.LatLng(lat, lng);

    var myOptions = {
      zoom: zoomlevel,
      center: LatLng,
      disableDefaultUI: false,
      disableDoubleClickZoom: false,
      draggable:false,
      keyboardShortcuts:false,
      mapTypeControl:false,
      navigationControl:false,
      scaleControl:false,
      scrollwheel:false,
      streetViewControl:false,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById("map_canvas"),  myOptions);
    $("#map_canvas").show();

  }

}