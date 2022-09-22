<?php
	session_start();
	$email = $_POST['email'];
	$password = $_POST['password'];

	require("dblogin.php");
	$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
	if (!$conn) {
            die("could not connect to db: <br />" . mysqli_error($con));
        }

    $q = $conn->prepare("SELECT * FROM users WHERE email = ? and password = md5(?) LIMIT 1");
    $q -> bind_param("ss", $email, $password);
    $q -> execute();

    $r = $q -> get_result();

    if($r){
    	$row = $r->fetch_assoc();

    	if($row){
    		$_SESSION['user_email'] = $row['email'];
    		$_SESSION['user_tag'] = $row['tag'];
    		$_SESSION['user_info'] = $row['info'];

    		header("Location: ../index/puzzlelist.php");
    	} else {
                $_SESSION['FatalError'] = 2;
    		header("Location: ../index/loginPage.php");
    	}
    } else {
        $_SESSION['FatalError'] = 1;
    	header("Location: ../index/loginPage.php");
    }

    $q -> close();
    $conn -> close();
?>