BRJ = {}
BRJ.Data = {}
BRJ.Util = {}

new function() {
BRJ.Env = {

	device: '',
	module: '',
	pathBase: '',
	pathBaseModule: '',

	setModule: function(s) {
		self.module = s;
		self.pathBaseModule = self.pathBase + '/' + self.module;
	}

}

	var self = BRJ.Env;

	var buffer = location.pathname.match(/\/(branches|tags)\/([^\/]+)/);
	if (buffer) self.pathBase = buffer[0];

	var ua = window.navigator.userAgent;
if (ua.match(/(iPhone|Android.*Mobile|Windows.*Phone)/)) {
	self.device = 'sp';
} else {
	self.device = 'other';
}

}

new function() {

				// ページのロケーションからベースとなるアドレスを取得
				BRJ.Env.pathBase = '';
				var buffer = location.pathname.match(/\/(branches|tags)\/([^\/]+)/);
				if (buffer) BRJ.Env.pathBase = buffer[0];

				BRJ.Env.module = '';
				BRJ.Env.pathBaseModule = '';
}


	Date.prototype.addDateBRJ = function(nDate) {
		this.setDate(this.getDate() + nDate);
	}

	Date.prototype.toWeek = function(sFormat) {
		sFormat = sFormat || 'D';
		sResult = '';
				if (sFormat == 'j') { sResult = ['日'		, '月'		, '火'		 , '水' 			, '木'			, '金'		, '土'			][this.getDay()]; }
		else if (sFormat == 'D') { sResult = ['Sun' 	, 'Mon' 	, 'Tue' 	 , 'Wed'			, 'Thu' 		, 'Fri' 	, 'Sat' 		][this.getDay()]; }
		else if (sFormat == 'l') { sResult = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][this.getDay()]; }
		return(sResult);
	}

	Date.prototype.toWeekBRJ = function(sFormat) {
		return this.toWeek(sFormat);
	}

	Date.prototype.toStringBRJ = function(sFormat, sLeftPad) {
		sFormat  = sFormat	|| 'Y-m';
		sLeftPad = sLeftPad || '0';
		var y = this.getFullYear(), m = this.getMonth() + 1, d = this.getDate();
		if (sLeftPad.length > 0) {
			m = ('' + sLeftPad + m).slice(-2);
			d = (sLeftPad + d).slice(-2);
		}
		return sFormat.replace(/y+/i, y).replace(/m+/i, m).replace(/d+/i, d);
	}

	Date.prototype.getDateLastOfMonth = function() {
		return (new Date(this.getFullYear(), this.getMonth() + 1, 0).getDate());
	}

	Date.prototype.getDateLastOfMonthBRJ = function() {
		return (this.getDateLastOfMonth());
	}

	Date.prototype.setStringBRJ = function(s) {
		p = s.split(/[\/-]/, 3);
		this.setMonth(0);
		this.setDate(1);
		this.setFullYear(p[0])
		this.setDate(p[2] || 1);
		this.setMonth((p[1] || 1) - 1)
		this.setHours(0);
		this.setMinutes(0);
		this.setSeconds(0);
		this.setMilliseconds(0);
	}

	
	// RollOver
	function changeFileSufix(p, s) {
		return(p.replace(/(\_[A-Za-z0-9]+|)(?=\.(gif|png|jpg)$)/, (s == '' ? '' : '_' + s)));
	}

	function testDriveButtonImage(s) {
		return(!/_disable(?=\.(gif|png|jpg)$)/.test(s) && !/-current(?=\.(gif|png|jpg)$)/.test(s));
	}

	function setEventsButtonImage() {

		var a = $('a.btnimg');
		a.mouseover ( function()	{ t = $(this).children()[0]; if(testDriveButtonImage(t.src)) t.src = changeFileSufix(t.src, 'over'); } );
		a.focus 		( function()	{ t = $(this).children()[0]; if(testDriveButtonImage(t.src)) t.src = changeFileSufix(t.src, 'over'); } );
		a.mouseout	( function()	{ t = $(this).children()[0]; if(testDriveButtonImage(t.src)) t.src = changeFileSufix(t.src, ''); } );
		a.blur			( function()	{ t = $(this).children()[0]; if(testDriveButtonImage(t.src)) t.src = changeFileSufix(t.src, ''); } );
		// a.mousedown ( function()  { t = $(this).children()[0]; t.src = changeFileSufix(t.src, 'click');return(false); } );
		// a.keypress  ( function(e) { if(e.keyCode == 13 && e.altKey == false) { t = $(this).children()[0];t.src = changeFileSufix(t.src, 'click');} } )

		var i = $('input.btnimg');
		i.mouseover ( function() { t = this; if(testDriveButtonImage(t.src)) t.src = changeFileSufix(t.src, 'over'); } );
		i.focus 		( function() { t = this; if(testDriveButtonImage(t.src)) t.src = changeFileSufix(t.src, 'over'); } );
		i.mouseout	( function() { t = this; if(testDriveButtonImage(t.src)) t.src = changeFileSufix(t.src, ''); } );
		i.blur			( function() { t = this; if(testDriveButtonImage(t.src)) t.src = changeFileSufix(t.src, ''); } );

		var p = $('a.btnpnl');

		p.mouseover ( function()	{ ts = $('img.btn', this); ts.each( function() { t = this; if(testDriveButtonImage(t.src)) t.src = changeFileSufix(t.src, 'over'); } ) } );
		p.focus 		( function()	{ ts = $('img.btn', this); ts.each( function() { t = this; if(testDriveButtonImage(t.src)) t.src = changeFileSufix(t.src, 'over'); } ) } );
		p.mouseout	( function()	{ ts = $('img.btn', this); ts.each( function() { t = this; if(testDriveButtonImage(t.src)) t.src = changeFileSufix(t.src, ''); } ) } );
		p.blur			( function()	{ ts = $('img.btn', this); ts.each( function() { t = this; if(testDriveButtonImage(t.src)) t.src = changeFileSufix(t.src, ''); } ) } );

		var m = $('map.btnimg > area, li.btnimg a');
		m.mouseover( function() { t = $('img[usemap="#' + $(this).parent().attr('name') + '"]')[0]; t.src = changeFileSufix(t.src, this.attributes['name'].value); } );
		m.focus 	 ( function() { t = $('img[usemap="#' + $(this).parent().attr('name') + '"]')[0]; t.src = changeFileSufix(t.src, this.attributes['name'].value); } );
		m.mouseout ( function() { t = $('img[usemap="#' + $(this).parent().attr('name') + '"]')[0]; t.src = changeFileSufix(t.src, ''); } );
		m.blur		 ( function() { t = $('img[usemap="#' + $(this).parent().attr('name') + '"]')[0]; t.src = changeFileSufix(t.src, ''); } );

	}

//
// BRJ.util
//
BRJ.util = BRJ.util || {};

BRJ.util.overset = function(a, b) {
	for(v in b){
		a[v] = b[v];
	}
}

BRJ.util.parseQueryString = function(qs) {
	var o = {};
	var qss = qs.split('&');
	for(var i = 0; i < qss.length; i++){
		var kv = qss[i].split('=');
		if(kv[0]) {
			var k = decodeURI(kv[0]);
			var v = decodeURI(kv[1]||'');
			var ks = k.match(/^([^[]+)\[([^[]+)\]$/);
			if(ks) {
				o[ks[1]] = o[ks[1]] || {};
				o[ks[1]][ks[2]] = v;
			}else{
				o[k] = v;
			}
		}
	}
	return(o);
}




//
// BRJ.UI
//
BRJ.UI = BRJ.UI || {};
BRJ.UI.SlideBanner = {
	banners: [],
	start: function(t, s){
		var e = $(t);
		e.css('position', 'relative');

		BRJ.util.overset(this.settings, s);

		var b = e.children();
		b.css('position', 'absolute');
		for(i = 0; i < b.length; i++) {
			this.banners[i] = b[i];
			$(b[i]).css(this.settings.getSlideAxis(), this.settings.getSlideDirection() * this.settings.handleSize * (b.length - 1 - i)
					).css('z-index', b.length - 1 - i);
			$(b[i]).click(this.clickBanner);
		}

		this.startInterval();

	},

	startInterval: function() {
		$('body').oneTime(this.settings.interval, 'SlideBannerInterval', function() {
			BRJ.UI.SlideBanner.slide(1);
		});
	},

	clickBanner: function(e) {

		var b = BRJ.UI.SlideBanner.banners;
		var s = BRJ.UI.SlideBanner.settings;

		for(var i = 0; i < b.length; i++) {
			if($(b[i]).queue().length > 0) {
				return(false)
			}
		}

		$('body').stopTime('SlideBannerInterval');

		var x = e.pageX - $(this).offset().left;
		var y = e.pageY - $(this).offset().top;

		var executeSlide = false;
		if(s.handlePosition == 'left') {
			if(0 <= x && x <= s.handleSize){
				executeSlide = true;
			}
		} else if(s.handlePosition == 'right') {
			if(s.width - s.handleSize <= x && x <= s.width) {
				executeSlide = true;
			}
		} else if(s.handlePosition == 'top') {
			if(0 <= y && y <= s.handleSize) {
				executeSlide = true;
			}
		} else if(s.handlePosition == 'bottom') {
			if(s.height - s.handleSize <= y && y <= s.height){
				executeSlide = true;
			}
		}

		if($(this).css('z-index') == b.length - 1) {
			executeSlide = false;
		}

		if(executeSlide){
			BRJ.UI.SlideBanner.slide(b.length - 1 - $(this).css('z-index'));
			return(false);
		}

	},

	slide: function(n) {
		var b = this.banners;
		var s = this.settings;

		var w = s.getSlideSize() - s.handleSize * (this.banners.length - n);
		var p = {}
		p[s.getSlideAxis()] = (s.getSlideDirection() > 0 ? '+' : '-') + '=' + w;
		p['opacity' 			] = 0.1;
		for(var i = 0; i < n; i++) {
			$(b[i]).animate(p, 'slow', function() {
				var b = BRJ.UI.SlideBanner.banners;
				var s = BRJ.UI.SlideBanner.settings;
				var w = $(this).position()[s.getSlideAxis()] - (s.getSlideSize() * s.getSlideDirection());
				$(this).css('z-index', (w / s.handleSize) * s.getSlideDirection());
				$(this).css(s.getSlideAxis(), w);
				$(this).animate({opacity:1}, 'slow', function() {
					if ($(this).css('z-index') == 0) {
						BRJ.UI.SlideBanner.startInterval();
					}
				});
			});
		}

		p = {}
		p[s.getSlideAxis()] = (s.getSlideDirection() > 0 ? '+' : '-') + '=' + (s.handleSize * n);
		for(var i = n; i < this.banners.length; i++) {
			$(b[i]).animate(p, 'slow', function() {
				var s = BRJ.UI.SlideBanner.settings;
				$(this).css('z-index', ($(this).position()[s.getSlideAxis()] / s.handleSize) * s.getSlideDirection());
			});
		}

		for(var i = 0; i < n; i++) {
			this.banners.push(this.banners.shift());
		}
	},


	settings:{
		interval:3000,
		width: 600,
		height: 166,
		handlePosition: 'right',
		handleSize: 25,

		getSlideAxis: function() {
			if(this.handlePosition == 'left' || this.handlePosition == 'right') {
				return('left');
			} else {
				return('top');
			}
		},

		getSlideDirection: function() {
			if(this.handlePosition == 'right' || this.handlePosition == 'bottom') {
				return(-1);
			} else {
				return(1);
			}
		},

		getSlideSize: function() {
			if(this.hanldePosition == 'right' || this.handlePosition == 'left') {
				return(this.width);
			} else {
				return(this.height);
			}
		}
	}

}

BRJ.UI.CountDown = {

	deadend: '',
	target: '',
	start: function(t, l) {

		this.target = $(t);
		var d = $(l).text().split(/[\/ :\-]/);
		this.deadend = new Date(d[0], d[1] - 1, d[2], d[3], d[4], d[5]);
		this.execute();
		$('body').everyTime(1000, 'countdown', function() {
			BRJ.UI.CountDown.execute();

		});
	},
	execute: function() {
		var t = BRJ.UI.CountDown.target
		var d = Math.floor((BRJ.UI.CountDown.deadend - (new Date())) / 1000);
		if (d <= 0) {
			t.html('0<span class="unit"> 時間 </span>0<span class="unit"> 分 </span>0<span class="unit"> 秒</span>');
			$('body').stopTime('countdown')
		}else{
			a = Math.floor(d / 3600);
			b = Math.floor((d % 3600) / 60);
			c = (d % 3600) % 60;
			t.html(a + '<span class="unit"> 時間 </span>' + b + '<span class="unit"> 分 </span>' + c + '<span class="unit"> 秒</span>');
		}
	}

}

//
//	Tab Object Constructor
//
//	Syntax :
//		var tab = new BRJ.UI.Tab(tabId, contentId[, hoverClassName[, activeClassName[, passiveClassName]]]);
//

BRJ.UI.Tab = function(tabId, contentId, hoverClassName, activeClassName, passiveClassName) {

	//
	//	Local Functions
	//

	function replaceClassName(currentClassNames, removeClassName, addClassName) {
		var a = (currentClassNames + ' ' + addClassName).split(/ +/);
		var newClassName = '';
		for (var i = 0;i < a.length; i++) {
			if (a[i] != '' && a[i] != removeClassName) newClassName += ' ' + a[i];
		}
		return(newClassName.replace(/^ +| $/g, ''));
	}

	//
	//	Methods
	//

	this.doActive = function () {
		if (this.status != 'active') {
			this.contentEl.style.display = origin.style.display;
			if (this.activeClassName != '') {
				this.tabEl.className = replaceClassName(this.tabEl.className, this.passiveClassName, this.activeClassName);
			}
			this.status = 'active';
		}
	}

	this.doPassive = function () {
		this.contentEl.style.display = 'none';
		if (this.activeClassName != '') {
			this.tabEl.className = replaceClassName(this.tabEl.className, this.activeClassName, this.passiveClassName);
		}
		this.status = 'passive';
	}

	this.doOn = function () {
		if (this.status != 'active' && this.hoverClassName) {
			this.tabEl.className = replaceClassName(this.tabEl.className, this.passiveClassName, this.hoverClassName);
		}
	}

	this.doOff = function () {
		if (this.hoverClassName != '') {
			this.tabEl.className = replaceClassName(this.tabEl.className, this.hoverClassName, this.passiveClassName);
		}
	}

	//
	//	Constructor
	//

	this.tabId						 = tabId;
	this.contentId				 = contentId;
	this.tabEl						 = document.getElementById(tabId);
	this.tabEl.onmouseover = function () { _this.doOn(); }
	this.tabEl.onmouseout  = function () { _this.doOff();  }
	this.contentEl				 = document.getElementById(contentId);
	this.activeClassName	 = (activeClassName  ? activeClassName	: '');
	this.passiveClassName  = (passiveClassName ? passiveClassName : '');
	this.hoverClassName 	 = (hoverClassName	 ? hoverClassName 	: '');

	var _this = this;
	var origin = {
		style : {
			display : (this.contentEl.style.display == 'none' ? '' : this.contentEl.style.display)
		}
	}

	this.doPassive();

}

//
//	Tab View Object Constructor
//
//	Syntax :
//		var tabview = new BRJ.UI.TabView([hoverClassName[, activeClassName[, passiveClassName]]]);
//		tabview.addTab(new BRJ.UI.Tab(tabId, contentId[, hoverClassName[, activeClassName[, passiveClassName]]]):
//		....
//

BRJ.UI.TabView = function(hoverClassName, activeClassName, passiveClassName) {

	//
	//	Constructor
	//

	this.hoverClassName 	= (hoverClassName 	? hoverClassName	 : '');
	this.activeClassName	= (activeClassName	? activeClassName  : '');
	this.passiveClassName = (passiveClassName ? passiveClassName : '');

	var tabs = new Array();

	//
	//	Methods
	//

	this.addTab = function(tab) {

		tabs[tabs.length] = tab;

		if (tab.hoverClassName	 == '' && this.hoverClassName 	!= '') tab.hoverClassName 	= this.hoverClassName;
		if (tab.activeClassName  == '' && this.activeClassName	!= '') tab.activeClassName	= this.activeClassName;
		if (tab.passiveClassName == '' && this.passiveClassName != '') tab.passiveClassName = this.passiveClassName;

			if (tabs.length == 1) {
				tab.doActive();
			}

		tab.tabEl.onclick = function() {
				if (tab.status != 'active') {
				for (i = 0; i < tabs.length; i++) {
					if (tabs[i] !== tab) tabs[i].doPassive();
				}
				tab.doActive();
			}
			return(false);
			}
	}
}

//// ページが表示された後、サーバからHTMLを取得して挿入
//BRJ.IncludeHTML = function(parent) {
//	$('div.jqs-include', parent).each( function() {
//
//		var jqTargrt = $(this);
//
//		// 取得先がモジュール（/module/）で開始されている場合はソースツリーを考慮
//		var url = jqTargrt.attr('name');
//		if (url.substr(0, BRJ.Env.module.length + 2) == '/' + BRJ.Env.module + '/') {
//			url = BRJ.Env.pathBase + url
//		}
//
//		// データを取得してHTMLに挿入して表示
//		$.getJSON(url, function(json) {
//			jqTargrt.html(unescape(json.html)).show();
//		});
//
//	});
//}

// コンテンツを読み込み出力
new function() {
	BRJ.UI.IncludeHTML = {
		initialize: function(parent) {
			$('.jqs-include', parent).each( function() {
				var target = $(this);

				// コンテンツのアドレスを取得
				var url = target.attr('name');

				// アドレスの先頭がモジュールで開始されている場合はソースツリーを考慮
				if (BRJ.Env.module.length > 0 && url.substr(0, BRJ.Env.module.length + 2) == '/' + BRJ.Env.module + '/') {
					url = BRJ.Env.pathBase + url;
				}

				// コンテンツを取得して要素内に出力
				$.ajax({
					type: 'GET',
					url: url,
					cache: false,
					success: function(data) {
						if (!$.isPlainObject(data)) {
							data = { html: data };
						}
						target.html(unescape(data.html)).show();
						if (data.AfterExecute) {
							eval(data.AfterExecute);
						}
					}
				});
			});
		}
	}
}


BRJ.Calendar = {}

BRJ.Calendar.getHoliday = function(dDate, n) {

	// ローカル変数を宣言、初期化
	var y = dDate.getFullYear();
	var m = dDate.getMonth() + 1;
	var d = dDate.getDate();
	var w = dDate.getDay();
	var c = Math.floor(d / 7 + 0.9);
	var ymd = y * 10000 + m * 100 + d;
	var n = (n) ? n : 0;
	var s = '', a = 0, b = 0, i = 0;

	// 祝日を判定
	// 祝日を判定
	     if (1949 <= y && y <=    y && m ==  1 && d ==  1          ) { s = '元旦';         } // 元旦          （1948/07/20 施行）
	else if (1874 <= y && y <= 1948 && m ==  1 && d ==  3          ) { s = '元始祭';       } // 元始祭        （1873/10/14 施行 1848/07/20 廃止）
	else if (1874 <= y && y <= 1948 && m ==  1 && d ==  5          ) { s = '新年宴會';     } // 新年宴會      （1873/10/14 施行 1948/07/20 廃止）
	else if (1949 <= y && y <= 1999 && m ==  1 && d == 15          ) { s = '成人の日';     } // 成人の日      （1948/07/20 施行）
	else if (2000 <= y && y <=    y && m ==  1 && c ==  2 && w == 1) { s = '成人の日';     } // 成人の日      （2000/01/01 改正）
	else if (1874 <= y && y <= 1912 && m ==  1 && d == 30          ) { s = '孝明天皇祭';   } // 孝明天皇祭    （1873/10/14 施行 1912/09/03 廃止）
	else if (1874 <= y && y <= 1948 && m ==  2 && d == 11          ) { s = '紀元節';       } // 紀元節        （1873/10/14 施行 1948/07/20 廃止）
	else if (1967 <= y && y <=    y && m ==  2 && d == 11          ) { s = '建国記念の日'; } // 建国記念の日  （1966/06/25 施行）
	else if (1874 <= y && y <= 1948 && m ==  4 && d ==  3          ) { s = '神武天皇祭';   } // 神武天皇祭    （1873/10/14 施行 1948/07/20 廃止）
	else if (1928 <= y && y <= 1948 && m ==  4 && d == 29          ) { s = '天長節';       } // 天長節        （1927/03/03 施行 1948/07/20 廃止）
	else if (1949 <= y && y <= 1988 && m ==  4 && d == 29          ) { s = '天皇誕生日';   } // 天皇誕生日    （1948/07/20 施行 1989/02/17 廃止）
	else if (1989 <= y && y <= 2006 && m ==  4 && d == 29          ) { s = 'みどりの日';   } // みどりの日    （1989/02/17 施行 2007/01/01 廃止）
	else if (2007 <= y && y <=    y && m ==  4 && d == 29          ) { s = '昭和の日';     } // 昭和の日      （2007/01/01 改正）
	else if (2019 == y && y <=    y && m ==  5 && d == 1           ) { s = '祝日';         } // 皇太子さま即位 (2018/12/08 成立 2019年のみ）
	else if (1949 <= y && y <=    y && m ==  5 && d ==  3          ) { s = '憲法記念日';   } // 憲法記念日    （1948/07/20 施行）
	else if (2007 <= y && y <=    y && m ==  5 && d ==  4          ) { s = 'みどりの日';   } // みどりの日    （2007/01/01 改正）
	else if (1949 <= y && y <=    y && m ==  5 && d ==  5          ) { s = 'こどもの日';   } // こどもの日    （1948/07/20 施行）
	else if (1996 <= y && y <= 2002 && m ==  7 && d == 20          ) { s = '海の日';       } // 海の日        （1996/01/01 施行 2003/01/01 廃止）
	else if (2003 <= y && y <=    y && m ==  7 && c ==  3 && w == 1) { s = '海の日';       } // 海の日        （2003/01/01 改訂）
	else if (1913 <= y && y <= 1926 && m ==  7 && d == 30          ) { s = '明治天皇祭';   } // 明治天皇祭    （1912/09/03 施行 1927/03/03 廃止）
	else if (1913 <= y && y <= 1926 && m ==  8 && d == 31          ) { s = '天長節';       } // 天長節        （1912/09/03 施行 1927/03/03 廃止）
	else if (1966 <= y && y <= 2002 && m ==  9 && d == 15          ) { s = '敬老の日';     } // 敬老の日      （1966/06/25 施行 2003/01/01 廃止）
	else if (2003 <= y && y <=    y && m ==  9 && c ==  3 && w == 1) { s = '敬老の日';     } // 敬老の日      （2003/01/01 改訂）
	else if (1874 <= y && y <= 1878 && m ==  9 && d == 17          ) { s = '神嘗祭';       } // 神嘗祭        （1873/10/14 施行 1879/07/05 廃止）
	else if (1879 <= y && y <= 1947 && m == 10 && d == 17          ) { s = '神嘗祭';       } // 神嘗祭        （1879/07/05 改訂 1948/07/20 廃止）
	else if (1966 <= y && y <= 1999 && m == 10 && d == 10          ) { s = '体育の日';     } // 体育の日      （1966/06/25 施行 2000/01/01 廃止）
	else if (2000 <= y && y <=    y && m == 10 && c ==  2 && w == 1) { s = '体育の日';     } // 体育の日      （2000/01/01 改訂）
	else if (2019 == y && y <=    y && m == 10 && d == 22          ) { s = '即位礼正殿の儀';}// 即位礼正殿の儀（2018/12/08 成立 2019年のみ）
	else if (1913 <= y && y <= 1926 && m == 10 && d == 31          ) { s = '天長節祝日';   } // 天長節祝日    （1913/07/16 施行 1927/03/03 廃止）
	else if (1873 <= y && y <= 1911 && m == 11 && d ==  3          ) { s = '天長節';       } // 天長節        （1873/10/14 施行 1912/09/03 廃止）
	else if (1927 <= y && y <= 1948 && m == 11 && d ==  3          ) { s = '明治節';       } // 明治節        （1927/03/03 施行 1948/07/20 廃止）
	else if (1948 <= y && y <=    y && m == 11 && d ==  3          ) { s = '文化の日';     } // 文化の日      （1948/07/20 施行）
	else if (1873 <= y && y <= 1947 && m == 11 && d == 23          ) { s = '新嘗祭';       } // 新嘗祭        （1873/10/14 施行 1948/07/20 廃止）
	else if (1948 <= y && y <=    y && m == 11 && d == 23          ) { s = '勤労感謝の日'; } // 勤労感謝の日  （1948/07/20 施行）
	else if (1989 <= y && y <= 2018 && m == 12 && d == 23          ) { s = '天皇誕生日';   } // 天皇誕生日    （1989/02/17 施行）
	else if (2020 <= y && y <=    y && m ==  2 && d == 23          ) { s = '天皇誕生日';   } // 天皇誕生日    （2019/05/01 施行）
	else if (1927 <= y && y <= 1947 && m == 12 && d == 25          ) { s = '大正天皇祭';   } // 大正天皇祭    （1927/03/03 施行 1948/07/20 廃止）

	// 春分と秋分を算出して判定
	if (s.length == 0) {
		if (m == 3 || m == 9) {
					if (1851 <= y && y <= 1899) { a =	0; b = y - 1983; }
			else if (1900 <= y && y <= 1979) { a =	1; b = y - 1983; }
			else if (1980 <= y && y <= 2099) { a =	2; b = y - 1980; }
			else if (2100 <= y && y <= 2150) { a =	3; b = y - 1980; }
			else														 { a = -1; b = -1;			 }

			if (a > 0 && b > 0) {
				if (m == 3) {
					if (d == Math.ceil(new Array(19.8277, 20.8357, 20.8431, 21.8510)[a] + 0.242194 * b - Math.ceil((b + 0.1) / 4))) {
								if (1879 <= y && y <= 1948) { s = "春季皇靈祭"; } // 春季皇靈祭（1878/06/05 施行 1948/07/20 廃止）
						else if (1949 <= y && y <= y	 ) { s = "春分の日";	 } // 春分の日　（1948/07/20 施行）
					}
				} else if (m == 9) {
					if (d == Math.ceil(new Array(22.2588, 23.2588, 23.2488, 24.2488)[a] + 0.242194 * b - Math.ceil((b + 0.1) / 4))) {
								if (1878 <= y && y <= 1947) { s = "秋季皇靈祭"; } // 秋季皇靈祭（1878/06/05 施行 1948/07/20 廃止）
						else if (1948 <= y && y <= y	 ) { s = "秋分の日";	 } // 秋分の日　（1948/07/20 施行）
					}
				}
			}
		}
	}

	// 振替休日を判定（再起で呼ばれたときは非処理）
	if (s.length == 0 && n == 0) {
		// 振替休日（1973/04/12 施行 2007/01/01 廃止）
		if (19730412 <= ymd && ymd <= 20061231 && w == 1) {
			if (BRJ.Calendar.getHoliday(new Date(y, m - 1, d - 1), 1).length > 0) {
				s = "振替休日";
			}

		// 振替休日（2007/01/01 改訂）
		} else if (20070101 <= ymd && ymd <= ymd) {
			if (BRJ.Calendar.getHoliday(new Date(y, m - 1, d - w), 1).length > 0) {
				s = '振替休日';
				for (i = 1; i < w; i++) {
					if (BRJ.Calendar.getHoliday(new Date(y, m - 1, d - w + i), 1).length == 0){
						s = ''; break;
					}
				}
			}
		}
	}

	// 国民の休日を判定（再起で呼ばれたときは非処理）
	if (s.length == 0 && n == 0) {
		if (19851227 <= ymd && ymd <= ymd && w != 0) {
			if (BRJ.Calendar.getHoliday(new Date(y, m - 1, d - 1), 1).length > 0 && BRJ.Calendar.getHoliday(new Date(y, m - 1, d + 1), 1).length > 0) {
				s = "国民の休日"; // （1985/12/27 施行 2007/01/01 改訂）
			}
		}
	}

	// 結果を返却
	return s;
}