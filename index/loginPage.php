<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Warehouse Game</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../css/loginCss.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Aldrich&display=swap" rel="stylesheet">
    </head>
    <body>
        <!-- Main Content -->
        <div id="grid-container">
        <div id="header"><h1>Warehouse Game</h1></div>        
            <div id="register-box">
                <div><h3>Registration</h3></div>
               <form id="registration" method="post" action="../sessions/register.php">
                    <label class="label-row" for="tag">Tag</label>
                    <div class="input-row">
                        <input type="text" id="tag"  name="tag" placeholder="Player's Tag">
                    </div>
                    <label class="label-row" for="email">Email</label>
                    <div class="input-row">
                        <input type="email" name="email" id="reg-email" placeholder="abc@email.com">
                    </div>
                    <label class="label-row" for="password">Password</label>
                    <div class="input-row">
                        <input type="password" name="password" id="reg-password" placeholder="********">
                    </div>
                    <div>
                        <input type="submit" id="reg-submit" value="Register">
                    </div>
               </form>
            </div>
           
          <div id="login-box">  
              <div><h3>Login</h3></div>
               <form id="login" method="post" action="../sessions/login.php">
                   <label class="label-row" for="email">Email</label>
                   <div class="input-row">
                        <input type="email" name="email" id="email" placeholder="abc@email.com">
                    </div>
                    <label class="label-row" for="password">Password</label>
                    <div class="input-row">
                        <input type="password" name="password" id="password" placeholder="********">
                    </div>
                     <div>
                        <input type="submit" id="submit" value="Login">
                    </div>
               </form>
           </div>
           <div id="error">
                <?php 
                require('../util/errorMap.php');
                if(isset($_SESSION['FatalError'])) :
                    $error = $_SESSION['FatalError'];
                    unset($_SESSION['FatalError']);
                    if (array_key_exists($error, $error_map)) {
                        $error_message = $error_map[$error];
                    }
                    
                ?>
               <h2><?= $error_message ?></h2>
                <?php endif; ?>
           </div><br>
           <div id="success">
               <?php
               require('../util/successMap.php');
                if(isset($_SESSION['success'])) :
                    $success = $_SESSION['success'];
                    unset($_SESSION['success']);
                    if (array_key_exists($success, $success_map)) {
                        $message = $success_map[$success];
                    }
                ?>
               <h2><?= $message ?></h2>
                <?php endif; ?>
           </div>
        </div>
    </body>
</html>
