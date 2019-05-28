<h2>レス削除画面</h2>
<!--ログイン情報-->
<partial name="shared/info" />
<!--エラーメッセージ-->
<partial name="shared/error" />
<ul>
    <li>
        名前：
        <?= $reply->name ?><br />
        本文：
        <font color="<?= $reply->color ?>">
            <?= $reply->comment ?>
        </font><br />
        画像：
        <?php if (!is_null($reply->picture)) : ?>
            <img src="/images/replies/<?= $reply->picture ?>" width="300" height="200"><br />
        <?php else : ?>
            なし<br />
        <?php endif ?>
        時間：
        <?= $reply->created_at ?><br />
        ---------------------------------------------<br />
        <form action="<?e uri('') ?>" method="post">
        <?php if ($reply->password !== null) : ?>
            <p>削除パスワード:</p>
            <input type="password" name="password_input"><br />
            <input type="submit" value="削除"/><br />
        <?php else : ?>
            <input type="hidden" name="password_input">
            <input type="submit" value="ユーザー削除"/><br />
        <?php endif ?>
        <a href="<?e uri('/') ?>">戻る</a>
        </form>        
    </li>
</ul>
