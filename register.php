<?php
session_start();
require('dbconnect.php');
// ★ポイント1★
if (!empty($_POST) ){
	// ★ポイント2★
    if ($_POST['name'] == "") {
        $error['name'] = 'blank';
    }
    if ($_POST['email'] == "") {
        $error['email'] = 'blank';
    } else {
		// ★ポイント3★
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
		$member->execute(array($_POST['email']));
		$record = $member->fetch();
		if ($record['cnt'] > 0) {
			$error['email'] = 'duplicate';
		}
	}
    if ($_POST['password'] == "") {
        $error['password'] = 'blank';
    }
    if ($_POST['password2'] == "") {
        $error['password2'] = 'blank';
    }
	// ★ポイント4★
    if (strlen($_POST['password'])< 6) {
        $error['password'] = 'length';
    }
	// ★ポイント5★
    if (($_POST['password'] != $_POST['password2']) && ($_POST['password2'] != "")) {
        $error['password2'] = 'difference';
    }

	// 追加：送信後の処理
    if (empty($error)) {
        $_SESSION['join'] = $_POST;
        header('Location: confirm.php');
        exit();
    }
}
// 追加：セッションに保存しておいたPOSTデータを取り出す
if (isset($_SESSION['join']) && isset($_REQUEST['action']) && ($_REQUEST['action'] == 'rewrite')) {
    $_POST =$_SESSION['join'];
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <title>会員登録をする</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
	<style>
		.error { color: red;font-size:0.8em; }
	</style>
</head>
<body>
	<div class="container">
		<h1>会員登録をする</h1>
		<form action="" method="post" class="registrationform">
			<label>
				<span class="label-text">名前<span class="red">*</span></span>
				<input type="text" name="name" value="<?php echo $_POST['name']??""; ?>">
				<?php if (isset($error['name']) && ($error['name'] == "blank")): ?>
				<p class="error">名前を入力してください</p>
				<?php endif; ?>
			</label>
			<br>
			<label>
				<span class="label-text">email<span class="red">*</span></span>
				<input type="text" name="email" value="<?php echo $_POST['email']??""; ?>">
				<?php if (isset($error['email']) && ($error['email'] == "blank")): ?>
				<p class="error">emailを入力してください</p>
				<?php endif; ?>
				<?php if (isset($error['email']) && ($error['email'] == "duplicate")): ?>
				<p class="error">すでにそのemailは登録されています。</p>
				<?php endif; ?>
			</label>
			<br>
			<label>
				<span class="label-text">パスワード<span class="red">*</span></span>
				<input type="password" name="password"  value="<?php echo $_POST['password']??""; ?>">
				<?php if (isset($error['password']) && ($error['password'] == "blank")): ?>
				<p class="error"> パスワードを入力してください</p>
				<?php endif; ?>
				<?php if (isset($error['password']) && ($error['password'] == "length")): ?>
				<p class="error"> 6文字以上で指定してください</p>
				<?php endif; ?>
			</label>
			<br>
			<label>
				<span class="label-text">パスワード再入力<span class="red">*</span></span>
				<input type="password" name="password2">
				<?php if (isset($error['password2']) && ($error['password2'] == "blank")): ?>
				<p class="error"> パスワードを入力してください</p>
				<?php endif; ?>
				<?php if (isset($error['password2']) && ($error['password2'] == "difference")): ?>
				<p class="error"> パスワードが上記と違います</p>
				<?php endif; ?>
			</label>
			<br>
			<input type="submit" value="確認する" class="button">
		</form>
		<div style="text-align:center; margin-top:10px;">
			<a href="login.php">ログイン画面に戻る</a>
		</div>
	</div>
</body>
</html>