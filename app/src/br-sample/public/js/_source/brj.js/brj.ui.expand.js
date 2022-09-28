new function() {
BRJ.UI.Expand = {

	initialize: function(parent) {

				$('.jqs-expand', parent).not(':checkbox, :radio').click( function() {
					eventClickExpand(this);
					return false;
				});

				$('.jqs-expand', parent).filter(':checkbox, :radio').change( function() {
					eventChangeExpand(this);
				});

	},

	bind: function(oTarget, fOn, fOff) {

				$(oTarget).unbind();

				$(oTarget).not(':checkbox, :radio').click( function() {
					eventClickExpand(this, fOn, fOff);
					return false;
				});

				$(oTarget).filter(':checkbox, :radio').change( function() {
					eventChangeExpand(this, fOn, fOff);
				});

	}


};

	// アクティブレベルを宣言
	// 表示のアニメーション中やプランの一覧の取得中に実行すると正しい表
	// 示にならないため、重複して実行されないように制限します。
	var activeLevel = 0;


	function eventChangeExpand(odTrigger, fOn, fOff) {

				// アクティブレベルがゼロより大きい場合は実行を制限
				if (activeLevel > 0) {
					return;
				}

				// アクティブレベルを加算
				activeLevel++;

				// 開閉を設定
				if ($(odTrigger).hasClass('jqs-on')) {
					var bExpand = odTrigger.checked;
				} else {
					var bExpand = !odTrigger.checked;
				}

				// 表示を変更
				expandTarget(odTrigger, fOn, fOff, bExpand);

	}


	//
	// クリックされたとき
	//
	function eventClickExpand(odTrigger, fOn, fOff) {

				// アクティブレベルがゼロより大きい場合は実行を制限
				if (activeLevel > 0) {
					return;
				}

				// アクティブレベルを加算
				activeLevel++;

				// 表示を変更
				expandTarget(odTrigger, fOn, fOff);

	}

	function expandTarget(odTrigger, fOn, fOff, bExpand) {


				// 作用する対象要素を選択
				// トリガー要素の名前の末尾に'_box'を付加した名前を持つ要素になります。
				// その要素が存在しない場合はトリガー要素の次の要素になります。
				var jqTarget = $('[name="' + $(odTrigger).attr('name') + '_box"]');
				if (jqTarget.length == 0) {
					var jqTarget = $(odTrigger).next();
					if (jqTarget.length == 0) {
						jqTarget = $(odTrigger).parent().next();
					}
				}

				// 対象要素の表示を変更
				if (bExpand == undefined) {
					jqTarget.slideToggle('first', function() {
						executeAfterEffect(odTrigger, jqTarget, fOn, fOff);
					});
				} else if (bExpand) {
					jqTarget.slideDown('first', function() {
						executeAfterEffect(odTrigger, jqTarget, fOn, fOff);
					});
				} else {
					jqTarget.slideUp('first', function() {
						executeAfterEffect(odTrigger, jqTarget, fOn, fOff);
					});
				}

	}

	function executeAfterEffect(odTrigger, jqTarget, fOn, fOff) {

					// 対象要素の表示が変更された前後の状態を取得
					if (jqTarget.css('display') == 'none') {
						var sBefore = 'open';
						var sAfter  = 'close';
						var fFinally = fOff;
					} else {
						var sBefore = 'close';
						var sAfter  = 'open';
						var fFinally = fOn;
					}

					// トリガー要素の内部の文言（HTML）を変更
					// トリガー要素の名前の末尾にアンダーバーと現在の状態（'_open' or '_close'）を付加した名前を持つ要素の内部のHTMLを使用します。
					// その要素が存在しない場合は変更されません。
					var jsContent = $('[name="' + $(odTrigger).attr('name') + '_msg_' + sAfter  + '"]', jqTarget);
					if (jsContent.length > 0) {
						$(odTrigger).html(jsContent.html());
					}

					// トリガー要素のクラスを変更
					// クラス名の末尾が'_open'または'_close'を持つクラス名がすべて削除されそれらの逆が追加されます。複数あればすべて変更します。
					var buffers = odTrigger.className.match(new RegExp('([a-zA-Z0-9_\-]+_' + sBefore + ')', 'g'));
					if (buffers) {
						for (var i = 0; i < buffers.length; i++){
							var sClass = buffers[i];
							$(odTrigger).removeClass(sClass).addClass(sClass.replace(sBefore, sAfter));
						}
					}

					// プロシージャを実行
					if (fFinally !== undefined) {
						fFinally(odTrigger, jqTarget, sAfter);
					}

					// すべての処理が終了したのでアクティブレベルを減算
					activeLevel--;

	}
};