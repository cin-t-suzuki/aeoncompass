
(function($) {

    $.fn.convert_zen_to_han = function(str) {
      return str.replace(/[！-～]/g, function(s) {
        return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
      });
    };
  
  
  
    $.fn.isVisible = function() {
      return $.expr.filters.visible(this[0]);
    };
  
  
  
    $.fn.showAndHideExpertMenu = function() {
  
      var cookiePath  = '';
      var cookieParam = $.cookies.get('EXPERT');
      var buffer = location.pathname.match(/\/(branches|tags)\/([^\/]+)/);
  
      if (buffer) {
        cookiePath = buffer[0] + '/ctl/';
      }
  
      if (cookieParam === 'on') {
        $(this).val('エキスパートメニュー非表示');
        $('.jqs-expert-menu').show();
      } else {
        $(this).val('エキスパートメニュー表示');
        $('.jqs-expert-menu').hide();
      }
  
      $(this).click(function() {
  
        cookieParam = $.cookies.get('EXPERT');
  
        if (cookieParam === 'on') {
          $.cookies.del('EXPERT', {path: cookiePath});
          $('.jqs-expert-menu').hide();
          $(this).val('エキスパートメニュー表示');
        } else {
          $.cookies.set('EXPERT', 'on', {path: cookiePath});
          $('.jqs-expert-menu').show();
          $(this).val('エキスパートメニュー非表示');
        }
  
      });
  
      return (this);
  
    };
  
  
  
    $.fn.scrollPageTop = function() {
  
      $(this).click(function() {
        $('html,body').animate({ scrollTop: 0 }, 'fast');
        return false;
      });
  
      return (this);
  
    };
  
  
  
    $.fn.moveRooms = function(aConfig) {
  
      $(this).click(function() {
  
        $(aConfig.selecter_src).find('input:checked').each(function() {
  
          $(aConfig.selecter_dest).append( $('#jqs-room-' + $(this).val()) );
  
          $('#selected-room-' + $(this).val()).remove();
  
          if ( aConfig.is_selected ) {
            $('#jqs-room-' + $(this).val()).append('<input type="hidden" id="selected-room-' 
                                                  + $(this).val()
                                                  + '" name="target_rooms[]" value="'
                                                  + $(this).val() + '" />');
          }
  
          $(this).attr('checked', false);
  
        });
  
      });
  
      return(this);
  
    };
  
  
  
    $.fn.previewRoom = function() {
      
      $(this).find('p').each(function() {
  
        var sRoomId = $(this).attr('id').slice(-10);
  
        $(this).mouseover(function() {
          $('.jqs-room-detail-' + sRoomId).show();
          $(this).css('background-color', '#FFE0B2');
        }).mouseout(function() {
          $('.jqs-room-detail-' + sRoomId).hide();
          $(this).css('background-color', '#FFFEEE');
        });
  
      });
  
      return (this);
  
    };
  
  
  
    $.fn.previewRoomSelected = function(aConfig) {
  
      var oRegPattern = new RegExp(aConfig.tag_name_room_detail + '-[0-9]{10}');
  
      $(this).children('select').each(function() {
  
        var sSelectedRoomId  = $(this).val();
  
        if ( sSelectedRoomId ) {
          $(this).parent(aConfig.tag_type_scope).removeClass('color-empty');
          $(this).parent(aConfig.tag_type_scope).addClass('color-room');
        } else {
          $(this).parent(aConfig.tag_type_scope).removeClass('color-room');
          $(this).parent(aConfig.tag_type_scope).addClass('color-empty');
        }
  
        $(this).parent(aConfig.tag_type_scope).children().each(function() {
  
          var sClassNames = $(this).attr('Class');
  
          if ( sClassNames != null ) {
  
            var sTargetClassName = sClassNames.match(oRegPattern).toString().slice(-10);
  
            if ( sTargetClassName == sSelectedRoomId ) {
              $(this).parent(aConfig.tag_type_scope).children('.' + aConfig.tag_name_room_detail + '-' + sTargetClassName).show();
            } else {
              $(this).parent(aConfig.tag_type_scope).children('.' + aConfig.tag_name_room_detail + '-' + sTargetClassName).hide();
            }
          }
  
        });
  
      });
  
      $(this).find('select').each(function() {
  
        $(this).change(function() {
  
          var sSelectedRoomId  = $(this).val();
  
          if ( sSelectedRoomId ) {
            $(this).parent(aConfig.tag_type_scope).removeClass('color-empty');
            $(this).parent(aConfig.tag_type_scope).addClass('color-room');
          } else {
            $(this).parent(aConfig.tag_type_scope).removeClass('color-room');
            $(this).parent(aConfig.tag_type_scope).addClass('color-empty');
          }
  
          $(this).parent(aConfig.tag_type_scope).children().each(function() {
  
            var sClassNames = $(this).attr('class');
  
            if ( sClassNames != '' ) {
  
              var sTargetClassName = sClassNames.match(oRegPattern).toString().slice(-10);
  
              if ( sTargetClassName == sSelectedRoomId ) {
                $(this).parent(aConfig.tag_type_scope).children('.' + aConfig.tag_name_room_detail + '-' + sTargetClassName).show();
              } else {
                $(this).parent(aConfig.tag_type_scope).children('.' + aConfig.tag_name_room_detail + '-' + sTargetClassName).hide();
              }
            }
  
          });
  
        });
      });
  
      return (this);
  
    };
  
    $.fn.PreviewPlanSelected = function() {
  
      var oRegPattern = new RegExp('jqs-plan-detail' + '-[0-9]{10}');
  
      var sSelectedPlanId  = $(this).val();
  
      if ( sSelectedPlanId ) {
        $(this).parent('div').parent('div').removeClass('info-empty-base-back');
        $(this).parent('div').parent('div').addClass('info-plan-base-back');
        $(this).parent('div').children('.jqs-plan-detail-' + sSelectedPlanId).show();
      } else {
        $(this).parent('div').parent('div').removeClass('info-plan-base-back');
        $(this).parent('div').parent('div').addClass('info-empty-base-back');
      }
  
      $(this).change(function() {
  
        var sSelectedPlanId = $(this).val();
  
        $(this).parent('div').children('div').each(function() {
  
          var sClassNames = $(this).attr('class');
  
          if ( sClassNames != null ) {
  
            var sTargetClassName = sClassNames.match(oRegPattern).toString().slice(-10);
  
            if ( sTargetClassName == sSelectedPlanId ) {
              $(this).show();
              $(this).parent('div').parent('div').removeClass('info-empty-base-back');
              $(this).parent('div').parent('div').addClass('info-plan-base-back');
            } else {
              $(this).hide();
            }
  
          }
  
        });
  
        if ( sSelectedPlanId.length == 0 ) {
          $(this).parent('div').parent('div').removeClass('info-plan-base-back');
          $(this).parent('div').parent('div').addClass('info-empty-base-back');
        }
  
      });
  
      return (this);
  
    };
  
    $.fn.settingChild = function() {
  
      for (ii=1; ii <= 5; ii++) {
        $('input[name^="child' + ii + '_accept"]').change(function() {
  
          var typeChild = $(this).attr('name').substr(5, 1);
  
          if ( $(this).val() == 1 ) {
            $('input[name^="child' + typeChild + '_charge_include"]').removeAttr('disabled');
            $('input[name^="child' + typeChild + '_charge"]').removeAttr('disabled');
            $('input[name^="child' + typeChild + '_charge_unit"]').removeAttr('disabled');
          } else {
            $('input[name^="child' + typeChild + '_charge_include"]').attr('disabled', 'disabled');
            $('input[name^="child' + typeChild + '_charge"]').attr('disabled', 'disabled');
            $('input[name^="child' + typeChild + '_charge_unit"]').attr('disabled', 'disabled');
          }
  
        });
      }
  
      return (this);
  
    };
  
  
  
    $.fn.lineCopyCharge = function(aConfig) {
  
      $(this).click(function() {
        var charge_src   = '';
        var self_class   = $(this).attr('class');
        var selecter_idx = $('.' + self_class).index(this);
  
        $(aConfig.copy_src).eq(selecter_idx).find('input:text').each(function (ii) {
          if ( ii == 0 ) {
            charge_src = $(this).val();
          } else {
            $(this).val(charge_src);
          }
        });
  
      });
  
      return (this);
  
    };
  
  
  
    $.fn.adaptCharge = function(aConfig) {
      $(this).click(function() {
  
        var aTempDayOfWeek = { 'sun':0,
                               'mon':1,
                               'tue':2,
                               'wed':3,
                               'thu':4,
                               'fri':5,
                               'sat':6,
                               'bfo':7,
                               'hol':8
                              };
  
        $(aConfig.copy_src).find('input:text').each(function() {
  
          var nDayOfWeek = aTempDayOfWeek[ $(this).attr('name').substr(13, 3)];
          var nCapacity  = $(this).attr('name').slice(-1);
          var nCharge    = $(this).val();
  
          if (!((nDayOfWeek == 7 || nDayOfWeek == 8) && nCharge == "")) {
            $('.jqs-adapt-' + nDayOfWeek + '-' + nCapacity).find('.input-charge-box').each(function() {
              if ( $(this).attr('type') === 'text' ) {
                $(this).val(nCharge);
              }
            });
          }
        });
  
      });
  
      return (this);
  
    };
  
  
  
    $.fn.rowCopyCharge = function(aConfig) {
      $(this).click(function() {
  
        var sClassName  = $(this).attr('class');
        var nCopySrcIdx = $('.' + sClassName).index(this);
        var aCopyCharge = [];
  
        $(aConfig.copy_src).eq(nCopySrcIdx).find('.input-charge-box').each(function() {
          aCopyCharge.push($(this).val());
        });
  
        $(aConfig.copy_src).eq(nCopySrcIdx + 1).find('.input-charge-box').each(function(ii) {
          $(this).val(aCopyCharge[ii]);
            if ( $(this).attr('type') === 'hidden' ) {
              $(this).attr('name', 'jqs_' + $(this).attr('name'));
            }
        });
  
      });
  
      return (this);
    };
  
  
  
    $.fn.lumpControlCharge = function(aConfig) {
  
      $(this).click(function() {
  
        var nCalculateCharge = $('#jqs-calculate_charge').val();
  
        if ( $('#jqs-calculate_charge').val() == '' ) {
          return (this);
        }
  
        nCalculateCharge = $.fn.convert_zen_to_han(nCalculateCharge);
        nCalculateCharge = nCalculateCharge.split(',').join('');
        nCalculateCharge = nCalculateCharge.split('、').join('');
  
        if ( isNaN(nCalculateCharge) ) {
            return (this);
        }
  
        nCalculateCharge = Number(nCalculateCharge);
  
        var nActionType     = $('#calculate_action').val();
        var nIsCalculateCap = $('input[name^="calculate_capacity"]:checked').val();
        var nCalcCapacity   = 1;
        var nChargeType     = $('input[name^="charge_type"]').val();
  
        $(aConfig.target_selecter).find('.input-charge-box').each(function() {
  
          if ( $(this).attr('type') !== 'text' ) {
            return true; /* continue */
          }
  
          if ( nChargeType == 1 ) {
            nCalcCapacity = 1;
          } else {
            if ( nIsCalculateCap == 1 ) {
              nCalcCapacity = Number($(this).attr('name').slice(-1));
            } else {
              nCalcCapacity = 1;
            }
  
          }
          
          var nDayCharge = $(this).val();
          
          nDayCharge = $.fn.convert_zen_to_han(nDayCharge);
          nDayCharge = nDayCharge.split(',').join('');
          nDayCharge = nDayCharge.split('、').join('');
  
          if ( nDayCharge == '' || nDayCharge == 0 ) {
            return true; /* continue */
          }
  
          switch (nActionType) {
          case '0':
            var nCharge = Number(nDayCharge) - (nCalculateCharge * nCalcCapacity);
  
            if ( nCharge < 0 ) {
              nCharge = 0;
            }
  
            $(this).val(nCharge);
            break;
  
          case '1':
            var nCharge = Number(nDayCharge) + (nCalculateCharge * nCalcCapacity);
  
            $(this).val(nCharge);
            break;
  
          }
  
        });
  
      });
  
      return (this);
  
    };
  
    $.fn.showAndHideRoomPlans = function(aConfig) {
      
      $(this).click(function() {
  
        var sRoomId = $(this).attr('id').slice(-10);
  
        if ( $('.' + aConfig.target_selecter + '-' + sRoomId).isVisible() ) {
  
          $('.' + aConfig.target_selecter + '-' + sRoomId).hide();
  
          $(this).val('+');
  
        } else {
  
          $('.' + aConfig.target_selecter + '-' + sRoomId).show();
  
          $(this).val('-');
  
        }
  
      });
      
      return (this);
    };
  
    $.fn.showHideToolTop = function() {
  
      $(this).mouseover(function(event){
        var sId = $(this).attr('id').slice(7);
  
        $('.jqs-tooltip-' + sId).show();
        $(this).css('background-color', '#E6E6FA');
      });
  
      $(this).mouseout(function(event){
        var sId = $(this).attr('id').slice(7);
  
        $('.jqs-tooltip-' + sId).hide();
        $(this).css('background-color', '');
      });
  
      return (this);
    };
  
  
    // これ使ってる（ hotel_area 系）
    $.fn.loadHotelArea = function(aConfig) {
  
      var aCashData = new Object();
      
      var sPropertySelectedAreaLarge   = '';
      var sPropertySelectedAreaPref    = '';
      var sPropertySelectedAreaMiddle  = '';
      var sPropertySelectedAreaSmall   = '';
      
      var nHotelAreaLargeId  = aConfig.area_large;
      var nHotelAreaPrefId   = aConfig.area_pref;
      var nHotelAreaMiddleId = aConfig.area_middle;
      var nHotelAreaSmallId  = aConfig.area_small;
      
      $.getJSON(aConfig.uri, function(data) {
      
        aCashData = data;
        
        for (var ii in data) {
          
          if ( nHotelAreaLargeId == data[ii].area_id ) {
            sPropertySelectedAreaLarge = 'selected="selected"';
          } else {
            sPropertySelectedAreaLarge = '';
          }
          
          
          if ( nHotelAreaPrefId == data[ii].area_id ) {
            sPropertySelectedAreaPref = 'selected="selected"';
          } else {
            sPropertySelectedAreaPref = '';
          }
          
          if ( nHotelAreaMiddleId == data[ii].area_id ) {
            sPropertySelectedAreaMiddle = 'selected="selected"';
          } else {
            sPropertySelectedAreaMiddle = '';
          }
          
          if ( nHotelAreaSmallId == data[ii].area_id ) {
            sPropertySelectedAreaSmall = 'selected="selected"';
          } else {
            sPropertySelectedAreaSmall = '';
          }
          
          // case を 文字列にするとすべて default: に落ちたので、数値に修正した。
          switch (data[ii].area_type) {
            case 1:
              $('#jqs-area-l-list').append('<option value="' + data[ii].area_id + '" ' + sPropertySelectedAreaLarge + '>' + data[ii].area_nm + '<\/option>');
              break;

            case 2:
              if ( data[ii].parent_area_id == nHotelAreaLargeId ) {
                $('#jqs-area-p-list').append('<option value="' + data[ii].area_id + '" ' + sPropertySelectedAreaPref + '>' + data[ii].area_nm + '<\/option>');
              }
              break;

            case 3:
              if ( data[ii].parent_area_id == nHotelAreaPrefId ) {
                $('#jqs-area-m-list').append('<option value="' + data[ii].area_id + '" ' + sPropertySelectedAreaMiddle + '>' + data[ii].area_nm + '<\/option>');
              }
              break;

            case 4:
              if ( data[ii].parent_area_id == nHotelAreaMiddleId ) {
                $('#jqs-area-s-list').append('<option value="' + data[ii].area_id + '" ' + sPropertySelectedAreaSmall + '>' + data[ii].area_nm + '<\/option>');
              }
              break;
            default:
              // ignored
              break;
          }
        }
        
        if ( $('#jqs-area-s-list').children().length == 1 ) {
          $('#jqs-area-s-list').hide();
        }
        
      });
      
      $('#jqs-area-l-list').change(function () {
      
        $('#jqs-area-s-list').hide();
        
        $('#jqs-area-p-list option').remove();
        
        $('#jqs-area-p-list').append('<option value="">未選択<\/option>');
        
        for (var ii in aCashData) {
          
          if ( aCashData[ii].parent_area_id == $('#jqs-area-l-list').children(':selected').val() ) {
            $('#jqs-area-p-list').append('<option value="' + aCashData[ii].area_id + '">' + aCashData[ii].area_nm + '<\/option>');
          }
        }
        
      });
      
      $('#jqs-area-p-list').change(function () {
      
        $('#jqs-area-s-list').hide();
        
        $('#jqs-area-m-list option').remove();
        
        $('#jqs-area-m-list').append('<option value="">未選択<\/option>');
        
        for (var ii in aCashData) {
          
          if ( aCashData[ii].parent_area_id == $('#jqs-area-p-list').children(':selected').val() ) {
            $('#jqs-area-m-list').append('<option value="' + aCashData[ii].area_id + '">' + aCashData[ii].area_nm + '<\/option>');
          }
        }
        
      });
      
      $('#jqs-area-m-list').change(function () {
      
        $('#jqs-area-s-list').hide();
        
        $('#jqs-area-s-list option').remove();
        
        $('#jqs-area-s-list').append('<option value="">未選択<\/option>');
        
        for (var ii in aCashData) {
          
          if ( aCashData[ii].parent_area_id == $('#jqs-area-m-list').children(':selected').val() ) {
            $('#jqs-area-s-list').append('<option value="' + aCashData[ii].area_id + '">' + aCashData[ii].area_nm + '<\/option>');
          }
        }
        
        if ( $('#jqs-area-s-list').children().length > 1 ) {
          $('#jqs-area-s-list').show();
        }
        
      });
  
      return (this);
  
    };
  
  
    $.fn.setModalWindowFull = function() {
  
      var nTimeoutId     = null;
      var nClassIdx      = null;
      var sLoadingMsg    = '読み込み中・・・';
      var nLoadingMsgIdx = 0;
      var bIsTimerOn     = false;
  
      $('body').append('<div id="modal-layer-back-full"><div id="modal-loading"><div id="jqs-loading-msg"></div><\/div><div id="modal-layer-over-full"><\/div><\/div>');
  
      $('.modal').click(function() {
  
        nClassIdx  = $('.modal').index(this);
        
  
        $('#modal-layer-back-full').show();
        $('#modal-loading').show();
  
        nLoadingMsgIdx = 0;
  
        bIsTimerOn = true;
  
        (function updateLoadingMsg() {
  
          if ( bIsTimerOn ) {
  
            if ( nTimeoutId != null ) {
              clearTimeout(nTimeoutId);
            }
  
            $('#modal-loading').text(sLoadingMsg.substr(0, 5 + nLoadingMsgIdx));
  
            if (nLoadingMsgIdx < 3) {
              nLoadingMsgIdx++;
            } else {
              nLoadingMsgIdx = 0;
            }
  
            nTimeoutId = setTimeout(updateLoadingMsg, 1000);
  
          }
  
        })();
  
  
        $.ajax({
          cache: false,
          type: 'get',
          url: window.location.protocol + '//' + window.location.host + $('.jqs-modal-uri').eq(nClassIdx).text(),
          success: function(data) {
            bIsTimerOn = false;
            $('#modal-loading').hide();
            $('#modal-layer-over-full').show().append(data);
          },
          error: function() {
            $('#modal-loading').hide();
            $('#modal-layer-back-full').hide();
            return false;
          }
        });
  
        if($.browser.msie && $.browser.version < 7) {
  
          $(window).scroll(function() {
            $("#modal-layer-back-full").get(0).style.setExpression("top", "$(document).scrollTop() + 'px'");
          });
  
        }
  
        return false;
  
      });
  
      $('.jqs-close-modal').live('click', function() {
        $('#modal-layer-back-full').hide();
        $('#modal-layer-over-full').hide();
        $('#modal-layer-over-full').children().remove();
  
        return false;
      });
  
    };
  
  })(jQuery);