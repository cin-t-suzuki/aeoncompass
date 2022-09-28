BRJ.Env.setModule('rsv');
BRJ.Rsv = {};
BRJ.Reserve = {};
BRJ.Reserve.ClickRecord = {

	getCookies: function() {
		return {
			m: $.cookies.get( 'cccm' ) || '',
			v: $.cookies.get( 'cccv' ) || '',
			r: $.cookies.get( 'cccr' ) || '',
			f: $.cookies.get( 'cccf' ) || ''
		};
	},

	getContents: function() {
		var b = BRJ.Reserve.ClickRecord.before;
		var a = BRJ.Reserve.ClickRecord.after;
		return '<dl>'
				 + '<dt>Before</dt><dd><dl>'
				 + '<dt>cccv</dt><dd>' + b.v + '</dd>'
				 + '<dt>cccm</dt><dd>' + b.m + '</dd>'
				 + '<dt>cccr</dt><dd>' + b.r + '</dd>'
				 + '<dt>cccf</dt><dd>' + b.f + '</dd>'
				 + '</dl></dd>'
				 + '<dt>After</dt><dd><dl>'
				 + '<dt>cccv</dt><dd>' + a.v + '</dd>'
				 + '<dt>cccm</dt><dd>' + a.m + '</dd>'
				 + '<dt>cccr</dt><dd>' + a.r + '</dd>'
				 + '<dt>cccf</dt><dd>' + a.f + '</dd>'
				 + '</dl></dd>'
				 + '</dl>';
	},

	getCookieString: function( f, p, t ) {
		var dt = new Date();
		var re = new RegExp( '^[0-9]$' );
		p = p || ''; if ( !p.match( ':' ) ) p += ':';
		return dt.getFullYear().toString()
				 + ( dt.getMonth() + 1 ).toString().replace( re, '0$&' )
				 + dt.getDate().toString().replace( re, '0$&' )
				 + dt.getHours().toString().replace( re, '0$&' )
				 + dt.getMinutes().toString().replace( re, '0$&' )
				 + dt.getSeconds().toString().replace( re, '0$&' )
				 + ':' + f + ':' + p + ':' + t;
	},

	matchSite: function( u ) {
		var re = new RegExp( '^http[s]?://' + document.location.host + '(/.*)' );
		var ma = re.exec( u );
		if ( ma ) {
			return ma[1];
		} else {
			return null;
		};
	},

	setFinal: function() {
		if ( ( BRJ.Reserve.ClickRecord.before.m || '' ) != '' ) {
			$.cookies.set( 'cccf', BRJ.Reserve.ClickRecord.before.m + document.location.pathname );
			if ( ( BRJ.Reserve.ClickRecord.before.r || '' ) != '' ) {
				$.cookies.set( 'cccr', BRJ.Reserve.ClickRecord.before.r );
			} else {
				$.cookies.del( 'cccr' );
			}
			BRJ.Reserve.ClickRecord.after = BRJ.Reserve.ClickRecord.getCookies();
		}
	},

	setMemory: function() {
		var f = document.location.pathname;
		var p = $( this ).attr( 'id' ); if ( !p ) p = '';
		$.cookies.set( 'cccm', BRJ.Reserve.ClickRecord.getCookieString( f, p, '' ) );
	},

	start: function() {
		BRJ.Reserve.ClickRecord.before = BRJ.Reserve.ClickRecord.getCookies();
		if ( BRJ.Reserve.ClickRecord.matchSite( document.referrer ) ) {
			if ( ( $.cookies.get( 'cccm' ) || '' ) != '' ) {
				$.cookies.set( 'cccr', $.cookies.get( 'cccm' ) + document.location.pathname );
				$.cookies.del( 'cccm' );
				$.cookies.del( 'cccf' );
			}
		} else {
			$.cookies.set( 'cccv', BRJ.Reserve.ClickRecord.getCookieString( '', '', document.location.pathname ) );
			$.cookies.del( 'cccm' );
			$.cookies.del( 'cccr' );
			$.cookies.del( 'cccf' );
		}
		BRJ.Reserve.ClickRecord.after = BRJ.Reserve.ClickRecord.getCookies();
	}

};

BRJ.Reserve.ClickRecord.start();
$( document ).ready( function () {
	$( '.ccc' ).bind( 'mousedown', BRJ.Reserve.ClickRecord.setMemory );
	$( '.ccc' ).bind( 'click', BRJ.Reserve.ClickRecord.setMemory );
});



// Count Guests
BRJ.Reserve.GuestsCounter = function() {
	$('select[name^="child"]').change( function() {
		var t = 0;
		var c = $('select[name^="child"]', this.form);
		for(var i = 0; i < c.length; i++){
			t += c[i].value * 1;
		}
		$('[name=children]', this.form).text(t);
	});
	$('select[name^="child"]').change();
}

BRJ.Reserve.BookingGender = {

	initialize: function() {
		$('select.jqs-capacity[name^="male"], select.jqs-capacity[name^="female"]').change(BRJ.Reserve.BookingGender.compute);
	},

	compute: function() {
		nResult = this.options.length - 1 - this.options.selectedIndex;
		if (this.name.substr(0, 2) == 'fe') {
			var sName = this.name.substr(2);
		} else {
			var sName = 'fe' + this.name;
		}
		$('select.jqs-capacity[name="' + sName + '"]').each(function() {
			this.options.selectedIndex = nResult;
		});
	}

}

	// Panel
BRJ.Reserve.setPanel=  function (o) {
		$('.panelsw', o).click( function() {
			$('div[name="panel-' + this.name + '"]').toggle();
//			BRJ.UI.panel.toggle($('div[name="panel-' + this.name + '"]'));
			return false;
		});
}

BRJ.Reserve.setBannerToSP = function() {
	if (BRJ.Env.device == 'sp') {
		$('.jqs-banner-tosp').html('<div style="padding:10px 5px;"><a href="' + BRJ.Env.pathBase + '/device/sp/" title="スマートフォン版サイトはこちら"><img src="' + BRJ.Env.pathBase + '/img/tpc/tpc-bnr-tosp.gif" alt="スマートフォン版サイトはこちら" width="950" height="146" /></a></div>');
	}
}



	// Clip
	$(document).ready( function() {

		// HasClip
		if ($("*").hasClass('jqs-clip-has')) {

			if ($('.jqs-clip').attr('href').slice(-10).match(/[0-9]{10}/)) {
				var hcd = $('.jqs-clip').attr('href').slice(-10);
			} else if ($('.jqs-clip').attr('href').slice(-11).slice(0, 10).match(/[0-9]{10}/)) {
				var hcd = $('.jqs-clip').attr('href').slice(-11).slice(0, 10);
			}

			$.getJSON(BRJ.Env.pathBaseModule + '/memberhotels/getclip/' + '&x=' + new Date().getTime(), function(json){
				for (var hotel_cd in json.CLIPH.hotels){
					if (hotel_cd == hcd){
						$('.btn-b06-clip2-s').show();
						$('.btn-b06-098-s').hide();
						break;
					}
				}
			});
		}


		// Query
		if ($("*").hasClass('jqs-clip-query')){
			$.getJSON(BRJ.Env.pathBaseModule + '/memberhotels/getclip/' + '&x=' + new Date().getTime(), function(json){

				for (var buf in json.CLIPH.hotels){
					$('img', '.jqs-clip-query').attr('src', '/img/btn/b05-search2.gif');
					break;
				}
			});
		}

		$('.jqs-clip-query').bind("click", function() {
			t = $(this).children()[0];
			if(!testDriveButtonImage(t.src)){
				return false;
			}
		});


		// Switch OnOff
		$('.jqs-clip').bind("click", function() {

			if ($(this).attr('href').slice(-10).match(/[0-9]{10}/)) {
				var hcd = $(this).attr('href').slice(-10);
			} else if ($(this).attr('href').slice(-11).slice(0, 10).match(/[0-9]{10}/)) {
				var hcd = $(this).attr('href').slice(-11).slice(0, 10);
			} else {
				return false;
			}

			if ($(this).hasClass('jqs-on')) {
				$.getJSON(BRJ.Env.pathBaseModule + '/memberhotels/destroy/clip/off/hotel_cd/'+hcd+'/', function(json){
					if (json.status == "success") {
						$("img", '.jqs-clip-' + hcd).attr('src', '/img/btn/b06-clip1.gif');
						$('.jqs-clip-' + hcd).removeClass('jqs-on');
						$('.jqs-clip-' + hcd).parent('div').removeClass('btn-b06-clip2-s');
						$('.jqs-clip-' + hcd).parent('div').addClass('btn-b06-098-s');

						// Query
						if ($("*").hasClass('jqs-clip-query')){
							$.getJSON(BRJ.Env.pathBaseModule + '/memberhotels/getclip/' + '&x=' + new Date().getTime(), function(json){

								var clip = false;
								for (var buf in json.CLIPH.hotels){
									clip = true;
									break;
								}
								if (!clip) {
									$('img', '.jqs-clip-query').attr('src', '/img/btn/b05-search2_disable.gif');
								}
							});
						}

					} else {
						alert(json.message);
					}
				});

			} else {
				$.getJSON(BRJ.Env.pathBaseModule + '/memberhotels/create/clip/on/hotel_cd/'+hcd+'/', function(json){
					if (json.status == "success") {
						$("img", '.jqs-clip-' + hcd).attr('src', '/img/btn/b06-clip2.gif');
						$('.jqs-clip-' + hcd).addClass('jqs-on');
						$('.jqs-clip-' + hcd).parent('div').addClass('btn-b06-clip2-s');
						$('.jqs-clip-' + hcd).parent('div').removeClass('btn-b06-098-s');

						if ($("*").hasClass('jqs-clip-query')){
							$('img', '.jqs-clip-query').attr('src', '/img/btn/b05-search2.gif');
						}
					} else {
						alert(json.message);
					}
				});
			}


			return false;
		});
	});




	$(document).ready( function() {
		setEventsButtonImage();
		BRJ.Reserve.setPanel();
	} );

	// Area
	$(document).ready( function() {

		var $v = $('input[name="capacity"]:checked', $('.sfm-area')).val();
		$('a', $('.sfm-area')).each(function(){
			this.href = this.href.replace(/&capacity=[0-9]/, "&capacity=" + $v);
		});

		$('input[name="capacity"]', $('.sfm-area')).click(function () {
			var $v = $(this).val();
			$('a', $('.sfm-area')).each(function(){
				this.href = this.href.replace(/&capacity=[0-9]/, "&capacity=" + $v);
			});
		});
	});


$(document).ready(function() {

	jo = $('a[href*="/rsv/plan/reserve/?hotel_cd="], a[href*="/rsv/hotel/reserve/?hotel_cd="]');
	jo.click(function(){

				// IE6の場合はポップアップさせない。BRJ.UI.Panelが未定であるとエラーになるため。要調査。
				if(typeof document.body.style.maxHeight === "undefined"){
					return true;
				}

				BRJ.UI.Panel.wait();
				var s = $(this).attr('href') + '&view=form';

				$.getJSON(s, function(json){

					if($('div.jqs-searchpanel').length == 0){
						$('body').append('<div class="jqs-searchpanel" style="position:absolute"></div>');
					}
					$('div.jqs-searchpanel').html(json.html);

					$('.jqs-panel-close', 'div.jqs-searchpanel').click(function(){
						BRJ.UI.Panel.close();
						return false;
					});

					setEventsButtonImage();
					BRJ.UI.Tab.initialize();
					BRJ.Reserve.setPanel($('div.jqs-searchpanel'));
					BRJ.Reserve.GuestsCounter();
					BRJ.Reserve.Jrc.initialize();
					BRJ.Reserve.Jrc.selectYMDByQueryString(s);
					BRJ.Reserve.CheckInSelector.initialize();

					BRJ.UI.Panel.open($('div.jqs-searchpanel'));

				});


				return false;
	});

});

// Authorize
BRJ.Reserve.Authorize = function (function_login, function_logout) {

		var login = false;

		$.getJSON(BRJ.Env.pathBaseModule + '/auth/getdata/?uri=' + document.location.pathname + '&_=' + new Date().getTime(), function(json){
    // テスト用 $.getJSON('/authtest.json', function(json){

			$('.pgh1-usr-member').hide();
			$('.pgh1-usr-guest').hide();

			if (json) {

				if (json.Welcome != "") {
					$('.welcome').html(json.Welcome);
					$('.member').addClass('member2');
				}

				if (json.Name == "") {
					login = false;
					$('.pgh1-usr-guest').show();
				} else {
					login = true;
					$('.pgh1-usr-member').show();
					$('.username').html(json.Name);

//					// 買取施設の処理
//					BRJ.Rsv.Highrank.init();
				}

				// 認証後のログイン状態別の処理を実行
				if (login) {
					// ログインしている時の処理
					function_login();
				} else {
					// ログアウトしている時の処理
					function_logout();
				}
				// サーバから指示された処理を実行
				if (json.AfterExecute) {
					BRJ.Reserve.AfterAuthorize(json.AfterExecute);
				}

			}
		});
};

BRJ.Reserve.AfterAuthorize = function (args) {

	if (!$.isArray(args)) {
		args = new Array(args);
	}
	for(var i = 0; i < args.length; i++){
		switch (args[i]) {
			// 50万ポイントキャンペーン（2016/10/11-2016/10/30実施）
			// 2016/10/28まで実行されます。以降は必要ありません。
			case 'b16101':
				if ($('.jqs-camp-b16101').length > 0) {
					$('.jqs-camp-b16101').slideDown();
				}
			case 'b16111':
				if ($('.jqs-camp-b16111').length > 0) {
					$('.jqs-camp-b16111').slideDown();
				}
		}
	}
}


// ドキュメントの準備が出来たら実行する場合は出来るかぎり以下にまとめること。
// 各々で行うと、準備が出来たときに何が動いているのかがわかないばかりか、
// 実行される順序が管理できずに予期しない結果になることがあります。
$(document).ready(function() {
	if (location.host.slice(-11) == 'bestrsv.com') {
		BRJ.UI.Expand.initialize();
		BRJ.UI.Tab.initialize();
		BRJ.UI.Switch.initialize();

		// 会員認証
		BRJ.Reserve.Authorize(
			// 認証後ログイン状態の時の処理
			function() {
				BRJ.Rsv.Highrank.processTop();
				BRJ.Rsv.Highrank.processList();
				BRJ.Rsv.Highrank.processDetail(true);
			},
			// 認証後ログアウト時の時の処理
			function() {
				BRJ.Rsv.Highrank.processDetail(false);
			}
			// 認証インターフェイスで指示（AfterExecute）される処理は
			// これらの後で処理されます。
		);

		BRJ.Reserve.GuestsCounter();
		BRJ.Reserve.BookingGender.initialize();
		BRJ.Reserve.Jrc.initialize();
		BRJ.Reserve.CheckInSelector.initialize();
		BRJ.Reserve.setBannerToSP();
		BRJ.Rsv.Highrank.controlSeachHotel();
                BRJ.UI.IncludeHTML.initialize();
                

	}
});
