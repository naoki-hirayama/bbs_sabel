<body>
    <h1>ログイン画面</h1>
    <!-- エラーメッセージ -->
    <partial name="shared/error" />
    
    <form action="<?php echo uri('') ?>" method="post" >
        <p><?php echo h($form->n('login_id')) ?>：</p>
        <?php echo $form->text('login_id') ?><br />
        <p><?php echo h($form->n('password')) ?>：</p>
        <?php echo $form->password('password') ?><br />
        <input type="submit" name="login" value="ログインする">
    </form>
    <a href="/auth/register"　class="btn btn-primary"> 未登録の方はこちらへ</a>
</body>