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


    $stmt = $db->prepare("SELECT * FROM advertisement WHERE id=? LIMIT 1");
    $stmt->bind_param("i",$_GET['ad_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $ad = $result->fetch_assoc();
    $_SESSION['hargeraEditUserIdhargera'] = $ad['user_id'];
    $_SESSION['hargeraEditAdIdhargera'] = $ad['id'];


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
    <?php
    echo '<script src="edit.js?'.filemtime('edit.js').'"></script>';

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
    <div class="container">
        <h1>Hirdetés szerkesztése</h1>
        <div class="content">
            <?php  if ($ad == NULL) : ?>
                <div class="title title2">Ez a hirdetés nem létezik</div>
            <?php endif ?>
            <?php  if ($ad != NULL) : ?>
                <div class="input-group submit-div delete-group">
                    <button class="submit btn delete-btn" name="ad_delete" onclick="deleteAd()">Hirdetés törlése</button>
                </div>
                <form id="data" method="post" enctype="multipart/form-data" >
                    <div class="input-group">
                        <label>A hirdetés címe</label>
                        <div style="display: block">
                            <textarea name="title" class="title-text" maxlength="100"><?php echo $ad['title'] ?></textarea>
                            <div class="info">Maximum 100 karakter</div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Leírás</label>
                        <div style="display: block">
                        <textarea name="description" class="description-text" maxlength="1500"><?php echo $ad['description'] ?></textarea>
                        <div class="info">Maximum 1500 karakter</div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Ár</label>
                        <div style="display: block">
                        <input type="number" name="price" min="0" value='<?php echo $ad['price'] ?>'>
                        <div class="info">Ft-ban</div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Csomagküldés</label>
                        <div class="checkbox-container">
                            <div style="display:inline-flex">
                            <input type="radio" name="shipping" value="Csak személyes átvétellel" <?php if($ad['shipping'] == "Csak személyes átvétellel") echo "checked" ?>>
                            <span class="radio-label">Csak személyes átvétellel</span>
                            </div>
                            <div style="display:inline-flex">
                            <input type="radio" name="shipping" value="Csomagküldéssel is" <?php if($ad['shipping'] == "Csomagküldéssel is") echo "checked" ?>>
                            <span class="radio-label">Csomagküldéssel is</span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Település</label>
                        <textarea name="settlement" class="settlement-text" maxlength="30"><?php echo $ad['settlement'] ?></textarea>
                    </div>
                    
                    <div class="input-group">
                        <label>Kategória</label>
                        <select class="btn" name="category">
                            <option selected value="<?php echo $ad['category']?>"><?php echo $ad['category']?></option>
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

                    <div class="input-group img-group" >
                        <label >Jelenlegi címlapkép</label>
                        <img class='edit-img' <?php 
                        if (file_exists("uploads/".$ad['user_id']."/".$ad['id']."/thumbnail.jpg")){
                            echo "src='uploads/".$ad['user_id']."/".$ad['id']."/thumbnail.jpg?".filemtime("uploads/".$ad['user_id']."/".$ad['id']."/thumbnail.jpg")."'";
                        }
                        else {echo "src='default2.jpg'";}
                        ?>>
                                                 
                    </div>

                    <div class="input-group">
                        <label>Címlapkép cserélése</label>
                        <input type="file" id="thumbnail" name="thumbnail" class="files" >
                    </div>

                            <?php
                                $images = glob("uploads/{$ad['user_id']}/{$ad['id']}/*"); 
                                if (count($images) > 1){
                                    echo "<div class='input-group img-group'>
                                    <label>Egyéb képek törlése</label>
                                    <div class='img-container'>";

                                
                                    foreach($images as $img){ 
                                        $base = basename($img);
                                        if ($base != "thumbnail.jpg"){
                                            echo "<div class='img-with-delete' id='{$base[0]}'>
                                                    <img class='edit-img' src='".$img."?".filemtime("$img")."' >
                                                    <div class='delete-img-btn' onclick=deleteImg('{$base}')>Törlés</div>
                                                </div>";
                                        }
                                    
                                    }
                                    echo "</div></div>";
                                }
                               
                            ?>
                    <div class="input-group">
                        <label>Egyéb képek feltöltése</label>
                        <input type="file" id="files" name="files[]" class="files" multiple>
                    </div>
                    <div id="errors" class="errors ajaxerrors"></div>
                    
                    <div class="input-group submit-div">
                        <button type="submit" class="submit btn" name="edit_upload">Mentés!</button>
                    </div>

                </form>
                <?php
        echo '<script src="edit2.js?'.filemtime('edit2.js').'"></script>';

    ?>
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