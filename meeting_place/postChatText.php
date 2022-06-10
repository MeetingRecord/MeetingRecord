<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');

include("dataBaseAccessCommon.php");

session_start();

$pdo = ConnectToDB();

if (
  !isset($_POST['channelname']) || $_POST['channelname'] == '' ||
  !isset($_POST['chattext']) || $_POST['chattext'] == ''
) {
  $output = [
  "result" => false,
  "detail" => "NO_INPUT",
  "channelname" => "",
  "chattext" => "",
  ];
  echo json_encode($output);
  exit();
}

$channel_name = $_POST["channelname"];
$chattext = $_POST["chattext"];
$channel_id = GetChannelIdFromChannelName($channel_name);

$sql = 'INSERT INTO chat_table(id, channel_id, user_id, chat, post_time) VALUES(NULL, :channel_id, :user_id, :chat, sysdate())';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':channel_id', $channel_id, PDO::PARAM_STR);
$stmt->bindValue(':user_id', $_SESSION["id"], PDO::PARAM_STR);
$stmt->bindValue(':chat', $chattext, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  $output = [
    "result" => false,
    "detail" => "{$e->getMessage()}",
    "channelname" => $channel_name,
    "chattext" => $chattext,
    ];
  echo json_encode($output);
  exit();
}

$output = [
  "result" => true,
  "detail" => "NORMAL_END",
  "channelname" => $channel_name,
  "chattext" => $chattext,
  ];
echo json_encode($output);

?>