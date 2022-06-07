<?php
/** pahooGoogleCloud.php
 * Google Cloud APIに関わるクラス（PHP 5/7）
 *
 * @copyright	(c)studio pahoo
 * @author		パパぱふぅ
 * @参考URL		http://www.pahoo.org/e-soul/webtech/php06/php06-62-01.shtm
 *
 * [利用するWebAPI]
 *  Google CloudPlatform
*/

//GooglgCloudクラス ==========================================================
class pahooGoogleCloud {
	var $items;		//検索結果格納用
	var $error;		//エラーフラグ
	var $errormsg;		//エラーメッセージ
	var $hits;			//検索ヒット件数
	var $webapi;		//直前に呼び出したWebAPI URL

	//Google API KEY
	//https://developers.google.com/maps/web/
	var $GOOGLE_API_KEY = '**************************';

/**
 * コンストラクタ
 * @param	なし
 * @return	なし
*/
function __construct() {
	$this->error  = FALSE;
	$this->errmsg = '';
	$this->hits = 0;
	$this->webapi = '';
	if (! $this->isphp5over()) {
		$this->error  = TRUE;
		$this->errmsg = 'PHP5 未満では動作しません';
	}
}

/**
 * デストラクタ
 * @return	なし
*/
function __destruct() {
	unset($this->items);
}

/**
 * エラー状況
 * @return	bool TRUE:異常／FALSE:正常
*/
function iserror() {
	return $this->error;
}

/**
 * エラーメッセージ取得
 * @param	なし
 * @return	string 現在発生しているエラーメッセージ
*/
function geterror() {
	return $this->errmsg;
}

/**
 * PHP5以上かどうか検査する
 * @return	bool TRUE：PHP5以上／FALSE:PHP5未満
*/
function isphp5over() {
	$version = explode('.', phpversion());

	return $version[0] >= 5 ? TRUE : FALSE;
}

// GoogleCloud Speech API ===================================================
/**
 * Google Cloud Speech API を用いて音声データからテキストを起こす
 * @param	string $file  音声ファイル名
 * @return	string テキスト／FALSE：失敗
*/
function speech_syncrecognize($file) {
	$url = 'https://speech.googleapis.com/v1beta1/speech:syncrecognize?key=' . $this->GOOGLE_API_KEY;
	$this->webapi = $url;
	$n = 1;

	//音声データファイル読み込み
	$data = @file_get_contents($file);
	if ($data == FALSE) {
		$this->error = TRUE;
		$this->errmsg = $file . ' が読み込めない';
		return FALSE;
	}
	$contents = base64_encode($data);

	$json =<<< EOD
{
	"config": {
		"encoding" : "LINEAR16",
		"sample_rate" : 44100,
		"language_code" : "ja-JP"
	},
	"audio": {
		"content" : "{$contents}"
	}
}

EOD;
	$header = array('Content-Type: application/json');

	//cURLを使ってリクエスト
	$curl = curl_init() ;
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_POST, TRUE); 
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);		//証明書は無視
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);		//結果を文字列で
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);

	$res = curl_exec($curl);
	curl_close($curl);

	if ($res == FALSE || $res == '') {
		$this->error = TRUE;
		$this->errmsg = $file . ' の音声解析に失敗';
		return FALSE;
	}

	//結果処理
	$this->webapi = $url;
	$this->responses = json_decode($res);

	if (isset($this->responses->error)) {
		$this->error = TRUE;
		$this->errmsg = $this->responses->error->status;
		return FALSE;
	} else if (! isset($this->responses->results[0]->alternatives[0]->transcript)) {
		$this->error = TRUE;
		$this->errmsg = $file . ' の音声解析に失敗';
		return FALSE;
	}

	return $this->responses->results[0]->alternatives[0]->transcript;
}

// End of Class ===========================================================
}

/*
** バージョンアップ履歴 ===================================================
 *
 * @version  1.0  2017/09/17
*/
