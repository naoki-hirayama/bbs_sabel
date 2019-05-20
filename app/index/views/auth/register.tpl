<body>
    <h1>ユーザー登録画面</h1>
    <!-- エラーメッセージ -->
    <partial name="shared/error" />
    
    <form action="<?php echo uri('') ?>" method="post" >
        <p><?php echo h($form->n('name')) ?>：</p>
        <?php echo $form->text('name') ?><br />
        <p><?php echo h($form->n('login_id')) ?>：</p>
        <?php echo $form->text('login_id') ?><br />
        <p><?php echo h($form->n('password')) ?>：</p>
        <?php echo $form->password('password') ?><br />
        <p><?php echo h($form->n('confirm_password')) ?>：</p>
        <?php echo $form->password('confirm_password') ?><br />
        <input type="submit" name="signup" value="登録する">
    </form>
    <a href="/auth/login"　class="btn btn-primary">すでに登録済みの方はこちらへ</a><br />
    <a href="/">登録しないで使う</a>
</body>
