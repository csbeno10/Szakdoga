<?php  if (count($errors) > 0) : ?>
  <div class="errors">
  	<?php foreach ($errors as $error) : ?>
  	  <p class="error"><?php echo $error ?></p>
  	<?php endforeach ?>
  </div>
<?php  endif ?>