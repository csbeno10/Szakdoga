<?php include('server.php') ?>
<?php 
    if (isset($_GET['mustlogin'])) {
        unset($_SESSION['mustlogin']);
        array_push($errors, "A művelethet végrehajtásához bejelentkezés szükséges!");
    }

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
  <div class="header">
  	<h2>Bejelentkezés</h2>
  </div>
	 
  <form method="post" action="login.php">
  	<?php include('errors.php'); ?>
  	<div class="input-group">
  		<label>Felhasználónév</label>
  		<input type="text" name="username">
  	</div>
  	<div class="input-group">
  		<label>Jelszó</label>
  		<input type="password" name="password">
  	</div>
  	<div class="input-group">
  		<button type="submit" class="btn" name="login_user">Bejelentkezés</button>
  	</div>
  	<p>
  		Még nincs fiókja? <a href="register.php">Regisztráció</a>
  	</p>
  </form>
</div>

</body>
</html>