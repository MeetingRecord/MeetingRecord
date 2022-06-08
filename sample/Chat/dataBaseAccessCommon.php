<?php

include("databaseDef.php");

// DBへ接続
function ConnectToDB()
{
  return _connectToDB(DB_HOST, 
  DB_CONNECT_PORT,
  DB_NAME, 
  DB_TABLE_NAME_USERS_TABEL_CHAR_SET,
  DB_ACCESS_USER,
  DB_ACCESS_PWD);
}

// DBへ接続
function _connectToDB($host, $port, $dbName, $charset, $user, $pwd)
{
  $dbn = "mysql:dbname=" . $dbName . 
  ";charset=" . $charset .
  ";port=" . $port .
  ";host=" . $host . ";";
  
  $pdo = NULL;
  try {
    $pdo = new PDO($dbn, $user, $pwd);
  } catch (PDOException $e) {
    echo json_encode(["db error" => "{$e->getMessage()}"]);
    exit();
  }
  return $pdo;
}

// function check_session_id()
// {
//   if (
//     !isset($_SESSION["session_id"]) ||
//     $_SESSION["session_id"] != session_id()
//   ) {
//     header("Location:todo_login.php");
//   } else {
//     session_regenerate_id(true);
//     $_SESSION["session_id"] = session_id();
//   }
// }

?>