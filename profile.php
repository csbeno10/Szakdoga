<?php include('server.php') ?>


<?php
    $mustlogin="";

    if (isset($_GET['logout'])) {
        session_destroy();
        header("location: index.php");
    }

    if (!isset($_SESSION['hargeraCurrentUserhargera'])) {
        header("location: login.php?mustlogin='1'");
    }

    if((!isset($_GET['profile']) || $_GET['profile'] == "" || $_GET['profile'] == $_SESSION['hargeraCurrentUserIdhargera']) && !($_SESSION['hargeraCurrentUserhargera'] == 1)) {
        $_SESSION['hargeraprofilehargera'] = "";
    }
    elseif ($_GET['profile'] == 1){
        $_SESSION['hargeraprofilehargera'] = "user does not exist";
    }

    else {

        $stmt = $db->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $_GET['profile']);
        $stmt->execute();
        $stmt->store_result();
        $numrows = $stmt -> num_rows();


        if($numrows == 0) {
            $_SESSION['hargeraprofilehargera'] = "user does not exist";
        }
        else {$_SESSION['hargeraprofilehargera'] = $_GET['profile'];}
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
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    

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




        <?php  if ($_SESSION['hargeraprofilehargera'] == "" && !(isset($_SESSION['hargeraPasswordEditSuccesshargera'])) ) : ?>
            <?php 
                $stmt = $db->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
                $stmt->bind_param("i", $_SESSION['hargeraCurrentUserIdhargera']);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                $stmt = $db->prepare("SELECT Count(*) FROM ratings WHERE target_id=?");
                $stmt->bind_param("i", $user['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $ratings = $result->fetch_assoc();
                $numratings = $ratings['Count(*)'];
                $numpages = (int)($numratings / 4);
                if ($numratings % 4 != 0) {$numpages = $numpages + 1;}
                if (!isset($_GET['page']) || (int)$_GET['page'] <= 1) {
                    $page = 1;
                }
                elseif ((int)$_GET['page'] >= $numpages) {
                    $page = $numpages;
                }
                else {$page = (int)$_GET['page'];}


            ?>
            <div class="container" style="max-width: 800px">
                <h1>Profilom</h1>
                <div class="content">
                    <div class="line">
                        <label class="data">Felhasználónév: </label>
                        <span> <?php echo $user['username']?> </span>
                    </div>
                    <div id="scroll-target2" class="line email-line" >
                        <label class="data" >E-mail cím: </label>
                        <div class="email-string"> <?php echo $user['email']?> </div>
                    </div>
                    <div  class="line">
                        <label class="data">Regisztrált: </label>
                        <span> <?php echo date("Y-m-d", strtotime($user['register_date']))?> </span>
                    </div>
                    <div class="line">
                        <label  class="data">Pontszám: </label>
                        <span <?php if ((int)$user['score'] > 0) {echo "style='color:green'";} elseif ((int)$user['score'] < 0) {echo "style='color:red'";} ?> > 
                        <?php echo $user['score']?>, összesen <?php echo $ratings['Count(*)'];?> értékelésből </span>
                    </div>
                    <div class="title" >További adatok megadása</div>
                    <form method="post" action="profile.php">
                        <div class="line">
                            <label class="data">Telefonszám: </label>
                            <div style="display: block">
                                <input type="tel" name="phone" pattern="[0-9]{2}-[0-9]{3}-[0-9]{4}" value="<?php echo $user['phone']?>">
                                <div class="info">Pl. 20-123-4567</div>
                            </div>
                        </div>
                        <div id="scroll-target3" class="line" id="scroll-target">
                            <label class="data">Teljes név:  </label>
                            <input type="text" name="full_name" value="<?php echo $user['full_name']?>">
                        </div>
                        <div class="submit-div" style="margin-top:20px">
                            <button class="submit btn submit2"  type="submit" class="btn" name="profile_update" onclick=navigated(2) >Adatok mentése</button>
                        </div>
                    </form>
                       
                    <div class="title title2 title3" >Jelszó módosítása</div>
                    <?php include('errors.php'); ?>
                    <form method="post" action="profile.php" style="margin-top: 25px">
                        <div class="line">
                            <label class="data">Jelenlegi jelszó: </label>
                            <input type="password" name="current_password">
                        </div>
                        <div class="line">
                            <label class="data">Új jelszó: </label>
                            <input type="password" name="new_password1">
                        </div>
                        <div class="line">
                            <label class="data">Új jelszó ismét: </label>
                            <input type="password" name="new_password2">
                        </div>
                        <div class="submit-div" style="margin-top:20px">
                            <button class="submit btn submit2"  type="submit" class="btn" name="edit_password" onclick=navigated(3) >Jelszó mentése</button>
                        </div>
                    </form>
                    <?php  if ($numratings > 0) : ?>
                        <div class="title title2">Rólad írt értékelések</div>
                        <?php 
                            $from = ($page - 1) * 4;
                            $stmt = $db->prepare("SELECT r.rate_date, r.rating, u.username as writer_name, r.text FROM ratings r left JOIN users as u ON r.writer_id = u.id 
                            WHERE r.target_id=? ORDER BY r.rate_date DESC LIMIT ?,4 ");
                            $stmt->bind_param("ii", $user['id'], $from);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $ratings = array();
                            while ($row = $result->fetch_assoc()) {
                                array_push($ratings, $row);
                            }

                            foreach ($ratings as $key => $value){
                                $dt = date("Y-m-d", strtotime($value['rate_date']));
                                echo "<div class='rating {$value['rating']}'>
                                        <div class='rating-header'>{$value['writer_name']} értékelése: </div>
                                        <div class='rating-text'>{$value['text']}</div>
                                        <div class='rating-date'>{$dt}</div>
                                    </div>";
                            }



                        ?>
                        <div class="navigation-buttons">
                            <?php 
                                if ($numratings < 5) {
                                }
                                elseif ($page == 1){
                                    echo "<a href='profile.php?page=2' onclick='navigated(1)' class='next'>Következő &raquo;</a>";
                                }
                                elseif($page == $numpages) {
                                    $min = $page - 1;
                                    echo "<a class='previous' href='profile.php?page={$min}' onclick='navigated(1)'>&laquo; Előző</a>";
                                }
                                else {
                                    $min = $page - 1;
                                    $max = $page + 1;
                                    echo "<a class='previous' href='profile.php?page={$min}' onclick='navigated(1)'>&laquo; Előző</a>
                                        <span class='slash'> | </span>
                                        <a class='next' href='profile.php?page={$max}' onclick='navigated(1)'>Következő &raquo;</a>";
                                }
                            ?>
                        </div>
                        
                    <?php endif ?>
                    <?php  if ($numratings == 0) : ?>
                        <div class="title title2">Még nem érkezett rólad értékelés</div>
                    <?php endif ?>
                </div>
            </div>
            <div class="bottom-container">
                <div class="bottom-contact">Kapcsolat:</div>
                <div class="bottom-email">&#9993 Email cím: hargera@gmail.com</div>
                <div class="bottom-phone">&#x260E Telefonszám: +36 20-583-8346 </div>

            </div>
            
        <?php endif ?>



        <?php  if (!$_SESSION['hargeraprofilehargera'] == "" && !($_SESSION['hargeraprofilehargera'] == "user does not exist") && !(isset($_SESSION['hargeraPasswordEditSuccesshargera'] ))) : ?>
            <?php 
                $stmt = $db->prepare("SELECT * FROM users WHERE BINARY id=? LIMIT 1");
                $stmt->bind_param("i", $_SESSION['hargeraprofilehargera']);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                $stmt = $db->prepare("SELECT Count(*) FROM ratings WHERE target_id=?");
                $stmt->bind_param("s", $user['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $ratings = $result->fetch_assoc();
                $numratings = $ratings['Count(*)'];
                $numpages = (int)($numratings / 4);
                if ($numratings % 4 != 0) {$numpages = $numpages + 1;}
                if (!isset($_GET['page']) || (int)$_GET['page'] <= 1) {
                    $page = 1;
                }
                elseif ((int)$_GET['page'] >= $numpages) {
                    $page = $numpages;
                }
                else {$page = (int)$_GET['page'];}

                $stmt = $db->prepare("SELECT * FROM ratings WHERE writer_id=? AND target_id=? LIMIT 1");
                $stmt->bind_param("ss", $_SESSION['hargeraCurrentUserIdhargera'], $user['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                if($check = $result->fetch_assoc())
                {
                    $numrows = 1;
                }
                else {$numrows = 0;}


            ?>
            <div class="container" style="max-width: 800px">
                <h1><?php echo $user['username']?> profilja</h1>
                <div class="content">
                    <div class="line email-line">
                        <label class="data">E-mail cím: </label>
                        <span class="email-string"> <?php echo $user['email']?> </span>
                    </div>
                    <div class="line">
                        <label class="data">Regisztrált: </label>
                        <span> <?php echo date("Y-m-d", strtotime($user['register_date']))?> </span>
                    </div>
                    <div class="line">
                        <label class="data">Pontszám: </label>
                        <span <?php if ((int)$user['score'] > 0) {echo "style='color:green'";} elseif ((int)$user['score'] < 0) {echo "style='color:red'";} ?>> 
                        <?php echo $user['score']?>, összesen <?php echo $ratings['Count(*)'];?> értékelésből </span>
                    </div>
                    <?php 
                        if(!$user['phone'] == "") {
                            echo "<div class='line'>
                                    <label class='data'>Telefonszám: </label>
                                    <span> {$user['phone']} </span>
                                </div>";
                        }
                        if(!$user['full_name'] == "") {
                            echo "<div class='line name-line'>
                                    <label class='data'>Teljes név: </label>
                                    <span class='name-string'>{$user['full_name']}</span>
                                </div>";
                        }
                    ?>

                    <div class="write-message-button" id="scroll-target" ><a href="chat.php?partner=<?php echo $user['id']?>"><button class="submit btn submit2">Üzenet a felhasználónak</button></a></div>

                    <?php  if ($numrows == 0 && $_SESSION['hargeraCurrentUserhargera'] != 'ADMIN') : ?>
                        <div class="title title2 title3">Írj értékelést!</div>
                        <?php include('errors.php'); ?>
                        <form method="post" action="profile.php?profile=<?php echo $_SESSION['hargeraprofilehargera']?>">
                            <div>
                                <div class="rate-radio">
                                    <input type="radio" class="radio-btn" name="rating" value="positive">
                                    <span class="radio-label radio-label2" style="color:green">Pozitív</span>
                                    <input type="radio" class="radio-btn" name="rating" value="negative">
                                    <span class="radio-label" style="color:red;margin-right:0">Negatív</span>
                                </div>
                            </div>
                            <div class="rate-text-div">
                                <textarea name="rating-text" class="rate-text" maxlength="500"><?php if(isset($_POST['rating-text'])) echo $_POST['rating-text']; ?></textarea>
                                <div class="info" style="text-align:left; margin-left:10%">Maximum 500 karakter</div>
                            </div>
                            <input type="hidden" name="target" value="<?php echo $user['id']?>">
                            <input type="hidden" name="score" value="<?php echo $user['score']?>">
                            <div class="submit-div" style="margin-top:20px">
                                <button class="submit btn submit2" type="submit" name="rate_user">Értékelés feltöltése</button>
                            </div>
                        </form>
                    <?php endif ?>

                    <?php  if ($numrows == 1 && $_SESSION['hargeraCurrentUserhargera'] != 'ADMIN') : ?>

                        <div style="margin-top:20px" class="rating <?php echo $check['rating']?>">
                            <div class="rating-header">A te értékelésed </div>
                            <div class="rating-text"><?php echo $check['text']?></div>
                            <div class='rating-date'><?php echo date("Y-m-d", strtotime($check['rate_date']))?></div>
                        </div> 
                    <?php endif ?>

                    <?php  if (!(($numratings == 1 && $numrows == 1) || ($numratings == 0))) : ?>

                        <div class="title title2" ><?php echo $user['username']?> egyéb értékelései</div>
                        <?php 
                            $from = ($page - 1) * 4;
                            $stmt = $db->prepare("SELECT r.rate_date, r.rating, r.text, u.username as writer_name FROM ratings r left JOIN users as u ON r.writer_id = u.id WHERE target_id=? AND writer_id != ? ORDER BY rate_date DESC LIMIT ?,4 ");
                            $stmt->bind_param("ssi", $user['id'], $_SESSION['hargeraCurrentUserIdhargera'], $from);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $ratings = array();
                            while ($row = $result->fetch_assoc()) {
                                array_push($ratings, $row);
                            }

                            foreach ($ratings as $key => $value){
                                $dt = date("Y-m-d", strtotime($value['rate_date']));
                                echo "<div class='rating {$value['rating']} num{$key}'>
                                        <div class='rating-header'>{$value['writer_name']} értékelése: </div>
                                        <div class='rating-text'>{$value['text']}</div>
                                        <div class='rating-date'>{$dt}</div>
                                    </div>";
                            }



                        ?>
                        <div class="navigation-buttons">
                            <?php 
                                if ($numratings < 5) {}
                                elseif ($page == 1){
                                    echo "<a href='profile.php?profile={$_SESSION['hargeraprofilehargera']}&page=2' onclick='navigated(1)' class='next' style='margin-left:100px'>Következő &raquo;</a>";
                                    
                                }
                                elseif($page == $numpages) {
                                    $min = $page - 1;
                                    echo "<a class='previous' href='profile.php?profile={$_SESSION['hargeraprofilehargera']}&page={$min}' onclick='navigated(1)' style='margin-right:100px'>&laquo; Előző</a>";
                                }
                                else {
                                    $min = $page - 1;
                                    $max = $page + 1;
                                    echo "<a class='previous' href='profile.php?profile={$_SESSION['hargeraprofilehargera']}&page={$min}' onclick='navigated(1)'>&laquo; Előző</a>
                                        <span class='slash'> | </span>
                                        <a class='next' href='profile.php?profile={$_SESSION['hargeraprofilehargera']}&page={$max}' onclick='navigated(1)'>Következő &raquo;</a>";
                                }
                            ?>
                        </div>
                    <?php endif ?>

                    <?php  if (($numratings == 1 && $numrows == 1) || ($numratings == 0)) : ?>
                        <div class="title title2">Még nem érkezett egyéb értékelés</div>
                    <?php endif ?>
                </div>
            </div>
            <div class="bottom-container">
                <div class="bottom-contact">Kapcsolat:</div>
                <div class="bottom-email">&#9993 Email cím: hargera@gmail.com</div>
                <div class="bottom-phone">&#x260E Telefonszám: +36 20-583-8346 </div>

            </div>
        <?php endif ?>



        <?php  if ($_SESSION['hargeraprofilehargera'] == "user does not exist" && !(isset($_SESSION['hargeraPasswordEditSuccesshargera'] ))) : ?>
            <div class="fakeuser">Ez a felhasználó nem létezik! </div>
            <div class="bottom-container" style="position:absolute;bottom:0;width:100%">
                <div class="bottom-contact">Kapcsolat:</div>
                <div class="bottom-email">&#9993 Email cím: hargera@gmail.com</div>
                <div class="bottom-phone">&#x260E Telefonszám: +36 20-583-8346 </div>

            </div>
        <?php endif ?>
        <?php  if (isset($_SESSION['hargeraPasswordEditSuccesshargera'])) : ?>
            <div class="success">Jelszó sikeresen megváltoztatva</div>
            <?php
            unset($_SESSION['hargeraPasswordEditSuccesshargera']);
            echo '<script type="text/JavaScript"> 
                    function navigate() {
                    window.location.href = "profile.php";
                    }
                    setTimeout(navigate, 2500);
                    </script>';
            ?>
            <div class="bottom-container" style="position:absolute;bottom:0;width:100%">
                <div class="bottom-contact">Kapcsolat:</div>
                <div class="bottom-email">&#9993 Email cím: hargera@gmail.com</div>
                <div class="bottom-phone">&#x260E Telefonszám: +36 20-583-8346 </div>

            </div>
        <?php endif ?>
        <?php
        echo '<script src="profile.js?'.filemtime('profile.js').'"></script>';

    ?>
       
                </div>
        
</body>

</html>