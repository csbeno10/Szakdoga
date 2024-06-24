<?php include('connect.php') ?>

<?php 
    session_start(); 
    $mustlogin="";

    if (!isset($_SESSION['hargeraCurrentUserhargera']) || $_SESSION['hargeraCurrentUserhargera'] != "ADMIN") {
        header("location: login.php?mustlogin='1'");
    }

    if (isset($_GET['logout'])) {
        session_destroy();
        header("location: index.php");
    }



    $stmt = $db->prepare("SELECT sender_id FROM messages WHERE receiver_id=? AND seen = 0 GROUP BY sender_id");
    $stmt->bind_param("i", $_SESSION['hargeraCurrentUserIdhargera']);
    $stmt->execute();
    $stmt->store_result();
    $newMessages = $stmt -> num_rows();

    $stmt = $db->prepare("SELECT r.id, r.r_text, r.writer_id, u1.username as writer, u2.username as target_user, r.target_user_id, target_ad_id, a.title as target_ad_title, r.r_date FROM reports r 
    left JOIN users as u1 ON r.writer_id = u1.id left JOIN users as u2 ON r.target_user_id = u2.id left JOIN advertisement as a ON r.target_ad_id = a.id WHERE r.id=?  ");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $report = $result->fetch_assoc();


    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    echo '<link rel="stylesheet" type="text/css" href="index.css?'.filemtime('index.css').'">';

    ?>
    <title>Hargera</title>
</head>
<body>
    
        <nav class="firstnavbar">
            <nav style="display:flexbox">
                <a href="index.php" ><button class="hargera">Hargera</button></a>
                <?php  if (isset($_SESSION['hargeraCurrentUserhargera'])) : ?>
                    <div class="user"><?php echo $_SESSION['hargeraCurrentUserhargera']; ?> </div>
                    <a class="btn" href="index.php?logout='1'"><button class="logout button">Kijelentkezés</button></a>
                <?php endif ?>
                <?php  if (!isset($_SESSION['hargeraCurrentUserhargera'])) : ?>
                    <a class="btn btn2" href="register.php"><button class="register button">Regisztráció</button></a>
                    <a class="btn btn2" href="login.php"><button class="login button">Bejelentkezés</button></a>
                <?php endif ?>
            </nav>
            <?php  if (isset($_SESSION['hargeraCurrentUserhargera']) && $_SESSION['hargeraCurrentUserhargera'] != "ADMIN") : ?>
                <ul>
                    <li><a href="upload.php">Hirdetésfeladás</a></li>
                    <li><a href="advertisements.php">Hirdetéseim kezelése</a></li>
                    <li><a href="messages.php">Üzeneteim<?php if($newMessages != 0) echo "<div class='new-message'>{$newMessages}</div>"?></a></li>
                    <li><a href="profile.php">Profilom</a></li>
                </ul>
            <?php endif ?>
            <?php  if (isset($_SESSION['hargeraCurrentUserhargera']) && $_SESSION['hargeraCurrentUserhargera'] == "ADMIN") : ?>
                <ul>
                    <li><a href="messages.php">Üzeneteim<?php if($newMessages != 0) echo "<div class='new-message'>{$newMessages}</div>"?></a></li>
                    <li><a href="reports.php">Jelentések</a></li>
                </ul>
            <?php endif ?>
            <?php  if (!isset($_SESSION['hargeraCurrentUserhargera'])) : ?>
                <ul>
                    <li><a href="login.php?mustlogin='1'">Hirdetésfeladás</a></li>
                </ul>
            <?php endif ?>

        </nav>
        <div class="bg">



        <div class=" container" style="max-width:700px;min-height:600px;margin-top:20px" >
            <div class="content">
                <?php  if ($report == NULL) : ?>
                        <div class="title title2">Ez a jelentés nem létezik</div>
                <?php endif ?>
                <?php  if ($report != NULL) : ?>
                    <div class="report-page-row">
                        <div class="report-page-title">Jelentés feladója: </div>
                        <a class="nodec" href="profile.php?profile=<?php echo $report['writer_id']?>"><div class="report-page-text"><?php echo $report['writer']?></div></a>
                    </div>
                    <div class="report-page-row">
                        <div class="report-page-title">jelentett felhasználó: </div>
                        <a class="nodec" href="profile.php?profile=<?php echo $report['target_user_id']?>"><div class="report-page-text"><?php echo $report['target_user']?></div></a>
                    </div>
                    <div class="report-page-row">
                        <div class="report-page-title">Jelentett hirdetés: </div>
                        <a class="nodec" href="ad page.php?ad_id=<?php echo $report['target_ad_id']?>"><div class="report-page-text"><?php echo $report['target_ad_title']?></div></a>
                    </div>
                    <div class="report-page-row">
                        <div class="report-page-title">Jelentés dátuma: </div>
                        <div class="report-page-text"><?php echo substr($report['r_date'],0,-3);?></div>
                    </div>
                    <div class="report-page-row">
                        <div class="report-page-title2" >Jelentés szövege: </div>
                        <div class="report-page-text2"><?php echo $report['r_text']?></div>
                    </div>
                 <?php endif ?>
                
            </div>
        
        </div>

        <div class="bottom-container">
            <div class="bottom-contact">Kapcsolat:</div>
            <div class="bottom-email">&#9993 Email cím: hargera@gmail.com</div>
            <div class="bottom-phone">&#x260E Telefonszám: +36 20-583-8346 </div>

        </div>


                </div>         
    
</body>
</html>