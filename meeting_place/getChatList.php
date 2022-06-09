<?php

include("dataBaseAccessCommon.php");

$pdo = ConnectToDB();

if (
  !isset($_POST['channelName']) || $_POST['channelName'] == ''
) {
  $output = [
  "result" => false,
  "detail" => "NO_INPUT",
  "chatList" => null,
  ];
  echo json_encode($output);
  exit();
}

$channelName = $_POST["channelName"];

// SQL作成&実行
$sql = 'SELECT * FROM chat_table LEFT OUTER JOIN users_table ON chat_table.user_id = users_table.id';
$stmt = $pdo->prepare($sql);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  $output = [
  "result" => false,
  "detail" => "{$e->getMessage()}",
  "chatList" => null,
  ];
  echo json_encode($output);
  exit();
}

$list = $stmt->fetchAll(PDO::FETCH_ASSOC);

$output = [
  "result" => true,
  "detail" => "NORMAL_END",
  "chatList" => $list,
  ];
  echo json_encode($output);
?>