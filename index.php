<?php include('connect.php') ?>

<?php 
    session_start(); 
    $mustlogin="";

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
                <a href="#" ><button class="hargera">Hargera</button></a>
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
        <div class="itemcontainer container" >
            <div class="search">
                <form method="post" action="index.php" >
                    <div class="search-group" >
                        <input type="text" class="search-main" name="search-text" placeholder="Keresés" value="<?php if(isset($_POST['search-text'])) echo $_POST['search-text']; elseif (isset($_GET['search_text'])) echo $_GET['search_text']; ?>">
                        <button type="submit" class="submit search-submit btn" name="search">Keresés!</button>
                    </div>
                    <div class="search-options">
                        <input type="text"  class="search-text" name="settlement" placeholder="Szűkítés településre" value="<?php if(isset($_POST['settlement'])) echo $_POST['settlement']; elseif(isset($_GET['settlement'])) echo $_GET['settlement']; ?>">
                        <input type="number"  class="search-price" min="0" name="min-price" placeholder="Min. ár" value="<?php if(isset($_POST['min-price'])) echo $_POST['min-price']; elseif(isset($_GET['min_price'])) echo $_GET['min_price']; ?>">
                        <input type="number"  class="search-price" min="0" name="max-price" placeholder="Max. ár" value="<?php if(isset($_POST['max-price'])) echo $_POST['max-price']; elseif(isset($_GET['max_price'])) echo $_GET['max_price'];?>">
                        <select class="search-select btn"  name="category">
                            <option hidden 
                                <?php 
                                $categories = array("Videókártya", "Processzor", "Alaplap", "Hűtés", "Számítógépház, tápegység", "Adattároló, merevlemez", "Memória", "Kábel, csatlakozó", "Laptop, notebook", "PC, asztali számítógép", "Hálózati eszköz", "Egyéb");
                                if(isset($_POST['category']) && $_POST['category'] != "default") {
                                    echo "value={$_POST['category']}";
                                    $categoryvalue = $_POST['category'];

                                } 
                                elseif (isset($_GET['category']) && in_array($_GET['category'], $categories)){
                                    echo "value={$_GET['category']}";
                                    $categoryvalue = $_GET['category'];


                                }
                                else {
                                    echo "value='default'";
                                }?>  >
                                <?php if(isset($categoryvalue)) echo $categoryvalue; else echo "--Szűkítés Kategóriára--"; ?></option>
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
                                <?php if(isset($categoryvalue)) echo "<option value='default'>Minden Kategóriában</option>";?>
                                
                            
                        </select>
                        <div class="search-checkbox">
                            <label>Csomagküldéssel is</label>
                            <input type="checkbox"  class="search-check" name="shipping" <?php if(isset($_POST['shipping'])) echo "checked"; elseif(isset($_GET['shipping']) && $_GET['shipping'] == "Csomagküldéssel is") echo "checked"?>>
                        </div>
                    </div>
                </form>
            </div>
            <div class="content" >
                <div class="items">
                    <?php 


                        $query1 = "SELECT Count(*) FROM advertisement as a WHERE 1=1";
                        $query2 = "SELECT u.username as user, a.title, a.price, a.shipping, a.settlement, u.score, a.date, a.id, a.user_id FROM advertisement as a left JOIN users as u ON a.user_id = u.id WHERE 1=1";
                        $params = array();
                        $types = "";


                        if (isset($_POST['search'])) {
                            $search_text = mysqli_real_escape_string($db, $_POST['search-text']);
                            $settlement = mysqli_real_escape_string($db, $_POST['settlement']);
                            $min_price = mysqli_real_escape_string($db, $_POST['min-price']);
                            $max_price = mysqli_real_escape_string($db, $_POST['max-price']);
                            $category = mysqli_real_escape_string($db, $_POST['category']);
                            $shipping = "";
                            

                            if(!empty($search_text)){
                                $query1 = $query1 . " AND a.title LIKE ?";
                                $query2 = $query2 . " AND a.title LIKE ?";
                                $params[] = "%$search_text%";
                                $types = $types . "s";
                            }
                            if(!empty($settlement)){
                                $query1 = $query1 . " AND a.settlement LIKE ?";
                                $query2 = $query2 . " AND a.settlement LIKE ?";
                                $params[] = "%$settlement%";
                                $types = $types . "s";
                            }
                            if(!empty($min_price)){
                                $query1 = $query1 . " AND a.price >= ?";
                                $query2 = $query2 . " AND a.price >= ?";
                                $params[] = (int)$min_price;
                                $types = $types . "i";
                            }
                            if(!empty($max_price)){
                                $query1 = $query1 . " AND a.price <= ?";
                                $query2 = $query2 . " AND a.price <= ?";
                                $params[] = (int)$max_price;
                                $types = $types . "i";
                            }
                            if($category != "default"){
                                $query1 = $query1 . " AND a.category LIKE ?";
                                $query2 = $query2 . " AND a.category LIKE ?";
                                $params[] = "%$category%";
                                $types = $types . "s";
                            }
                            if(isset($_POST['shipping'])){
                                $query1 = $query1 . " AND a.shipping LIKE 'Csomagküldéssel is'";
                                $query2 = $query2 . " AND a.shipping LIKE 'Csomagküldéssel is'";
                                $shipping = "Csomagküldéssel is";
                            }
                            

                        }
                        else {
                            $search_text = "";
                            $settlement = "";
                            $min_price = "";
                            $max_price = "";
                            $category = "";
                            $shipping = "";
                            if(!empty($_GET['search_text'])){
                                $query1 = $query1 . " AND a.title LIKE ?";
                                $query2 = $query2 . " AND a.title LIKE ?";
                                $search_text = $_GET['search_text'];
                                $params[] = "%$search_text%";
                                $types = $types . "s";
                            }
                            if(!empty($_GET['settlement'])){
                                $query1 = $query1 . " AND a.settlement LIKE ?";
                                $query2 = $query2 . " AND a.settlement LIKE ?";
                                $settlement = $_GET['settlement'];
                                $params[] = "%$settlement%";
                                $types = $types . "s";
                            }
                            if(!empty($_GET['min_price'])){
                                $query1 = $query1 . " AND a.price >= ?";
                                $query2 = $query2 . " AND a.price >= ?";
                                $min_price = $_GET['min_price'];
                                $params[] = (int)$_GET['min_price'];
                                $types = $types . "i";
                            }
                            if(!empty($_GET['max_price'])){
                                $query1 = $query1 . " AND a.price <= ?";
                                $query2 = $query2 . " AND a.price <= ?";
                                $max_price = $_GET['max_price'];
                                $params[] = (int)$_GET['max_price'];
                                $types = $types . "i";
                            }
                            if(!empty($_GET['category']) && $_GET['category'] != "default" && in_array($_GET['category'], $categories)){
                                $query1 = $query1 . " AND a.category LIKE ?";
                                $query2 = $query2 . " AND a.category LIKE ?";
                                $category = $_GET['category'];
                                $params[] = "%$category%";
                                $types = $types . "s";
                            }
                            if(!empty($_GET['shipping']) && $_GET['shipping'] == "Csomagküldéssel is"){
                                $query1 = $query1 . " AND a.shipping LIKE 'Csomagküldéssel is'";
                                $query2 = $query2 . " AND a.shipping LIKE 'Csomagküldéssel is'";
                                $shipping = $_GET['shipping'];
                            }
                        }
                      

                        $stmt = $db->prepare($query1);
                        if (count($params) > 0){
                            $stmt->bind_param($types, ...$params);
                        }
                        
                        
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $fetched_result = $result->fetch_assoc();
                        $numadvertisements = $fetched_result['Count(*)'];
                        if ($numadvertisements == 0){
                            echo "<div class='title item' ><div style='padding:10px'>A megadott keresési feltételek nem eredményeztek egy találatot sem!</div></div>";
               
                        }
                        

                        $numpages = (int)($numadvertisements / 10);
                        if ($numadvertisements % 10 != 0) {$numpages = $numpages + 1;}
                        if (!isset($_GET['page']) || (int)$_GET['page'] <= 1) {
                            $page = 1;
                        }
                        elseif ((int)$_GET['page'] >= $numpages) {
                            $page = $numpages;
                        }
                        else {$page = (int)$_GET['page'];}

                        $from = ($page - 1) * 10;

                        $query2 = $query2 . " ORDER BY a.date DESC LIMIT {$from},10";

                        $stmt = $db->prepare($query2);
                        if (count($params) > 0){
                            $stmt->bind_param($types, ...$params);
                        }

                        $stmt->execute();
                        $result = $stmt->get_result();
                        $ads = array();
                        while ($row = $result->fetch_assoc()) {
                            array_push($ads, $row);
                        }



                        foreach ($ads as $key => $value){
                            $price = number_format((int)$value['price'],0,""," ");
                            if ($value['score'] > 0) {$color = "green";}
                            elseif ($value['score'] < 0) {$color = "red";}
                            else {$color = "black";}
                            echo "<div class='item'>
                                    <a href='ad page.php?ad_id={$value['id']}' class='img'><img class='item-img'";
                                    if (file_exists("uploads/".$value['user_id']."/".$value['id']."/thumbnail.jpg")){
                                        echo "src='uploads/".$value['user_id']."/".$value['id']."/thumbnail.jpg?".filemtime("uploads/".$value['user_id']."/".$value['id']."/thumbnail.jpg")."'";
                                    }
                                    else {echo "src='default2.jpg'";}
                                    echo " 
                                    onerror=this.src='default2.jpg'></a>
                                    <div class='item-mid-section'>
                                        <div class='item-title'><a class='nodec' href='ad page.php?ad_id={$value['id']}' style='color:darkblue'>{$value['title']}</a></div>
                                        <div class='plus-section'>
                                            <div class='item-price'>{$price} Ft</div>
                                            <div class='item-settlement'>{$value['settlement']}</div>
                                        </div>
                                        <div class='item-shipping'>{$value['shipping']}</div>
                                        <div class='user-and-rating'>
                                            <div class='item-user'>Feladó: <a href='profile.php?profile={$value['user_id']}' class='nodec'>{$value['user']}</a></div>
                                            <div class='item-rating' style='color:{$color}'>({$value['score']})</div>
                                        </div>
            
                                    </div>
                                    
                                    <div class='vertical-line'></div>
                                    <div class='item-right-section'>
                                        <div class='item-price'>{$price} Ft</div>
                                        <div class='item-settlement'>{$value['settlement']}</div>
            
                                    </div>
                                </div>";
                        }
                    


                    ?>
                    <div class="navigation-buttons" style="margin-top: 10px">
                        <?php 
                            if ($numadvertisements <= 10) {}
                            elseif ($page == 1){
                                echo "<a style='color:white' href='index.php?page=2&search_text={$search_text}&settlement={$settlement}&min_price={$min_price}&max_price={$max_price}&category={$category}&shipping={$shipping}' 
                                class='next' style='margin-left:100px'>Következő &raquo;</a>";
                            }
                            elseif($page == $numpages) {
                                $min = $page - 1;
                                echo "<a class='previous' style='color:white' href='index.php?page={$min}&search_text={$search_text}&settlement={$settlement}&min_price={$min_price}&max_price={$max_price}&category={$category}&shipping={$shipping}'
                                 style='margin-right:100px'>&laquo; Előző</a>";
                            }
                            else {
                                $min = $page - 1;
                                $max = $page + 1;
                                echo "<a style='color:white' class='previous' href='index.php?page={$min}&search_text={$search_text}&settlement={$settlement}&min_price={$min_price}&max_price={$max_price}&category={$category}&shipping={$shipping}'>&laquo; Előző</a>
                                    <span class='slash'> | </span>
                                    <a style='color:white' class='next' href='index.php?page={$max}&search_text={$search_text}&settlement={$settlement}&min_price={$min_price}&max_price={$max_price}&category={$category}&shipping={$shipping}'>Következő &raquo;</a>";
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