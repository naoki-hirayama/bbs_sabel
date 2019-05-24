<body>
    <!--ログイン情報-->
    <partial name="shared/info" />
    <a href="/"　class="btn btn-primary">投稿画面へ戻る</a>
    <ul>
        <li>
            投稿ID:
            <?= $post->id ?><br />
            名前：
            <? if (!is_null($post->user_id)) : ?>
                <a href="/profile/index/<?= $post->user_id ?>">
                    <?= $user_name->name ?>
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
    <form action="<?= uri('') ?>" method="post" enctype="multipart/form-data">
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

        <select name="color">
        <? foreach($select_color_options as $key => $value) : ?>
            <? if (!empty($_POST['color'])) : ?>
                <option value="<?= $key ?>"<?= $key === $_POST['color'] ? 'selected' : '' ?>>
            <? else : ?>
                <option value="<?= $key ?>">
            <? endif ?>
            <? echo $value ?>
            </option>
        <? endforeach ?>
        </select>

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
        <?php if ($reply_posts) : ?>
            <?php foreach ($reply_posts as $reply_post) : ?>
                <li>
                    ID : 
                    <?= $reply_post->id ?><br />
                    名前：
                    <? if (!is_null($reply_post->user_id)) : ?>
                        <a href="/profile/index/<?e $post->user_id ?>"><?= $reply_post->name ?></a><br />
                    <? else : ?>
                        <?= $reply_post->name ?><br />
                    <? endif ?>
                    本文：
                    <font color="<?= $reply_post->color ?>">
                        <?= $reply_post->comment ?>
                    </font><br />
                    画像：
                    <? if (!is_null($reply_post->picture)) : ?>
                        <img src="/images/replies/<?= $reply_post->picture ?>" width="300" height="200"><br />
                    <? else : ?>
                        なし<br />
                    <? endif ?>
                    時間：
                    <?=  $reply_post->created_at ?><br />
                    
                    <!--if文でパスワードが設定されていなかったら非表示 -->
                    <? if (!is_null($reply_post->password) && is_null($reply_post->user_id)) : ?>
                        <a href="/reply/delete/<?= $reply_post->id ?>">削除</a><br />
                    <? elseif (!is_null($reply_post->user_id) && !is_null($LOGIN_USER) && $reply_post->user_id === $LOGIN_USER->id) : ?>
                        <a href="/reply/delete/<?= $reply_post->id ?>">ユーザー削除</a><br />
                    <? endif ?>
                    <!--　ここまで　-->
                
                    ---------------------------------------------<br />
                </li>
            <?php endforeach ?>
        <?php endif ?>
        </ul>
    <?php endif ?>    
</body>
