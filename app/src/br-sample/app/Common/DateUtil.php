<?php

namespace App\Common;

use App\Models\MastHoliday;
use App\Common\Traits;

/**
 * 日付関連のクラス
 * MEMO: 移植元 lib\Br\Models\Date.php
 * MEMO: 移植元 class name: Br_Models_Date
 */
class DateUtil
{
    use Traits;

    private $date = null; // 基準日時

    // コンストラクタ
    public function __construct($at_date = null)
    {

        // 数字のみの場合はシリアル値とみなし数値型に強制的に変換（sqlで取得した値はすべて文字型になるため）
        if (preg_match('/(^[0-9]+$)|(^-[0-9]+$)/', $at_date)) {
            $at_date = (int)$at_date;
        }

        if (strlen($at_date) == 0) {
            $this->date = time();
        } elseif (gettype($at_date) == 'string') {
            if (strlen($at_date) < 10) {
                throw new \Exception('日付が正しくない形で渡されています。' . $at_date);
            }

            $this->date = strtotime($at_date);

            // 日付型と認識できない場合
            if (!$this->is_date($at_date)) {
                // 日付型と認識しない場合（2月31日など）は月末にまるめる（2月28日）
                if (substr($at_date, 0, 4) . substr($at_date, 4, 2) != $this->to_format('Ym')) {
                    $this->add('M', -1);
                    $this->last_day();
                }
            }
        } else {
            $this->date = $at_date;
        }
    }

    /**
     * 基準日時の設定
     *    基準日時に指定した日付で設定する。
     *  at_date には 年月日の文字列を設定します。
     * example
     * clear(strtotime('2008/05/01'))
     *   > 2008/06/07
     *     >> $this->date が 2008/06/07になる
     */
    public function set($at_date = null)
    {

        // 数字のみの場合はシリアル値とみなし数値型に強制的に変換（sqlで取得した値はすべて文字型になるため）
        if (preg_match('/(^[0-9]+$)|(^-[0-9]+$)/', $at_date)) {
            $at_date = (int)$at_date;
        }

        // 指定がない場合、Unix タイムスタンプ をセット
        if (empty($at_date)) {
            $this->date = time();
        } elseif (gettype($at_date) == 'string') {
            $this->date = strtotime($at_date);
            if (strlen($at_date) < 10) {
                throw new \Exception('日付が正しくない形で渡されています。' . $at_date);
            }

            // 日付型と認識できない場合
            if (!$this->is_date($at_date)) {
                // 日付型と認識しない場合（2月31日など）は月末にまるめる（2月28日）
                if (substr($at_date, 0, 4) . substr($at_date, 4, 2) != $this->to_format('Ym')) {
                    $this->add('M', -1);
                    $this->last_day();
                }
            }
        } else {
            $this->date = $at_date;
        }

        return true;
    }

    /**
     * 基準日時の取得
     *    基準日時に指定した日付で設定する。
     *    at_date には 年月日の文字列を設定します。
     *    example
     *        > 2008/06/07
     *        >> $this->date が 2008/06/07になる
     *            clear(strtotime('2008/05/01'))
     *
     * @return
     */
    public function get()
    {
        return $this->date;
    }

    /**
     * 日付の確認
     *
     * 文字列の日付に正しいか確認する。
     *
     *  as_time には 年月日の文字列を設定します。
     *
     * example
     *
     * is_date('20080501')
     * is_date('20080501 235959')
     * is_date('2008-05-01')
     * is_date('2008/05/01 23/59/59')
     *
     *   > strtotime('2008/02/30')
     *     >> true
     */
    public function is_date($as_date)
    {
        // 数値の場合正常
        if (gettype($as_date) == 'integer') {
            return true;
        }

        // YYYYMMDD形式の場合
        if (preg_match('/^[0-9]{8}$/', $as_date)) {
            $year  = substr($as_date, 0, 4);
            $month = substr($as_date, 4, 2);
            $day   = substr($as_date, 6, 2);

            // YYYYMMDD HH24MI 形式などの場合
        } elseif (preg_match('/^[0-9]{8} [0-9]{4}$/', $as_date)) {
            $year   = substr($as_date, 0, 4);
            $month  = substr($as_date, 4, 2);
            $day    = substr($as_date, 6, 2);
            $hour   = substr($as_date, 9, 2);
            $minits = substr($as_date, 11, 2);

            // YYYYMMDD HH24MISS 形式などの場合
        } elseif (preg_match('/^[0-9]{8} [0-9]{6}/', $as_date)) {
            $year   = substr($as_date, 0, 4);
            $month  = substr($as_date, 4, 2);
            $day    = substr($as_date, 6, 2);
            $hour   = substr($as_date, 9, 2);
            $minits = substr($as_date, 11, 2);
            $secont = substr($as_date, 13, 2);

            // YYYY/MM/DD YYYY-MM-DD形式などの場合
        } elseif (preg_match('/^[0-9]{4}.[0-9]{2}.[0-9]{2}$/', $as_date)) {
            $year  = substr($as_date, 0, 4);
            $month = substr($as_date, 5, 2);
            $day   = substr($as_date, 8, 2);

            // YYYY/MM/DD HH24 形式などの場合
        } elseif (preg_match('/^[0-9]{4}.[0-9]{2}.[0-9]{2} [0-9]{2}$/', $as_date)) {
            $year   = substr($as_date, 0, 4);
            $month  = substr($as_date, 5, 2);
            $day    = substr($as_date, 8, 2);
            $hour   = substr($as_date, 11, 2);

            // YYYY/MM/DD HH24:MI 形式などの場合
        } elseif (preg_match('/^[0-9]{4}.[0-9]{2}.[0-9]{2} [0-9]{2}.[0-9]{2}$/', $as_date)) {
            $year   = substr($as_date, 0, 4);
            $month  = substr($as_date, 5, 2);
            $day    = substr($as_date, 8, 2);
            $hour   = substr($as_date, 11, 2);
            $minute = substr($as_date, 14, 2);

            // YYYY/MM/DD HH24:MI:SS 形式などの場合
        } elseif (preg_match('/^[0-9]{4}.[0-9]{2}.[0-9]{2} [0-9]{2}.[0-9]{2}.[0-9]{2}$/', $as_date)) {
            $year   = substr($as_date, 0, 4);
            $month  = substr($as_date, 5, 2);
            $day    = substr($as_date, 8, 2);
            $hour   = substr($as_date, 11, 2);
            $minute = substr($as_date, 14, 2);
            $secont = substr($as_date, 17, 2);
        } else {
            return false;
        }

        // 月
        if (!(1 <= $month and $month <= 12)) {
            return false;
        }

        // 日
        if ($month == 4 or $month == 6 or $month == 9 or $month == 11) {
            if (!($day <= 30)) {
                return false;
            }
        } elseif ($month == 2) {
            if (($year % 4) != 0 or ($year % 100) == 0 and ($year % 400) != 0) {
                if (!($day <= 28)) {
                    return false;
                }
            } else {
                if (!($day <= 29)) {
                    return false;
                }
            }
        } else {
            if (!($day <= 31)) {
                return false;
            }
        }

        // 時間が設定されているときのみバリデーション
        if (isset($hour)) {
            // 時間
            if (!(0 <= $hour and $hour <= 23)) {
                return false;
            }

            // 分
            if (!(0 <= $minute and $minute <= 59)) {
                return false;
            }

            // 秒 未定義の場合あり
            if (isset($secont) && !(0 <= $secont and $secont <= 59)) {
                return false;
            }
        }

        return true;
    }

    /**
     * ハイフンかスラッシュの日付のチェック
     *
     *  ・日付が正しくない場合はfalseを返す
     *    ・現行system.phpより
     *    date
     *    YYYY-MM-DD
     *    YYYY/MM/DD
     *    return
     *    true or false
     *
     * @param [type] $date
     * @return void
     */
    public function check_date_ymd($date)
    {
        try {
            // 値がなければnullを返す。
            if ($this->is_empty($date)) {
                return false;
            }

            // チェックパターンの設定
            $pattern  = '/\/|-/';

            // スラッシュ又はハイフンがあれば日付の型チェック
            if (preg_match($pattern, $date)) {
                // YYYY-MM-DD or YYYY/MM/DDどちらかに当てはまるかのチェック
                if (!preg_match("/([0-9]{4})(\/|-)([0-9]{1,2})(\/|-)([0-9]{1,2})/", $date)) {
                    return false;
                }
            } else {
                // 無ければエラー
                return false;
            }

            // 日付の型を揃える為に置換 スラッシュ又はハイフンを抜く
            $date = preg_replace($pattern, '', $date);

            // 数値でない場合、又は8文字出なければそのままの値を返す。
            if (!is_numeric($date) || strlen($date) != 8) {
                return false;
            }

            // 存在する日付かのチェック　※存在しなければnull
            if (!($this->is_date(substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, -2, 2)))) {
                return false;
            }

            return true;

            //各メソッドでExceptionが投げられた場合
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * 加算
     *
     * 基準日時に指定の単位で加算した結果をtime型でかえす。
     *
     *  as_interval には 加算する単位を設定します。
     *  an_number   には 加算する値をを設定します。
     *
     * example
     *   基準日を2008/05/10とする
     *
     * add('Y', 1)
     *
     *   > 'Y'
     *   > 1
     *     >> 2009/05/10 00:00:00
     *
     *   > 'm'
     *   > 1
     *     >> 2008/06/10 00:00:00
     *
     *   > 'd'
     *   > 1
     *     >> 2008/05/11 00:00:00
     *
     *   > 'H'
     *   > 1
     *     >> 2008/05/10 01:00:00
     *
     *   > 'i'
     *   > 1
     *     >> 2008/05/10 00:01:00
     *
     *   > 's'
     *   > 1
     *     >> 2008/05/10 00:00:01
     *
     *   > 's'
     *   > -1
     *     >> 2008/05/09 23:59:59
     *
     *
     *    単位($as_interval)
     *   ・年         : Y
     *   ・月         : m
     *   ・日         : d
     *   ・時(24時間) : H
     *   ・分         : i
     *   ・秒         : s
     */
    public function add($as_interval, $an_number)
    {

        switch (strtolower($as_interval)) {
            case 'y': //年
                $t_date = mktime(
                    date('H', $this->date),          // 時
                    date('i', $this->date),              // 分
                    date('s', $this->date),              // 秒
                    date('m', $this->date),              // 月
                    date('d', $this->date),              // 日
                    date('Y', $this->date) + $an_number  // 年
                );
                $t_first = mktime(
                    date('H', $this->date),              // 時
                    date('i', $this->date),              // 分
                    date('s', $this->date),              // 秒
                    date('m', $this->date),              // 月
                    1,                                   // 日
                    date('Y', $this->date) + $an_number  // 年
                );

                // 年月が翌月になってる
                if (date('Ym', $t_date) != date('Ym', $t_first)) {
                    // 前月の末日をかえす
                    $t_last = mktime(
                        date('H', $t_date), // 時
                        date('i', $t_date), // 分
                        date('s', $t_date), // 秒
                        date('m', $t_date), // 月
                        0,                  // 日
                        date('Y', $t_date)  // 年
                    );

                    $this->date = $t_last;
                    break;
                }

                $this->date = $t_date;
                break;

            case 'm': //月
                $t_date = mktime(
                    date('H', $this->date),              // 時
                    date('i', $this->date),              // 分
                    date('s', $this->date),              // 秒
                    date('m', $this->date) + $an_number, // 月
                    date('d', $this->date),              // 日
                    date('Y', $this->date)               // 年
                );
                $t_first = mktime(
                    date('H', $this->date),              // 時
                    date('i', $this->date),              // 分
                    date('s', $this->date),              // 秒
                    date('m', $this->date) + $an_number, // 月
                    1,                                   // 日
                    date('Y', $this->date)               // 年
                );

                // 年月が翌月になってる
                if (date('Ym', $t_date) != date('Ym', $t_first)) {
                    // 前月の末日をかえす
                    $t_last = mktime(
                        date('H', $t_date), // 時
                        date('i', $t_date), // 分
                        date('s', $t_date), // 秒
                        date('m', $t_date), // 月
                        0,                  // 日
                        date('Y', $t_date)  // 年
                    );
                    $this->date = $t_last;
                    break;
                }

                $this->date = $t_date;
                break;

            case 'd': //日
                $this->date =  mktime(
                    date('H', $this->date),              // 時
                    date('i', $this->date),              // 分
                    date('s', $this->date),              // 秒
                    date('m', $this->date),              // 月
                    date('d', $this->date) + $an_number, // 日
                    date('Y', $this->date)               // 年
                );
                break;

            case 'h': //時（２４）
                $this->date =  mktime(
                    date('H', $this->date) + $an_number, // 時
                    date('i', $this->date),              // 分
                    date('s', $this->date),              // 秒
                    date('m', $this->date),              // 月
                    date('d', $this->date),              // 日
                    date('Y', $this->date)               // 年
                );
                break;

            case 'i': //分
                $this->date =  mktime(
                    date('H', $this->date),              // 時
                    date('i', $this->date) + $an_number, // 分
                    date('s', $this->date),              // 秒
                    date('m', $this->date),              // 月
                    date('d', $this->date),              // 日
                    date('Y', $this->date)               // 年
                );
                break;

            case 's': //秒
                $this->date =  mktime(
                    date('H', $this->date),              // 時
                    date('i', $this->date),              // 分
                    date('s', $this->date) + $an_number, // 秒
                    date('m', $this->date),              // 月
                    date('d', $this->date),              // 日
                    date('Y', $this->date)               // 年
                );
                break;
        }
    }

    /**
     * 月末を設定し返却します。
     */
    public function last_day()
    {
        $date_ymd = $this->to_format('Y-m') . '-01 ' . $this->to_format('H:i:s');
        $this->set($date_ymd);
        $this->add('m', 1);
        $this->add('d', -1);

        return $this->date;
    }

    /**
     * フォーマット
     *
     *   ・年         : Y (YYYY)
     *   ・月         : m (MM)
     *   ・日         : d (DD)
     *   ・時(24時間) : H (HH)
     *   ・時(12時間) : h (HH)
     *   ・分         : i (MM)
     *   ・秒         : s (SS)
     *   ・月         : n (M)
     *   ・日         : j (D)
     *   ・時(24時間) : G (H)
     *   ・時(12時間) : g (HH)
     */
    public function to_format($as_format)
    {
        // 文字列なら
        if (gettype($this->date) == 'string') {

            // Timeに変換してから、結果をかえす。
            return date($as_format, strtotime($this->date));
        }

        return date($as_format, $this->date);
    }

    /**
     * 祝日を判断します。
     */
    public function is_holiday()
    {
        $mastHoliday = new MastHoliday();

        $a_row = $mastHoliday->isHoliday(array('holiday' => date('Y-m-d', $this->date)));

        return !(empty($a_row->holiday_nm));
    }

    /**
     * 祝日名称を取得します。
     */
    public function to_holiday_nm()
    {
        $mastHoliday = new MastHoliday();
        $a_row = $mastHoliday->isHoliday(array('holiday' => date('Y-m-d', $this->date)));

        return $a_row->holiday_nm;
    }

    /**
     * 曜日
     *
     * 基準日時を指定のスタイルで変換した値を文字列型でかえす。
     *
     *  as_style には変換するスタイルを設定します。
     *
     * example
     *   基準日を2008/06/15とする
     * to_week('d')
     *
     *   > 'n'
     *     >> 1
     *   > 'e'
     *     >> Sun
     *   > 'E'
     *     >> Sunday
     *   > 'j'
     *     >> 日
     *   > 'J'
     *     >> 日曜日
     *
     *    スタイル($as_style)
     *   ・数値             : n
     *   ・3文字の英語      : e
     *   ・フルスペルの英語 : E
     *   ・1文字日本語      : j
     *   ・3文字日本語      : J
     */
    public function to_week($as_style)
    {
        $a_week['n'] = array(1,        2,        3,         4,           5,          6,        7);
        $a_week['e'] = array('Sun',    'Mon',    'Tue',     'Wed',       'Thu',      'Fri',    'Sat');
        $a_week['E'] = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        $a_week['j'] = array('日',     '月',     '火',      '水',        '木',       '金',     '土');
        $a_week['J'] = array('日曜日', '月曜日', '火曜日',  '水曜日',    '木曜日',   '金曜日', '土曜日');

        return $a_week[$as_style][date('w', $this->date)];
    }
}
