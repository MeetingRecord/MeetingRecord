<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');

include("dataBaseAccessCommon.php");

$pdo = ConnectToDB();

$sql = 'SELECT channel_name FROM channel_table WHERE is_deleted=0';
$stmt = $pdo->prepare($sql);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  $output = [
  "result" => false,
  "detail" => "{$e->getMessage()}",
  "channelList" => null,
  ];
  echo json_encode($output);
  exit();
}

$list = $stmt->fetchAll(PDO::FETCH_ASSOC);

$output = [
  "result" => true,
  "detail" => "NORMAL_END",
  "channelList" => $list,
  ];

echo json_encode($output);
?>