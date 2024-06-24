<?php include('connect.php') ?>

<?php
session_start();
if($_SESSION['hargeraCurrentUserhargera'] == "ADMIN" && isset($_POST['delete-with-admin'])){
        $user_id = $_POST['user_id'];
        $ad_id = $_POST['ad_id'];

        $foldername = "uploads/" . $user_id . "/" . $ad_id;
        $files = glob($foldername . '/*');
        foreach ($files as $file) {
                unlink($file);
        }
        rmdir($foldername);

        $stmt = $db->prepare("DELETE FROM advertisement WHERE id = ?");
        $stmt->bind_param("i", $ad_id);
        $stmt->execute();

        header('location: deleteSuccess.php');
}


?>