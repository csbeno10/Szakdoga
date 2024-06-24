<?php include('connect.php') ?>

<?php 
    session_start(); 
    $mustlogin="";

    if (!isset($_SESSION['hargeraCurrentUserhargera']) || $_SESSION['hargeraCurrentUserhargera'] == "ADMIN") {
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
            <?php  if (isset($_SESSION['hargeraCurrentUserhargera'])) : ?>
                <ul>
                    <li><a href="upload.php">Hirdetésfeladás</a></li>
                    <li><a href="advertisements.php">Hirdetéseim kezelése</a></li>
                    <li><a href="messages.php">Üzeneteim<?php if($newMessages != 0) echo "<div class='new-message'>{$newMessages}</div>"?></a></li>
                    <li><a href="profile.php">Profilom</a></li>
                </ul>
            <?php endif ?>
            <?php  if (!isset($_SESSION['hargeraCurrentUserhargera'])) : ?>
                <ul>
                    <li><a href="login.php?mustlogin='1'">Hirdetésfeladás</a></li>
                </ul>
            <?php endif ?>

        </nav>
        <div class="bg">
        <div class="container" style="max-width:800px;padding-bottom:20px">
            <h1>Hirdetéseim</h1>
            <div class="content">
                <?php 
                    

                    $stmt = $db->prepare("SELECT Count(*) FROM advertisement WHERE user_id=? ORDER BY date DESC");
                    $stmt->bind_param("s", $_SESSION['hargeraCurrentUserIdhargera']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $fetched_result = $result->fetch_assoc();
                    $numads = $fetched_result['Count(*)'];

                    if ($numads == 0){
                        echo "<div class='title'>Nincsenek megjeleníthető hirdetéseid</div>";
                        echo "<a class='btn' style='display:block;text-align:center' href='upload.php'><button class='button submit'>Hírdetés feltöltése</button></a>";
                    }

                    $numpages = (int)($numads / 10);
                    if ($numads % 10 != 0) {$numpages = $numpages + 1;}
                    if (!isset($_GET['page']) || (int)$_GET['page'] <= 1) {
                        $page = 1;
                    }
                    elseif ((int)$_GET['page'] >= $numads) {
                        $page = $numads;
                    }
                    else {$page = (int)$_GET['page'];}
                    $from = ($page - 1) * 10;

                    $stmt = $db->prepare("SELECT title, price, id, user_id FROM advertisement WHERE user_id=? ORDER BY date DESC LIMIT {$from},10");
                    $stmt->bind_param("s", $_SESSION['hargeraCurrentUserIdhargera']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $ads = array();
                    while ($row = $result->fetch_assoc()) {
                        array_push($ads, $row);
                    }

                    

                    foreach ($ads as $key => $value){
                        $price = number_format((int)$value['price'],0,""," ");
                        echo "<div class='item ad-edit-item'>
                            <div class='ad-img'><img class='ad-item-img'";
                            if (file_exists("uploads/".$value['user_id']."/".$value['id']."/thumbnail.jpg")){
                                echo "src='uploads/".$value['user_id']."/".$value['id']."/thumbnail.jpg?".filemtime("uploads/".$value['user_id']."/".$value['id']."/thumbnail.jpg")."'";
                            }
                            else {echo "src='default2.jpg'";}
                            echo "
                            onerror=this.src='default2.jpg'></div>               
                                <div class='item-mid-section'>
                                    <div class='ad-item-title'>{$value['title']}</div>
                                    <div class='ad-item-price'>{$price} Ft</div>
            
                                </div>
                                <div class='item-right-section ad-item-right-section'>
                                    <a class='btn ad-item-btn' href='edit.php?ad_id={$value['id']}'><button class='button submit edit-button'>Szerkesztés</button></a>
                                </div>
            
                            </div>";
                    }
                ?>
                <div class="navigation-buttons" style="margin-top: 10px">
                    <?php 
                        if ($numads < 10) {}
                        elseif ($page == 1){
                            echo "<a href='advertisements.php?page=2' class='next' style='margin-left:100px'>Következő &raquo;</a>";
                        }
                        elseif($page == $numpages) {
                            $min = $page - 1;
                            echo "<a class='previous' href='advertisements.php?page={$min}' style='margin-right:100px'>&laquo; Előző</a>";
                        }
                        else {
                            $min = $page - 1;
                            $max = $page + 1;
                            echo "<a class='previous' href='advertisements.php?page={$min}'>&laquo; Előző</a>
                                <span class='slash'> | </span>
                                <a class='next' href='advertisements.php?page={$max}'>Következő &raquo;</a>";
                        }
                    ?>
                </div>
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