$(function(){
  var DEFAULT_TEXT = 'ただ今検索しています・・・';
  
  var toList = {
      query   : {text: DEFAULT_TEXT},
      list    : {text: DEFAULT_TEXT},
      keywords: {text: DEFAULT_TEXT},
      booking : {text: 'ただ今処理しています・・・'},
    };
    
  $('a').on('click', function(){
    let href = $(this).attr('href');
    let action = href.replace(/(http|https)\:\/\/.*com\//g, '/');
    let to = action.split('/');
    if(toList[to[1]] && to[2] !== 'map'){
      $('.loading-text').text(toList[to[1]]['text']);
      showLoading();
    }
  });
  
  $('area').on('click', function(){
    let to = $(this).attr('href').split('/');
    if(to[2].slice(0, 1) === 'm'){
      showLoading();
    }
  });
  
  $('form').submit(function(event) {
    if(event.target.id === 'form-keywords'){
        var input = $('#f_query').val();
        if(input.length<2){
            showCaution('キーワードは2文字以上でご入力ください');
            return false;
        }
    }
    
    showLoading();
  });
    
  $('.pop-up .caution-close').on('click', function(){
    $('.pop-up').fadeOut(100);
    $('.pop-up-wrapper').fadeOut(100);
  });
});

var timer = null;
function showLoading(){
    if($('.pop-up-map-wrapper').css('display') !== 'none'){
        hiddenMapLoading();
    }
    
    $('.pop-up-wrapper').fadeIn(300, function(){
        $('.pop-up-wrapper .loading').fadeIn();
      toggleCircle();
    });
}

function hiddenLoading() {
    $('.pop-up-wrapper').fadeOut();
    clearInterval(timer);
    timer=null;
}

function showMapLoading() {
    $('.pop-up-map-wrapper').fadeIn(300, function(){
        $('.pop-up-map-wrapper .loading').fadeIn();
      toggleCircle();
    });
}

function hiddenMapLoading() {
    $('.pop-up-map-wrapper').fadeOut();
    clearInterval(timer);
    timer=null;
}

function toggleCircle(){
  if(timer !== null){
    clearInterval(timer);
    timer = null;
  }

  if($('.circle').hasClass('circle-show')){
    $('.circle').removeClass('circle-show');
    timer = setInterval(function(){
      toggleCircle(); 
    }, 1500);
  }else{
    $('.circle').addClass('circle-show');
    timer = setInterval(function(){
      toggleCircle(); 
    }, 1500);
  }
}

function showCaution(text){
    $('.caution-main .caution-main-text').text(text);
    $('.pop-up-wrapper').fadeIn(300, function(){
      $('.pop-up-wrapper .caution').fadeIn();
    });
}