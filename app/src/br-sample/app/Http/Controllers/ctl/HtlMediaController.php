<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use App\Http\Requests\MediaUpdateRequest;
use App\Http\Requests\MediaUploadRequest;
use App\Models\HotelMedia;
use App\Models\Media;
use App\Models\MediaOrg;
use App\Services\HtlMediaService as Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HtlMediaController extends Controller
{
    /**
     * インデックス
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return redirect()->route('ctl.htl.media.list', [
            'target_cd' => $request->input('target_cd'),
        ]);
    }

    /**
     * 画像一覧管理
     *
     * @param Request $request
     * @param Service $service
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request, Service $service)
    {
        $hotelCd = $request->input('target_cd', '');
        $conditions = $request->input('label_cd', [
            'outside'   => true,
            'map'       => true,
            'inside'    => true,
            'room'      => true,
            'other'     => true,
            'nothing'   => true,
        ]);

        $mediaList          = $service->getAllMediaList($hotelCd, $conditions);
        $hotelMediaOutside  = $service->getHotelMedia($hotelCd, HotelMedia::TYPE_HOTEL);
        $hotelMediaMap      = $service->getHotelMedia($hotelCd, HotelMedia::TYPE_MAP);

        return view('ctl.htl.media.list', [
            'target_cd'     => $hotelCd,
            'media_list'    => $mediaList,
            'outside'       => $hotelMediaOutside,
            'map'           => $hotelMediaMap,

            'media_type' => $request->input('media_type'),
            'target_order_no' => $request->input('target_order_no'),
            'setting_media_no' => $request->input('setting_media_no'),
            'label_type' => $request->input('label_type'),

            'guides'    => $request->session()->get('guides', []),

            // TODO: 暫定実装、消す方向で修正を進める
            'form_params' => [
                'list_width' => $request->input('list_width'),
                'label_cd' => [
                    'outside'   => $request->input('label_cd.outside'),
                    'map'       => $request->input('label_cd.map'),
                    'inside'    => $request->input('label_cd.inside'),
                    'room'      => $request->input('label_cd.room'),
                    'other'     => $request->input('label_cd.other'),
                    'nothing'   => $request->input('label_cd.nothing'),
                ],

                'target_cd'         => $hotelCd,
                'room_id'           => '',
                'plan_id'           => '',

                'media_type' => $request->input('media_type'),
                'target_order_no' => $request->input('target_order_no'),
                'setting_media_no' => $request->input('setting_media_no'),
                'label_type' => $request->input('label_type'),
            ],
        ]);
    }

    /**
     * 画像アップロード
     *
     * @param MediaUploadRequest $request
     * @param Service $service
     * @return \Illuminate\Http\Response
     */
    public function upload(MediaUploadRequest $request, Service $service)
    {
        $hotelCd = $request->input('target_cd');

        // TODO: 部屋プランメンテナンス画面から遷移してきた時
        $room_id                = $request->input('room_id');
        $plan_id                = $request->input('plan_id');
        $room_plan_mainte_flg   = $request->input('room_plan_mainte_flg');

        // title, label_cd の成型
        $title = '';
        $labelCd = '';
        if ($request->input('select') == 'normal') {
            $title = $request->input('title');
            $labelCd .= $request->input('label_cd.outside', '0');  // 外観
            $labelCd .= '0';                                       // 地図
            $labelCd .= $request->input('label_cd.inside', '0');   // 館内
            $labelCd .= $request->input('label_cd.room', '0');     // 客室
            $labelCd .= $request->input('label_cd.other', '0');    // その他
        } else {
            $title = '地図';
            $labelCd .= '01000';
        }

        // 枝番の取得
        $sql = <<<SQL
            select 
                ifnull(max(media_no), 0) + 1 as media_no,
                ifnull(max(order_no), 0) + 1 as order_no
            from 
                media
            where
                hotel_cd = :hotel_cd
        SQL;
        $res = DB::select($sql, [
            'hotel_cd' => $hotelCd,
        ]);
        $mediaNo = $res[0]->media_no;
        $orderNo = $res[0]->order_no;

        // アップロードされたファイル名を取得
        /** @var \Illuminate\Http\UploadedFile */
        $image = $request->file('file');
        $fileName   = $image->getClientOriginalName();
        $mimeType   = $image->getClientMimeType();
        $extension  = $image->getClientOriginalExtension(); // 拡張子

        // 実際にアップロードされた名前に近い形で、 media_org に保存するファイル名
        $originalFileName = preg_replace('/\.jpeg\z/', '.jpg', strtolower($fileName));

        // ストレージに保存するファイル名
        $storeFileName = implode([$hotelCd, '_', $mediaNo, '_', time(), '.', $extension]);

        DB::beginTransaction();
        try {
            // 取得したファイル名で保存し、保存した画像の幅と高さを取得
            // TODO: 保存先のストレージに合わせて、実装を変更する必要あり。
            $path = $image->storeAs('public/images/hotel/' . $hotelCd, $storeFileName);
            [$width, $height] = getimagesize(storage_path('app/' . $path));

            // 画像情報を DB に保存
            Media::create([
                'hotel_cd'  => $hotelCd,
                'media_no'  => $mediaNo,
                'order_no'  => $orderNo,
                'label_cd'  => $labelCd,
                'title'     => $title,
                'file_nm'   => $storeFileName,
                'mime_type' => $mimeType,
                'width'     => $width,
                'height'    => $height,
            ]);

            MediaOrg::create([
                'hotel_cd'      => $hotelCd,
                'media_no'      => $mediaNo,
                'org_file_nm'   => $originalFileName,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        $request->session()->regenerateToken(); // 二重送信対策

        // TODO: 画像選択画面 (selectMedia) からのアップロードでは、 selectMedia に返す (redirect back でよい？)
        return redirect()->route('ctl.htl.media.list', ['target_cd' => $hotelCd])->with([
            'guides' => ['アップロードが完了しました。'],
        ]);
    }

    /**
     * 画像選択表示順変更
     *
     * @param Request $request
     * @param Service $service
     * @return \Illuminate\Http\Response
     */
    public function sortMedia(Request $request, Service $service)
    {
        $hotelCd = $request->input('target_cd');
        $mediaNo = $request->input('media_no');
        $targetMediaNo = $request->input('target_media_no');
        $up = $request->input('change_flg') == 'up';

        $succeeded = $service->sortMedia($hotelCd, $mediaNo, $targetMediaNo, $up);

        if (!$succeeded) {
            return redirect()->back()->withErrors([
                '画像の並び替えに失敗しました。',
            ]);
        }

        return redirect()->route('ctl.htl.media.list', [
            'target_cd' => $hotelCd,
            'label_cd'  => $request->input('label_cd'),
        ])->with([
            'guides' => ['画像を並び替えました。'],
        ]);
    }

    /**
     * 画像情報編集
     *
     * @param Request $request
     * @param Service $service
     * @return \Illuminate\Http\Response
     */
    public function editMedia(Request $request, Service $service)
    {
        $hotelCd = $request->input('target_cd');
        $mediaNo = $request->input('media_no');

        $media = $service->findMediaWithOriginal($hotelCd, $mediaNo);

        // ラベル表示用に整形
        $labelCd = [
            'outside'   => $media->isLabeledAs(Media::LABEL_CD_OUTSIDE),    // 外観
            'map'       => $media->isLabeledAs(Media::LABEL_CD_MAP),        // 地図
            'inside'    => $media->isLabeledAs(Media::LABEL_CD_INSIDE),     // フォトギャラリー
            'room'      => $media->isLabeledAs(Media::LABEL_CD_ROOM),       // 客室
            'other'     => $media->isLabeledAs(Media::LABEL_CD_OTHER),      // その他
        ];

        return view('ctl.htl.media.edit-media', [
            'target_cd' => $hotelCd,
            'media_no'  => $mediaNo,
            'media'     => $media,
            'label_cd'  => $labelCd,

            'guides' => $request->session()->get('guides', []),
        ]);
    }

    /**
     * 画像情報更新
     *
     * @param MediaUpdateRequest $request
     * @param Service $service
     * @return \Illuminate\Http\Response
     */
    public function updateMedia(MediaUpdateRequest $request, Service $service)
    {
        $hotelCd = $request->input('target_cd');
        $mediaNo = $request->input('media_no');
        $title = $request->input('title');

        // ラベルコード作成
        $labelCd = '';
        $labelCd .= $request->input('label_cd.outside', '0');
        $labelCd .= $request->input('label_cd.map', '0');
        $labelCd .= $request->input('label_cd.inside', '0');
        $labelCd .= $request->input('label_cd.room', '0');
        $labelCd .= $request->input('label_cd.other', '0');

        $succeeded = $service->updateMedia($hotelCd, $mediaNo, $title, $labelCd);

        if (!$succeeded) {
            return redirect()->back()->withErrors([
                '画像情報の更新に失敗しました。',
            ])->withInput();
        }

        return redirect()->route('ctl.htl.media.edit_media', [
            'target_cd' => $hotelCd,
            'media_no' => $mediaNo
        ])->with([
            'guides' => ['画像情報を更新しました。'],
        ]);
    }

    /**
     * 画像削除
     *
     * @param Request $request
     * @param Service $service
     * @return \Illuminate\Http\Response
     */
    public function destroyMedia(Request $request, Service $service)
    {
        $hotelCd = $request->input('target_cd');
        $mediaNo = $request->input('media_no');

        $errorMessages = $service->destroyMedia($hotelCd, $mediaNo);
        if (count($errorMessages) > 0) {
            return redirect()->back()->withErrors($errorMessages);
        }

        return redirect()->route('ctl.htl.media.list', ['target_cd' => $hotelCd])->with([
            'guides' => ['画像を削除しました。'],
        ]);
    }

    /**
     * 施設画像編集
     *
     * @param Request $request
     * @param Service $service
     * @return \Illuminate\Http\Response
     */
    public function editHotel(Request $request, Service $service)
    {
        $hotelCd = $request->input('target_cd');

        // 外観画像取得
        $hotelMediaOutside          = $service->getHotelMedia($hotelCd, HotelMedia::TYPE_HOTEL);
        // 地図画像取得
        $hotelMediaMap              = $service->getHotelMedia($hotelCd, HotelMedia::TYPE_MAP);
        // フォトギャラリー画像取得
        $hotelMediaGalleryPhotos    = $service->getHotelMedia($hotelCd, HotelMedia::TYPE_OTHER);

        // TODO: 登録可能数(プレミアムは分岐？)
        $inside_media_count = 30;
        $room_media_count = 10;
        $plan_media_count = 10;

        return view('ctl.htl.media.edit-hotel', [
            'target_cd'     => $hotelCd,

            'outside'       => $hotelMediaOutside,
            'map'           => $hotelMediaMap,
            'galleryPhotos' => $hotelMediaGalleryPhotos,

            'media_count_inside'    => $inside_media_count,
            'media_count_room'      => $room_media_count,
            'media_count_plan'      => $plan_media_count,

            'guides' => $request->session()->get('guides', []),
        ]);
    }

    /**
     * 画像選択
     *
     * @param Request $request
     * @param Service $service
     * @return \Illuminate\Http\Response
     */
    public function selectMedia(Request $request, Service $service)
    {
        $hotelCd = $request->input('target_cd');
        $mediaType = $request->input('media_type');

        // ラベル検索用パラメータ設定
        $conditions = $request->input('label_cd', [
            'outside'   => true,
            'map'       => false,
            'inside'    => true,
            'room'      => true,
            'other'     => true,
            'nothing'   => true,
        ]);
        // listページでワイド表示を選択している場合

        // 画像一覧の取得
        $medias = $service->getAllMediaList($hotelCd, $conditions);

        // メッセージの表示
        $messageIndex = $request->input('label_type', 0);
        $messageWords = [
            'room'  => [0 => '部屋'],
            'plan'  => [0 => 'プラン'],
            'hotel' => [
                HotelMedia::TYPE_HOTEL  => '外観',
                HotelMedia::TYPE_MAP    => '地図',
                HotelMedia::TYPE_OTHER  => 'フォトギャラリー',
            ],
        ];
        $guides = [
            $messageWords[$mediaType][$messageIndex] . 'に設定する画像を選択してください。',
        ];

        return view('ctl.htl.media.select-media', [
            'target_cd' => $hotelCd,
            'medias'    => $medias,

            'guides' => $guides,

            'media_type' => $request->input('media_type'),
            'label_type' => $request->input('label_type'),
            'target_order_no' => $request->input('target_order_no'),
            'setting_media_no'  => $request->input('setting_media_no'),

            // TODO: 暫定実装、消す方向で修正を進める
            'form_params' => [
                'list_width' => $request->input('list_width'),
                'label_cd' => [
                    'outside'   => $request->input('label_cd.outside'),
                    'map'       => $request->input('label_cd.map'),
                    'inside'    => $request->input('label_cd.inside'),
                    'room'      => $request->input('label_cd.room'),
                    'other'     => $request->input('label_cd.other'),
                    'nothing'   => $request->input('label_cd.nothing'),
                ],

                // TODO: 適切な値を設定
                'room_id' => '',
                'plan_id' => '',
            ],
        ]);
    }

    /**
     * 施設画像更新
     *
     * @param Request $request
     * @param Service $service
     * @return \Illuminate\Http\Response
     */
    public function updateHotel(Request $request, Service $service)
    {
        $hotelCd    = $request->input('target_cd');
        $type       = $request->input('label_type');
        $oldMediaNo = $request->input('setting_media_no');
        $newMediaNo = $request->input('media_no');
        $orderNo    = $request->input('target_order_no');

        $errorMessages = $service->updateHotel($hotelCd, $type, $oldMediaNo, $newMediaNo, $orderNo);

        if (count($errorMessages) > 0) {
            return redirect()->back()->withErrors($errorMessages);
        }
        return redirect()->route('ctl.htl.media.edit_hotel', [
            'target_cd' => $hotelCd,
        ])->with([
            'guides' => ['施設画像を編集しました。'],
        ]);
    }

    /**
     * 施設画像（フォトギャラリー）削除
     *
     * @param Request $request
     * @param Service $service
     * @return \Illuminate\Http\Response
     */
    public function removeGallery(Request $request, Service $service)
    {
        $hotelCd = $request->input('target_cd');
        $mediaNoToRemove  = $request->input('setting_media_no');
        $service->removeGallery($hotelCd, $mediaNoToRemove);
        return redirect()->back()->with([
            'guides' => ['施設画像を編集しました。'],
        ]);
    }

    /**
     * 施設画像ソート（フォトギャラリーのみ）
     *
     * @param Request $request
     * @param Service $service
     * @return \Illuminate\Http\Response
     */
    public function sortGallery(Request $request, Service $service)
    {
        $hotelCd        = $request->input('target_cd');
        $mediaNo        = $request->input('media_no');
        $targetMediaNo  = $request->input('target_media_no');

        $succeeded = $service->sortGallery($hotelCd, $mediaNo, $targetMediaNo);

        if (!$succeeded) {
            return redirect()->back()->withErrors([
                '施設画像の並び替えに失敗しました。',
            ])->withInput();
        }

        return redirect()->route('ctl.htl.media.edit_hotel', [
            'target_cd' => $hotelCd,
        ])->with([
            'guides' => ['施設画像を並び替えました。'],
        ]);
    }
}
