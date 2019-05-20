<?php if (!empty($errors)) : ?>
  <ul>
      <?php foreach ($errors as $error) : ?>
        <li><?php echo $error ?></li>
      <?php endforeach ?>
  </ul>
<?php endif ?>