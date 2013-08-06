<?php
session_start();

function createMenu(){
  $mysqli = new mysqli("localhost", "admin", "manager", "test");
  if (mysqli_connect_errno()) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      $error_message = "せやかて工藤、接続失敗してもーたがな！";
      exit();
  }
  $query = "SELECT no,name FROM lure";
  if ($result = $mysqli->query($query)) {
    /* 連想配列を取得します */
    print "<select name=\"example\">";
    while ($row = $result->fetch_assoc()) {
      //printf ("%s (%s)\n", $row["no"], $row["name"]);
      // 取得したデータをリストボックス化
      print '<option value="' . $row["no"] . '">' . $row["name"] . '</option>';
    }
    print "</select>";
    
    /* 結果セットを開放します */
    $result->free();
    // 接続――――断――――!!
    mysqli_close($mysqli);
  }
}
?>

<!-- ここからHTML -->
<html>
  <head>
    <title>メニュー</title>
  </head>
  <body>
  
  <?php
    print 'ようこそ<font color="red">'.$_SESSION["user_name"].'さん</font><br/>';
  ?>
  メニューだお！<br/>
  <?php
    createMenu();
  ?>
  <form method="GET" action="menu.php">
  <input type="submit" value="ログアウト" name="logout" />
  <?php
    // ログアウトボタンが押されたら...
    if (isset($_GET["logout"])) {
      $_SESSION = array();	// セッション変数を空にする
      if (isset($_COOKIE["PHPSESSID"])) {
        setcookie("PHPSESSID", '', time() - 1800, '/');	// cookieがあったらそれも消す
      }
      session_destroy();	// セッション破棄
      $url = "/php/login.php";	// login画面へ遷移させるｩ!
      header('Location: '.$url);
    }
  ?>
  </body>
</html>