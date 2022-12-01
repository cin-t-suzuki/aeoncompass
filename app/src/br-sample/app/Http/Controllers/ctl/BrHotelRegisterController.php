<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use App\Models\MastCity;
use App\Models\MastPref;
use App\Models\MastWard;
use App\Models\Hotel;
use App\Models\HotelControl;
use App\Services\BrHotelRegisterService as Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// MEMO: 移植元では、 BrhotelController に一緒くたにされていた。
class BrHotelRegisterController extends Controller
{
    public function new(Request $request)
    {
        $guides = ['施設登録の際はウィザードに添ってSTEP 6/6 まで必ず完了してください。'];

        // 登録処理からの戻りで session にデータがある場合、 old を使って取得
        // そうでない場合、初期表示（第2引数でデフォルト値を指定）
        // HACK: （工数次第）画面側で old ヘルパ関数を使うほうが一般的と思われる（更新処理と合わせての修正が必要）
        $a_hotel = $request->old('Hotel', [
            'hotel_cd'          => null,
            'hotel_category'    => null,
            'hotel_nm'          => null,
            'hotel_kn'          => null,
            'hotel_old_nm'      => null,
            'postal_cd'         => null,
            'pref_id'           => null,
            'city_id'           => null,
            'ward_id'           => null,
            'address'           => null,
            'tel'               => null,
            'fax'               => null,
            'room_count'        => null,
            'check_in'          => null,
            'check_in_end'      => null,
            'check_in_info'     => null,
            'check_out'         => null,
            'midnight_status'   => null,
        ]);
        $a_hotel_control = $request->old('Hotel_Control', [
            'stock_type' => null,
        ]);

        if (!array_key_exists('city_id', $a_hotel)) {
            $a_hotel['city_id'] = null;
        }
        if (!array_key_exists('ward_id', $a_hotel)) {
            $a_hotel['ward_id'] = null;
        }

        // 都道府県
        $a_mast_prefs = (new MastPref())->getMastPrefs();
        // 市
        $a_mast_cities = ['values' => []];
        if (!is_null($a_hotel['pref_id'])) {
            $a_mast_cities = (new MastCity())->getMastCities($a_hotel['pref_id']);
        }
        // 区
        $a_mast_wards = null;
        if (!is_null($a_hotel['city_id'])) {
            $a_mast_wards = (new MastWard())->getMastWards($a_hotel['city_id']);
        }

        $errors = $request->session()->get('errors', []);

        return view('ctl.brhotel.new', [
            'action'        => 'new',
            'guides'        => $guides,
            'errors'        => $errors,

            'mast_prefs'    => $a_mast_prefs,
            'mast_cities'   => $a_mast_cities,
            'mast_wards'    => $a_mast_wards,

            'hotel'         => $a_hotel,
            'hotel_control' => $a_hotel_control,
            'target_cd'     => $request->input('target_cd'),
        ]);
    }

    public function create(Request $request, Service $service)
    {
        $a_hotel = $request->input('Hotel');
        $a_hotel_control = $request->input('Hotel_Control');

        /*
            validation
         */
        // TODO: 正規表現のバリデーション
        $rules = [
            // 'Hotel.hotel_cd'          => ['required', 'regex:/\A[^ｦ-ﾟ]*\z/', 'between:0,10'],
            'Hotel.hotel_category'    => ['regex:/\A(a|b|c|j)\z/'],
            'Hotel.hotel_nm'          => [
                'required',
                // 'regex:/\A[^ｦ-ﾟ]*\z/',
                'between:0,50'
            ],
            'Hotel.hotel_kn'          => [
                'required',
                // 'regex:/\A[ァ-ヶ　ー ]*\z/',
                'between:0,150'
            ],
            'Hotel.hotel_old_nm'      => [
                // 'regex:/\A[^ｦ-ﾟ]*\z/',
                'between:0,50'
            ],
            'Hotel.postal_cd'         => ['required', 'regex:/\A[^ｦ-ﾟ]*\z/', 'regex:/\A\d{3}[-]\d{4}\z/'],
            'Hotel.pref_id'           => ['required', 'integer', 'numeric', 'between:0,50'], // TODO: between
            'Hotel.city_id'           => ['required', 'integer', 'numeric', 'between:0,50000'], // TODO: between
            'Hotel.ward_id'           => ['integer', 'numeric', 'between:0,50000'], // TODO: between
            'Hotel.address'           => [
                'required',
                // 'regex:/\A[^ｦ-ﾟ]*\z/',
                'between:0,100'
            ],

            // custom validation TODO: 電話番号、郵便番号
            'Hotel.tel'               => ['required', 'regex:/(\A0\d{1,4}?-\d{1,4}?-\d{1,4}\z|\A\d{9,12}\z)/'],
            'Hotel.fax'               => ['regex:/(\A0\d{1,4}?-\d{1,4}?-\d{1,4}\z|\A\d{9,12}\z)/'],
            'Hotel.room_count'        => ['integer', 'numeric', 'between:0,9999'],
            'Hotel.check_in'          => ['required', 'regex:/\A\d{2}:\d{2}\z/'],
            'Hotel.check_in_end'      => ['regex:/\A\d{2}:\d{2}\z/'], // TODO: 独自チェック
            'Hotel.check_in_info'     => [
                // 'regex:/\A[^ｦ-ﾟ]*\z/',
                'between:0,75'
            ],
            'Hotel.check_out'         => ['required', 'regex:/\A\d{2}:\d{2}\z/'],
            'Hotel.midnight_status'   => ['required', 'regex:/\A(0|1)\z/'],

            'Hotel_Control.stock_type'  => [],
        ];

        // TODO:
        $messages = [
            // 'Hotel.hotel_category.regex' => ':attribute を適切に選択してください。',
            // 'Hotel.hotel_nm.regex' => ':attribute に半角カナが含まれています。',
            // 'Hotel.hotel_kn.regex' => ':attribute に半角カナが含まれています。',
            'regex' => '[:attribute] に正規表現違反があります。',
        ];
        $attributes = [
            'Hotel.hotel_cd'        => '施設コード',
            'Hotel.order_no'        => '表示順序',
            'Hotel.hotel_category'  => '施設区分',
            'Hotel.hotel_nm'        => '施設名称',
            'Hotel.hotel_kn'        => '施設名称かな',
            'Hotel.hotel_old_nm'    => '旧施設名称',
            'Hotel.postal_cd'       => '郵便番号',
            'Hotel.pref_id'         => '都道府県',
            'Hotel.city_id'         => '市',
            'Hotel.ward_id'         => '区',
            'Hotel.address'         => '住所',
            'Hotel.tel'             => '電話番号',
            'Hotel.fax'             => 'ＦＡＸ番号',
            'Hotel.room_count'      => '保有部屋数',
            'Hotel.check_in'        => 'チェックイン開始時刻',
            'Hotel.check_in_end'    => 'チェックイン終了時刻',
            'Hotel.check_in_info'   => 'チェックイン時刻コメント',
            'Hotel.check_out'       => 'チェックアウト時刻',
            'Hotel.midnight_status' => '深夜受付状態',
            'Hotel.accept_status'   => '予約受付状態',
            'Hotel.accept_auto'     => '予約受付状態自動更新有無',
            'Hotel.accept_dtm'      => '予約受付状態更新日時',
            'Hotel_Control.stock_type' => '仕入タイプ',
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $attributes);
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->with([
                    'errors' => $validator->errors()->all(),
                ]);
        }

        /*
            データ整形
         */
        // hotel_cd 採番
        // TODO: AC 用にカスタマイズが必要か。
        if ($a_hotel_control['stock_type'] == HotelControl::STOCK_TYPE_SANPU) {
            // 三普用の施設の場合
            $hotelCd = $service->getHotelCdSanpu();
        } else {
            // 受託販売  買取販売の場合
            $hotelCd = $service->getHotelCd();
        }

        // hotel
        $hotel = $service->makeHotelData($hotelCd, $a_hotel);

        // hotel_insurance_weather
        $hotelInsuranceWeather = $service->makeHotelInsuranceWeatherData($hotelCd, $hotel);

        // deny_list
        $denyLists = $service->makeDenyListsData($hotelCd);

        /*
            DB登録処理
         */
        $errorMessages = $service->store($hotel, $hotelInsuranceWeather, $denyLists);
        if (count($errorMessages) > 0) {
            return redirect()->back()
                ->withInput()
                ->with([
                    'errors' => $errorMessages,
                ]);
        }

        // 結果表示用データ取得
        $a_hotel = (new Hotel())->selectByKey($hotelCd);

        // 都道府県
        $a_mast_pref = null;
        $a_mast_pref = (new MastPref())->selectByKey($a_hotel['pref_id']);

        // 市
        $a_mast_city = null;
        if (array_key_exists('city_id', $a_hotel)) {
            $a_mast_city = (new MastCity())->selectByKey($a_hotel['city_id']);
        }

        // 区
        $a_mast_ward = null;
        if (array_key_exists('ward_id', $a_hotel)) {
            $a_mast_ward = (new MastWard())->selectByKey($a_hotel['ward_id']);
        }

        return view('ctl.brhotel.create', [
            'hotel'             => $a_hotel,
            'a_mast_pref'       => $a_mast_pref,
            'a_mast_city'       => $a_mast_city,
            'a_mast_ward'       => $a_mast_ward,
            'target_cd'         => $request->input('target_cd'),
            'target_stock_type' => $a_hotel_control['stock_type'],
        ]);
    }

    public function management(Request $request)
    {
        return 'TODO: br hotel register controller management';
    }
}
