<? if (!empty($errors)) : ?>
  <ul>
      <? foreach ($errors as $error) : ?>
        <li><?e $error ?></li>
      <? endforeach ?>
  </ul>
<? endif ?>