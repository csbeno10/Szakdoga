<?php include('connect.php') ?>

<?php
session_start();

$errors = array(); 

  





  $username = $_SESSION['hargeraCurrentUserhargera'];
  $user_id = $_SESSION['hargeraCurrentUserIdhargera'];
  $title = rtrim($_POST['title']);
  $title = trim(preg_replace('/\s+/', ' ', $title));
  $description = $_POST['description'];
  $description = trim(preg_replace('/\s+/', ' ', $description));
  $price = $_POST['price'];
  $shipping = $_POST['shipping'];
  $settlement = $_POST['settlement'];
  $settlement = trim(preg_replace('/\s+/', ' ', $settlement));
  $category =  $_POST['category'];
  $date = date("Y-m-d H:i:s");

  if (empty($title)) { array_push($errors, "Adjon meg egy címet"); }

  if (empty($description)) { array_push($errors, "Adjon meg egy leírást"); }
  if (empty($price)) { array_push($errors, "Adjon meg egy árat"); }
  if (empty($settlement)) { array_push($errors, "Adjon meg egy települést"); }
  if ($category == "default") { array_push($errors, "Válasszon kategóriát"); }


  $stmt = $db->prepare("SELECT * FROM advertisement WHERE user_id=? AND title=? LIMIT 1");
  $stmt->bind_param("ss", $user_id, $title);
  $stmt->execute();
  $result = $stmt->get_result();
  $ad = $result->fetch_assoc();
  
  if ($ad) { 
    array_push($errors, "Ilyen címmel már adott fel hirdetést");

  }

  if (!is_uploaded_file($_FILES['thumbnail']['tmp_name'])) { array_push($errors, "Töltsön fel címlapképet"); }
  elseif (count($_FILES['files']['name']) > 10) { array_push($errors, "Maximum 10 képet tölthet fel a címlapképen kívül"); }
  else {

    $target_dir = "uploads/" . $user_id . "/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir);
    }



    if (is_uploaded_file($_FILES['thumbnail']['tmp_name'][0])) { 
      foreach($_FILES['files']['name'] as $key=>$val) {
        $target_file = $target_dir . basename($_FILES["files"]["name"][$key]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


        if ($_FILES["files"]["size"][$key] > 1024 * 1024 * 10) {
          array_push($errors, "" . $_FILES["files"]["name"][$key] . " túl nagy");
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "webp"  ) {
          array_push($errors, "" . $_FILES["files"]["name"][$key] . " nem megfelelő kiterjesztésű (jpg, png, jpeg)  fájl");
        } 


      }
    }
    $target_file = $target_dir . basename($_FILES["thumbnail"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


    if ($_FILES["thumbnail"]["size"] > 1024 * 1024 * 10) {
      array_push($errors, "A címlapkép túl nagy");
    }

    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "webp" ) {
      array_push($errors, "A címlapkép nem megfelelő kiterjesztésű (jpg, png, jpeg) fájl");
    } 

  }
  if (count($errors) > 0) {
    echo json_encode(array('result' => 0, 'errors' => $errors));
  }


  if (count($errors) == 0) {
    $stmt = $db->prepare("INSERT INTO advertisement(user_id, title, description, price, shipping, settlement, category, date) VALUES (?, ? ,?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississss", $user_id, $title, $description, $price, $shipping, $settlement, $category, $date);
    $stmt->execute();

    $stmt = $db->prepare("SELECT id FROM advertisement WHERE BINARY user_id=? AND BINARY title=? LIMIT 1");
    $stmt->bind_param("ss", $user_id, $title);
    $stmt->execute();
    $result = $stmt->get_result();
    $ad = $result->fetch_assoc();
    $ad_id = $ad['id'];
    $target_dir = $target_dir . $ad_id . "/";
    if (!is_dir($target_dir)) {
      mkdir($target_dir);
    }

    foreach($_FILES['files']['name'] as $key=>$val) {
      $target_file = $target_dir . $key . ".jpg";
      move_uploaded_file($_FILES["files"]["tmp_name"][$key], $target_file);
    }


    $target_file = $target_dir .  "thumbnail.jpg"; 
    move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file);
    echo json_encode(array('result' => 1,'ad_id' => $ad_id));

  }
  



?>