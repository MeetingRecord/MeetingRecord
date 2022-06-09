<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');

include("dataBaseAccessCommon.php");

$pdo = ConnectToDB();

if (
  !isset($_POST['username']) || $_POST['username'] == '' ||
  !isset($_POST['username']) || $_POST['username'] == ''
) {
  $output = [
  "result" => false,
  "detail" => "NO_INPUT",
  "userName" => "",
  "pw" => "",
  ];
  echo json_encode($output);
  exit();
}

$username = $_POST["username"];
$password = $_POST["password"];

$sql = 'SELECT * FROM users_table WHERE username=:username AND password=:password AND is_deleted=0';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  $output = [
  "result" => false,
  "detail" => "{$e->getMessage()}",
  "userName" => $username,
  "pw" => $password,
  ];
  echo json_encode($output);
  exit();
}

$val = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$val) {
  $output = [
  "result" => true,
  "detail" => "NO_USER",
  "userName" => $username,
  "pw" => $password,
  ];
} else {
  session_start();
  $_SESSION = array();
  $_SESSION["session_id"] = session_id();
  $_SESSION["is_admin"] = $val["is_admin"];
  $_SESSION["username"] = $val["username"];
  $_SESSION["id"] = $val["id"];

  $output = [
  "result" => true,
  "detail" => "NORMAL_END",
  "userName" => $username,
  "pw" => $password,
  ];
}

echo json_encode($output);

?>