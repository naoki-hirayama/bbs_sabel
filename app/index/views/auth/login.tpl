<body>
    <h1>ログイン画面</h1>
    <!-- エラーメッセージ -->
    <partial name="shared/error" />
    
    <form action="<? uri('') ?>" method="post" >
        <p><?= $form->n('login_id') ?>：</p>
        <?e $form->text('login_id') ?><br />
        <p><?= $form->n('password') ?>：</p>
        <?e $form->password('password') ?><br />
        <input type="submit" value="ログインする">
    </form>
    <a href="/auth/register"　class="btn btn-primary"> 未登録の方はこちらへ</a>
</body>