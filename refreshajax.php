<?php include('connect.php') ?>

<?php
session_start();

$errors = array(); 




  $receiver = $_SESSION['hargeraCurrentUserIdhargera'];
  $sender = $_SESSION['hargerapartneridhargera'];
  $seen = 1;

  $stmt = $db->prepare("SELECT * FROM messages WHERE sender_id=? AND receiver_id=? AND seen=0");
  $stmt->bind_param("ii", $sender, $receiver);
  $stmt->execute();
  $result = $stmt->get_result();
  $messages = array();

  while ($row = $result->fetch_assoc()) {
      array_push($messages, array('date' => substr($row['m_date'],0,-3), 'text' => $row['text']));
  }

  $stmt = $db->prepare("UPDATE messages SET seen = ? WHERE sender_id=? AND receiver_id=? AND seen=0");
  $stmt->bind_param("iii", $seen, $sender, $receiver);
  $stmt->execute();

  echo json_encode($messages);


  



  



