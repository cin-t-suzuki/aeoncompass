<?php

namespace App\Http\Controllers\rsv;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


/**
 * 宿泊予約用、共通コントローラ
 */
class _commonController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** 会員情報 */
    protected $member = null;
    /** 管理サイトオペレーター情報 */
    protected $operator = null;
    /** パートナー情報 */
    protected $partner = null;

    /** View用のデータ */
    protected $viewData = [];
    /** エラーメッセージ配列 */
    protected $errorMessageArr = [];
    /** ガイドメッセージ配列 */
    protected $guideMessageArr = [];


    /**
     * Viewへの受渡データ取得
     */
    protected function getViewData(){
        return [
            "views" => (object)$this->viewData,
            "messages" => [
                "errors" => $this->errorMessageArr,
                "guides" => $this->guideMessageArr,
            ],
        ];
    }

    /**
     * Viewデータの追加
     */
    protected function addViewData($key, $value){
        $this->viewData[$key] = $value;
    }

    /**
     * エラーメッセージの追加
     */
    protected function addErrorMessage($msg){
        $this->errorMessageArr[] = $msg;
    }
    /**
     * エラーメッセージ配列の追加
     */
    protected function addErrorMessageArray($msgArr){
        $this->errorMessageArr = array_merge($this->errorMessageArr, $msgArr);
    }

    /**
     * ガイドメッセージの追加
     */
    protected function addGuideMessage($msg){
        $this->guideMessageArr[] = $msg;
    }

}
