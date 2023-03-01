<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * アフィリエイター マスタ
 */
class Affiliater extends CommonDBModel
{
    use Traits;

    protected $table = "affiliater";

    // カラム
    const COL_AFFILIATER_CD = "affiliater_cd";
    const COL_AFFILIATER_NM = "affiliater_nm";
    const COL_URL = "url";
    const COL_ACCOUNT_ID = "account_id";
    const COL_PASSWORD = "password";
    const COL_POSTAL_CD = "postal_cd";
    const COL_ADDRESS = "address";
    const COL_TEL = "tel";
    const COL_FAX = "fax";
    const COL_PERSON_POST = "person_post";
    const COL_PERSON_NM = "person_nm";
    const COL_PERSON_KN = "person_kn";
    const COL_PERSON_EMAIL = "person_email";
    const COL_OPEN_YMD = "open_ymd";

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // これ自体は残さないとエラーになる
    }


    /**
     * 主キーで取得
     *
     * @param string $affiliater_cd
     * @return array
     */
    public function selectByKey($affiliater_cd)
    {
        $data = $this->where("affiliater_cd", $affiliater_cd)->get();
        if (!is_null($data) && count($data) > 0) {
            return [
                self::COL_AFFILIATER_CD => $data[0]->affiliater_cd,
                self::COL_AFFILIATER_NM => $data[0]->affiliater_nm,
                self::COL_URL => $data[0]->url,
                self::COL_ACCOUNT_ID => $data[0]->account_id,
                self::COL_PASSWORD => $data[0]->password,
                self::COL_POSTAL_CD => $data[0]->postal_cd,
                self::COL_ADDRESS => $data[0]->address,
                self::COL_TEL => $data[0]->tel,
                self::COL_FAX => $data[0]->fax,
                self::COL_PERSON_POST => $data[0]->person_post,
                self::COL_PERSON_NM => $data[0]->person_nm,
                self::COL_PERSON_KN => $data[0]->person_kn,
                self::COL_PERSON_EMAIL => $data[0]->person_email,
                self::COL_OPEN_YMD => $data[0]->open_ymd
            ];
        }
        return null;
    }

}
