<?php include('connect.php') ?>

<?php
session_start();
if($_POST['start'] == 1){
        $user_id = $_SESSION['hargeraEditUserIdhargera'];
        $ad_id = $_SESSION['hargeraEditAdIdhargera'];

        $foldername = "uploads/" . $user_id . "/" . $ad_id;
        $files = glob($foldername . '/*');
        foreach ($files as $file) {
                unlink($file);
        }
        rmdir($foldername);
        $stmt = $db->prepare("DELETE FROM advertisement WHERE id=? ");
        $stmt->bind_param("i", $ad_id);
        $stmt->execute();

        $res = array('result' => 1);
        echo json_encode($res);
}

?>