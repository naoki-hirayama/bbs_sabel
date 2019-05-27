<body>
    <!--ログイン情報-->
    <partial name="shared/info" />
    <a href="<?e uri('') ?>"　class="btn btn-primary">投稿画面へ戻る</a>
    <ul>
        <li>
            投稿ID:
            <?= $post->id ?><br />
            名前：
            <? if (!is_null($post->user_id)) : ?>
                <a href="<?e uri("c: profile, a: index, param: {$post->user_id}") ?>">
                    <?= $user->name ?>
                </a><br />
            <? else : ?>
                <?= $post->name ?><br />
            <? endif ?>
            本文 :
            <font color="<?= $post->color ?>">
                <?= $post->comment ?>
            </font><br />
            画像：
            <? if (!is_null($post->picture)) : ?>
                <img src="/images/posts/<?= $post->picture ?>" width="300" height="200"><br />
            <?php else : ?>
                なし<br />
            <?php endif ?>
            時間：
            <?= $post->created_at ?><br />
            ---------------------------------------------<br />
        </li>
    </ul>
    <!--エラーメッセージ-->
    <partial name="shared/error" />
    <h2>レス投稿画面</h2>
    <form action="<?e uri('') ?>" method="post" enctype="multipart/form-data">
        <? if ($IS_LOGIN) : ?>
        <p><?= $form->n('name') ?>：<?= $LOGIN_USER->name ?></p>
        <?e $form->hidden('name', "value={$LOGIN_USER->name}") ?>
        <? else : ?>
        <p><?= $form->n('name') ?>：</p>
        <?e $form->text('name') ?>
        <? endif ?>
        <p><?= $form->n('comment') ?>：</p>
        <?e $form->textarea('comment', 'rows=4', 'ls=20') ?><br />
        <p><?= $form->n('picture') ?>：</p>
        <?e $form->file('picture') ?><br />

        <?e $form->select('color', $select_color_options) ?>

        <? if (!$IS_LOGIN) : ?>
            <p>削除パスワード:</p>
            <?e $form->password('password') ?><br />
        <? else : ?>
            <?e $form->hidden('password') ?><br />
        <? endif ?>
        <input type="submit" name="submit" value="投稿">
    </form>
    
    <?php if (empty($errors)) : ?>
        <h2>レス一覧</h2>
        <p>総レス数：<?= $total_replies ?>件</p>
        <ul>
        <?php if ($replies) : ?>
            <?php foreach ($replies as $reply) : ?>
                <li>
                    ID : 
                    <?= $reply->id ?><br />
                    名前：
                    <? if (!is_null($reply->user_id)) : ?>
                        <a href="<?e uri("c: profile, a: index, param: {$post->user_id}") ?>"><?= $reply->name ?></a><br />
                    <? else : ?>
                        <?= $reply->name ?><br />
                    <? endif ?>
                    本文：
                    <font color="<?= $reply->color ?>">
                        <?= $reply->comment ?>
                    </font><br />
                    画像：
                    <? if (!is_null($reply->picture)) : ?>
                        <img src="/images/replies/<?= $reply->picture ?>" width="300" height="200"><br />
                    <? else : ?>
                        なし<br />
                    <? endif ?>
                    時間：
                    <?=  $reply->created_at ?><br />
                    
                    <!--if文でパスワードが設定されていなかったら非表示 -->
                    
                    <? if (!is_null($reply->password) && is_null($reply->user_id)) : ?>
                        <a href="<?e uri("a: delete, param: {$reply->id}") ?>">削除</a><br />
                    <? elseif (!is_null($reply->user_id) && !is_null($LOGIN_USER) && $reply->user_id === $LOGIN_USER->id) : ?>
                        <a href="<?e uri("a: delete, param: {$reply->id}") ?>">ユーザー削除</a><br />
                    <? endif ?>
                    <!--　ここまで　-->
                
                    ---------------------------------------------<br />
                </li>
            <?php endforeach ?>
        <?php endif ?>
        </ul>
    <?php endif ?>    
</body>
