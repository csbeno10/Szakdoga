<?php include('connect.php') ?>

<?php
session_start();

$errors = array(); 


  

  $sender = $_SESSION['hargeraCurrentUserIdhargera'];
  $receiver = $_SESSION['hargerapartneridhargera'];
  $text = $_POST['message'];
  $date = date("Y-m-d H:i:s");
  $seen=0;

  if (!empty($text)) { 
    $stmt = $db->prepare("INSERT INTO messages(sender_id, receiver_id, m_date, text, seen) VALUES (?, ? ,?, ?,?)");
    $stmt->bind_param("iissi", $sender, $receiver, $date, $text, $seen);
    $stmt->execute();

    $result = array('date' => substr($date,0,-3), 'text' => $text);
    echo json_encode($result);


  }



  



