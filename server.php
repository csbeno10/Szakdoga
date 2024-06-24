<?php include('connect.php') ?>

<?php
session_start();

$errors = array(); 

  

if (isset($_POST['reg_user'])) {


  $username = rtrim(mysqli_real_escape_string($db, $_POST['username']));
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);


  if (empty($username)) { array_push($errors, "Adja meg a felhasználónevet"); }
  elseif ((preg_match("/admin/i", strtolower($username)))) {array_push($errors, "A felhasználónév nem tartalmazhatja az admin szót");}


  if (empty($email)) { array_push($errors, "Adja meg az email címet"); }
  elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { array_push($errors, "Adjon meg egy valós email címet"); }
  if (empty($password_1)) { array_push($errors, "Adja meg a jelszót"); }
  if ($password_1 != $password_2) {
	array_push($errors, "A két jelszó nem egyezik meg");
  }
  if (strlen($username) > 15) { array_push($errors, "A felhasználónév maximum 15 karakter hosszú lehet"); }
  if (strlen($password_1) > 15) { array_push($errors, "A jelszó maximum 15 karakter hosszú lehet"); }
  if (strlen($email) > 30) { array_push($errors, "Az email cím maximum 30 karakter hosszú lehet"); }

  $stmt = $db->prepare("SELECT * FROM users WHERE BINARY username=? OR BINARY email=? LIMIT 1");
  $stmt->bind_param("ss", $username, $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
  
  if ($user) { 
    if ($user['username'] == $username) {
      array_push($errors, "A felhasználónév már létezik");
    }

    if ($user['email'] == $email) {
      array_push($errors, "Az email cím már létezik");
    }
  }


  if (count($errors) == 0) {
    $date = date("Y-m-d H:i:s");
    $password = $password_1;
    $stmt = $db->prepare("INSERT INTO users(username, email, password, register_date) VALUES (?, ? ,?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $date);
    $stmt->execute();
  	$_SESSION['hargeraCurrentUserhargera'] = $username;
    $stmt = $db->prepare("SELECT id FROM users WHERE BINARY username=?");
    $stmt->bind_param("s", $_SESSION['hargeraCurrentUserhargera']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $_SESSION['hargeraCurrentUserIdhargera'] = $user['id'];
  	header('location: index.php');
  }

}

if (isset($_POST['login_user'])) {


  $username = rtrim(mysqli_real_escape_string($db, $_POST['username']));
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Adja meg a felhasználónevét");
  }
  if (empty($password)) {
  	array_push($errors, "Adja meg a jelszavát");
  }

  if (count($errors) == 0) {
    $stmt = $db->prepare("SELECT * FROM users WHERE BINARY username=? AND BINARY password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->store_result();
    $numrows = $stmt -> num_rows();
  	if ($numrows == 1) {
  	  $_SESSION['hargeraCurrentUserhargera'] = $username;
      $stmt = $db->prepare("SELECT id FROM users WHERE BINARY username=?");
      $stmt->bind_param("s", $_SESSION['hargeraCurrentUserhargera']);
      $stmt->execute();
      $result = $stmt->get_result();
      $user = $result->fetch_assoc();
      $_SESSION['hargeraCurrentUserIdhargera'] = $user['id'];
  	  header('location: index.php');
  	}else {
  		array_push($errors, "Rossz felhasználónév/jelszó");
  	}
  }
}


if (isset($_POST['profile_update'])) {

  $user_id = $_SESSION['hargeraCurrentUserIdhargera'];
  $phone = mysqli_real_escape_string($db, $_POST['phone']);
  $full_name = mysqli_real_escape_string($db, $_POST['full_name']);
  $stmt = $db->prepare("UPDATE users SET phone=?, full_name=? WHERE id=?");
  $stmt->bind_param("sss", $phone, $full_name, $user_id);
  $stmt->execute();

}

if (isset($_POST['edit_password'])) {

  $user_id = $_SESSION['hargeraCurrentUserIdhargera'];
  $current = mysqli_real_escape_string($db, $_POST['current_password']);
  $new1 = mysqli_real_escape_string($db, $_POST['new_password1']);
  $new2 = mysqli_real_escape_string($db, $_POST['new_password2']);
  $stmt = $db->prepare("SELECT password from users WHERE id=?");
  $stmt->bind_param("s", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
  if (empty($current) || empty($new1) || empty($new2)) { array_push($errors, "Adja meg a jelenlegi és az új jelszavát is"); }
  else {
    if ($current != $user['password']) { array_push($errors, "Hibás jelszót adott meg"); }
    if ($new1 != $new2) { array_push($errors, "A két jelszó nem egyezik meg");}
    if (strlen($new1) > 15 || strlen($new2) > 15) { array_push($errors, "Az új jelszó maximum 15 karakter hosszú lehet"); }
  }

  if(count($errors) == 0){
    $stmt = $db->prepare("UPDATE users SET password=? WHERE id=?");
    $stmt->bind_param("ss", $new1, $user_id);
    $stmt->execute();
    $_SESSION['hargeraPasswordEditSuccesshargera'] = 1;

  }


}

if (isset($_POST['rate_user'])) {


  if (empty($_POST['rating'])) { array_push($errors, "Válasszd ki, hogy pozitív vagy negatív az értékelésed"); }
  else {$rating = mysqli_real_escape_string($db, $_POST['rating']); }
  $text = mysqli_real_escape_string($db, $_POST['rating-text']);
  $target = mysqli_real_escape_string($db, $_POST['target']);
  $score = mysqli_real_escape_string($db, $_POST['score']);

  if (empty($text)) { array_push($errors, "Írj szöveget az értékeléshez"); }

  $writer = $_SESSION['hargeraCurrentUserIdhargera'];

  $stmt = $db->prepare("SELECT * FROM ratings WHERE writer_id=? AND target_id=? LIMIT 1");
  $stmt->bind_param("ii", $writer, $target);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
  if ($user) {array_push($errors, "Már írtál értékelést erről a felhasználóról");}

  if (count($errors) == 0) {

    $date = date("Y-m-d H:i:s");

    $stmt = $db->prepare("INSERT INTO ratings(writer_id, target_id, rate_date, text, rating) VALUES (?, ? ,?, ?,?)");
    $stmt->bind_param("iisss", $writer, $target, $date, $text, $rating);
    $stmt->execute();
    $score = (int)$score;
    if($rating == "positive") {$score=$score+1;}
    else {$score=$score-1;}


    $stmt = $db->prepare("UPDATE users SET score=? WHERE id=?");
    $stmt->bind_param("ss", $score, $target);
    $stmt->execute();

  }

}

if (isset($_POST['report_ad'])) {


  $text = rtrim($_POST['report_text']);
  $ad_id = mysqli_real_escape_string($db, $_POST['ad_id']);
  $text = trim(preg_replace('/\s+/', ' ', $text));

  if (empty($_POST['report_text'])) { array_push($errors, "Írj szöveget a jelentéshez!"); }

  $stmt = $db->prepare("SELECT * FROM advertisement WHERE id=? ");
  $stmt->bind_param("i", $ad_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $ad = $result->fetch_assoc();

  if(!($ad)) {array_push($errors, "Ez a hirdetés nem létezik");}


  if (count($errors) == 0) {

    $writer = $_SESSION['hargeraCurrentUserIdhargera'];
    $date = date("Y-m-d H:i:s");
    $stmt = $db->prepare("INSERT INTO reports(writer_id, r_text, target_user_id, target_ad_id, r_date ) VALUES (?, ? , ?,?,?)");
    $stmt->bind_param("isiis", $writer, $text, $ad['user_id'], $ad_id,  $date);
    $stmt->execute();

    $_SESSION['hargeraReportSuccesshargera'] = "1";

  }
  
}

?>