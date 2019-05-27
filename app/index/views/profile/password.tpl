<body>
    <h1>パスワードを編集</h1>
    <!--ログイン情報-->
    <partial name="shared/info" />
    <!-- エラーメッセージ -->
    <partial name="shared/error" />
    <!-- ここまで -->
    <form action="<?= uri('') ?>" method="post">
        <p><?= $form->n('current_password') ?>:</p>
        <?e $form->password('current_password') ?>
        <p><?= $form->n('password') ?>:</p>
        <?e $form->password('password') ?>
        <p><?= $form->n('confirm_password') ?>:</p>
        <?e $form->password('confirm_password') ?><br />
        <input type="submit" value="変更する"/><br />
    </form>
    <a href="<?e uri('c:profile, a:edit') ?>">戻る</a><br />
</body>
