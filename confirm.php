<?php
session_start();
require 'dbconnect.php';

if (!isset($_SESSION['join'])) {
    header('Location: register.php');
    exit();
}

$hash = password_hash($_SESSION['join']['password'], PASSWORD_BCRYPT);

if (!empty($_POST)) {
    $statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, created=NOW()');
    $statement->execute([$_SESSION['join']['name'], $_SESSION['join']['email'], $hash]);
    unset($_SESSION['join']);
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <title>User Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>User Registration</h1>
        <form action="" method="post">

            <input type="hidden" name="action" value="submit">

            <p>
                Name
                <span class="check"><?php echo htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES); ?></span>
            </p>
            <p>
                Email
                <span class="check"><?php echo htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES); ?></span>
            </p>
            <p>
                Password
                <span class="check">[Hidden for security reasons] </span>
            </p>

            <div class="button-container">
                <input type="button" onclick="event.preventDefault();location.href='register.php?action=rewrite'"
                    value="Revise" name="rewrite">
                <input type="submit" value="Register" name="registration">
            </div>
        </form>
    </div>
</body>

</html>
