<?php include('connect.php') ?>

<?php
    session_start(); 
    $mustlogin="";

    if (isset($_GET['logout'])) {
        session_destroy();
        //unset($_SESSION['username']);
        header("location: index.php");
    }

    if (!isset($_SESSION['hargeraCurrentUserIdhargera'])) {
        header("location: login.php?mustlogin='1'");
    }

    if ($_SESSION['hargeraCurrentUserhargera'] == "ADMIN") {
        header("location: login.php?mustlogin='1'");
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
    <div class="container">
        <h1>Hirdetés feladása</h1>
        <div class="content">
                <form id="data" method="post" enctype="multipart/form-data">
                    <div class="input-group">
                        <label>A hirdetés címe</label>
                        <div style="display: block">
                            <textarea name="title" class="title-text" maxlength="100"><?php if(isset($_POST['title'])) echo $_POST['title']; ?></textarea>
                            <div class="info">Maximum 100 karakter</div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Leírás</label>
                        <div style="display: block">
                        <textarea name="description" class="description-text" maxlength="1500"><?php if(isset($_POST['description'])) echo $_POST['description']; ?></textarea>
                        <div class="info">Maximum 1500 karakter</div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Ár</label>
                        <div style="display: block">
                        <input type="number" name="price" min="0" value='<?php if(isset($_POST['price'])) echo $_POST['price']; ?>'>
                        <div class="info">Ft-ban</div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label class="package-label">Csomagküldés</label>
                        <div style="display:inline-flex">
                        <input type="radio" name="shipping" value="Csak személyes átvétellel" <?php if(!isset($_POST['shipping']) || $_POST['shipping'] == "Csak személyes átvétellel") echo "checked" ?>>
                        <span class="radio-label">Csak személyes átvétellel</span>
                        </div>
                        <div style="display:inline-flex">
                        <input type="radio" name="shipping" value="Csomagküldéssel is" <?php if(isset($_POST['shipping']) && $_POST['shipping'] == "Csomagküldéssel is") echo "checked" ?>>
                        <span class="radio-label">Csomagküldéssel is</span>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Település</label>
                        <textarea name="settlement" class="settlement-text" maxlength="30"><?php if(isset($_POST['settlement'])) echo $_POST['settlement']; ?></textarea>
                        </div>
                    
                    <div class="input-group">
                        <label>Kategória</label>
                        <select class="btn" name="category">
                            <option hidden selected value="default">--Válassz Kategóriát--</option>
                            <option value=Videókártya>Videókártya</option>
                            <option value=Processzor>Processzor</option>
                            <option value=Alaplap>Alaplap</option>
                            <option value=Hűtés>Hűtés</option>
                            <option value=Monitor>Monitor</option>
                            <option value="Számítógépház, tápegység">Számítógépház, tápegység</option>
                            <option value="Adattároló, merevlemez">Adattároló, merevlemez</option>
                            <option value=Memória>Memória</option>
                            <option value="Kábel, csatlakozó">Kábel, csatlakozó</option>
                            <option value="Laptop, notebook">Laptop, notebook</option>
                            <option value="PC, asztali számítógép">PC, asztali számítógép</option>
                            <option value="Hálózati eszköz">Hálózati eszköz</option>
                            <option value=Egyéb>Egyéb</option>
                            
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Címlapkép feltöltése</label>
                        <input type="file" id="thumbnail" name="thumbnail" class="files" >
                    </div>

                    <div class="input-group">
                        <label>Egyéb képek feltöltése</label>
                        <input type="file" id="files" name="files[]" class="files" multiple>
                    </div>
                    <div id="errors" class="errors ajaxerrors"></div>
                    
                    <div class="input-group submit-div">
                        <button type="submit" class="submit btn" name="ad_upload">Hirdetés feladása!</button>
                    </div>

                </form>
                <?php
        echo '<script src="upload.js?'.filemtime('upload.js').'"></script>';

    ?>
                
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