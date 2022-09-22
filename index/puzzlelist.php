<?php
    if(!isset($_SESSION)){
        session_start();
    }
    if(!isset($_SESSION['user_email'])){
        $_SESSION['FatalError'] = 9;
    	header("Location: ../index/loginPage.php");
        die();
    }
    require('../sessions/dblogin.php'); //download or copy: defines $db_host,...,$db_name
        $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
        if (!$conn) {
            die("could not connect to db: <br />" . mysqli_error($conn));
        }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Warehouse Game | Puzzle List</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../css/loginCss.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Aldrich&display=swap" rel="stylesheet">
    </head>
    <body>
        <div id="grid-container">
            <h1 id="header">Puzzle List</h1>
            <div  id="logout" >
            <button onclick="window.location.href = '../sessions/logout.php';">Logout</button>
            </div>
            <h2 id="username">Tag: <?= $_SESSION['user_tag']?></h2>
        <?php
        $q = "SELECT * FROM `puzzles`"; //a very simple query. NOTE the funky ` tick marks NOT '
        $r = mysqli_query($conn, $q); //making the query
        if (!$r) {
            die("query failed: <br/>" . mysqli_error($conn));
        }
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            $puz = $row['puzname']; //Yes arrays are maps. puzname is a column name
            $rec = $row['recname'];
            $seed = $row['seedlev'];
            $score = $row['score'];
            $date = $row['when'];
            print "<div class = 'puz'style = 'text-align:center;'>";
            print "<br>";
            print "<a href = 'warehouseGame/index.php?seed=$seed'> $puz </a>";
            print "|| $rec || $score || $date";
            print "</div>";
        }
        ?>
        </div>
    </body>
</html>

<?php
if(!$q){
    $q -> close();
}
if(!$conn){
    $conn -> close();
}
?>