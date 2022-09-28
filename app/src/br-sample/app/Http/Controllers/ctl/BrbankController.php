<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;
use App\Models\MastBank;
use App\Models\MastBankBranch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;
use App\Common\Traits;

/**
 * 管理画面_銀行支店マスタ
 */
class BrbankController extends _commonController
{
    use Traits;

    /**
     * 検索画面
     */
    public function index()
    {
        $keyword = "";
        $banks = [];

        // 検索条件を取得
        if(count(Request::query()) > 0){
            $keyword = trim(Request::query()["keyword"]);
            if($this->is_empty($keyword)){
                $this->addErrorMessage("銀行・支店名称を入力してください。");
            }
        }

        // 検索処理
        if(!$this->is_empty($keyword)){
            $mastBank = new MastBank();
            $banks = $mastBank->get_bankbranch($keyword);
        }
        
        // ビュー情報を設定
        $this->addViewData("keyword", $keyword);
        $this->addViewData("banks", $banks);
        // ビューを表示
        return view("ctl.brbank.search", $this->getViewData());
    }


    /**
     * 銀行新規登録画面
     */
    public function newbank()
    {
        $keyword = "";
        $bankArr = [];
        $request = Request::all();
        if(count($request) > 0){
            $keyword = isset($request["keyword"]) ? trim($request["keyword"]) : "";
            $bankArr = isset($request["bank"]) ? $request["bank"] : array("bank_cd"=>"", "bank_nm"=>"", "bank_kn"=>"");
        }

        // ビュー情報を設定
        $this->addViewData("keyword", $keyword);
        $this->addViewData("bank", $bankArr);
        // ビューを表示
        return view("ctl.brbank.newbank", $this->getViewData());
    }


    /**
     * 銀行 - 新規登録処理
     */
    public function createbank()
    {
        $request = Request::all();
        if(!isset($request["bank"])){
            $this->addErrorMessage("登録パラメータが存在しません");
            return $this->newbank();
        }

        // モデル
        $mastBank = new MastBank();
        // 登録情報
        $bankData = [];
        $bankData[$mastBank->COL_BANK_CD] = $request["bank"]["bank_cd"];
        $bankData[$mastBank->COL_BANK_NM] = $request["bank"]["bank_nm"];
        $bankData[$mastBank->COL_BANK_KN] = $request["bank"]["bank_kn"];
        // バリデーション
        $errorArr = $mastBank->validation($bankData);
        if(count($errorArr) > 0){
            $this->addErrorMessageArray($errorArr);
            return $this->newbank();
        }
        // 登録処理
        try{
    		$con = DB::connection('mysql');
    		$dbErr = $con->transaction(function() use($con, $mastBank, $bankData) {
                $actionCd = "Brbank/create." . "testUser";
                $bankData["entry_cd"] = $actionCd;
                $bankData["entry_ts"] = date("Y-m-d H:i:s");
                $bankData["modify_cd"] = $actionCd;
                $bankData["modify_ts"] = date("Y-m-d H:i:s");
                return $mastBank->singleInsert($con, $bankData);
            });
        } catch (\Exception $e) {
    		Log::error($e);
            $this->addErrorMessage("登録中に例外が発生しました");
            return $this->newbank();
    	}
        // 登録時エラー
        if (!empty($dbErr)){
            $this->addErrorMessage($dbErr);
            return $this->newbank();
        }

        // 再度取得
        $bankData = $mastBank->selectByKey($bankData[$mastBank->COL_BANK_CD]);

        // 成功
        $this->addViewData("keyword", $request["keyword"]);
        $this->addViewData("bank", $bankData);
        return view("ctl.brbank.createbank", $this->getViewData());
    }


    /**
     * 銀行確認画面
     */
    public function viewbank()
    {
        $keyword = "";
        $bankCd = "";
        $bankData = [];

        $request = Request::all();
        if(count($request) > 0){
            $keyword = isset($request["keyword"]) ? trim($request["keyword"]) : "";
            $bankCd = isset($request["bank_cd"]) ? trim($request["bank_cd"]) : "";
            $bankData = isset($request["bank"]) ? $request["bank"] : [];
        }

        // 銀行データ取得
        $mastBank = new MastBank();
        if($bankCd != "" && count($bankData) == 0) {
            $bankData = $mastBank->selectByKey($bankCd);
        }
        if(is_null($bankData)){
            // エラー
            $this->addErrorMessage("パラメータが不正です");
            $bankData = array("bank_cd"=>"", "bank_nm"=>"", "bank_kn"=>"");
        }
        // 支店情報の取得
        $mastBankBranch = new MastBankBranch();
        $branchData = $mastBankBranch->selectByBankCd($bankCd);

        // ビュー情報を設定
        $this->addViewData("keyword", $keyword);
        $this->addViewData("bank", $bankData);
        $this->addViewData("branch", $branchData);

        Log::info($keyword);//bank 蒲田 2060 array()
        Log::info(implode($bankData));//bank 蒲田 2060 array()
//TODO        Log::info(implode($branchData));//bank 蒲田 2060 array()

        return view("ctl.brbank.viewbank", $this->getViewData());
    }


    /**
     * 銀行 - 更新処理
     */
    public function updatebank()
    {
        $request = Request::all();
        if(!isset($request["bank"])){
            $this->addErrorMessage("更新パラメータが存在しません");
            return $this->viewbank();
        }

        // モデル
        $mastBank = new MastBank();
        // 更新情報
        $bankCd = $request["bank"]["bank_cd"];
        $bankData = [];
        $bankData[$mastBank->COL_BANK_NM] = $request["bank"]["bank_nm"];
        $bankData[$mastBank->COL_BANK_KN] = $request["bank"]["bank_kn"];
        // バリデーション
        $errorArr = $mastBank->validation($bankData);
        if(count($errorArr) > 0){
            $this->addErrorMessageArray($errorArr);
            return $this->viewbank();
        }
        // 更新処理
        try{
            $con = DB::connection('mysql');
            $dbErr = $con->transaction(function() use($con, $mastBank, $bankCd, $bankData) {
                $actionCd = "Brbank/create." . "testUser";
                $bankData["modify_cd"] = $actionCd;
                $bankData["modify_ts"] = date("Y-m-d H:i:s");
                return $mastBank->singleUpdate($con, $bankCd, $bankData);
            });

        } catch (\Exception $e) {
            Log::error($e);
            $this->addErrorMessage("更新中に例外が発生しました");
            return $this->viewbank();
        }
        // 更新時エラー
        if (!empty($dbErr)){
            $this->addErrorMessage($dbErr);
            return $this->viewbank();
        }

        // 再度取得
        $bankData = $mastBank->selectByKey($bankCd);

        // 成功
        $this->addViewData("keyword", $request["keyword"]);
        $this->addViewData("bank", $bankData);
        return view("ctl.brbank.updatebank", $this->getViewData());
    }
    

    /**
     * 支店新規登録画面
     */
    public function newbankbranch()
    {
        $keyword = "";
        $bankCd = "";
        $branchData = array("bank_cd"=>"", "bank_branch_cd"=>"", "bank_branch_nm"=>"", "bank_branch_kn"=>"");
        $request = Request::all();
        if(count($request) > 0){
            $keyword = isset($request["keyword"]) ? trim($request["keyword"]) : "";
            $bankCd  = isset($request["bank_cd"]) ? trim($request["bank_cd"]) : "";
            $branchData = isset($request["bank_branch"]) ? $request["bank_branch"] : $branchData;
        }

        // 銀行データ取得
        $mastBank = new MastBank();
        $bankData = $mastBank->selectByKey($bankCd);
        if(is_null($bankData)){
            $this->addErrorMessage("ご指定の銀行が存在しませんでした");
            return $this->_forward('search');
        }
        
        // ビュー情報を設定
        $this->addViewData("keyword", $keyword);
        $this->addViewData("bank", $bankData);
        $this->addViewData("bank_branch", $branchData);
        // ビューを表示
        return view("ctl.brbank.newbranch", $this->getViewData());
    }


    /**
     * 支店 - 新規登録処理
     */
    public function createbankbranch()
    {
        $request = Request::all();
        if(!isset($request["bank_branch"])){
            $this->addErrorMessage("登録パラメータが存在しません");
            return $this->newbankbranch();
        }
        $branchData = $request["bank_branch"];

        // 銀行存在チェック
        $mastBank = new MastBank();
        $bankData = $mastBank->selectByKey($branchData['bank_cd']);
        if(is_null($bankData)){
            $this->addErrorMessage("ご指定の銀行が存在しませんでした");
            return $this->_forward('search');
        }

        // モデル
        $mastBankBranch = new MastBankBranch();
        // 登録情報
        $branchData = [];
        $branchData[$mastBankBranch->COL_BANK_CD]        = $request["bank_branch"]["bank_cd"];
        $branchData[$mastBankBranch->COL_BANK_BRANCH_CD] = $request["bank_branch"]["bank_branch_cd"];
        $branchData[$mastBankBranch->COL_BANK_BRANCH_NM] = $request["bank_branch"]["bank_branch_nm"];
        $branchData[$mastBankBranch->COL_BANK_BRANCH_KN] = $request["bank_branch"]["bank_branch_kn"];
        // バリデーション
        $errorArr = $mastBankBranch->validation($branchData);
        if(count($errorArr) > 0){
            $this->addErrorMessageArray($errorArr);
            return $this->newbankbranch();
        }

        // 登録処理
        try{
            $con = DB::connection('mysql');
            $dbErr = $con->transaction(function() use($con, $mastBankBranch, $branchData) {
                $actionCd = "Brbank/create." . "testUser";
                $branchData["entry_cd"] = $actionCd;
                $branchData["entry_ts"] = date("Y-m-d H:i:s");
                $branchData["modify_cd"] = $actionCd;
                $branchData["modify_ts"] = date("Y-m-d H:i:s");
                return $mastBankBranch->singleInsert($con, $branchData);
            });
        } catch (\Exception $e) {
            Log::error($e);
            $this->addErrorMessage("登録中に例外が発生しました");
            return $this->newbank();
        }
        // 登録時エラー
        if (!empty($dbErr)){
            $this->addErrorMessage($dbErr);
            return $this->newbankbranch();
        }

        // 再取得
        $branchData = $mastBankBranch->selectByKey(
            $branchData[$mastBankBranch->COL_BANK_CD], $branchData[$mastBankBranch->COL_BANK_BRANCH_CD]
        );

        // 成功
        $this->addGuideMessage("下記内容で新規登録しました");
        $this->addViewData("keyword", $request["keyword"]);
        $this->addViewData("bank", $bankData);
        $this->addViewData("bank_branch", $branchData);
        return view("ctl.brbank.createbranch", $this->getViewData());
    }


    /**
     * 支店確認画面
     */
    public function viewbankbranch()
    {
        $keyword = "";
        $bankCd = "";
        $branchCd = "";
        $branchData = null;
        $request = Request::all();
        if(count($request) > 0){
            $keyword  = isset($request["keyword"]) ? trim($request["keyword"]) : "";
            $bankCd   = isset($request["bank_cd"]) ? trim($request["bank_cd"]) : "";
            $branchCd = isset($request["bank_branch_cd"]) ? trim($request["bank_branch_cd"]) : "";
            $branchData = isset($request["bank_branch"]) ? $request["bank_branch"] : $branchData;
        }
        // 銀行データ取得
        $mastBank = new MastBank();
        $bankData = $mastBank->selectByKey($bankCd);
        if(is_null($bankData)){
            $this->addErrorMessage("ご指定の銀行が存在しませんでした");
            return $this->_forward('search');
        }
        // 支店情報取得
        if(is_null($branchData)){
            $mastBankBranch = new MastBankBranch();
            $branchData = $mastBankBranch->selectByKey($bankCd, $branchCd);
            if(is_null($branchData)){
                $this->addErrorMessage("ご指定の支店が存在しませんでした");
                return $this->_forward('search');
            }
        }

        // ビュー情報を設定
        $this->addViewData("keyword", $keyword);
        $this->addViewData("bank", $bankData);
        $this->addViewData("bank_branch", $branchData);
        // ビューを表示
        return view("ctl.brbank.viewbranch", $this->getViewData());
    }


    /**
     * 支店 - 更新処理
     */
    public function updatebankbranch()
    {
        $request = Request::all();
        if(!isset($request["bank_branch"])){
            $this->addErrorMessage("更新パラメータが存在しません");
            return $this->viewbankbranch();
        }

        // Key
        $bankCd = $request["bank_branch"]["bank_cd"];
        $branchCd = $request["bank_branch"]["bank_branch_cd"];
        
        // 銀行存在チェック
        $mastBank = new MastBank();
        $bankData = $mastBank->selectByKey($bankCd);
        if(is_null($bankData)){
            $this->addErrorMessage("ご指定の銀行が存在しませんでした");
            return $this->_forward('search');
        }

        // 更新情報
        $mastBankBranch = new MastBankBranch();
        $branchData = [];
        $branchData[$mastBankBranch->COL_BANK_BRANCH_NM] = $request["bank_branch"]["bank_branch_nm"];
        $branchData[$mastBankBranch->COL_BANK_BRANCH_KN] = $request["bank_branch"]["bank_branch_kn"];
        // バリデーション
        $errorArr = $mastBankBranch->validation($branchData);
        if(count($errorArr) > 0){
            $this->addErrorMessageArray($errorArr);
            return $this->viewbankbranch();
        }
        // 更新処理
        try{
            $con = DB::connection('mysql');
            $dbErr = $con->transaction(function() use($con, $mastBankBranch, $bankCd, $branchCd, $branchData) {
                $actionCd = "Brbank/create." . "testUser";
                $branchData["modify_cd"] = $actionCd;
                $branchData["modify_ts"] = date("Y-m-d H:i:s");
                return $mastBankBranch->singleUpdate($con, $bankCd, $branchCd, $branchData);
            });
        } catch (\Exception $e) {
            Log::error($e);
            $this->addErrorMessage("更新中に例外が発生しました");
            return $this->viewbankbranch();
        }
        // 更新時エラー
        if (!empty($dbErr)){
            $this->addErrorMessage($dbErr);
            return $this->viewbankbranch();
        }

        // 再度取得
        $branchData = $mastBankBranch->selectByKey($bankCd, $branchCd);

        // 成功
        $this->addViewData("keyword", $request["keyword"]);
        $this->addViewData("bank", $bankData);
        $this->addViewData("bank_branch", $branchData);
        return view("ctl.brbank.updatebranch", $this->getViewData());
    }

}
