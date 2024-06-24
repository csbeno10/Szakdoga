<?php include('connect.php') ?>

<?php
session_start();

$errors = array(); 


  

  $username = $_SESSION['hargeraCurrentUserhargera'];
  $user_id = $_SESSION['hargeraEditUserIdhargera'];
  $title = rtrim($_POST['title']);
  $title = trim(preg_replace('/\s+/', ' ', $title));
  $ad_id = $_SESSION['hargeraEditAdIdhargera'];
  $description = $_POST['description'];
  $description = trim(preg_replace('/\s+/', ' ', $description));
  $price = intval($_POST['price']);
  $shipping = $_POST['shipping'];
  $settlement = $_POST['settlement'];
  $settlement = trim(preg_replace('/\s+/', ' ', $settlement));
  $category =  $_POST['category'];

  if (empty($title)) { array_push($errors, "Adjon meg egy címet"); }
  if (empty($description)) { array_push($errors, "Adjon meg egy leírást"); }
  if (empty($price)) { array_push($errors, "Adjon meg egy árat"); }
  if (empty($settlement)) { array_push($errors, "Adjon meg egy települést"); }

  $stmt = $db->prepare("SELECT * FROM advertisement WHERE BINARY user_id=? AND title=? AND id != ? LIMIT 1");
  $stmt->bind_param("ssi", $user_id, $title, $ad_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $ad = $result->fetch_assoc();
  
  if ($ad) { 

      array_push($errors, "Ilyen címmel már adott fel másik hirdetést");

  }

  $availableNames = array();
  $target_dir = "uploads/" . $user_id . "/" . $ad_id . "/";

  for ($i = 0; $i < 10; $i++) {
    $filecheck = $target_dir . $i . ".jpg";
    if(!file_exists($filecheck)) {array_push($availableNames, $i . ".jpg");}
  }

  if ((count($_FILES['files']['name']) - count($availableNames)) > 0) { array_push($errors, "Maximum 10 képe lehet a címlapképen kívül"); }
  else {


    if (is_uploaded_file($_FILES['files']['tmp_name'][0])) { 
      foreach($_FILES['files']['name'] as $key=>$val) {
        $target_file = $target_dir . basename($_FILES["files"]["name"][$key]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


        if ($_FILES["files"]["size"][$key] > 1024 * 1024 * 10) {
          array_push($errors, "" . $_FILES["files"]["name"][$key] . " túl nagy");
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
          array_push($errors, "" . $_FILES["files"]["name"][$key] . " nem megfelelő kiterjesztésű (jpg, png, jpeg) fájl");
        } 


      }
    }
    if (is_uploaded_file($_FILES['thumbnail']['tmp_name'])) {
      $target_file = $target_dir . basename($_FILES["thumbnail"]["name"]);
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


      if ($_FILES["thumbnail"]["size"] > 1024 * 1024 * 10) {
        array_push($errors, "A címlapkép túl nagy");
      }

      if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
        array_push($errors, "A címlapkép nem megfelelő kiterjesztésű (jpg, png, jpeg) fájl");
      } 

    }
  }
  if (count($errors) > 0) {
    echo json_encode(array('result' => 0, 'errors' => $errors));
  }


  if (count($errors) == 0) {
    $stmt = $db->prepare("UPDATE advertisement SET title = ?, description = ?, price = ?, shipping = ?, settlement = ?, category = ? WHERE user_id=? AND id=?");
    $stmt->bind_param("ssisssii", $title, $description, $price, $shipping, $settlement, $category, $user_id, $ad_id);
    $stmt->execute();

    foreach($_FILES['files']['name'] as $key=>$val) {

      $target_file = $target_dir . array_shift($availableNames);
      move_uploaded_file($_FILES["files"]["tmp_name"][$key], $target_file);
    }

    $target_file = $target_dir . "thumbnail.jpg"; 
    move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file);
    $result = array('result' => 1,);
    echo json_encode(array('result' => 1,'ad_id' => $ad_id));

  }
  



