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



        <div class=" container" style="max-width:750px" >
        <h1>Jelentések</h1>
            <div class="search">
                <form method="post" action="reports.php" class="report-form">
                    <div class="report-search-group">
                        <label>Küldő</label>
                        <input type="text"  class="report-search-text" name="writer" value="<?php if(isset($_POST['writer'])) echo $_POST['writer']; elseif(isset($_GET['writer'])) echo $_GET['writer']; ?>">
                    </div>
                    <div class="report-search-group">
                        <label>Hirdetés tulajdonosa</label>
                        <input type="text"  class="report-search-text" name="target_user" value="<?php if(isset($_POST['target_user'])) echo $_POST['target_user']; elseif(isset($_GET['target_user'])) echo $_GET['target_user']; ?>">
                    </div>
                    
                    
                    


                    <div class="report-submit" style='margin-top:25px'>
                        <button type="submit" class="btn submit " name="report-search">Keresés</button>
                    </div>


                </form>
            </div>
            <div class="content" >
                <div class="items">

                    <?php 

                        $query1 = "SELECT Count(*) FROM reports r left JOIN users as u1 ON r.writer_id = u1.id left JOIN users as u2 ON r.target_user_id = u2.id left JOIN advertisement as a ON r.target_ad_id = a.id WHERE 1=1 ";
                        $query2 = "SELECT r.id, r.writer_id, u1.username as writer, u2.username as target_user, r.target_user_id, target_ad_id, a.title as target_ad_title, r.r_date FROM reports r left JOIN users as u1 ON r.writer_id = u1.id left JOIN users as u2 ON r.target_user_id = u2.id 
                        left JOIN advertisement as a ON r.target_ad_id = a.id WHERE 1=1 ";
                        $params = array();
                        $types = "";


                        if (isset($_POST['report-search'])) {
                            $writer = mysqli_real_escape_string($db, $_POST['writer']);
                            $target_user = mysqli_real_escape_string($db, $_POST['target_user']);
                            

                            if(!empty($writer)){
                                $query1 = $query1 . " AND u1.username LIKE ?";
                                $query2 = $query2 . " AND u1.username LIKE ?";
                                $params[] = "%$writer%";
                                $types = $types . "s";
                            }
                            if(!empty($target_user)){
                                $query1 = $query1 . " AND u2.username LIKE ?";
                                $query2 = $query2 . " AND u2.username LIKE ?";
                                $params[] = "%$target_user%";
                                $types = $types . "s";
                            }
                            
                        }
                        else {
                            $writer = "";
                            $target_user = "";

                            if(!empty($_GET['writer'])){
                                $query1 = $query1 . " AND u1.username LIKE ?";
                                $query2 = $query2 . " AND u1.username LIKE ?";
                                $writer = $_GET['writer'];
                                $params[] = "%$writer%";
                                $types = $types . "s";
                            }
                            if(!empty($_GET['target_user'])){
                                $query1 = $query1 . " AND u2.username LIKE ?";
                                $query2 = $query2 . " AND u2.username LIKE ?";
                                $target_user = $_GET['target_user'];
                                $params[] = "%$target_user%";
                                $types = $types . "s";
                            }
                            
                        }
                      

                        $stmt = $db->prepare($query1);
                        if (count($params) > 0){
                            $stmt->bind_param($types, ...$params);
                        }
                        
                        
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $fetched_result = $result->fetch_assoc();
                        $numreports = $fetched_result['Count(*)'];
                        if ($numreports == 0) {$numreports = 1;}

                        $numpages = (int)($numreports / 10);
                        if ($numreports % 10 != 0) {$numpages = $numpages + 1;}
                        if (!isset($_GET['page']) || (int)$_GET['page'] <= 1) {
                            $page = 1;
                        }
                        elseif ((int)$_GET['page'] >= $numpages) {
                            $page = $numpages;
                        }
                        else {$page = (int)$_GET['page'];}

                        $from = ($page - 1) * 10;

                        $query2 = $query2 . " ORDER BY r.r_date DESC LIMIT {$from},10";

                        $stmt = $db->prepare($query2);
                        if (count($params) > 0){
                            $stmt->bind_param($types, ...$params);
                        }

                        $stmt->execute();
                        $result = $stmt->get_result();
                        $reports = array();
                        while ($row = $result->fetch_assoc()) {
                            array_push($reports, $row);
                        }



                        foreach ($reports as $key => $value){
                            $dt = substr($value['r_date'],0,-3);

                            echo "
                            <a href='reportPage.php?id={$value['id']}' class='report-link'>
                                <div class='report'>
                                    <div class='report-group'> 
                                        <div class='report-title'>Küldő: </div>
                                        <div class='report-text'>{$value['writer']}</div>
                                    </div>
                                    <div class='report-group'> 
                                        <div class='report-title'>Hirdetés tulajdonosa: </div>
                                        <div class='report-text'>{$value['target_user']}</div>
                                    </div>
                                    <div class='report-group'> 
                                        <div class='report-title'>Jelentett hirdetés: </div>
                                        <div class='report-text'>{$value['target_ad_title']}</div>
                                    </div>
                                    <div class='report-group'> 
                                        <div class='report-title'>Jelentés dátuma: </div>
                                        <div class='report-text'>{$dt}</div>
                                    </div>
                                    
                                </div>
                            </a>";
                                    
                            
                        }


                    ?>
                    <div class="navigation-buttons" style="margin-top: 10px">
                        <?php 
                            if ($numreports < 10) {}
                            elseif ($page == 1){
                                echo "<a href='reports.php?page=2&writer={$writer}&target_user={$target_user}' 
                                class='next' style='margin-left:100px'>Következő &raquo;</a>";
                            }
                            elseif($page == $numpages) {
                                $min = $page - 1;
                                echo "<a class='previous' href='reports.php?page={$min}&writer={$writer}&target_user={$target_user}'
                                 style='margin-right:100px'>&laquo; Előző</a>";
                            }
                            else {
                                $min = $page - 1;
                                $max = $page + 1;
                                echo "<a class='previous' href='reports.php?page={$min}&writer={$writer}&target_user={$target_user}'>&laquo; Előző</a>
                                    <span class='slash'> | </span>
                                    <a class='next' href='reports.php?page={$max}&writer={$writer}&target_user={$target_user}'>Következő &raquo;</a>";
                            }
                        ?>
                    </div>
                    
                    
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