<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return view('toppage-test');
})->name('ctl.index');


/**
 * 宿泊予約
 */
Route::namespace("App\Http\Controllers\rsv")->prefix("rsv")->group(function(){
	// 施設情報
	Route::controller(HotelController::class)->prefix("hotel")->group(function(){
		Route::get('/{hotel_cd}', 'info')->name('rsv.hotel.info');
	});
});


/**
 * 管理システム
 */
Route::namespace("App\Http\Controllers\ctl")->prefix("ctl")->group(function(){


	// 社内トップ
	Route::controller(BrTopController::class)->prefix("brtop")->group(function(){
		Route::get('/', 'index')->name('ctl.brtop.index');
	});

	// ホテルトップ
	Route::controller(HotelTopController::class)->prefix("htltop")->group(function(){
		Route::get('/', 'index')->name('ctl.htltop.index');
	});

	// 管理画面一覧
	Route::controller(TopController::class)->prefix("top")->group(function(){
		Route::get('/', 'index')->name('ctl.top.index');
	});

	// 銀行支店マスタ
	Route::controller(BrbankController::class)->prefix("brbank")->group(function(){
		Route::get('/', 'index')->name('ctl.brbank.index');
		Route::post('/newbank', 'newbank')->name('ctl.brbank.newbank');
		Route::post('/createbank', 'createbank')->name('ctl.brbank.createbank');
		Route::post('/viewbank', 'viewbank')->name('ctl.brbank.viewbank');
		Route::post('/updatebank', 'updatebank')->name('ctl.brbank.updatebank');
		Route::post('/newbankbranch', 'newbankbranch')->name('ctl.brbank.newbankbranch');
		Route::post('/createbankbranch', 'createbankbranch')->name('ctl.brbank.createbankbranch');
		Route::post('/viewbankbranch', 'viewbankbranch')->name('ctl.brbank.viewbankbranch');
		Route::post('/updatebankbranch', 'updatebankbranch')->name('ctl.brbank.updatebankbranch');
	});

	// 施設管理TOPお知らせ情報管理
	Route::controller(BrbroadcastMessageController::class)->prefix("brbroadcastMessage")->group(function(){
		Route::get('/', 'index')->name('ctl.brbroadcastMessage.index');
		Route::post('/new', 'new')->name('ctl.brbroadcastMessage.new');
		Route::post('/create', 'create')->name('ctl.brbroadcastMessage.create');
		Route::post('/detail', 'detail')->name('ctl.brbroadcastMessage.detail');
		Route::post('/edit', 'edit')->name('ctl.brbroadcastMessage.edit');
		Route::post('/update', 'update')->name('ctl.brbroadcastMessage.update');
		Route::post('/destroy', 'destroy')->name('ctl.brbroadcastMessage.destroy');
	});

	// 予約通知ＦＡＸ広告 掲載文章
	Route::controller(BrfaxPrController::class)->prefix("brfaxPr")->group(function(){
		Route::post('/edit', 'edit')->name('ctl.brfaxPr.edit');
		Route::post('/update', 'update')->name('ctl.brfaxPr.update');
		Route::get('/show', 'show')->name('ctl.brfaxPr.show');
	});

	// 施設情報
	Route::controller(HtlhotelInfoController::class)->prefix("brhotelInfo")->group(function(){
		Route::match(['get','post'], '/show', 'show')->name('ctl.htlhotelInfo.show');
		Route::match(['get','post'], '/new', 'new')->name('ctl.htlhotelInfo.new');
		Route::match(['get','post'], '/edit', 'edit')->name('ctl.htlhotelInfo.edit');
		Route::post('/create', 'create')->name('ctl.htlhotelInfo.create');
		Route::post('/update', 'update')->name('ctl.htlhotelInfo.update');
	});

    // 施設情報メイン
    Route::controller(BrhotelController::class)->prefix("brhotel")->group(function(){
        Route::get('/hotelsearch', 'hotelsearch')->name('ctl.brhotel.hotelsearch'); //宿泊施設検索
        Route::match(['get','post'], '/new', 'new')->name('ctl.brhotel.new');

        Route::match(['get','post'], '/edit', 'edit')->name('ctl.brhotel.edit');
        Route::post('/update', 'update')->name('ctl.brhotel.update'); //施設更新？

        Route::get('/index', 'index')->name('ctl.brhotel.index'); // 検索 初期表示
        Route::match(['get','post'],'/show', 'show')->name('ctl.brhotel.show'); // 詳細変更 施設各情報ハブ

        Route::get('/searchcity', 'searchcity')->name('ctl.brhotel.searchcity'); // 検索部品 市プルダウン
        Route::get('/searchward', 'searchward')->name('ctl.brhotel.searchward'); // 検索部品 区プルダウン

        Route::post('/createnote', 'createnote')->name('ctl.brhotel.createnote'); //施設管理特記事項
        Route::post('/updatenote', 'updatenote')->name('ctl.brhotel.updatenote'); //

    });

    // 施設情報変更 登録状態変更
    Route::controller(BrhotelStatusController::class)->prefix("brhotelStatus")->group(function(){
        Route::match(['get','post'],'/index', 'index')->name('ctl.brhotelStatus.index'); //表示
        Route::post('/update', 'update')->name('ctl.brhotelStatus.update'); //更新処理
    });

    // 料率マスタ
    Route::controller(BrhotelRateController::class)->prefix("brhotelRate")->group(function(){
        Route::match(['get','post'],'/index', 'index')->name('ctl.brhotelRate.index'); //表示
        Route::match(['get','post'],'/edit', 'edit')->name('ctl.brhotelRate.edit'); //更新 表示
        Route::post('/update', 'update')->name('ctl.brhotelRate.update'); //更新処理
        Route::match(['get','post'],'/new', 'new')->name('ctl.brhotelRate.new'); //新規 表示
        Route::post('/create', 'create')->name('ctl.brhotelRate.create'); //新規処理
        Route::post('/destroy', 'destroy')->name('ctl.brhotelRate.destroy'); //削除処理
    });


	// パートナー精算先
	Route::get('/brpartnercustomer/', 'BrPartnerCustomerController@index')->name('brpartnercustomer.index');
	Route::get('/brpartnercustomer/search', 'BrPartnerCustomerController@search')->name('brpartnercustomer.search');

	Route::get('/brpartnercustomer/create', 'BrPartnerCustomerController@create')->name('brpartnercustomer.create');
	Route::post('/brpartnercustomer/register', 'BrPartnerCustomerController@register')->name('brpartnercustomer.register');

	// TODO: 要調査 クエリパラメータに ? をつけて任意にしないとレンダリングが通らなくなっている。
	// ルートパラメータではなく通常のクエリパラメータにしたほうがスッキリするように思われる。
	Route::get('/brpartnercustomer/edit/{customer_id?}', 'BrPartnerCustomerController@edit')->name('brpartnercustomer.edit');
	Route::post('/brpartnercustomer/modify', 'BrPartnerCustomerController@modify')->name('brpartnercustomer.modify');


	// 精算サイト
	Route::get('/brpartnersite/', 'BrPartnerSiteController@index');
	Route::get('/brpartnersite/search', 'BrPartnerSiteController@search')->name('ctl.brPartnerSite.search');
	Route::get('/brpartnersite/edit', 'BrPartnerSiteController@edit')->name('ctl.brPartnerSite.edit');
	Route::post('/brpartnersite/modify', 'BrPartnerSiteController@modify')->name('ctl.brPartnerSite.modify');

});
