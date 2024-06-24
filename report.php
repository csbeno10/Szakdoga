<?php include('server.php') ?>
<?php 
    
	$stmt = $db->prepare("SELECT * FROM advertisement WHERE id=?");
	$stmt->bind_param("i", $_GET['ad_id']);
	$stmt->execute();
    $result = $stmt->get_result();
    $ad = $result->fetch_assoc();
	
?>
<!DOCTYPE html>
<html>
<head>
  <title>Bejelentkezés</title>
  <?php
    echo '<link rel="stylesheet" type="text/css" href="style.css?'.filemtime('style.css').'">';

    ?>
</head>
<body>
<div class="bg">
	<?php  if ($ad == NULL && !isset($_SESSION['hargeraReportSuccesshargera'])) : ?>
			<div class="fakeuser">Ez a hirdetés nem létezik! </div>

	<?php endif ?>
	<?php  if ($ad != NULL && !isset($_SESSION['hargeraReportSuccesshargera'])) : ?>
		<div class="header" style="background-color:firebrick">
			<h2 >Jelentés</h2>
		</div>
			
		<form method="post" action="report.php?ad_id=<?php echo $_GET['ad_id']?>">
			<?php include('errors.php'); ?>
			<div class="input-group">
				<label>Jelentés szövege:</label>
				<textarea name="report_text"  maxlength="1000"><?php if(isset($_POST['report-text'])) echo $_POST['report-text']; ?></textarea>
				<div class="info" >Maximum 1000 karakter</div>
			</div>
			<input type="hidden" name="ad_id" value="<?php echo $_GET['ad_id']?>">

			<div class="input-group">
				<button type="submit" style="background-color:firebrick" class="btn" name="report_ad">Küldés</button>
			</div>

		</form>

	<?php endif ?>
	<?php  if (isset($_SESSION['hargeraReportSuccesshargera'])) : ?>
		<div class="success">Jelentés sikeresen leadva</div>
		<?php
		unset($_SESSION['hargeraReportSuccesshargera']);
		echo '<script type="text/JavaScript"> 
				function navigate() {
				window.location.href = "index.php";
				}
				setTimeout(navigate, 2500);
				</script>';
		?>
	<?php endif ?>
			</div>
</body>
</html>