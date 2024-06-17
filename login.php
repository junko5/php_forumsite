<?php
session_start();
require 'dbconnect.php';

if (!empty($_POST)) {
    if ($_POST['email'] != '' && $_POST['password'] != '') {
        $login = $db->prepare('SELECT * FROM members WHERE email=?');
        $login->execute([$_POST['email']]);
        $member = $login->fetch();

        if ($member != false && password_verify($_POST['password'], $member['password'])) {
            $_SESSION['id'] = $member['id'];
            $_SESSION['time'] = time();
            header('Location: post.php');
            exit();
        } else {
            $error['login'] = 'failed';
        }
    } else {
        $error['login'] = 'blank';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <style>
        .error {
            color: red;
            font-size: 0.8em;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Login</h1>
        <form action='' method="post">
            <label>
                <span class="label-text">email</span>
                <input type="text" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES); ?>">
                <?php if (isset($error['login']) && ($error['login'] =='blank')): ?>
                <p class="error">Please enter your email and password</p>
                <?php endif; ?>

                <?php if (isset($error['login']) && $error['login'] =='failed'): ?>
                <p class="error">The email or password is incorrect</p>
                <?php endif; ?>
            </label>
            <br />
            <label>
                <span class="label-text">Password</span>
                <input type="password" name="password" value="<?php echo htmlspecialchars($_POST['password'] ?? '', ENT_QUOTES); ?>">
            </label>

            <input type="submit" value="Login" class="button">

        </form>
        <div style="text-align:center; margin-top:10px;">
            <a href="register.php">User Registration</a>
        </div>
    </div>
</body>

</html>
