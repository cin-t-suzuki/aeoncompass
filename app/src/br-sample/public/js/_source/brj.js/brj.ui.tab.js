new function() {

	BRJ.UI.Tab = {

		initialize: function(o) {

				j = $('.jqs-tab', o);
				if (j.length > 0) {

					j.filter('.jqs-tab:radio').click( function() {
						eventClickTabRadio(this);
					});

					j.not('.jqs-tab:radio').click( function() {
						eventClickTab(this);
						return false;
					});

					// 保存されている選択されたタブを反映
					defaultset = $.cookies.get('ui-tab') || {};
					for (var i in defaultset) {
						j.filter('[name="' + defaultset[i] + '"]').click();
						j.filter(':radio[name="' + i + '"]').each( function() {
							if(this.name + '_' + this.value == defaultset[i]) {
								this.checked = true;
								$(this).click();
							}
						});
					}
				}


		}

	}


	// jqs-save
	// jqs-saveoff
	// jqs-savehold

	var defaultset = {};

	function eventClickTabRadio(odRadio) {

				var jqRadio = $(odRadio);
				var sTagGroup = jqRadio.attr('name');
				var sTagName = sTagGroup + '_' + jqRadio.val();

				$('.jqs-tab[name="' + sTagGroup + '"]').removeClass('current');
				$('[name^="' + sTagGroup + '_"][name$="_box"]').hide();
				$('[name="' + sTagName + '_box"]').show();
				jqRadio.addClass('current');

				// ラベルのスタイルを変更
				$('.jqs-tab:radio[name="' + sTagGroup + '"]').each( function() {
					$('label[for="' + $(this).attr('id') + '"]').removeClass('current');
				});
				$('label[for="' + jqRadio.attr('id') + '"]').addClass('current');

				// 選択されたタブをクッキーに保存
				if($('.jqs-tab.jqs-saveoff[name^="' + sTagGroup + '"]').length == 0) {
					defaultset[sTagGroup] = sTagName;
					$.cookies.set('ui-tab', defaultset);
				}

	}

	function eventClickTab(o) {

				if (!$(o).hasClass('current')) {

					var tagname = $(o).attr('name');
					var taggroup = tagname.split('_')[0];

					$('.jqs-tab[name^="' + taggroup + '_"]').removeClass('current').removeClass('active');
					$('[name^="' + taggroup + '_"][name$="_box"]').hide();
					$('[name="' + tagname + '_box"]').show();
					$(o).addClass('current').addClass('active');

					// タブ画像の切り替え
					$('.jqs-tab[name^="' + taggroup + '_"]').each( function() {
						if ($(this).hasClass('btnimg')) {
							$('img', $(this)).each( function() {
								this.src = this.src.replace(/(-current)(?=\.(gif|png|jpg)$)/, '');
							});

						}
					});
					if ($(o).hasClass('btnimg')) {
						$('img', $(o)).each( function() {
							this.src = this.src.replace(/(\_[A-Za-z0-9]+|)(?=\.(gif|png|jpg)$)/, ('-current'));
						});
					}


					// 選択されたタブをクッキーに保存
				if($('.jqs-tab.jqs-saveoff[name^="' + taggroup + '"]').length == 0) {
						defaultset[taggroup] = tagname;
						$.cookies.set('ui-tab', defaultset);
					}

				}

	}

}
