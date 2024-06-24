<?php include('connect.php') ?>

<?php 
    session_start(); 
    $mustlogin="";

    if (!isset($_SESSION['hargeraCurrentUserhargera'])) {
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
        <div class="container" style="max-width:650px">
            <h1>Üzeneteim</h1>
            <div class="content">

                <div class="contact-container">
                    <?php 
                    if ($_SESSION['hargeraCurrentUserhargera'] != 'ADMIN'){
                        $currentUserId = $_SESSION['hargeraCurrentUserIdhargera'];


                        $stmt = $db->prepare("SELECT sender_id, seen FROM messages WHERE (sender_id=1 and receiver_id=?) or (sender_id=? AND receiver_id=1) ORDER BY m_date DESC limit 1");
                        $stmt->bind_param("ii", $currentUserId, $currentUserId);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        if ($row['sender_id'] != null) {
                            echo "<a class='contact' style='background-color:#ffcccb;margin-bottom:40px;border:2px solid firebrick'href='chat.php?partner=1'>
                                        <div class='contact-user' >Az oldal karbantartójának üzenete</div>";
                            if ($row['sender_id'] == 1 && $row['seen'] == 0){
                                echo "<div class='contact-new-message'>Új!</div>";
                            }
                            echo "</a>";
                        }

                        $stmt = $db->prepare("SELECT m.sender_id, m.m_date, m.seen, u.username as sender FROM messages m 
                        INNER JOIN (SELECT sender_id as sender_id, max(m_date) AS MaxDate FROM messages WHERE receiver_id = ? AND sender_id != 1 GROUP BY sender_id) m2 ON m.sender_id = m2.sender_id and m.m_date = m2.MaxDate
                        left JOIN users as u ON m.sender_id = u.id WHERE seen=0 ORDER BY m.m_date DESC" );
                        $stmt->bind_param("i", $_SESSION['hargeraCurrentUserIdhargera']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $new = array();
                        $users = array();
                        while ($row = $result->fetch_assoc()) {
                            array_push($new, ['user' => $row['sender'],'id' => $row['sender_id'],  'date' => $row['m_date']]);
                            array_push($users, $row['sender']);
                        }


                        $stmt = $db->prepare("SELECT m.sender_id, m.receiver_id, max(m.m_date) as m_date, u1.username as sender, u2.username as receiver FROM messages m left JOIN users as u1 ON m.sender_id = u1.id 
                        left JOIN users as u2 ON m.receiver_id = u2.id WHERE (m.sender_id=? or m.receiver_id = ?) AND m.sender_id!=1 AND m.receiver_id!=1 group by m.sender_id, m.receiver_id ORDER BY max(m.m_date) DESC ");
                        $stmt->bind_param("ii", $_SESSION['hargeraCurrentUserIdhargera'], $_SESSION['hargeraCurrentUserIdhargera']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $rows = array();
                        while ($row = $result->fetch_assoc()) {
                            array_push($rows, $row);
                        }
                    }
                    else {

                        $stmt = $db->prepare("SELECT m.sender_id, m.m_date, m.seen, u.username as sender FROM messages m INNER JOIN (SELECT sender_id as sender_id, max(m_date) AS MaxDate FROM messages WHERE receiver_id = 1 GROUP BY sender_id) m2 ON m.sender_id = m2.sender_id and m.m_date = m2.MaxDate
                        left JOIN users as u ON m.sender_id = u.id WHERE seen=0 ORDER BY m.m_date DESC" );
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $new = array();
                        $users = array();
                        while ($row = $result->fetch_assoc()) {
                            array_push($new, ['user' => $row['sender'],'id' => $row['sender_id'],  'date' => $row['m_date']]);
                            array_push($users, $row['sender']);
                        }


                        $stmt = $db->prepare("SELECT m.sender_id, m.receiver_id, max(m.m_date) as m_date, u1.username as sender, u2.username as receiver FROM messages m left JOIN users as u1 ON m.sender_id = u1.id 
                        left JOIN users as u2 ON m.receiver_id = u2.id WHERE (m.sender_id=1 or m.receiver_id = 1) group by m.sender_id, m.receiver_id ORDER BY max(m.m_date) DESC");
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $rows = array();
                        while ($row = $result->fetch_assoc()) {
                            array_push($rows, $row);
                        }

                    }
                        
                        
                        $old = array();
                        foreach ($rows as $key => $value){
                            if($value['sender'] != $_SESSION['hargeraCurrentUserhargera']){
                                if(!(in_array($value['sender'], $users))){
                                    array_push($old, ['user' => $value['sender'], 'id' => $value['sender_id'], 'date' => $value['m_date']]);
                                    array_push($users, $value['sender']);
                                }
                                
                            }
                            elseif ($value['receiver'] != $_SESSION['hargeraCurrentUserhargera']){
                                if(!(in_array($value['receiver'], $users))){
                                    array_push($old, ['user' => $value['receiver'],'id' => $value['receiver_id'],  'date' => $value['m_date']]);
                                    array_push($users, $value['receiver']);
                                }
                            }

                        }

                        if (empty($old) && empty($new)) {
                            echo "<div class='title title2'>Még nincsenek megjeleníthető beszélgetéseid</div>";
                        }
                        else {

                            echo "<div class='contact-header' >
                                        <div class='contact-header-user'>Felhasználó</div>
                                        <div class='contact-header-date'>Utolsó üzenet dátuma</div>
                                    </div>";


                            foreach ($new as $key => $value){
                                $dt = substr($value['date'],0,-3);
                                echo "<a class='contact' href='chat.php?partner={$value['id']}'>
                                        <div class='contact-user'>{$value['user']}</div>
                                        <div class='contact-new-message'>Új!</div>
                                        <div class='contact-date'>{$dt}</div>
                                    </a>";
                                
                            }

                            foreach ($old as $key => $value){
                                $dt = substr($value['date'],0,-3);
                                echo "<a class='contact' href='chat.php?partner={$value['id']}'>
                                        <div class='contact-user'>{$value['user']}</div>
                                        <div class='contact-date' style='margin-left: auto'>{$dt}</div>
                                    </a>";
                                
                            }
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