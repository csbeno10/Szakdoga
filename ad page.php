<?php include('connect.php') ?>
<?php
    session_start(); 
    $mustlogin="";

    if (isset($_GET['logout'])) {
        session_destroy();
        header("location: index.php");
    }


    $stmt = $db->prepare("SELECT a.id ,a.user_id, u.username as user, a.title, a.price, a.shipping, a.settlement, u.score, a.date, a.description, a.category FROM advertisement as a left JOIN users as u ON a.user_id = u.id WHERE a.id=? LIMIT 1");
    $stmt->bind_param("i", $_GET['ad_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $ad = $result->fetch_assoc();
    $price = number_format((int)$ad['price'],0,""," ");

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
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


    
    <?php
    echo '<link rel="stylesheet" type="text/css" href="index.css?'.filemtime('index.css').'">';

    ?>
    
    <title>Hargera</title>
</head>
<body>
    <nav class="firstnavbar">
        <nav style="display:flexbox">
            <a href="index.php" ><button class="hargera hargera2">Hargera</button></a>
            <?php  if (isset($_SESSION['hargeraCurrentUserhargera'])) : ?>
                <div class="user user2"><?php echo $_SESSION['hargeraCurrentUserhargera']; ?> </div>
                <a class="btn2 btn3" href="index.php?logout='1'"><div class="logout logout2 button">Kijelentkezés</div></a>
            <?php endif ?>
            <?php  if (!isset($_SESSION['hargeraCurrentUserhargera'])) : ?>
                <a class="btn2" href="register.php"><div class="register register2 button">Regisztráció</div></a>
                <a class="btn2" href="login.php"><div class="login login2 button">Bejelentkezés</div></a>
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
    <div class="container" style="max-width:850px;margin-top:20px">
            <?php  if ($ad == NULL) : ?>
                <div class="content content2" >
                    <div class="title title2">Ez a hirdetés nem létezik</div>
                </div>
            <?php endif ?>
            <?php  if ($ad != NULL) : ?>
                <div class="content content2" style="padding:0">
                    <div class="ad-page-title"><?php echo $ad['title'] ?></div>
                    <div class="ad-page-line" ></div>
                    <div class="ad-page-group">
                        <div class="ad-page-user">Feladó: <?php echo " &nbsp <a href='profile.php?profile={$ad['user_id']}' class='nodec'>{$ad['user']}</a>" ?></div>
                        <div class="ad-page-score" <?php if($ad['score']>0) echo 'style=color:green'; elseif ($ad['score']<0) echo 'style=color:red'?>>(<?php echo $ad['score'] ?>)</div>
                        <div class="ad-page-date">Feladás dátuma: <?php echo substr($ad['date'],0,-3); ?></div>
                        <div class="ad-page-category">Kategória: <?php echo $ad['category'] ?></div>
                    </div>
                    <div class="ad-page-line" ></div>

                    <?php 
                        $images = glob("uploads/{$ad['user_id']}/{$ad['id']}/*"); 
                        echo "<div id='carouselExampleControls' class='carousel slide hargeracarousel' data-ride='carousel'>
                                <div class='carousel-inner hargera-carousel-inner '>
                                    <div class='carousel-item hargera-carousel-item active'>
                                    <img class='d-block w-100 hargera-carousel-img' ";
                                    if (file_exists("uploads/".$ad['user_id']."/".$ad['id']."/thumbnail.jpg")){
                                        echo "src='uploads/".$ad['user_id']."/".$ad['id']."/thumbnail.jpg?".filemtime("uploads/".$ad['user_id']."/".$ad['id']."/thumbnail.jpg")."'>";
                                    }
                                    else {echo "src='default2.jpg'>";}
                                    echo "</div>";
                        if (count($images) > 1){ 
                            foreach($images as $img){ 
                                $base = basename($img);
                                if ($base != "thumbnail.jpg"){
                                    echo "<div class='carousel-item hargera-carousel-item'>
                                            <img class='d-block w-100 hargera-carousel-img' src='".$img."?".filemtime("$img")."' onerror=this.src='default2.jpg'>
                                            </div>";
                                }
                            }

                        
                            echo "</div>
                            <a class='carousel-control-prev' href='#carouselExampleControls' role='button' data-slide='prev'>
                                <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                                <span class='sr-only'>Previous</span>
                            </a>
                            <a class='carousel-control-next' href='#carouselExampleControls' role='button' data-slide='next'>
                                <span class='carousel-control-next-icon' aria-hidden='true'></span>
                                <span class='sr-only'>Next</span>
                            </a>";
                        }
                        else {echo "</div>";}
                                echo "</div>" ;
                                    
                        
                        
                    ?>

                    
                    <div class="ad-page-line" ></div>
                    <div class="ad-page-group">
                        <div class="ad-page-price"><?php echo $price ?> Ft</div>
                        <div class="ad-page-settlement"><?php echo $ad['settlement'] ?></div>
                        <div class="ad-page-shipping"><?php echo $ad['shipping'] ?></div>
                    </div>
                    <div class="ad-page-line" ></div>
                    <div class="ad-page-description"><?php echo $ad['description'] ?></div>

                    <div style="margin:auto;text-align:center">
                    <?php 
                    if(isset($_SESSION['hargeraCurrentUserhargera']) && $ad['user'] != $_SESSION['hargeraCurrentUserhargera']){

                        echo    "<div class='ad-page-button' style='padding-bottom:40px'><a class='ad-page-link' href='chat.php?partner={$ad['user_id']}'>
                                <div style='width:fit-content'class='submit btn2 submit2'>Üzenet írása</div></a></div>";
                    }

                    
                        
                    if(isset($_SESSION['hargeraCurrentUserhargera']) && $_SESSION['hargeraCurrentUserhargera'] != "ADMIN" && $ad['user'] != $_SESSION['hargeraCurrentUserhargera']){
                        echo "<div class='ad-page-button' style='padding-bottom:40px'><a class='ad-page-link' href='report.php?ad_id={$ad['id']}'>
                                <div style='width:fit-content'class='submit btn2 submit2 submit-red'>Jelentés</div></a></div>";
                    }
                    if (isset($_SESSION['hargeraCurrentUserhargera']) && $_SESSION['hargeraCurrentUserhargera'] == "ADMIN") {
                        echo "<form method='post' action='deleteWithAdmin.php' style='display:inline'>
                                <div class='ad-page-button' style='padding-bottom:40px'><button type='submit' name='delete-with-admin' style='width:fit-content'class='submit btn2 submit2 submit-red'>Hirdetés Törlése</button></div>
                                <input type='hidden' name='user_id' value='{$ad['user_id']}'>
                                <input type='hidden' name='ad_id' value='{$ad['id']}'>

                            </form>";
                    }
                        ?>

                
                    </div>
                </div>

                
            <?php endif ?>

        
    </div>
    

    <div class="bottom-container">
        <div class="bottom-contact">Kapcsolat:</div>
        <div class="bottom-email">&#9993 Email cím: hargera@gmail.com</div>
        <div class="bottom-phone">&#x260E Telefonszám: +36 20-583-8346 </div>

    </div>
                </div>
</body>
</html>