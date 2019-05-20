<h2 class="error">5xx Server Error</h2>
<div class="box">
  <p>サーバーでエラーが発生し、<br>処理は中断されました。</p>

  <? if (isset($exception_message)) : ?>
    <p id="exceptionMessage">
      <?= $exception_message ?>
    </p>
  <? endif ?>
</div>
