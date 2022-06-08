<?php

include("dataBaseAccessCommon.php");

$pdo = ConnectToDB();

if (
  !isset($_POST['username']) || $_POST['username'] == '' ||
  !isset($_POST['password']) || $_POST['password'] == ''
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

$sql = 'SELECT COUNT(*) FROM users_table WHERE username=:username';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':username', $username, PDO::PARAM_STR);

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

if ($stmt->fetchColumn() > 0) {
  $output = [
  "result" => true,
  "detail" => "ALREADY_EXIST",
  "userName" => $username,
  "pw" => $password,
  ];
  echo json_encode($output);
  exit();
}

$sql = 'INSERT INTO users_table(id, username, password, is_admin, is_deleted, created_at, updated_at) VALUES(NULL, :username, :password, 0, 0, sysdate(), sysdate())';

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

$output = [
  "result" => true,
  "detail" => "NORMAL_END",
  "userName" => $username,
  "pw" => $password,
  ];
echo json_encode($output);

?>