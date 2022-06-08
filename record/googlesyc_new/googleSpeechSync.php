<?php
/** googleSpeechSync.php
 * 「Google Cloud Speech」を利用し、音声ファイルをテキスト変換する
 *
 * @copyright	(c)studio pahoo
 * @author		パパぱふぅ
 * @参考URL		http://www.pahoo.org/e-soul/webtech/php06/php06-62-01.shtm
 *
*/
// 初期化処理 ================================================================
define('INTERNAL_ENCODING', 'UTF-8');
mb_internal_encoding(INTERNAL_ENCODING);
mb_regex_encoding(INTERNAL_ENCODING);
define('MYSELF', basename($_SERVER['SCRIPT_NAME']));
define('REFERENCE', 'http://www.pahoo.org/e-soul/webtech/php06/php06-62-01.shtm');

//プログラム・タイトル
define('TITLE', 'Google Cloud Speech：音声をテキスト変換');

//リリース・フラグ（公開時にはTRUEにすること）
define('FLAG_RELEASE', FALSE);

//GoogleGeoCodeAPI クラス
require_once('pahooGoogleCloud.php');

/**
 * 共通HTMLヘッダ
 * @global string $HtmlHeader
*/
$encode = INTERNAL_ENCODING;
$title = TITLE;
$HtmlHeader =<<< EOT
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="{$encode}">
<title>{$title}</title>
<meta name="author" content="studio pahoo" />
<meta name="copyright" content="studio pahoo" />
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW" />
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<style type="text/css">
.balloon {
	position: relative;
	display: inline-block;
	margin: 1.5em 0;
	padding: 7px 10px;
	min-width: 120px;
	max-width: 500px;
	color: #555;
	font-size: 16px;
	background: #FFF;
	border: solid 3px #555;
	box-sizing: border-box;
	border-radius: 15px;
}

.balloon:before {
	content: "";
	position: absolute;
	top: -24px;
	left: 50%;
	margin-left: -15px;
	border: 12px solid transparent;
	border-bottom: 12px solid #FFF;
	z-index: 2;
}

.balloon:after {
	content: "";
	position: absolute;
	top: -30px;
	left: 50%;
	margin-left: -17px;
	border: 14px solid transparent;
	border-bottom: 14px solid #555;
	z-index: 1;
}

.balloon p {
	margin: 0;
	padding: 0;
}
</style>
</head>

EOT;

/**
 * 共通HTMLフッタ
 * @global string $HtmlFooter
*/
$HtmlFooter =<<< EOT
</html>

EOT;

// サブルーチン ==============================================================
/**
 * エラー処理ハンドラ
*/
function myErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
	echo "Sory, system error occured !";
	exit(1);
}
error_reporting(E_ALL);
if (FLAG_RELEASE)	$old_error_handler = set_error_handler('myErrorHandler');

/**
 * 指定したパラメータを取り出す
 * @param	string $key  パラメータ名（省略不可）
 * @param	bool   $auto TRUE＝自動コード変換あり／FALSE＝なし（省略時：TRUE）
 * @param	mixed  $def  初期値（省略時：空文字）
 * @return	string パラメータ／NULL＝パラメータ無し
*/
function getParam($key, $auto=TRUE, $def='') {
	if (isset($_GET[$key]))			$param = $_GET[$key];
	else if (isset($_POST[$key]))	$param = $_POST[$key];
	else							$param = $def;
	if ($auto)	$param = mb_convert_encoding($param, INTERNAL_ENCODING, 'auto');
	return $param;
}

/**
 * 指定したファイル名を取り出す
 * @param	string $key  パラメータ名（省略不可）
 * @param	mixed  $def  初期値（省略時：空文字）
 * @return	string パラメータ／NULL＝パラメータ無し
*/
function getFileName($key, $def='') {
	$param = $def;
	if (isset($_FILES[$key]['tmp_name']))	$param = $_FILES[$key]['tmp_name'];
	return $param;
}

/**
 * HTML BODYを作成する
 * @param	int    $mode   0：全部表示，1:テキストのみ
 * @param	string $file   音声ファイル
 * @param	array  $text   テキスト
 * @param	string $url    WebAPIのURL
 * @param   string $errmsg エラーメッセージ
 * @return	string HTML
*/
function makeCommonBody($mode, $file, $text, $url, $errmsg) {
	$title  = TITLE;
	$refere = REFERENCE;
	$myself = MYSELF;
	$version = '<span style="font-size:small;">' . date('Y/m/d版', filemtime(__FILE__)) . '</span>';

	if (FLAG_RELEASE) {
		$msg = '';
	} else {
		$phpver = phpversion();
		$msg =<<< EOT
PHPver : {$phpver}<br />
WebAPI : <a href="{$url}">{$url}</a>
<dl>

EOT;
	}

	//検索結果
	if ($errmsg != '') {
		$res =<<< EOD
<p style="color:red">
エラー：{$errmsg}．
</p>

EOD;
	} else {
		$res = "<div class=\"balloon\"><p>{$text}</p></div>";
	}

	if ($mode == 0) {
		$html =<<< EOD
<body>
<h2>{$title} {$version}</h2>
<form name="myform" method="post" action="{$myself}" enctype="multipart/form-data">
音声ファイル：<input type="file" id="image" name="image" />
<input type="submit" id="exec" name="exec" value="解析" />
</form>
{$res}
<div style="border-style:solid; border-width:1px; margin:20px 0px 20px 0px; padding:5px; width:550px; font-size:small; overflow:auto;">
<h3>使い方</h3>
<ol>
<li>［<span style="font-weight:bold;">音声ファイル</span>］で音声ファイルを選択してください。<br />
入力できるのは、WAV形式の音声ファイル（16bit，44KHz，モノラル）のみです。
</li>
<li>［<span style="font-weight:bold;">解析</span>］ ボタンを押してください。</li>
<li>テキストが表示されます。</li>
</ol>
※参考サイト：<a href="{$refere}">{$refere}</a>
<p>{$msg}</p>
</div>
</body>

EOD;
	} else {
		$html =<<< EOD
<body>
{$res}
</body>

EOD;
	}

	return $html;
}

// メイン・プログラム =======================================================
//初期値
$mode = getParam('mode', FALSE, 0);
$file = getFileName('image', '');
$text = '';
$pgc = new pahooGoogleCloud();

//音声認識
if ($pgc->errmsg == '' && $file != '') {
	$text = $pgc->speech_syncrecognize($file);
}
$HtmlBody = makeCommonBody($mode, $file, $text, $pgc->webapi, $pgc->errmsg);

// 表示処理
echo $HtmlHeader;
echo $HtmlBody;
echo $HtmlFooter;

$pgc = NULL;

/*
** バージョンアップ履歴 ===================================================
 *
 * @version  1.0  2017/09/17
*/
