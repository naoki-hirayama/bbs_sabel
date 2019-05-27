<body>
    <h1>ユーザー登録画面</h1>
    <!-- エラーメッセージ -->
    <partial name="shared/error" />
    
    <form action="<?e uri('') ?>" method="post" >
        <p><?= $form->n('name') ?>：</p>
        <?e $form->text('name') ?><br />
        <p><?= $form->n('login_id') ?>：</p>
        <?e $form->text('login_id') ?><br />
        <p><?= $form->n('password') ?>：</p>
        <?e $form->password('password') ?><br />
        <p><?= $form->n('confirm_password') ?>：</p>
        <?e $form->password('confirm_password') ?><br />
        <input type="submit" name="signup" value="登録する">
    </form>
    <a href="<?e uri("a: login") ?>"　class="btn btn-primary">すでに登録済みの方はこちらへ</a><br />
    <a href="<?e uri('') ?>">登録しないで使う</a>
</body>
