<body>
    <h2>レス削除画面</h2>
    <!--ログイン情報-->
    <partial name="shared/info" />
    <!--エラーメッセージ-->
    <partial name="shared/error" />
    <ul>
        <li>
            名前：
            <?= $post->name ?><br />
            本文：
            <font color="<?= $post->color ?>">
                <?= $post->comment ?>
            </font><br />
            画像：
            <?php if (!is_null($post->picture)) : ?>
                <img src="/images/replies/<?= $post->picture ?>" width="300" height="200"><br />
            <?php else : ?>
                なし<br />
            <?php endif ?>
            時間：
            <?= $post->created_at ?><br />
            ---------------------------------------------<br />
            <form action="<?= uri('') ?>" method="post">
            <?php if ($post->password !== null) : ?>
                <p>削除パスワード:</p>
                <input type="password" name="password_input"><br />
                <input type="submit" value="削除"/><br />
            <?php else : ?>
                <input type="hidden" name="password_input">
                <input type="submit" value="ユーザー削除"/><br />
            <?php endif ?>
            <a href="/">戻る</a>
            </form>        
        </li>
    </ul>
</body>