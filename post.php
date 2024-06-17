<?php
session_start();
require 'dbconnect.php';

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();

    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute([$_SESSION['id']]);
    $member = $members->fetch();
} else {
    header('Location: login.php');
    exit();
}

if (!empty($_POST)) {
    if (isset($_POST['token']) && $_POST['token'] === $_SESSION['token']) {
        $post = $db->prepare('INSERT INTO posts SET created_by=?, post=?, created=NOW()');
        $post->execute([$member['id'], $_POST['post']]);
        header('Location: post.php');
        exit();
    } else {
        header('Location: login.php');
        exit();
    }
}

$posts = $db->query('SELECT m.name, p.* FROM members m  JOIN posts p ON m.id=p.created_by ORDER BY p.created DESC');

$TOKEN_LENGTH = 16;
$tokenByte = openssl_random_pseudo_bytes($TOKEN_LENGTH);
$token = bin2hex($tokenByte);
$_SESSION['token'] = $token;

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <title>Post</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">

        <header>
            <div class="head">
                <h1>Weekend Plan Posting Screen</h1>
                <span class="logout"><a href="login.php">Logout</a></span>

            </div>
        </header>

        <form action='' method="post">
            <input type="hidden" name="token" value="<?= $token ?>">
            <?php if (isset($error['login']) &&  ($error['login'] =='token')): ?>
            <p class="error">This is unauthorized access.</p>
            <?php endif; ?>
            <div class="edit">
                <p>
                    <?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?> san、Welcome
                </p>
                <textarea name="post" cols='50' rows='10'><?php echo htmlspecialchars($post ?? '', ENT_QUOTES); ?></textarea>
            </div>

            <input type="submit" value="投稿する" class="button02">
        </form>

        <?php foreach($posts as $post): ?>
        <div class="post" style="margin-top:10px;">
            <?php echo htmlspecialchars($post['post'], ENT_QUOTES); ?> |
            <span class="name">
                <?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?> |
                <?php echo htmlspecialchars($post['created'], ENT_QUOTES); ?> |

                <?php if($_SESSION['id'] == $post['created_by']): ?>
                [<a href="delete.php?id=<?php echo htmlspecialchars($post['id'], ENT_QUOTES); ?>">Delete</a>]
                <?php endif; ?>

            </span>
        </div>
        <?php endforeach; ?>
    </div>
</body>

</html>
