<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');

include("dataBaseAccessCommon.php");

$pdo = ConnectToDB();

if (
  !isset($_POST['channelname']) || $_POST['channelname'] == ''
) {
  $output = [
  "result" => false,
  "detail" => "NO_INPUT",
  "channelname" => "",
  ];
  echo json_encode($output);
  exit();
}

$channel_name = $_POST["channelname"];

// チャンネル重複チェック
$sql = 'SELECT COUNT(*) FROM channel_table WHERE channel_name=:channel_name';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':channel_name', $channel_name, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  $output = [
  "result" => false,
  "detail" => "{$e->getMessage()}",
  "channelname" => $channel_name,
  ];
  echo json_encode($output);
  exit();
}

if ($stmt->fetchColumn() > 0) {
  $output = [
  "result" => true,
  "detail" => "ALREADY_EXIST",
  "channelname" => $channel_name,
  ];
  echo json_encode($output);
  exit();
}

// チャンネル追加
$sql = 'INSERT INTO channel_table(id, channel_name, is_deleted) VALUES(NULL, :channel_name, 0)';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':channel_name', $channel_name, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  $output = [
    "result" => false,
    "detail" => "{$e->getMessage()}",
    "channelname" => $channel_name,
    ];
  echo json_encode($output);
  exit();
}

$output = [
  "result" => true,
  "detail" => "NORMAL_END",
  "channelname" => $channel_name,
  ];
echo json_encode($output);

?>