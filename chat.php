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


    else {


        $stmt = $db->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $_GET['partner']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        

        if(!($user) || $_GET['partner'] == $_SESSION['hargeraCurrentUserhargera']) {
            $_SESSION['hargerapartnerhargera'] = "user does not exist";
        }
        else {
            $_SESSION['hargerapartnerhargera'] = $user['username'];
            $_SESSION['hargerapartneridhargera'] = $_GET['partner'];
        }
    }

    if (!($_SESSION['hargerapartnerhargera'] == "user does not exist")){
        $stmt = $db->prepare("SELECT * FROM messages WHERE (sender_id=? AND receiver_id=?) OR (sender_id=? AND receiver_id=?)");
        $stmt->bind_param("iiii", $_SESSION['hargeraCurrentUserIdhargera'], $_GET['partner'],$_GET['partner'],$_SESSION['hargeraCurrentUserIdhargera']);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = array();
        while ($row = $result->fetch_assoc()) {
            array_push($messages, $row);
        }
        $seen = 1;
        $stmt = $db->prepare("UPDATE messages SET seen = ? WHERE sender_id=? AND receiver_id=? AND seen=0");
        $stmt->bind_param("iii", $seen, $_GET['partner'], $_SESSION['hargeraCurrentUserIdhargera']);
        $stmt->execute();

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
            <?php  if (isset($_SESSION['hargeraCurrentUserhargera']) && $_SESSION['hargeraCurrentUserhargera'] != 'ADMIN') : ?>
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


        <?php  if ($_SESSION['hargerapartnerhargera'] == "user does not exist") : ?>
            <div class="fakeuser">Hibás felhasználó! </div>

            <div class="bottom-container" style="position:absolute;bottom:0;width:100%">
                <div class="bottom-contact">Kapcsolat:</div>
                <div class="bottom-email">&#9993 Email cím: hargera@gmail.com</div>
                <div class="bottom-phone">&#x260E Telefonszám: +36 20-583-8346 </div>

            </div>
        <?php endif ?>


        <?php  if ($_SESSION['hargerapartnerhargera'] != "user does not exist") : ?>

            <div class="container" style="max-width:800px" >
            <h1 style="margin-bottom:0" > <?php if($_SESSION['hargerapartnerhargera'] != 'ADMIN') {
                    echo "Üzeneteim <a style='color:white' href='profile.php?profile={$_SESSION['hargerapartneridhargera']}'>{$_SESSION['hargerapartnerhargera']}</a> felhasználóval";
                }
                else {
                    echo "Üzeneteim a weboldal karbantartójával";
                }
                ?> </h1>
                <div class="content" style="padding:0;margin-right:0"  >
                    <div class="chat-container">
                        <?php 
                            foreach ($messages as $key => $value){
                                $dt = substr($value['m_date'],0,-3);
                                if ($value['sender_id'] == '1' && $_SESSION['hargerapartnerhargera'] == 'ADMIN') {$writer = 'admin';}
                                elseif($value['sender_id'] == $_SESSION['hargeraCurrentUserIdhargera']) {$writer = "my";}
                                else {$writer = "partner";}
                                echo "<div class='message-container {$writer}-message-container'>
                                        <div class='message-date {$writer}-message-date'>{$dt}</div>
                                        <div class='message-text {$writer}-message'>{$value['text']}</div>
                                    </div>";
                                
                        }

                        ?>
                        
                        
                    </div>
                    <form method="post" id="data">
                    
                        <div style="display:block;width:100%;height:fit-content;padding-bottom:20px">
                            <div class="chat-input-container">
                                <textarea type="text" class="chat-input" name="message" maxlength="300"></textarea>
                                <button class="submit btn chat-submit" type="submit" name="send_message">Küldés</button>
                            
                            </div>
                            <div class="info chat-info" >Maximum 300 karakter</div>
                        
                        
                        </div>
                    </form>

                </div>  

            </div>
            <?php
        echo '<script src="chat.js?'.filemtime('chat.js').'"></script>';

    ?>
    <div class="bottom-container">
        <div class="bottom-contact">Kapcsolat:</div>
        <div class="bottom-email">&#9993 Email cím: hargera@gmail.com</div>
        <div class="bottom-phone">&#x260E Telefonszám: +36 20-583-8346 </div>

    </div>
        <?php endif ?>

                    </div>
</body>
</html>