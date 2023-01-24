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
    Route::get('/', [\App\Http\Controllers\ctl\BrtopController::class,'index'])->name('ctl.brtop.index');
    Route::post('brtop/inspect', [\App\Http\Controllers\ctl\BrtopController::class, 'inspect'])->name('ctl.brtop.inspect');
    Route::post('brtop/registration', [\App\Http\Controllers\ctl\BrtopController::class, 'registration'])->name('ctl.brtop.registration');
    Route::post('brtop/payment', [\App\Http\Controllers\ctl\BrtopController::class, 'payment'])->name('ctl.brtop.payment');

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

    Route::get('/htltop/index/target_cd/{target_cd}', function ($targetCd) {return 'TODO: htl top index : ' . $targetCd; })->name('ctl.htl_top.index');

    Route::match(['get', 'post'], '/htlHotel/show'              , [\App\Http\Controllers\ctl\HtlHotelController::class, 'show'])->name('ctl.htl_hotel.show');
    Route::match(['get', 'post'], '/htlHotel/edit/'             , [\App\Http\Controllers\ctl\HtlHotelController::class, 'edit'])->name('ctl.htl_hotel.edit');
    Route::match(['get', 'post'], '/htlHotel/update/'           , [\App\Http\Controllers\ctl\HtlHotelController::class, 'update'])->name('ctl.htl_hotel.update');
    Route::match(['get', 'post'], '/htlHotelCard/show/'         , function(){ return 'TODO:'; })->name('ctl.htl_hotel_card.show');
    Route::match(['get', 'post'], '/htlHotelInfo/'              , function(){ return 'TODO:'; })->name('ctl.htl_hotel_info.index');
    Route::match(['get', 'post'], '/htlHotelInform/list/'       , function(){ return 'TODO:'; })->name('ctl.htl_hotel_inform.list');
    Route::match(['get', 'post'], '/htlHotelLink/list/'         , function(){ return 'TODO:'; })->name('ctl.htl_hotel_link.list');
    Route::match(['get', 'post'], '/htlHotelStation/list/'      , function(){ return 'TODO:'; })->name('ctl.htl_hotel_station.list');
    Route::match(['get', 'post'], '/htlHotelAmenity/list/'      , function(){ return 'TODO:'; })->name('ctl.htl_hotel_amenity.list');
    Route::match(['get', 'post'], '/htlHotelService/list/'      , function(){ return 'TODO:'; })->name('ctl.htl_hotel_service.list');
    Route::match(['get', 'post'], '/htlHotelNearby/list/'       , function(){ return 'TODO:'; })->name('ctl.htl_hotel_nearby.list');
    Route::match(['get', 'post'], '/htlHotelFacility/list/'     , function(){ return 'TODO:'; })->name('ctl.htl_hotel_facility.list');
    Route::match(['get', 'post'], '/htlHotelFacilityRoom/list/' , function(){ return 'TODO:'; })->name('ctl.htl_hotel_facility_room.list');
    Route::match(['get', 'post'], '/htlHotelChargeRound/index/' , function(){ return 'TODO:'; })->name('ctl.htl_hotel_charge_round.index');
    Route::match(['get', 'post'], '/htlHotelCancel/index/'      , function(){ return 'TODO:'; })->name('ctl.htl_hotel_cancel.index');
    Route::match(['get', 'post'], '/htlHotelReceipt/index/'     , function(){ return 'TODO:'; })->name('ctl.htl_hotel_receipt.index');
    Route::match(['get', 'post'], '/htlBathTax/'                , function(){ return 'TODO:'; })->name('ctl.htl_bath_tax.index');


	// 施設情報
	Route::controller(HtlhotelInfoController::class)->prefix("brhotelInfo")->group(function(){
		Route::match(['get','post'], '/show', 'show')->name('ctl.htlhotelInfo.show');
		Route::match(['get','post'], '/new', 'new')->name('ctl.htlhotelInfo.new');
		Route::match(['get','post'], '/edit', 'edit')->name('ctl.htlhotelInfo.edit');
		Route::post('/create', 'create')->name('ctl.htlhotelInfo.create');
		Route::post('/update', 'update')->name('ctl.htlhotelInfo.update');
	});

    // 施設登録
    // HACK: 重複排除
    Route::get('/brHotel/new', [\App\Http\Controllers\ctl\BrHotelRegisterController::class, 'new'])->name('ctl.br_hotel.new');
    Route::post('/brHotel/create', [\App\Http\Controllers\ctl\BrHotelRegisterController::class, 'create'])->name('ctl.br_hotel.create');
    Route::get('/brHotel/management', [\App\Http\Controllers\ctl\BrHotelRegisterController::class, 'management'])->name('ctl.br_hotel.management');
    Route::post('/brHotel/createManagement', [\App\Http\Controllers\ctl\BrHotelRegisterController::class, 'createManagement'])->name('ctl.br_hotel.create_management');
    Route::get('/brHotel/state', [\App\Http\Controllers\ctl\BrHotelRegisterController::class, 'state'])->name('ctl.br_hotel.state');
    Route::post('/brHotel/createState', [\App\Http\Controllers\ctl\BrHotelRegisterController::class, 'createState'])->name('ctl.br_hotel.create_state');

    // 施設情報メイン
    Route::controller(BrhotelController::class)->prefix("brhotel")->group(function () {
        Route::get('/hotelsearch', 'hotelsearch')->name('ctl.brhotel.hotelsearch'); //宿泊施設検索
        // Route::match(['get','post'], '/new', 'new')->name('ctl.brhotel.new');

        Route::match(['get','post'], '/edit', 'edit')->name('ctl.brhotel.edit');
        Route::post('/update', 'update')->name('ctl.brhotel.update'); //施設更新？

        Route::get('/', 'index')->name('ctl.brhotel.index'); // 検索 初期表示
        Route::match(['get', 'post'], '/show', 'show')->name('ctl.brhotel.show'); // 詳細変更 施設各情報ハブ

        Route::get('/searchcity', 'searchcity')->name('ctl.brhotel.searchcity'); // 検索部品 市プルダウン
        Route::get('/searchward', 'searchward')->name('ctl.brhotel.searchward'); // 検索部品 区プルダウン

        Route::post('/createnote', 'createnote')->name('ctl.brhotel.createnote'); //施設管理特記事項
        Route::post('/updatenote', 'updatenote')->name('ctl.brhotel.updatenote'); //

        Route::get ('/editSurvey', 'editSurvey')->name('ctl.br_hotel.edit_survey');    // 施設測地情報更新
        Route::post('/updateSurvey', 'updateSurvey')->name('ctl.br_hotel.update_survey');  // 施設測地情報更新 処理後結果

        Route::get ('/editManagement', 'editManagement')->name('ctl.br_hotel.edit_management');    // 施設管理情報更新
        Route::post('/updateManagement', 'updateManagement')->name('ctl.br_hotel.update_management');  // 施設管理情報更新処理
    });

    Route::controller(BrHotelAreaController::class)->prefix('brHotelArea')->group(function () {
        Route::get('/', 'index')->name('ctl.br_hotel_area.index');
        Route::get('/new', 'new')->name('ctl.br_hotel_area.new');
        Route::post('/create', 'create')->name('ctl.br_hotel_area.create');
        Route::get('/edit', 'edit')->name('ctl.br_hotel_area.edit');
        Route::post('/update', 'update')->name('ctl.br_hotel_area.update');
        Route::post('/delete', 'delete')->name('ctl.br_hotel_area.delete');
        Route::get('/complete', 'complete')->name('ctl.br_hotel_area.complete');
        Route::get('/json', 'json')->name('ctl.br_hotel_area.json');
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

    //パートナー管理画面
    Route::match(['get', 'post'], '/brpartner/searchlist/', 'BrpartnerController@searchList')
        ->name('ctl.brpartner.searchlist'); //表示
    Route::match(['get', 'post'], '/brpartner/partnerconf/', 'BrpartnerController@partnerConf')
        ->name('ctl.brpartner.partnerconf'); //表示
    Route::post('/brpartner/partnerupdate/', 'BrpartnerController@partnerUpdate')
        ->name('ctl.brpartner.partnerupdate'); //更新
    Route::match(['get', 'post'], '/brpartner/partnercontroledt/', 'BrpartnerController@partnerControlEdt')
        ->name('ctl.brpartner.partnercontroledt'); //編集
    Route::post('/brpartner/partnercontrolupd/', 'BrpartnerController@partnerControlUpd')
        ->name('ctl.brpartner.partnercontrolupd'); //更新


    //所属団体設定画面
    Route::match(['get', 'post'], '/brpartnersection/', 'BrpartnerSectionController@index')
        ->name('ctl.brpartnerSection.index'); //表示
    Route::post('/brpartnersection/new/', 'BrpartnerSectionController@new')
        ->name('ctl.brpartnerSection.new'); //新規登録
    Route::post('/brpartnersection/create/', 'BrpartnerSectionController@create')
        ->name('ctl.brpartnerSection.create'); //作成
    Route::post('/brpartnersection/edit/', 'BrpartnerSectionController@edit')
        ->name('ctl.brpartnerSection.edit'); //編集
    Route::post('/brpartnersection/update/', 'BrpartnerSectionController@update')
        ->name('ctl.brpartnerSection.update'); //更新
    Route::post('/brpartnersection/delete/', 'BrpartnerSectionController@delete')
        ->name('ctl.brpartnerSection.delete'); //削除処理
    Route::post('/brpartnersection/up/', 'BrpartnerSectionController@up')
        ->name('ctl.brpartnerSection.up'); //1つ上へ
    Route::post('/brpartnersection/down/', 'BrpartnerSectionController@down')
        ->name('ctl.brpartnerSection.down'); //1つ下へ
    Route::post('/brpartnersection/head/', 'BrpartnerSectionController@head')
        ->name('ctl.brpartnerSection.head'); //一番上へ
    Route::post('/brpartnersection/tail/', 'BrpartnerSectionController@tail')
        ->name('ctl.brpartnerSection.tail'); //一番下へ

    //キーワード設定
    Route::match(['get', 'post'], '/brpartnerkeyword/', 'BrpartnerKeywordController@index')
        ->name('ctl.brpartnerKeyword.index'); //表示
    Route::match(['get', 'post'], '/brpartnerkeyword/new/', 'BrpartnerKeywordController@new')
        ->name('ctl.brpartnerKeyword.new'); //新規登録
    Route::post('/brpartnerkeyword/_input/', 'BrpartnerKeywordController@_input')
        ->name('ctl.brpartnerKeyword._input'); //
    Route::post('/brpartnerkeyword/create/', 'BrpartnerKeywordController@create')
        ->name('ctl.brpartnerKeyword.create'); //新規登録
    Route::match(['get', 'post'], '/brpartnerkeyword/edit/', 'BrpartnerKeywordController@edit')
        ->name('ctl.brpartnerKeyword.edit'); //編集
    Route::post('/brpartnerkeyword/update/', 'BrpartnerKeywordController@update')
        ->name('ctl.brpartnerKeyword.update'); //更新
    Route::post('/brpartnerkeyword/sort/', 'BrpartnerKeywordController@sort')
        ->name('ctl.brpartnerKeyword.sort'); //順番変更

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

    // 重点表示プラン
    Route::match(['get', 'post'], '/list', 'BrroomPlanPriority2Controller@list')
        ->name('ctl.brroomplanpriority2.list'); //表示
    Route::post('/registration', 'BrroomPlanPriority2Controller@registration')
        ->name('ctl.brroomplanpriority2.registration'); //登録更新処理
    Route::post('/sort', 'BrroomPlanPriority2Controller@sort')
        ->name('ctl.brroomplanpriority2.sort'); //登録更新処理

    // 迷わずここ！
    Route::match(['get', 'post'], '/brhoteladvert2009000400/list', 'BrhotelAdvert2009000400Controller@list')
        ->name('ctl.brhoteladvert2009000400.list'); //表示
    Route::post('/brhoteladvert2009000400/new', 'BrhotelAdvert2009000400Controller@new')
        ->name('ctl.brhoteladvert2009000400.new'); //新規登録画面
    Route::post('/brhoteladvert2009000400/create', 'BrhotelAdvert2009000400Controller@create')
        ->name('ctl.brhoteladvert2009000400.create'); //登録
    Route::post('/brhoteladvert2009000400/edit', 'BrhotelAdvert2009000400Controller@edit')
        ->name('ctl.brhoteladvert2009000400.edit'); //編集画面
    Route::post('/brhoteladvert2009000400/update', 'BrhotelAdvert2009000400Controller@update')
        ->name('ctl.brhoteladvert2009000400.update'); //更新
});
