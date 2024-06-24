<?php
session_start();
$user_id = $_SESSION['hargeraEditUserIdhargera'];
$ad_id = $_SESSION['hargeraEditAdIdhargera'];
$img = $_POST["img"];

$filename = "uploads/" . $user_id . "/" . $ad_id . "/" . $img;
if(unlink($filename)) {
    $num = $img[0];
    $res = array('result' => 1, 'message' => $num);
    echo json_encode($res);
}
?>