<?php

include("dataBaseAccessCommon.php");

session_start();

$pdo = ConnectToDB();

if (
  !isset($_POST['chattext']) || $_POST['chattext'] == ''
) {
  $output = [
  "result" => false,
  "detail" => "NO_INPUT",
  "chattext" => "",
  ];
  echo json_encode($output);
  exit();
}

$chattext = $_POST["chattext"];

$sql = 'INSERT INTO chat_table(id, user_id, chat, created_at) VALUES(NULL, :user_id, :chat, sysdate())';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $_SESSION["id"], PDO::PARAM_STR);
$stmt->bindValue(':chat', $chattext, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  $output = [
    "result" => false,
    "detail" => "{$e->getMessage()}",
    "chattext" => $chattext,
    ];
  echo json_encode($output);
  exit();
}

$output = [
  "result" => true,
  "detail" => "NORMAL_END",
  "chattext" => $chattext,
  ];
echo json_encode($output);

?>