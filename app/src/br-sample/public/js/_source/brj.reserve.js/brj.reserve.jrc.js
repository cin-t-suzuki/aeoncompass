new (function () {
    BRJ.Reserve.Jrc = {
        //
        //	initialize
        //
        //	機能	JRコレクションのフォームを初期化します。
        //
        initialize: function () {
            // おでかけネットからの遷移ではJRコレクション関係を非表示
            if ($.cookies.get('CP') == '1169008784') {
                $('.jqs-jrc').hide()
                return
            }

            var jf = getTargetForm()
            if (jf.length > 0) {
                // フォームのイベントを設定
                jf.submit(function () {
                    return eventSubmitForm(this)
                })

                //
                // 年月日の選択ボックス
                //

                // 取扱期間を算出
                // 0時以降12時までは、3日後から2ヶ月後の同日まで。
                // 12時以降0時までは、4日後から2ヶ月後の同日まで。
                var d = new Date()
                if (0 <= d.getHours() && d.getHours() < 12) {
                    var ds = new Date(
                        d.getFullYear(),
                        d.getMonth(),
                        d.getDate() + 3
                    )
                } else {
                    var ds = new Date(
                        d.getFullYear(),
                        d.getMonth(),
                        d.getDate() + 4
                    )
                }
                var df = new Date(
                    d.getFullYear(),
                    d.getMonth() + 2,
                    d.getDate()
                )
                var dn = new Date(d.getFullYear(), d.getMonth() + 2, 1)
                while (
                    df.getFullYear() != dn.getFullYear() ||
                    df.getMonth() != dn.getMonth()
                ) {
                    df.setDate(df.getDate() - 1)
                }
                startYMD = ds
                finalYMD = df

                // 初期値を設定
                defaultYMD = ds

                // 年月の選択ボックスを抽出
                var jym = $('select[name="year_month"]', jf)

                // 年月の選択ボックスの選択肢を生成、イベントを設定、イベントを実行
                jym.each(function () {
                    checkin.setYearMonthOptions(
                        this,
                        startYMD,
                        finalYMD,
                        defaultYMD
                    )
                })
                jym.change(function () {
                    eventChangeYM(this)
                })
                jym.change()

                // クエリーストリングから初期値を算出して選択
                self.selectYMDByQueryString(location.search)

                //
                // 方面、都道府県の選択ボックス
                //

                // 方面、都道府県の選択ボックスを抽出
                var jdict = $('select[name="dict"]', jf)
                var jpref = $('select[name="pref"]', jf)
                if (jdict.length + jpref.length > 0) {
                    // ページアドレスから初期値を設定
                    var spref = ''
                    var sdict = ''
                    var spath = location.pathname
                    var m = spath.match(/\/area\/l([0-9]{1,2})\/$/)
                    if (m) {
                        // 地図の大エリア（地方）
                        spref = ''
                        sdict = m[1]
                    } else {
                        var m = spath.match(/\/area\/([0-4][0-9])\/$/)
                        if (m) {
                            // 地図の都道府県エリア
                            spref = m[1] * 1 + ''
                            sdict = area.getLAreaParentP(spref).i
                        } else {
                            var m = spath.match(/\/area\/m([0-9]{1,3})\/$/)
                            if (m) {
                                // 地図の中エリア
                                spref = area.getPAreaParentM(m[1]).i
                                sdict = area.getLAreaParentP(spref).i
                            }
                        }
                    }

                    // 沖縄にはプランが存在しないので初期値から除外
                    if (sdict == '12') {
                        spref = ''
                        sdict = ''
                    }

                    // 方面の選択ボックスの選択肢を生成、イベントを設定
                    jdict.each(function () {
                        setDictOptions(this, sdict)
                    })
                    jdict.change(function () {
                        eventChangeDict(this)
                    })
                    jdict.change()

                    // 方面の選択ボックスを生成、イベントを設定
                    if (spref.length > 0) {
                        jpref.each(function () {
                            setPrefOptions(this, spref, sdict)
                            eventChangePref(this)
                        })
                    }
                    jpref.change(function () {
                        eventChangePref(this)
                    })
                }
            }
        },

        selectYMDByQueryString: function (s) {
            // クエリストリングからチェックイン年月日を検索
            var m = s.match(/check_in_ymd=([0-9]{4})-([0-9]{2})-([0-9]{2})/)

            // チェックイン年月日が見つかった場合
            if (m) {
                var jf = getTargetForm()

                // チェックイン年月日が取扱期間かを確認
                var d = new Date(m[1], m[2] - 1, m[3])
                if (startYMD <= d && d <= finalYMD) {
                    defaultYMD = d

                    // 年月を選択
                    var sym = m[1] + '-' + m[2]
                    $('select[name="year_month"]', jf).each(function () {
                        for (var i = 0; i < this.options.length; i++) {
                            if (this.options[i].value == sym) {
                                this.options.selectedIndex = i
                                $(this).change()
                                break
                            }
                        }
                    })

                    // 日を選択
                    $('select[name="day"]', jf).each(function () {
                        for (var i = 0; i < this.options.length; i++) {
                            if (this.options[i].value == m[3]) {
                                this.options.selectedIndex = i
                                break
                            }
                        }
                    })
                } else {
                    // JRコレクション関連のオブジェクトを非表示
                    $('.jqs-jrc').hide()
                }
            }
        }
    }

    // 自身への参照を設定
    var self = BRJ.Reserve.Jrc

    // 外部ライブラリへの参照を設定
    var area = BRJ.Reserve.Area
    var checkin = BRJ.Reserve.CheckInSelector

    // 関連日付の保持変数を宣言
    var startYMD // 取扱期間の開始年月日
    var finalYMD // 取扱期間の最終年月日
    var defaultYMD // 規定の選択年月日

    var getTargetForm = function () {
        return $('form[action$="/jrc/"]')
    }

    //
    //	eventChangeDict
    //
    //	機能	方面の選択ボックスの値によって都道府県の選択ボックスの選択肢を変更します。
    //				通常、方面の選択ボックスのchangeイベントで実行します。
    //	引数	os : 方面の選択ボックスをDOMオブジェクトで指定します。
    //
    var eventChangeDict = function (os) {
        var js = $('select[name="pref"]', $(os.form))
        var sdict = os.options[os.options.selectedIndex].value
        js.each(function () {
            setPrefOptions(this, '', sdict)
            eventChangePref(this)
        })
    }

    //
    //	eventChangePref
    //
    //	機能	都道府県の選択ボックスの値によってエリアの選択ボックスの選択肢を変更します。
    //				通常、都道府県の選択ボックスのchangeイベントで実行します。
    //				なお、エリアは日本旅行のエリアになります。
    //	引数	os : 都道府県の選択ボックスをDOMオブジェクトで指定します。
    //
    var eventChangePref = function (os) {
        var js = $('select[name="area"]', $(os.form))
        var spref = os.options[os.options.selectedIndex].value
        js.each(function () {
            setAreaOptions(this, '', spref)
        })
    }

    //
    //	eventChangeYM
    //
    //	機能	年月の選択ボックスの値によって日の選択ボックスの選択肢を変更します。
    //				通常、年月の選択ボックスのchangeイベントで実行します。
    //	引数	os : 年月の選択ボックスをDOMオブジェクトで指定します。
    //
    var eventChangeYM = function (os) {
        var js = $('select[name="day"]', $(os.form))
        var dym = new Date()
        dym.setStringBRJ(os.options[os.options.selectedIndex].value)
        js.each(function () {
            checkin.setDateOptions(this, dym, defaultYMD, startYMD, finalYMD)
        })
    }

    var eventSubmitForm = function (of) {
        var p = {
            SiteCode: '00574251',
            compCode: 'BRV,TYK'
        }
        $(':input', $(of)).each(function () {
            var sn = $(this).attr('name')
            var sv = $(this).val()

            if (sv.length > 0) {
                // 出発年月
                if (sn == 'year_month') {
                    if (p.SelectDay) {
                        p.SelectDay = sv.replace('-', '') + p.SelectDay
                    } else {
                        p.SelectDay = sv.replace('-', '')
                    }
                }

                // 出発日
                else if (sn == 'day') {
                    if (p.SelectDay) {
                        p.SelectDay = p.SelectDay + sv.replace('-', '')
                    } else {
                        p.SelectDay = sv.replace('-', '')
                    }
                } else if (sn == 'dept') {
                    p.Departure = sv
                } // 出発地（Departure）
                else if (sn == 'dict') {
                    p.Direction = area.getLArea(sv).ia
                } // 方面（Direction）
                else if (sn == 'pref') {
                    p.Pref = ('0' + sv).slice(-2)
                } // 都道府県(Pref）
                else if (sn == 'area') {
                    p.Area = sv
                } // エリア（Area）
                else if (sn == 'guest') {
                    p.Pax = sv
                } // 1室人数（Pax）
                else if (sn == 'hotel_cd') {
                    p.HotelCD = sv
                } // 施設コード（HotelCD）

                // 泊数（StayNights）
                // 部屋数（NumberOfRooms）
                // プランコード（PlanCD）
                // 部屋タイプコード（RoomTypeCD）
            }
        })

        if (p.HotelCD) {
            p.PageType = 'hotel'
        } else {
            p.PageType = 'index'
        }

        // パネルを非表示
        BRJ.UI.Panel.close()

        // 別ウインドウでJRコレクションのページを表示
        window.open('/jrc/?' + $.param(p), '_blank')

        return false
    }

    //
    //	setAreaOptions
    //
    //	機能	エリアの選択ボックスの選択肢を生成します。
    //				なお、エリアは日本旅行のエリアになります。
    //	引数	os		: エリアの選択ボックスをDOMオブジェクトで指定します。
    //				sdef	: 選択されるエリアIDを文字列または数値で指定します。
    //				sdict : エリアを抽出する都道府県IDを文字列または数値で指定します。
    //
    var setAreaOptions = function (os, sdef, spref) {
        // 中エリア（エリア）を取得
        var m = area.getMAreasNTAInP(spref)

        // 選択肢を生成、初期値を設定
        os.options.length = 0
        os.options[0] = new Option('指定なし', '')
        os.options.selectedIndex = 0
        if (spref != 47) {
            for (var i in m) {
                os.options[os.options.length] = new Option(m[i].na, m[i].ia)
                if (m[i].ia == sdef)
                    os.options.selectedIndex = os.options.length - 1
            }
        }

        // 選択肢が指定なしを含めてふたつになったときは指定なしではない方を選択
        if (os.options.selectedIndex == 0 && os.options.length == 2) {
            os.options.selectedIndex = os.options.length - 1
        }
    }

    //
    //	setDispOptions
    //
    //	機能	地方（方面）の選択ボックスの選択肢を生成します。
    //	引数	os	 : 地方の選択ボックスをDOMオブジェクトで指定します。
    //				sdef : 選択される大エリアIDを文字列または数値で指定します。
    //
    var setDictOptions = function (os, sdef) {
        // 大エリア（地方）を取得
        var l = area.getLAreas()

        // 選択肢を生成、初期値を設定
        os.options.length = 0
        os.options[0] = new Option('指定なし', '')
        os.options.selectedIndex = 0
        for (var i in l) {
            if (i != 12) {
                os.options[os.options.length] = new Option(l[i].n, l[i].i)
                if (l[i].i == sdef)
                    os.options.selectedIndex = os.options.length - 1
            }
        }

        // 選択肢が指定なしを含めてふたつになったときは指定なしではない方を選択
        if (os.options.selectedIndex == 0 && os.options.length == 2) {
            os.options.selectedIndex = os.options.length - 1
        }
    }

    //
    //	setPrefOptions
    //
    //	機能	都道府県の選択ボックスの選択肢を生成します。
    //	引数	os		: 方面の選択ボックスをDOMオブジェクトで指定します。
    //				sdef	: 選択される都道府県IDを文字列または数値で指定します。
    //				sdict : 都道府県を抽出する大エリアIDを文字列または数値で指定します。
    //
    var setPrefOptions = function (os, sdef, sdict) {
        // 都道府県エリアを取得
        var p = area.getPAreasInL(sdict)

        // 選択肢を生成、初期値を設定
        os.options.length = 0
        os.options[0] = new Option('指定なし', '')
        os.options.selectedIndex = 0
        for (var i in p) {
            if (i != 47) {
                os.options[os.options.length] = new Option(p[i].n, p[i].i)
                if (p[i].i == sdef)
                    os.options.selectedIndex = os.options.length - 1
            }
        }

        // 選択肢が指定なしを含めてふたつになったときは指定なしではない方を選択
        if (os.options.selectedIndex == 0 && os.options.length == 2) {
            os.options.selectedIndex = os.options.length - 1
        }
    }
})()
