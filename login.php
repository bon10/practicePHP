<?php
session_start();

// エラーメッセージを格納する変数を初期化
$error_message = "";

// ログインボタンが押されたかを判定
// 初めてのアクセスでは認証は行わずエラーメッセージは表示しないように
if (isset($_POST["login"])) {

  // ユーザの入力情報を格納する！
  $userName = $_POST["user_name"];
  $userKey =  $_POST["password"];

  // MySQLにぶっつなゲル！！
  $mysqli = new mysqli("localhost", "admin", "manager", "test");
  if (mysqli_connect_errno()) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      $error_message = "せやかて工藤、接続失敗してもーたがな！";
      exit();
  }
  if ($stmt = $mysqli->prepare("SELECT user_name FROM users WHERE user_id=? and user_key=?")) {
    $stmt->bind_param('ss', $userName,$userKey);
    $stmt->execute(); // 実行じゃ！
    $stmt->bind_result($name); // 結果をバインドするぞい！
    $stmt->fetch(); // ｹﾞﾄｰ
    // 接続――――断――――!!
    mysqli_close($mysqli);
  }
  
  if ($name!=null) {  
    // ログインが成功した証をセッションに保存
    $_SESSION["user_name"] = $name;
    
    // 管理者専用画面へリダイレクト
    $login_url = "http://{$_SERVER["HTTP_HOST"]}/php/menu.php";
    header("Location: {$login_url}");
    exit;
  }
  $error_message = "<strong>ｳｿﾞﾀﾞﾄﾞﾝﾄﾞｺﾄﾞｰﾝ!!</strong><br/>残念ながらユーザ名もしくはパスワードが違っています。<br/>";
}
?>

<html>
<head>
<title>ログイン画面</title>
</head>
<body>

<?php
if ($error_message) {
print '<font color="red">'.$error_message.'</font>';
}
?>
せっかくだからオレはログインしちゃうぜ！<br/>
<form action="login.php" method="POST">
ユーザ名：<input type="text" name="user_name" value="" /><br />
パスワード：<input type="password" name="password" value"" /><br />
<input type="submit" name="login" value="ログイン" />
</form>
</body>
</html>