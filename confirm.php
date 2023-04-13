<?php
session_start();
require('dbconnect.php');
// ★ポイント1★
if (!isset($_SESSION['join'])) {
    header ('Location: register.php');
    exit();
}
// ★ポイント2★
$hash = password_hash($_SESSION['join']['password'], PASSWORD_BCRYPT);
// ★ポイント3★
if (!empty($_POST)) {
    $statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, created=NOW()');
    $statement->execute(array(
        $_SESSION['join']['name'],
        $_SESSION['join']['email'],
        $hash));
    unset($_SESSION['join']);
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<title>ユーザ登録確認画面</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="container">
		<h1>ユーザ登録確認画面</h1>
		<form action="" method="post">

			<input type="hidden" name="action" value="submit">
			<!-- ★ポイント4★ -->
			<p>
				名前
				<span class="check"><?php echo (htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES)); ?></span>
			</p>
			<p>
				email
				<span class="check"><?php echo (htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES)); ?></span>
			</p>
			<p>
				パスワード
				<span class="check">[セキュリティのため非表示] </span>
			</p>
			<!-- ★ポイント5★ -->
			<div class="button-container">
				<input type="button" onclick="event.preventDefault();location.href='register.php?action=rewrite'" value="修正する" name="rewrite">
				<input type="submit" value="登録する" name="registration">
			</div>
		</form>
	</div>
</body>
</html>