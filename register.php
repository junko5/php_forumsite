<?php
session_start();
require 'dbconnect.php';

if (!empty($_POST)) {
    if ($_POST['name'] == '') {
        $error['name'] = 'blank';
    }
    if ($_POST['email'] == '') {
        $error['email'] = 'blank';
    } else {
        $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
        $member->execute([$_POST['email']]);
        $record = $member->fetch();
        if ($record['cnt'] > 0) {
            $error['email'] = 'duplicate';
        }
    }
    if ($_POST['password'] == '') {
        $error['password'] = 'blank';
    }
    if ($_POST['password2'] == '') {
        $error['password2'] = 'blank';
    }
    if (strlen($_POST['password']) < 6) {
        $error['password'] = 'length';
    }
    if ($_POST['password'] != $_POST['password2'] && $_POST['password2'] != '') {
        $error['password2'] = 'difference';
    }

    // Post-submission processing
    if (empty($error)) {
        $_SESSION['join'] = $_POST;
        header('Location: confirm.php');
        exit();
    }
}
// Retrieve POST data saved in the session
if (isset($_SESSION['join']) && isset($_REQUEST['action']) && $_REQUEST['action'] == 'rewrite') {
    $_POST = $_SESSION['join'];
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <title>Register as a member</title>
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
        <h1>Register as a member</h1>
        <form action="" method="post" class="registrationform">
            <label>
                <span class="label-text">Name<span class="red">*</span></span>
                <input type="text" name="name" value="<?php echo $_POST['name'] ?? ''; ?>">
                <?php if (isset($error['name']) && ($error['name'] == "blank")): ?>
                <p class="error">Please enter your name</p>
                <?php endif; ?>
            </label>
            <br>
            <label>
                <span class="label-text">Email<span class="red">*</span></span>
                <input type="text" name="email" value="<?php echo $_POST['email'] ?? ''; ?>">
                <?php if (isset($error['email']) && ($error['email'] == "blank")): ?>
                <p class="error">Please enter your email</p>
                <?php endif; ?>
                <?php if (isset($error['email']) && ($error['email'] == "duplicate")): ?>
                <p class="error">That email is already registered.</p>
                <?php endif; ?>
            </label>
            <br>
            <label>
                <span class="label-text">Password<span class="red">*</span></span>
                <input type="password" name="password" value="<?php echo $_POST['password'] ?? ''; ?>">
                <?php if (isset($error['password']) && ($error['password'] == "blank")): ?>
                <p class="error">Please enter your password</p>
                <?php endif; ?>
                <?php if (isset($error['password']) && ($error['password'] == "length")): ?>
                <p class="error">Please specify at least 6 characters</p>
                <?php endif; ?>
            </label>
            <br>
            <label>
                <span class="label-text">Re-enter Password<span class="red">*</span></span>
                <input type="password" name="password2">
                <?php if (isset($error['password2']) && ($error['password2'] == "blank")): ?>
                <p class="error">Please enter your password</p>
                <?php endif; ?>
                <?php if (isset($error['password2']) && ($error['password2'] == "difference")): ?>
                <p class="error">The password does not match the above</p>
                <?php endif; ?>
            </label>
            <br>
            <input type="submit" value="確認する" class="button">
        </form>
        <div style="text-align:center; margin-top:10px;">
            <a href="login.php">Return to the login screen</a>
        </div>
    </div>
</body>

</html>
