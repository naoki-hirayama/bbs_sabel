<body>
    <!--ログイン情報-->
    <partial name="shared/info" />
    <h1>プロフィール</h1>
    <ul>            
        <li>
            名前：
            <?= $user->name ?><br />
            画像：
            <? if (!is_empty($user->picture)) : ?>
                <img src="/images/users/<?= $user->picture ?>" width="150" height="150"><br />
            <? else : ?>
                なし<br />
            <? endif ?>
            一言コメント：
            <? if (!is_empty($user->comment)) : ?>
                <?= $user->comment ?><br />
            <? else : ?>
                なし<br />
            <? endif ?>
        </li>
    </ul>
    <? if ($IS_LOGIN && $LOGIN_USER->id === $user->id) : ?>
        <a href="<?e uri('c:profile, a:edit') ?>">編集する</a><br />
        <a href="<?e uri('') ?>">戻る</a>
    <? else : ?>
        <a href="<?e uri('') ?>">戻る</a>
    <? endif ?>
</body>