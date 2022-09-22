<?php
	session_start();
	unset($_SESSION['user_email']);
        unset($_SESSION['user_tag']);
        unset($_SESSION['user_info']);

        $_SESSION['success'] = 2;
	header("Location: ../index/loginPage.php");
	die();
?>