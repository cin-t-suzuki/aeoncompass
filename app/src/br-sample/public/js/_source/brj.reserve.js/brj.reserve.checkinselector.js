new function(){

	BRJ.Reserve.CheckInSelector = {

		initialize: function(){

						//
						var jf = getTargetForm();


						// 基本の取扱期間と初期選択年月日を設定
						// 取扱期間の開始年月日は本日、最終年月日は本日から12ヶ月後の月末日、初期値は本日となります。
						// ただし、深夜予約を考慮して0時から5時59分までは開始年月日が本日の前日になります。
						var d = new Date();
						var ds = new Date(d.getFullYear(), d.getMonth(), d.getDate());
						if(0 <= d.getHours() && d.getHours() <= 5){
							ds.setDate(d.getDate() - 1);
						}
						var df = new Date(d.getFullYear(), d.getMonth() + 13, 0);
						var dd = new Date(d.getFullYear(), d.getMonth(), d.getDate());

						// ページで指定された取扱期間と初期選択年月日を導入
						if ($(':hidden[name="params"]', jf).length > 0) {
							oparam = eval($(':hidden[name="params"]', jf).first().text());
							if(oparam.startYMD)   ds = new Date(oparam.startYMD.substr(0, 4),   oparam.startYMD.substr(5, 2) - 1,   oparam.startYMD.substr(8, 4)  );
							if(oparam.finalYMD)   df = new Date(oparam.finalYMD.substr(0, 4),   oparam.finalYMD.substr(5, 2) - 1,   oparam.finalYMD.substr(8, 4)  );
							if(oparam.defaultYMD) dd = new Date(oparam.defaultYMD.substr(0, 4), oparam.defaultYMD.substr(5, 2) - 1, oparam.defaultYMD.substr(8, 4));
						}

						// 取扱期間と初期選択年月日を格納
						startYMD = ds;
						finalYMD = df;
						defaultYMD = dd;

						// 年月と日の選択ボックスを抽出
						var jym = $('select[name="year_month"]', jf);
						var jd	= $('select[name="day"]', jf);

						// 年月の選択ボックスの選択肢を生成、イベントを設定
						jym.each(function(){
							self.setYearMonthOptions(this, ds, df, dd);
						});
						jym.change(function(){
							eventChangeYM(this);
						});
						jym.change();

						// 都道府県の選択リストにイベントを設定
						$('select[name="place_p"]', jf).change( function() {
							eventChangePlaceP(this);
						});

						// 中小エリアの選択リストにイベントを設定
						$('select[name="place_ms"]', jf).change( function() {
							eventChangePlaceMS(this);
						});


						// 日の選択ボックスの選択肢を生成、イベントを設定
//						jd.each(function(){
//							if(this.options.length < dd.getDate()){
//								this.options.selectedIndex = this.options.length - 1;
//							}else{
//								this.options.selectedIndex = dd.getDate() - 1;
//							}
//						});
						jd.change(function(){
							eventChangeD(this);
						});


					$('.jqs-expand[name="open_sesame_form_search"]').each( function() {
						BRJ.UI.Expand.bind(this, reflectLinksExpand, removeLinksExpand);
					});



					// フォームにイベントを設定
					jf.change( function() {
						eventChangeForm(this);
					});


		},



		//
		//	setYearMonthOptions
		//
		//	機能	年月の選択ボックスの選択肢を生成します。
		//	引数	os		: 年月の選択ボックス
		//				dsym	: 対象期間の開始年月の日付値
		//				dfym	: 対象期間の最終年月の日付値
		//				ddym	: 初期年月の日付値
		//
		setYearMonthOptions: function(os, dsym, dfym, ddym){

						// 開始年月と終了年月を整理
						var ds = new Date(dsym); ds.setDate(1);
						var df = new Date(dfym); df.setDate(1);

						// 初期年月を調整
						var dd = new Date(ddym); dd.setDate(1);
						if(os.options.selectIndex < 0 || os.options.length == 0 || os.options[0].value.length == 0){
							if(dd < ds) dd = ds;
							if(dd > df) dd = df;
						}else{
							dd.setStringBRJ(os.options[os.options.selectedIndex].value);
						}

						// 選択肢を生成、初期値を設定
						os.options.length = 0;
						for(var dym = ds; dym <= df;){
							os.options[os.options.length] = new Option(dym.toStringBRJ('Y年m月', ' '), dym.toStringBRJ('Y-m'));
							if(dym.getFullYear() == dd.getFullYear() && dym.getMonth() == dd.getMonth()){
								os.options.selectedIndex = os.options.length - 1;
							}
							// 次の年月を処理
							dym.setMonth(dym.getMonth() + 1);
						}

		},



		//
		//	setDateOptions
		//
		//	機能	日の選択ボックスの選択肢を生成します。
		//	引数	os		 : 日の選択ボックス
		//				dym 	 : 対象年月の日付値
		//				ddymd  : 初期年月日の日付値
		//				dlsymd : 取扱期間の開始年月日の日付値
		//				dlfymd : 取扱期間の最終年月日の日付値
		//
		setDateOptions: function(os, dym, ddymd, dlsymd, dlfymd){

						// 初期年月日もしくは現在選択されている日を生成後の選択日として取得
						if(os.options.selectedIndex < 0 || os.options.length == 0 || os.options[0].value.length == 0){
							var d = ddymd.getDate();
						}else{
							var d = os.options.selectedIndex + 1;
						}

						// 選択日が取扱期間内でなかったときの調整
						// 調整の詳細は以下の通りです。
						// - 取扱期限の開始年月以前のときは取扱開始年月の月初日（1日）に調整。
						// - 取扱期限の開始年月で開始日以前のときは取扱開始日に調整。
						// - 取扱期限の最終年月以降のときは取扱最終年月の月末日に調整。
						// - 取扱期限の最終年月で最終日以降のときは取扱最終日に調整。
						var dw = new Date(dym);
						if(dw.getDateLastOfMonth() < d){
							d = dw.getDateLastOfMonth();
						}
						dw.setDate(d);

						if(dw < dlsymd){
							if(dw.getFullYear() == dlsymd.getFullYear() && dw.getMonth() == dlsymd.getMonth()){
								d = dlsymd.getDate();
							}else{
								d = 1;
							}
						}
						if(dw > dlfymd){
							if(dw.getFullYear() == dlfymd.getFullYear() && dw.getMonth() == dlfymd.getMonth()){
								d = dlfymd.getDate();
							}else{
								d = dw.getDateLastOfMonth();;
							}
						}

						// 開始年月日と終了年月日を取得
						var ds = new Date(dym); ds.setDate(1);
						var df = new Date(dym); df.setDate(df.getDateLastOfMonth());

						// 選択肢を生成、初期値を設定
						os.options.length = 0;
						for(var dymd = ds; dymd <= df;){
							os.options[os.options.length] = new Option(dymd.getDate() + '日（' + dymd.toWeek('j') + '）', dymd.toStringBRJ('d'));

							// 選択肢に期間、祝祭日、曜日にまつわるCSSクラスを追加
							var jo = $(os.options[os.options.length - 1]);
							var sholiday = BRJ.Calendar.getHoliday(dymd);
							if(sholiday.length > 0){						// 祝祭日の名称
								jo.attr('title', sholiday);
							}
							if(dymd < dlsymd || dlfymd < dymd){ // 取扱期間外
								jo.addClass('disable');
							}else if(sholiday.length > 0){			// 祝祭日
								jo.addClass('hol');
							}else{															// 曜日
								jo.addClass(dymd.toWeek('D').toLowerCase());
							}

							// 次の日を処理
							dymd.setDate(dymd.getDate() + 1);
						}

						// 選択日を設定
						os.options.selectedIndex = d - 1;
		}

	}

	// 自身への参照を設定
	var self = BRJ.Reserve.CheckInSelector;

	// 関連日付の保持変数を宣言
	var startYMD; 	// 取扱期間の開始年月日
	var finalYMD; 	// 取扱期間の最終年月日
	var defaultYMD; // 規定の選択年月日


	var getTargetForm = function(){

					return $('form.jqs-query');
					// return $('form').filter(function(){
					// 	if($(this).attr("action").match(/\/jrc\/$/)){
					// 		return false;
					// 	}else{
					// 		return true;
					// 	}
					// });
	}

	var eventChangeYM = function(os){

					var js = $('select[name="day"]', $(os.form));
					var dym = new Date();
					dym.setStringBRJ(os.options[os.options.selectedIndex].value);
					js.each(function(){
						self.setDateOptions(this, dym, defaultYMD, startYMD, finalYMD);
					});

					$('select[name="year_month"]', getTargetForm()).each(function(){
						if(os != this){
							this.options.selectedIndex = os.options.selectedIndex;
							$('select[name="day"]', $(this.form)).each(function(){
								self.setDateOptions(this, dym, defaultYMD, startYMD, finalYMD);
							});
						}
					});
	}

	var eventChangeD = function(os){

					$('select[name="day"]', getTargetForm()).each(function(){
						if(os != this){
							this.options.selectedIndex = os.options.selectedIndex;
						}
					});
	}

	//
	//	eventChangePlaceMS
	//
	function eventChangePlaceMS(o) {

				// 市区の選択リストの内容を特定する場所IDを取得
				// 通常は中小エリアの選択リストで選択されている場所IDになりますが、
				// 「全域」が選択されている時は上の階層の都道府県の場所IDになります。
				// さらに都道府県が固定されているページでは選択リストではなく隠し
				// フィールドになることもあります。
				var v = o.options[o.options.selectedIndex].value;
				if (v == '') {
					$('select[name="place_p"]', o.form).each( function() {
						v = this.options[this.options.selectedIndex].value;
					});
					if (v == '') {
						$('input[type="hidden"][name="place_p"]', o.form).each( function() {
							v = this.options[this.options.selectedIndex].value;
						});
					}
				}

				// 市区の選択リストを更新
				$('[select[name="place_cw"]', o.form).each( function() {
					setPlaceCWOptions(this, v);
				});

	}



	//
	// eventChangePlaceP
	//
	function eventChangePlaceP(o) {

				// 都道府県の場所IDを取得
				v = o.options[o.options.selectedIndex].value;

				// 都道府県の選択リストと同じフォームに存在する中小エリアの選択リストを更新
				$('select[name="place_ms"]', o.form).each( function() {
					setPlaceMSOptions(this, v);
					eventChangePlaceMS(this);
				});



	}

	//
	// setPlaceCWOptions
	//
	// 機能
	//    市区の選択リストを指定された中小エリアもしくは都道府県に含まれる市区
	//		に更新します。
	// 引数
	//    o
	//        更新する市区の選択リストをDOMオブジェクトで指定します。
	//    s
	//        中小エリアもしくは都道府県の場所IDを文字列でしています。
	//
	function setPlaceCWOptions(o, s) {

				// 中小エリアもしくは都道府県に含まれる市区エリアを取得
				a = placeRelation[s].cw;

				// 選択リストを生成、初期値を設定
				o.options.length = 0;
				o.options[0] = new Option('全域', '');
				o.options.selectedIndex = 0;
				for(var i in a) {
					p = placeEntity[a[i]];
					if (p.eh == 1) {
						o.options[o.options.length] = new Option(p.n, p.i);
					}
				}

				// 選択リストが全域を含めて二つしかないときは全域ではない方を選択
				if (o.options.lenght == 2) {
					o.options.selectedIndex = 1;
				}
	}



	//
	// setPlaceMSOptions
	//
	// 機能
	//    中小エリアの選択リストを指定された都道府県に含まれる中小エリアに更新
	//    します。
	// 引数
	//    o
	//        更新する中小エリアの選択リストをDOMオブジェクトで指定します。
	//    s
	//        都道府県の場所IDを文字列で指定します。
	//
	function setPlaceMSOptions(o, s) {

				// 都道府県に含まれる中小エリアを取得
				a = placeRelation[s].ms;

				// 選択リストを生成、初期値を設定
				o.options.length = 0;
				o.options[0] = new Option('全域', '');
				o.options.selectedIndex = 0;
				for(var i in a) {
					p = placeEntity[a[i]];
					if (p.eh == 1) {
						o.options[o.options.length] = new Option(p.n, p.i);
					}
				}

				// 選択リストが全域を含めて二つしかないときは全域ではない方を選択
				if (o.options.lenght == 2) {
					o.options.selectedIndex = 1;
				}
	}

	//
	//	eventChangeForm
	//
	function eventChangeForm(o) {

				// フォームの内容をリンクに反映
				reflectLinks(o);

	}

	//
	//	reflectLinks
	//
	function reflectLinks(o) {

				// 反映するフィールドとその値を取得
				var p = {};
				for (i in reflectfields) {
					var j = $(':input[name="' + reflectfields[i] + '"]', o);
					if (j.length > 0) {
						if (typeof(j.attr('checked')) == 'undefined' || j.attr('type') == 'hidden' || j.attr('checked')) {
							p[reflectfields[i]] = j[0].value;
						} else {
							p[reflectfields[i]] = '';
						}
					}
				}

				// フィールドをリンクに反映
				$('.jqs-dqs a').each( function() {
					qs = this.href.split('?');
					q = qs.length == 2 ? BRJ.util.parseQueryString(qs[1]) : {};
					BRJ.util.overset(q, p);
					this.href = this.href.split('?').shift() + ($.param(q).length == 0 ? '' : '?' + $.param(q));
				});

	}

	function removeLinks(o) {

				$('.jqs-dqs a').each( function() {
					qs = this.href.split('?');
					q = qs.length == 2 ? BRJ.util.parseQueryString(qs[1]) : {};
					for (i in reflectfields) {
						delete q[reflectfields[i]];
					}
					this.href = this.href.split('?').shift() + ($.param(q).length == 0 ? '' : '?' + $.param(q));
				});

	}



	function reflectLinksExpand(oExpand, jqTarget) {
		jqTarget.each( function() {
			reflectLinks(this);
		});
	}

	function removeLinksExpand(oExpand, jqTarget) {
		jqTarget.each( function() {
			removeLinks(this);
		});
	}



	// 外部ライブラリへの参照を設定
	var placeRelation = BRJ.Data.Place.Relation;
	var placeEntity   = BRJ.Data.Place.Entity;

	// リンクに反映が必要なフィールドリスト
	var reflectfields = [
				'year_month',
				'day',
				'stay',
				'date_status',
				'rooms',
				'senior',
				'child1',
				'child2',
				'child3',
				'child4',
				'child5',
				'charge_min',
				'charge_max',
				'hotel_category_business',
				'hotel_category_inn',
				'hotel_category_capsule'
	];




}
