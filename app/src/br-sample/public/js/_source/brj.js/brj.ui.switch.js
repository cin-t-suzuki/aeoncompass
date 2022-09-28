new function() {
BRJ.UI.Switch = {

	initialize: function(parent) {

				$('.jqs-switch:checkbox, .jqs-switch:radio').change( function() {
						eventChangeSwitch(this);
				});
	}

};


	function eventChangeSwitch(odTrigger, fOn, fOff) {

				// 作用する対象要素を選択
				// トリガー要素の名前の末尾に'_box'を付加した名前を持つ要素になります。
				// その要素が存在しない場合はトリガー要素の次の要素になります。
				var sName = $(odTrigger).attr('name')
				var jqTarget = $('[name="' + sName + '_box"]');
				if (jqTarget.length == 0) {
					var jqTarget = $(odTrigger).next();
				}

				// 使用可否を設定
				if ($(odTrigger).hasClass('jqs-on')) {
					var bDisable = !odTrigger.checked;
				} else {
					var bDisable = odTrigger.checked;
				}

				// 使用可否によって処理後に実行されるプロシージャを選定
				if (bDisable) {
					fFinary = fOff;
				} else {
					fFinary = fOn;
				}

				// 対象要素内の入力要素のすべてを使用可否を設定
				$(':input', jqTarget).attr('disabled', (bDisable ? 'disabled' : false));

				// 対象要素内の画像要素のすべてを使用可否に応じて画像を変更
				$('img, :image', jqTarget).each( function() {
					this.src = BRJ.Util.changeFileSufix(this.src, (bDisable ? 'disable' : ''));
				});

				// プロシージャを実行
				if (fFinary) {
					fFinary(odTrigger, bDisable);
				}

	}

};