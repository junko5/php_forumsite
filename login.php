<?php
session_start();
require('dbconnect.php');

// ★ポイント1★
if (!empty($_POST)) {
    if (($_POST['email'] != '') && ($_POST['password'] != '')) {
        $login = $db->prepare('SELECT * FROM members WHERE email=?');
        $login->execute(array($_POST['email']));
        $member=$login->fetch();
		// ★ポイント2★
        if ($member != false && password_verify($_POST['password'],$member['password'])) {
            $_SESSION['id'] = $member['id'];
            $_SESSION['time'] =time();
            header('Location: post.php');
            exit();
        } else {
			// ★ポイント3★
            $error['login']='failed';
        } 
    } else {
        $error['login'] ='blank';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<title>ログイン画面</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
	<style>
		.error { color: red;font-size:0.8em; }
	</style>
</head>
<body>
	<div class="container">
		<h1>ログイン画面</h1>
		<form action='' method="post">
			<label>
				<span class="label-text">email</span>
				<input type="text" name="email" 
				value="<?php echo htmlspecialchars($_POST['email']??"", ENT_QUOTES); ?>">
				<?php if (isset($error['login']) && ($error['login'] =='blank')): ?>
				<p class="error">メールとパスワードを入力してください</p>
				<?php endif; ?>

				<?php if (isset($error['login']) && $error['login'] =='failed'): ?>
				<p class="error">メールかパスワードが間違っています</p>
				<?php endif; ?>
			</label>
			<br />
			<label>
				<span class="label-text">パスワード</span>
				<input type="password" name="password" 
				value="<?php echo htmlspecialchars($_POST['password']??"", ENT_QUOTES); ?>">
			</label>

			<input type="submit" value="ログインする" class="button">

		</form>
		<div style="text-align:center; margin-top:10px;">
			<a href="register.php">ユーザ登録する</a>
		</div>
	</div>
</body>
</html>