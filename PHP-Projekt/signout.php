<?php
    define('__APP__', TRUE);
	session_start();
	unset($_POST);
	unset($_SESSION['user']);
	$_SESSION['user']['valid'] = 'false';
	$_SESSION['message'] = '<p>See you again soon!</p>';
	header("Location: index.php?menu=1");
	exit;
?>