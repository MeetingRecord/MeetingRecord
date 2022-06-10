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
  "channelName" => null,
  "channel_id" => null,
  "chatList" => null,
  ];
  echo json_encode($output);
  exit();
}

$channelname = $_POST["channelname"];
$channel_id = GetChannelIdFromChannelName($channelname);

// SQL作成&実行
$sql = 'SELECT * FROM chat_table LEFT OUTER JOIN users_table ON chat_table.user_id = users_table.id WHERE channel_id=:channel_id ORDER BY post_time ASC';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':channel_id', $channel_id, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  $output = [
  "result" => false,
  "detail" => "{$e->getMessage()}",
  "channelName" => $channelname,
  "channel_id" => $channel_id,
  "chatList" => null,
  ];
  echo json_encode($output);
  exit();
}

$list = $stmt->fetchAll(PDO::FETCH_ASSOC);

$output = [
  "result" => true,
  "detail" => "NORMAL_END",
  "channelName" => $channelname,
  "channel_id" => $channel_id,
  "chatList" => $list,
  ];
  echo json_encode($output);
?>