<?php
	session_start();
	$tag = $_POST['tag'];
	$email = $_POST['email'];
	$password = $_POST['password'];

	require('../util/validate.php');

	if(!validAlphaString($tag)){
                $_SESSION['FatalError'] = 5;
		header("Location: ../index/loginPage.php");
		die();
	}

	if(!validEmail($email)){
                $_SESSION['FatalError'] = 6;
		header("Location: ../index/loginPage.php");
		die();
	}

	if(!validPass($password)){
                $_SESSION['FatalError'] = 7;
		header("Location: ../index/loginPage.php");
		die();
	}

	//Connecting to warehousedb
	require('./dblogin.php'); 
	$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
	if (!$conn) {
            die("could not connect to db: <br />" . mysqli_error($con));
        }

        $dupePrep = $conn->prepare("SELECT * FROM users WHERE email = ? or tag = ? LIMIT 1");
        $dupePrep->bind_param("ss", $email, $tag);
    	$dupePrep->execute();

    	$dupeCheck = $dupePrep->get_result();

    	if($dupeCheck){
    		$row = $dupeCheck->fetch_assoc();

    		if($row){
    			if($row['email'] == $email){
                                $_SESSION['FatalError'] = 3;
    				header("Location: ../index/loginPage.php");
    			}elseif($row['tag'] == $tag){
                                $_SESSION['FatalError'] = 4;
    				header("Location: ../index/loginPage.php");
    			}
    		} else {
    			$insert = $conn->prepare("INSERT into users(tag, email, password)
        								  values(?,?, md5(?))");
        		$insert->bind_param("sss",  $tag, $email, $password);
        		if($insert->execute()){
                            $_SESSION['success'] = 1;
        			header("Location: ../index/loginPage.php");
        		}else{
                                $_SESSION['FatalError'] = 8;
        			header("Location: ../index/loginPage.php");
        		}
        		$insert->close();
        		die();
    		}
    	}else{
                $_SESSION['FatalError'] = 1;
    		header("Location: ../index/loginPage.php");
    	}

        
  
        $dupePrep->close();
        $conn->close();
?>