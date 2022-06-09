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

// (チャンネル名から)チャンネルID取得
function GetChannelIdFromChannelName($channel_name){

  $pdo = ConnectToDB();

  // チャンネル重複チェック
  $sql = 'SELECT * FROM channel_table WHERE channel_name=:channel_name';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':channel_name', $channel_name, PDO::PARAM_STR);

  try {
    $status = $stmt->execute();
  } catch (PDOException $e) {
    $output = [
    "result" => false,
    "detail" => "{$e->getMessage()}",
    ];
    echo json_encode($output);
    exit();
  }

  $val = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($val){
    return $val["id"];
  }else{
    return -1;
  }
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