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
Route::namespace('App\Http\Controllers\rsv')->prefix('rsv')->group(function () {
    // 施設情報
    Route::controller(HotelController::class)->prefix("hotel")->group(function () {
        Route::get('/{hotel_cd}', 'info')->name('rsv.hotel.info');
    });
});

Route::middleware(['guest:staff' /* TODO: 各ロールについて guest ミドルウェアを追加 */])->group(function () {
    Route::get('ctl/brLogin', [\App\Http\Controllers\ctl\BrLoginController::class, 'index'])->name('ctl.br.login.index');
    Route::post('ctl/brLogin/login', [\App\Http\Controllers\ctl\BrLoginController::class, 'login'])->name('ctl.br.login.authenticate');
    // TODO: 各種ロールのログインのルートを追加
});

Route::get('ctl/logout', [\App\Http\Controllers\ctl\BrLoginController::class, 'logout'])->name('ctl.logout')->middleware('auth:staff');
Route::get('ctl/brTop', [\App\Http\Controllers\ctl\BrTopController::class, 'index'])->name('ctl.br.top')->middleware('auth:staff');

Route::get('/ctl/brChangePassword/', [\App\Http\Controllers\ctl\BrChangePasswordController::class, 'index'])->name('ctl.br.change.password')->middleware('auth:staff');
Route::post('/ctl/brChangePassword/update', [\App\Http\Controllers\ctl\BrChangePasswordController::class, 'update'])->name('ctl.br.change.password.update')->middleware('auth:staff');

/**
 * 管理システム
 */
// 社内トップ
Route::get('/ctl', [\App\Http\Controllers\ctl\BrtopController::class,'index'])->name('ctl.brtop.index');
Route::get('/ctl/brtop', [\App\Http\Controllers\ctl\BrtopController::class, 'index'])->name('ctl.brtop.index');
Route::post('/ctl/brtop/inspect', [\App\Http\Controllers\ctl\BrtopController::class, 'inspect'])->name('ctl.brtop.inspect');
Route::post('/ctl/brtop/registration', [\App\Http\Controllers\ctl\BrtopController::class, 'registration'])->name('ctl.brtop.registration');
Route::post('/ctl/brtop/payment', [\App\Http\Controllers\ctl\BrtopController::class, 'payment'])->name('ctl.brtop.payment');

// 管理画面一覧
Route::get('/ctl/top', [\App\Http\Controllers\ctl\TopController::class, 'index'])->name('ctl.top.index');

// 銀行支店マスタ
Route::get('/ctl/brbank', [\App\Http\Controllers\ctl\BrbankController::class, 'index'])->name('ctl.brbank.index');
Route::post('/ctl/brbank/newbank', [\App\Http\Controllers\ctl\BrbankController::class, 'newbank'])->name('ctl.brbank.newbank');
Route::post('/ctl/brbank/createbank', [\App\Http\Controllers\ctl\BrbankController::class, 'createbank'])->name('ctl.brbank.createbank');
Route::post('/ctl/brbank/viewbank', [\App\Http\Controllers\ctl\BrbankController::class, 'viewbank'])->name('ctl.brbank.viewbank');
Route::post('/ctl/brbank/updatebank', [\App\Http\Controllers\ctl\BrbankController::class, 'updatebank'])->name('ctl.brbank.updatebank');
Route::post('/ctl/brbank/newbankbranch', [\App\Http\Controllers\ctl\BrbankController::class, 'newbankbranch'])->name('ctl.brbank.newbankbranch');
Route::post('/ctl/brbank/createbankbranch', [\App\Http\Controllers\ctl\BrbankController::class, 'createbankbranch'])->name('ctl.brbank.createbankbranch');
Route::post('/ctl/brbank/viewbankbranch', [\App\Http\Controllers\ctl\BrbankController::class, 'viewbankbranch'])->name('ctl.brbank.viewbankbranch');
Route::post('/ctl/brbank/updatebankbranch', [\App\Http\Controllers\ctl\BrbankController::class, 'updatebankbranch'])->name('ctl.brbank.updatebankbranch');

// 施設管理TOPお知らせ情報管理
Route::get('/ctl/brbroadcastMessage', [\App\Http\Controllers\ctl\BrbroadcastMessageController::class, 'index'])->name('ctl.brbroadcastMessage.index');
Route::post('/ctl/brbroadcastMessage/new', [\App\Http\Controllers\ctl\BrbroadcastMessageController::class, 'new'])->name('ctl.brbroadcastMessage.new');
Route::post('/ctl/brbroadcastMessage/create', [\App\Http\Controllers\ctl\BrbroadcastMessageController::class, 'create'])->name('ctl.brbroadcastMessage.create');
Route::post('/ctl/brbroadcastMessage/detail', [\App\Http\Controllers\ctl\BrbroadcastMessageController::class, 'detail'])->name('ctl.brbroadcastMessage.detail');
Route::post('/ctl/brbroadcastMessage/edit', [\App\Http\Controllers\ctl\BrbroadcastMessageController::class, 'edit'])->name('ctl.brbroadcastMessage.edit');
Route::post('/ctl/brbroadcastMessage/update', [\App\Http\Controllers\ctl\BrbroadcastMessageController::class, 'update'])->name('ctl.brbroadcastMessage.update');
Route::post('/ctl/brbroadcastMessage/destroy', [\App\Http\Controllers\ctl\BrbroadcastMessageController::class, 'destroy'])->name('ctl.brbroadcastMessage.destroy');


// 予約通知ＦＡＸ広告 掲載文章
Route::post('/ctl/brfaxPr/edit', [\App\Http\Controllers\ctl\BrfaxPrController::class, 'edit'])->name('ctl.brfaxPr.edit');
Route::post('/ctl/brfaxPr/update', [\App\Http\Controllers\ctl\BrfaxPrController::class, 'update'])->name('ctl.brfaxPr.update');
Route::get('/ctl/brfaxPr/show', [\App\Http\Controllers\ctl\BrfaxPrController::class, 'show'])->name('ctl.brfaxPr.show');

// TODO: 不要なルーティングか？ PR#20 が merge されたら確認
Route::get('/ctl/htltop/index/target_cd/{hotel_cd}', function ($targetCd) {return 'TODO: htl top index : ' . $targetCd; })->name('ctl.htl_top.index');

Route::get('/ctl/htlHotel/show', [\App\Http\Controllers\ctl\HtlHotelController::class, 'show'])->name('ctl.htl_hotel.show');
Route::match(['get', 'post'], '/ctl/htlHotel/edit/'             , function(){ return 'TODO:'; })->name('ctl.htl_hotel.edit');
Route::match(['get', 'post'], '/ctl/htlHotelCard/show/'         , function(){ return 'TODO:'; })->name('ctl.htl_hotel_card.show');
Route::match(['get', 'post'], '/ctl/htlHotelInfo/'              , function(){ return 'TODO:'; })->name('ctl.htl_hotel_info.index');
Route::match(['get', 'post'], '/ctl/htlHotelInform/list/'       , function(){ return 'TODO:'; })->name('ctl.htl_hotel_inform.list');
Route::match(['get', 'post'], '/ctl/htlHotelLink/list/'         , function(){ return 'TODO:'; })->name('ctl.htl_hotel_link.list');
Route::match(['get', 'post'], '/ctl/htlHotelStation/list/'      , function(){ return 'TODO:'; })->name('ctl.htl_hotel_station.list');
Route::match(['get', 'post'], '/ctl/htlHotelAmenity/list/'      , function(){ return 'TODO:'; })->name('ctl.htl_hotel_amenity.list');
Route::match(['get', 'post'], '/ctl/htlHotelService/list/'      , function(){ return 'TODO:'; })->name('ctl.htl_hotel_service.list');
Route::match(['get', 'post'], '/ctl/htlHotelNearby/list/'       , function(){ return 'TODO:'; })->name('ctl.htl_hotel_nearby.list');
Route::match(['get', 'post'], '/ctl/htlHotelFacility/list/'     , function(){ return 'TODO:'; })->name('ctl.htl_hotel_facility.list');
Route::match(['get', 'post'], '/ctl/htlHotelFacilityRoom/list/' , function(){ return 'TODO:'; })->name('ctl.htl_hotel_facility_room.list');
Route::match(['get', 'post'], '/ctl/htlHotelChargeRound/index/' , function(){ return 'TODO:'; })->name('ctl.htl_hotel_charge_round.index');
Route::match(['get', 'post'], '/ctl/htlHotelCancel/index/'      , function(){ return 'TODO:'; })->name('ctl.htl_hotel_cancel.index');
Route::match(['get', 'post'], '/ctl/htlHotelReceipt/index/'     , function(){ return 'TODO:'; })->name('ctl.htl_hotel_receipt.index');
Route::match(['get', 'post'], '/ctl/htlBathTax/'                , function(){ return 'TODO:'; })->name('ctl.htl_bath_tax.index');


// 施設情報
Route::match(['get','post'], '/ctl/brhotelInfo/show', [\App\Http\Controllers\ctl\HtlhotelInfoController::class, 'show'])->name('ctl.htlhotelInfo.show');
Route::match(['get','post'], '/ctl/brhotelInfo/new', [\App\Http\Controllers\ctl\HtlhotelInfoController::class, 'new'])->name('ctl.htlhotelInfo.new');
Route::match(['get','post'], '/ctl/brhotelInfo/edit', [\App\Http\Controllers\ctl\HtlhotelInfoController::class, 'edit'])->name('ctl.htlhotelInfo.edit');
Route::post('/ctl/brhotelInfo/create', [\App\Http\Controllers\ctl\HtlhotelInfoController::class, 'create'])->name('ctl.htlhotelInfo.create');
Route::post('/ctl/brhotelInfo/update', [\App\Http\Controllers\ctl\HtlhotelInfoController::class, 'update'])->name('ctl.htlhotelInfo.update');

// 施設登録
// HACK: 重複排除
Route::get('/ctl/brHotel/new', [\App\Http\Controllers\ctl\BrHotelRegisterController::class, 'new'])->name('ctl.br_hotel.new');
Route::post('/ctl/brHotel/create', [\App\Http\Controllers\ctl\BrHotelRegisterController::class, 'create'])->name('ctl.br_hotel.create');
Route::get('/ctl/brHotel/management', [\App\Http\Controllers\ctl\BrHotelRegisterController::class, 'management'])->name('ctl.br_hotel.management');
Route::post('/ctl/brHotel/createManagement', [\App\Http\Controllers\ctl\BrHotelRegisterController::class, 'createManagement'])->name('ctl.br_hotel.create_management');
Route::get('/ctl/brHotel/state', [\App\Http\Controllers\ctl\BrHotelRegisterController::class, 'state'])->name('ctl.br_hotel.state');
Route::post('/ctl/brHotel/createState', [\App\Http\Controllers\ctl\BrHotelRegisterController::class, 'createState'])->name('ctl.br_hotel.create_state');

// 施設情報メイン
Route::get('/ctl/brhotel/hotelsearch', [\App\Http\Controllers\ctl\BrhotelController::class, 'hotelsearch'])->name('ctl.brhotel.hotelsearch'); //宿泊施設検索
// Route::match(['get','post'], '/ctl/brhotel/new', [\App\Http\Controllers\ctl\BrhotelController::class, 'new'])->name('ctl.brhotel.new');
Route::match(['get','post'], '/ctl/brhotel/edit', [\App\Http\Controllers\ctl\BrhotelController::class, 'edit'])->name('ctl.brhotel.edit');
Route::post('/ctl/brhotel/update', [\App\Http\Controllers\ctl\BrhotelController::class, 'update'])->name('ctl.brhotel.update'); //施設更新？
Route::get('/ctl/brhotel/', [\App\Http\Controllers\ctl\BrhotelController::class, 'index'])->name('ctl.brhotel.index'); // 検索 初期表示
Route::match(['get', 'post'], '/ctl/brhotel/show', [\App\Http\Controllers\ctl\BrhotelController::class, 'show'])->name('ctl.brhotel.show'); // 詳細変更 施設各情報ハブ
Route::get('/ctl/brhotel/searchcity', [\App\Http\Controllers\ctl\BrhotelController::class, 'searchcity'])->name('ctl.brhotel.searchcity'); // 検索部品 市プルダウン
Route::get('/ctl/brhotel/searchward', [\App\Http\Controllers\ctl\BrhotelController::class, 'searchward'])->name('ctl.brhotel.searchward'); // 検索部品 区プルダウン
Route::post('/ctl/brhotel/createnote', [\App\Http\Controllers\ctl\BrhotelController::class, 'createnote'])->name('ctl.brhotel.createnote'); //施設管理特記事項
Route::post('/ctl/brhotel/updatenote', [\App\Http\Controllers\ctl\BrhotelController::class, 'updatenote'])->name('ctl.brhotel.updatenote'); //
Route::get('/ctl/brhotel/editSurvey', [\App\Http\Controllers\ctl\BrhotelController::class, 'editSurvey'])->name('ctl.br_hotel.edit_survey');    // 施設測地情報更新
Route::post('/ctl/brhotel/updateSurvey', [\App\Http\Controllers\ctl\BrhotelController::class, 'updateSurvey'])->name('ctl.br_hotel.update_survey');  // 施設測地情報更新 処理後結果
Route::get('/ctl/brhotel/editManagement', [\App\Http\Controllers\ctl\BrhotelController::class, 'editManagement'])->name('ctl.br_hotel.edit_management');    // 施設管理情報更新
Route::post('/ctl/brhotel/updateManagement', [\App\Http\Controllers\ctl\BrhotelController::class, 'updateManagement'])->name('ctl.br_hotel.update_management');  // 施設管理情報更新処理

Route::get('/ctl/brHotelArea', [\App\Http\Controllers\ctl\BrHotelAreaController::class, 'index'])->name('ctl.br_hotel_area.index');
Route::get('/ctl/brHotelArea/new', [\App\Http\Controllers\ctl\BrHotelAreaController::class, 'new'])->name('ctl.br_hotel_area.new');
Route::post('/ctl/brHotelArea/create', [\App\Http\Controllers\ctl\BrHotelAreaController::class, 'create'])->name('ctl.br_hotel_area.create');
Route::get('/ctl/brHotelArea/edit', [\App\Http\Controllers\ctl\BrHotelAreaController::class, 'edit'])->name('ctl.br_hotel_area.edit');
Route::post('/ctl/brHotelArea/update', [\App\Http\Controllers\ctl\BrHotelAreaController::class, 'update'])->name('ctl.br_hotel_area.update');
Route::post('/ctl/brHotelArea/delete', [\App\Http\Controllers\ctl\BrHotelAreaController::class, 'delete'])->name('ctl.br_hotel_area.delete');
Route::get('/ctl/brHotelArea/complete', [\App\Http\Controllers\ctl\BrHotelAreaController::class, 'complete'])->name('ctl.br_hotel_area.complete');
Route::get('/ctl/brHotelArea/json', [\App\Http\Controllers\ctl\BrHotelAreaController::class, 'json'])->name('ctl.br_hotel_area.json');


// 施設情報変更 登録状態変更
Route::match(['get', 'post'], '/ctl/brhotelStatus/index', [\App\Http\Controllers\ctl\BrhotelStatusController::class, 'index'])->name('ctl.brhotelStatus.index'); //表示
Route::post('/ctl/brhotelStatus/update', [\App\Http\Controllers\ctl\BrhotelStatusController::class, 'update'])->name('ctl.brhotelStatus.update'); //更新処理

// 料率マスタ
Route::match(['get', 'post'], '/ctl/brhotelRate/index', [\App\Http\Controllers\ctl\BrhotelRateController::class, 'index'])->name('ctl.brhotelRate.index'); //表示
Route::match(['get', 'post'], '/ctl/brhotelRate/edit', [\App\Http\Controllers\ctl\BrhotelRateController::class, 'edit'])->name('ctl.brhotelRate.edit'); //更新 表示
Route::post('/ctl/brhotelRate/update', [\App\Http\Controllers\ctl\BrhotelRateController::class, 'update'])->name('ctl.brhotelRate.update'); //更新処理
Route::match(['get', 'post'], '/ctl/brhotelRate/new', [\App\Http\Controllers\ctl\BrhotelRateController::class, 'new'])->name('ctl.brhotelRate.new'); //新規 表示
Route::post('/ctl/brhotelRate/create', [\App\Http\Controllers\ctl\BrhotelRateController::class, 'create'])->name('ctl.brhotelRate.create'); //新規処理
Route::post('/ctl/brhotelRate/destroy', [\App\Http\Controllers\ctl\BrhotelRateController::class, 'destroy'])->name('ctl.brhotelRate.destroy'); //削除処理

//パートナー管理画面
Route::match(['get', 'post'], '/ctl/brpartner/searchlist/', [\App\Http\Controllers\ctl\BrpartnerController::class, 'searchList'])->name('ctl.brpartner.searchlist'); //表示
Route::match(['get', 'post'], '/ctl/brpartner/partnerconf/', [\App\Http\Controllers\ctl\BrpartnerController::class, 'partnerConf'])->name('ctl.brpartner.partnerconf'); //表示
Route::post('/ctl/brpartner/partnerupdate/', [\App\Http\Controllers\ctl\BrpartnerController::class, 'partnerUpdate'])->name('ctl.brpartner.partnerupdate'); //更新
Route::match(['get', 'post'], '/ctl/brpartner/partnercontroledt/', [\App\Http\Controllers\ctl\BrpartnerController::class, 'partnerControlEdt'])->name('ctl.brpartner.partnercontroledt'); //編集
Route::post('/ctl/brpartner/partnercontrolupd/', [\App\Http\Controllers\ctl\BrpartnerController::class, 'partnerControlUpd'])->name('ctl.brpartner.partnercontrolupd'); //更新


//所属団体設定画面
Route::match(['get', 'post'], '/ctl/brpartnersection/', [\App\Http\Controllers\ctl\BrpartnerSectionController::class, 'index'])->name('ctl.brpartnerSection.index'); //表示
Route::post('/ctl/brpartnersection/new/', [\App\Http\Controllers\ctl\BrpartnerSectionController::class, 'new'])->name('ctl.brpartnerSection.new'); //新規登録
Route::post('/ctl/brpartnersection/create/', [\App\Http\Controllers\ctl\BrpartnerSectionController::class, 'create'])->name('ctl.brpartnerSection.create'); //作成
Route::post('/ctl/brpartnersection/edit/', [\App\Http\Controllers\ctl\BrpartnerSectionController::class, 'edit'])->name('ctl.brpartnerSection.edit'); //編集
Route::post('/ctl/brpartnersection/update/', [\App\Http\Controllers\ctl\BrpartnerSectionController::class, 'update'])->name('ctl.brpartnerSection.update'); //更新
Route::post('/ctl/brpartnersection/delete/', [\App\Http\Controllers\ctl\BrpartnerSectionController::class, 'delete'])->name('ctl.brpartnerSection.delete'); //削除処理
Route::post('/ctl/brpartnersection/up/', [\App\Http\Controllers\ctl\BrpartnerSectionController::class, 'up'])->name('ctl.brpartnerSection.up'); //1つ上へ
Route::post('/ctl/brpartnersection/down/', [\App\Http\Controllers\ctl\BrpartnerSectionController::class, 'down'])->name('ctl.brpartnerSection.down'); //1つ下へ
Route::post('/ctl/brpartnersection/head/', [\App\Http\Controllers\ctl\BrpartnerSectionController::class, 'head'])->name('ctl.brpartnerSection.head'); //一番上へ
Route::post('/ctl/brpartnersection/tail/', [\App\Http\Controllers\ctl\BrpartnerSectionController::class, 'tail'])->name('ctl.brpartnerSection.tail'); //一番下へ

//キーワード設定
Route::match(['get', 'post'], '/ctl/brpartnerkeyword/', [\App\Http\Controllers\ctl\BrpartnerKeywordController::class, 'index'])->name('ctl.brpartnerKeyword.index'); //表示
Route::match(['get', 'post'], '/ctl/brpartnerkeyword/new/', [\App\Http\Controllers\ctl\BrpartnerKeywordController::class, 'new'])->name('ctl.brpartnerKeyword.new'); //新規登録
Route::post('/ctl/brpartnerkeyword/_input/', [\App\Http\Controllers\ctl\BrpartnerKeywordController::class, '_input'])->name('ctl.brpartnerKeyword._input'); //
Route::post('/ctl/brpartnerkeyword/create/', [\App\Http\Controllers\ctl\BrpartnerKeywordController::class, 'create'])->name('ctl.brpartnerKeyword.create'); //新規登録
Route::match(['get', 'post'], '/ctl/brpartnerkeyword/edit/', [\App\Http\Controllers\ctl\BrpartnerKeywordController::class, 'edit'])->name('ctl.brpartnerKeyword.edit'); //編集
Route::post('/ctl/brpartnerkeyword/update/', [\App\Http\Controllers\ctl\BrpartnerKeywordController::class, 'update'])->name('ctl.brpartnerKeyword.update'); //更新
Route::post('/ctl/brpartnerkeyword/sort/', [\App\Http\Controllers\ctl\BrpartnerKeywordController::class, 'sort'])->name('ctl.brpartnerKeyword.sort'); //順番変更

// パートナー精算先
Route::get('/ctl/brpartnercustomer/', [\App\Http\Controllers\ctl\BrPartnerCustomerController::class, 'index'])->name('brpartnercustomer.index');
Route::get('/ctl/brpartnercustomer/search', [\App\Http\Controllers\ctl\BrPartnerCustomerController::class, 'search'])->name('brpartnercustomer.search');

Route::get('/ctl/brpartnercustomer/create', [\App\Http\Controllers\ctl\BrPartnerCustomerController::class, 'create'])->name('brpartnercustomer.create');
Route::post('/ctl/brpartnercustomer/register', [\App\Http\Controllers\ctl\BrPartnerCustomerController::class, 'register'])->name('brpartnercustomer.register');

// TODO: 要調査 クエリパラメータに ? をつけて任意にしないとレンダリングが通らなくなっている。
// ルートパラメータではなく通常のクエリパラメータにしたほうがスッキリするように思われる。
Route::get('/ctl/brpartnercustomer/edit/{customer_id?}', [\App\Http\Controllers\ctl\BrPartnerCustomerController::class, 'edit'])->name('brpartnercustomer.edit');
Route::post('/ctl/brpartnercustomer/modify', [\App\Http\Controllers\ctl\BrPartnerCustomerController::class, 'modify'])->name('brpartnercustomer.modify');


// 精算サイト
Route::get('/ctl/brpartnersite/', [\App\Http\Controllers\ctl\BrPartnerSiteController::class, 'index']);
Route::get('/ctl/brpartnersite/search', [\App\Http\Controllers\ctl\BrPartnerSiteController::class, 'search'])->name('ctl.brPartnerSite.search');
Route::get('/ctl/brpartnersite/edit', [\App\Http\Controllers\ctl\BrPartnerSiteController::class, 'edit'])->name('ctl.brPartnerSite.edit');
Route::post('/ctl/brpartnersite/modify', [\App\Http\Controllers\ctl\BrPartnerSiteController::class, 'modify'])->name('ctl.brPartnerSite.modify');

// 重点表示プラン
Route::match(['get', 'post'], '/ctl/list', [\App\Http\Controllers\ctl\BrroomPlanPriority2Controller::class, 'list'])->name('ctl.brroomplanpriority2.list'); //表示
Route::post('/ctl/registration', [\App\Http\Controllers\ctl\BrroomPlanPriority2Controller::class, 'registration'])->name('ctl.brroomplanpriority2.registration'); //登録更新処理
Route::post('/ctl/sort', [\App\Http\Controllers\ctl\BrroomPlanPriority2Controller::class, 'sort'])->name('ctl.brroomplanpriority2.sort'); //登録更新処理

// 迷わずここ！
Route::match(['get', 'post'], '/ctl/brhoteladvert2009000400/list', [\App\Http\Controllers\ctl\BrhotelAdvert2009000400Controller::class, 'list'])->name('ctl.brhoteladvert2009000400.list'); //表示
Route::post('/ctl/brhoteladvert2009000400/new', [\App\Http\Controllers\ctl\BrhotelAdvert2009000400Controller::class, 'new'])->name('ctl.brhoteladvert2009000400.new'); //新規登録画面
Route::post('/ctl/brhoteladvert2009000400/create', [\App\Http\Controllers\ctl\BrhotelAdvert2009000400Controller::class, 'create'])->name('ctl.brhoteladvert2009000400.create'); //登録
Route::post('/ctl/brhoteladvert2009000400/edit', [\App\Http\Controllers\ctl\BrhotelAdvert2009000400Controller::class, 'edit'])->name('ctl.brhoteladvert2009000400.edit'); //編集画面
Route::post('/ctl/brhoteladvert2009000400/update', [\App\Http\Controllers\ctl\BrhotelAdvert2009000400Controller::class, 'update'])->name('ctl.brhoteladvert2009000400.update'); //更新

// 参考として一応残す。
// Route::namespace('App\Http\Controllers\ctl')->prefix('ctl')->group(function () {
// });


// TODO: to be deleted 社内スタッフ登録 移植元では存在していない(？)
Route::get('create', [\App\Http\Controllers\ctl\BrLoginController::class, 'create'])->middleware('guest:staff');
Route::post('register', [\App\Http\Controllers\ctl\BrLoginController::class, 'register'])->middleware('guest:staff')->name('register');

// TODO: 実装したら削除する。
// 情報を取得しているだけであれば、 get method を使うほうが適切ではないか？
Route::post('/ctl/brreserve/', function () {return 'TODO: 【未実装】 /ctl/brreserve/'; })->name('ctl.br.reserve');
Route::post('/ctl/brreserveck/', function () {return 'TODO: 【未実装】 /ctl/brreserveck/'; })->name('ctl.br.reserve.check');
Route::post('/ctl/brdemandresult/list/', function () {return 'TODO: 【未実装】 /ctl/brdemandresult/list/'; })->name('ctl.br.demand.result.list');
Route::post('/ctl/brpartner/', function () {return 'TODO: 【未実装】 /ctl/brpartner/'; })->name('ctl.br.partner');
Route::post('/ctl/braffiliate/', function () {return 'TODO: 【未実装】 /ctl/braffiliate/'; })->name('ctl.br.affiliate');
Route::post('/ctl/brtop/payment/', function () {return 'TODO: 【未実装】 /ctl/brtop/payment/'; })->name('ctl.br.top.payment');
Route::post('/ctl/brtop/confirmation/', function () {return 'TODO: 【未実装】 /ctl/brtop/confirmation/'; })->name('ctl.br.top.confirmation');
Route::post('/ctl/brtop/registration/', function () {return 'TODO: 【未実装】 /ctl/brtop/registration/'; })->name('ctl.br.top.registration');
Route::post('/ctl/brtop/offer/', function () {return 'TODO: 【未実装】 /ctl/brtop/offer/'; })->name('ctl.br.top.offer');
Route::post('/ctl/brtop/stock/', function () {return 'TODO: 【未実装】 /ctl/brtop/stock/'; })->name('ctl.br.top.stock');
Route::post('/ctl/brtop/claim/', function () {return 'TODO: 【未実装】 /ctl/brtop/claim/'; })->name('ctl.br.top.claim');
Route::post('/ctl/brgroupbuying/deals/', function () {return 'TODO: 【未実装】 /ctl/brgroupbuying/deals/'; })->name('ctl.br.group.buying.deals');

Route::post('/ctl/brtop/inspect/', function () {return 'TODO: 【未実装】 /ctl/brtop/inspect/'; })->name('ctl.br.top.inspect');
Route::post('/ctl/brvoice/', function () {return 'TODO: 【未実装】 /ctl/brvoice/'; })->name('ctl.br.voice');
Route::post('/ctl/brpoint/', function () {return 'TODO: 【未実装】 /ctl/brpoint/'; })->name('ctl.br.point');
Route::post('/ctl/brmailmagazine/', function () {return 'TODO: 【未実装】 /ctl/brmailmagazine/'; })->name('ctl.br.mail.magazine');
Route::post('/ctl/brmailmagazine2/', function () {return 'TODO: 【未実装】 /ctl/brmailmagazine2/'; })->name('ctl.br.mail.magazine2');
Route::post('/ctl/brtop/kbs_brv_tool_member.touroku', function () {return 'TODO: 【未実装】 kbs_brv_tool_member.touroku'; })->name('ctl.br.top.kbs.brv.tool.member.touroku');

Route::get('/ctl/brmoneyschedule/new', function (\Illuminate\Http\Request $request) {
    var_dump($request->input());
    return 'TODO: 未実装';
})->name('ctl.br.money.schedule.new');

// MAIL_BUFFER一覧
Route::get('/ctl/brmailbuffer/search', [\App\Http\Controllers\ctl\BrMailBufferController::class, 'search'])
    ->name('ctl.brMailBuffer.search');
Route::get('/ctl/brmailbuffer/show', [\App\Http\Controllers\ctl\BrMailBufferController::class, 'show'])
    ->name('ctl.brMailBuffer.show');

// TODO: pull request #25 (精算先、施設情報：請求関連（請求先）精算先（登録施設）) が merge されたら削除
Route::post('/ctl/brCustomer/list', function () {return 'TODO'; })->name('ctl.brCustomer.list');
Route::post('/ctl/brCustomer/csv', function () {return 'TODO'; })->name('ctl.brCustomer.csv');
