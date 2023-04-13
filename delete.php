<?php 
session_start();
require('dbconnect.php'); 
if (isset($_SESSION['id'])) {
	$id = $_REQUEST['id'];
	$posts = $db->prepare('SELECT * FROM posts WHERE id=?');
	$posts -> execute(array($id)); 
	$post = $posts->fetch();
	if ($post['created_by'] == $_SESSION['id']) {
		$del = $db->prepare('DELETE FROM posts WHERE id=?');
		$del->execute(array($id));
	}
}
header('Location: post.php');
exit();
?>