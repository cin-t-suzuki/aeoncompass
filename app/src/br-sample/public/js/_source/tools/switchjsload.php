<?php

	//
	// 読み込むクライアントサイドスクリプトを決定する設定を変更します。
	// 

	try {

		// 引数を確認
		if ($argc == 2) {
			$s_config_file = $argv[1];

			// 設定ファイルに書き込めるかを確認
			if (is_writable($s_config_file)) {
			
				// 設定ファイルがXMLなのかを確認
				$o_config_xml = simplexml_load_file($s_config_file);
				if ($o_config_xml) {
				
					// 設定ファイルの値を変更
					if ($o_config_xml->environment->jsload == 'unpack') {
						$o_config_xml->environment->jsload = null;
					} else {
						$o_config_xml->environment->jsload = 'unpack';
					}
					
					// 設定ファイルを出力
					$f = fopen($s_config_file, 'w');
					fwrite($f, $o_config_xml->asXML());
					fclose($f);
					
					// 結果をメッセージで表示
					if ($o_config_xml->environment->jsload == 'unpack') {
						echo '[OK] load development (unpack) script';
					} else {
						echo '[OK] load product (packed) script';
					}
				} else {
					// ファイルがXMLではない
					echo '[NG] not xml (' . $s_config_file .')';
				}
			} else {
				// ファイルに書き込み権限がない
				echo '[NG] not write (' . $s_config_file .')';
			}
		} else {
			// ファイルが指定されていない
			echo '[NG] syntax error';
		}
	
	} catch (Exception $e) {
	
		// 例外エラーが発生
		echo '[NG] exception error';
	}

?>