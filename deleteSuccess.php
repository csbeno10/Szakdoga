<?php include('connect.php') ?>

<?php
    session_start(); 
    $mustlogin="";

    if (isset($_GET['logout'])) {
        session_destroy();
        header("location: index.php");
    }

    if (!isset($_SESSION['hargeraCurrentUserhargera'])) {
        header("location: login.php?mustlogin='1'");
    }


    $stmt = $db->prepare("SELECT sender_id FROM messages WHERE BINARY receiver_id=? AND seen = 0 GROUP BY sender_id");
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
        <?php  if (isset($_SESSION['hargeraCurrentUserhargera'])) : ?>
            <ul>
                <li><a href="">Hirdetésfeladás</a></li>
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
        <div class="success">Hírdetés sikeresen törölve! </div>
            <div class="bottom-container" style="position:absolute;bottom:0;width:100%">
                <div class="bottom-contact">Kapcsolat:</div>
                <div class="bottom-email">&#9993 Email cím: hargera@gmail.com</div>
                <div class="bottom-phone">&#x260E Telefonszám: +36 20-583-8346 </div>

            </div>
        <?php
            echo '<script type="text/JavaScript"> 
				function navigate() {
				window.location.href = "index.php";
				}
				setTimeout(navigate, 2000);
				</script>';
		?>
    
    

    
    </div>
</body>
</html>