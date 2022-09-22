<?php
    session_start();
    if(!isset($_SESSION['user_email'])){
        header("Location: ../loginPage.php?FatalError=9");
        die();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Warehouse Game</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Aldrich&display=swap" rel="stylesheet">
        <script src="../../util/utils.js"></script>
        
        <?php
        if(isset($_GET["seed"])){
            $s = $_GET["seed"];
        }else{
            $s = rand(1111, 999999);
        }
        print "<script> var seed = $s; </script>"
        ?>
        
    </head>
    <body>
        
        <div id="all">
        <div> <h1 style = "text-align:center;">Warehouse Game</h1></div>
        <div> <h2 style = "text-align:center;"> Tag: <?= $_SESSION['user_tag']?></h2></div>
        <div class="buttons" style = "text-align:center;">
            <button id="rstrt">Restart</button>
            <button id="rndm">Randomize</button>
            <button id="pzlist" onclick="window.location.href = '../puzzlelist.php';">Puzzle List</button>
            <button id="logout" onclick="window.location.href = '../../sessions/logout.php';">Logout</button>
        </div><br>
        <img hidden id = "crate"  src = "images/box.png" height = "50" width = "50">
        <img hidden id = "crate2"  src = "images/box2.png" height = "50" width = "50">
        <img hidden id = "box"  src = "images/box3.png" height = "50" width = "50">
        <img hidden id = "back" src="images/playerspriteback.png" height="50" width="50">
        <img hidden id = "left" src="images/playerspriteleft.png" height="50" width="50">
        <img hidden id = "right" src="images/playerspriteright.png" height="50" width="50">
        <img hidden id = "front" src="images/playerspritefront.png" height="50" width="50">
        <img hidden id = "goal1" src="images/tgt1.png" height="50" width="50">
        <img hidden id = "goal2" src="images/tgt2.png" height="50" width="50">
        <img hidden id = "goal3" src="images/tgt3.png" height="50" width="50">
        <img hidden id = "expl" src="images/expl.png" height="50" width="50">
        
        <div id="navbar">
            <div id="score"></div>
        </div>
        <div style="text-align:center;">
        <canvas id="canvas" width="552" height="552"></canvas>
        </div>
        <div id="myText"></div>
        <br>
        </div>
    <script src="warehouse.js"></script></body>
</body>
    

</html>
