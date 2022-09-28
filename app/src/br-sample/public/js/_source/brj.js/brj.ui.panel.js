new function(){

	BRJ.UI.Panel = {

		open: function(p){

//					// オーバーレイレイヤーを準備
//					if($('div#jqs-uioverray').length == 0){
//						// オーバーレイレイヤーを作成
//						if(typeof document.body.style.maxHeight === "undefined"){
//							return true;
//							// $('body', 'html').css({height: '100%', width: '100%'});
//							// $('html').css('overflow', 'hidden');
//							// $('body').append('<iframe id="jqs-uioverrayif" style="z-index:99;position:absolute;top:0;left:0;background-color:#fff;border:none;filter:alpha(opacity=0);-moz-opacity: 0;opacity: 0;height:100%;width:100%;"></iframe><div id="jqs-uioverray" style="opacity:0.4;dispay:none;background-color:#f00;position:absolute;top:0;left:0;width:100%;height:100%;filter:alpha(opacity=60)"></div>');
//						}else{
//							$('body').append('<div id="jqs-uioverray" class="overray" style="dispay:none;position:fixed;z-index:100;top:0;left:0;width:100%;height:100%"></div>');
//						}
//						// オーバーレイレイヤーにイベントを設定
//						$('div#jqs-uioverray').click(function(){
//							eventClickOverray();
//							return false;
//						});
//					}

					// オーバーレイレイヤーを準備
					createOverray();

					// スタック数をカウント、パネルを保存
					stack += 1;
					panels[stack] = p;

					// パネルの表示位置を設定
					$(p).css('left', $(window).scrollLeft() + ($(window).width()	- $(p).width() ) / 2
						 ).css('top' , $(window).scrollTop()	+ ($(window).height() - $(p).height()) / 2
						 );

					// オーバーレイレイヤーとパネルを表示
					$('iframe#jqs-uioverrayif').show();
					$('div#jqs-uioverray').css('z-index', 100 + stack * 2).fadeIn('fast');
					$(p).css('z-index', 101 + stack * 2).fadeIn('fast');

		},



		close: function(){

					if(stack > -1){
						// スタック数をデクリメント、パネルを非表示にして破棄
						stack -= 1;
						$(panels.pop()).fadeOut('fast');

						// オーバーレイレイヤーを非表示または表示変更
						if(stack < 0){
							$('div#jqs-uioverray').fadeOut('fast');
							$('iframe#jqs-uioverrayif').hide();
						}else{
							$('div#jqs-uioverray').css('z-index', 100 + stack * 2);
						}
					}

		},



		wait: function(){

					if(stack < 0){
						// オーバーレイレイヤーを準備
						createOverray();

						// オーバーレイレイヤーを表示
						$('div#jqs-uioverray').css('z-index', 100).fadeIn('fast');
					}

		},

		toggle: function(p){

			if($(p).css('display') == 'none'){
				self.open($(p));
			}else{
				self.remove();
			}
		}

	}

	// 自身への参照を設定
	var self = BRJ.UI.Panel;

	// パネルの配列、スタック数の変数を宣言
	var panels = Array();
	var stack = -1;

	var eventClickOverray = function(){
					self.close();
	}

	var createOverray = function(){

					if($('div#jqs-uioverray').length == 0){
						// オーバーレイレイヤーを作成
						if(typeof document.body.style.maxHeight === "undefined"){
							return true;
							// $('body', 'html').css({height: '100%', width: '100%'});
							// $('html').css('overflow', 'hidden');
							// $('body').append('<iframe id="jqs-uioverrayif" style="z-index:99;position:absolute;top:0;left:0;background-color:#fff;border:none;filter:alpha(opacity=0);-moz-opacity: 0;opacity: 0;height:100%;width:100%;"></iframe><div id="jqs-uioverray" style="opacity:0.4;dispay:none;background-color:#f00;position:absolute;top:0;left:0;width:100%;height:100%;filter:alpha(opacity=60)"></div>');
						}else{
							$('body').append('<div id="jqs-uioverray" class="overray" style="dispay:none;position:fixed;z-index:100;top:0;left:0;width:100%;height:100%"></div>');
						}
						// オーバーレイレイヤーにイベントを設定
						$('div#jqs-uioverray').click(function(){
							eventClickOverray();
							return false;
						});
					}
	}

}
