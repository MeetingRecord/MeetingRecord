<?php

// オーディオ ファイルを読み込む
$filename = 'hello.wav';
$fp = fopen( $filename, 'rb' );
$content = fread( $fp, filesize( $filename ) );
fclose( $fp );

// それをBase64エンコードする
$encodedContent = base64_encode( $content );

// JSON形式でリクエスト ボディを生成する
$requestBody = <<<EOM
{
  "config": {
    "encoding": "FLAC",
    "sampleRateHertz": 48000,
    "languageCode": "ja-JP"
  },
  "audio": {
    "content": "{$encodedContent}"
  }
}
EOM;

$apiKey = ''; // APIキー
$url = 'https://speech.googleapis.com/v1/speech:recognize?key='.$apiKey;

$header = array( 'Content-Type: application/json; charset=utf-8' );

$ch = curl_init( $url );
curl_setopt( $ch, CURLOPT_HTTPHEADER,  $header );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $requestBody );
curl_setopt( $ch, CURLOPT_FAILONERROR, TRUE );
curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );

// リクエストし、結果を出力する
if (curl_exec($ch) === FALSE) echo curl_error($ch);
curl_close($ch);
