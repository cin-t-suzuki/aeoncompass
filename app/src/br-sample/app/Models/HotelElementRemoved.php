<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;

class HotelElementRemoved extends CommonDBModel

{


		// テーブル名称
		protected $table = 'hotel_element_removed';
		protected $primaryKey = ['hotel_cd', 'table_name'];
		public $timestamps = false;

		// フィールド名称
		protected $fillable = [
			'hotel_cd',
			'table_name',
			'destroy_dtm',
			'entry_cd',
			'entry_ts',
			'modify_cd',
			'modify_ts'
		];
	
		function __construct(){


		}


	}

?>