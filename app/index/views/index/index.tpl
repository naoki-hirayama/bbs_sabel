    <? if ($IS_LOGIN) : ?>
        <a href="/auth/logout">ログアウト</a><br />
    <? else : ?>
        <a href="/auth/register">登録はこちらから</sa><br />
        <a href="/auth/login">ログインはこちらから</a>
    <? endif ?>
    <!--ログイン情報-->
    <partial name="shared/info" />
    <h1>投稿画面</h1>
    <!-- エラーメッセージ -->
    <partial name="shared/error" />
    <form action="<?= uri('') ?>" method="post" enctype="multipart/form-data">
        <? if ($IS_LOGIN) : ?>
        <p><?= $form->n('name') ?>：<?= $LOGIN_USER->name ?></p>
        <?e $form->hidden('name', "value={$LOGIN_USER->name}") ?>
        <? else : ?>
        <p><?= $form->n('name') ?>：</p>
        <?e $form->text('name') ?>
        <? endif ?>

        <p><?= $form->n('comment') ?>：</p>
        <?e $form->textarea('comment', 'rows=4', 'ls=20') ?>

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
        </select><br />
        <? if (!$IS_LOGIN) : ?>
            <p>削除パスワード:</p>
            <?e $form->password('password') ?><br />
        <? else : ?>
            <?e $form->hidden('password') ?><br />
        <? endif ?>
        <input type="submit" name="submit" value="投稿">
    </form>
    <?php if (empty($errors)) : ?>
        <h2>投稿一覧</h2>
        
        <ul>
        <if expr="$paginator->results">
            <foreach from="$paginator->results" value="$post">
                <li>
                    ID : 
                    <?= $post->id ?><br />
                    名前：
                    <? if (!is_null($post->user_id)) : ?>
                        <a href="/profile/index/<?e $post->user_id ?>"><?= $post->name ?></a><br />
                    <? else : ?>
                        <?= $post->name ?><br />
                    <? endif ?>
                    本文：
                    <font color="<?= $post->color ?>">
                        <?= $post->comment ?>
                    </font><br />
                    画像：
                    <? if (!is_null($post->picture)) : ?>
                        <img src="/images/posts/<?= $post->picture ?>" width="300" height="200"><br />
                    <? else : ?>
                        なし<br />
                    <? endif ?>
                    時間：
                    <?=  $post->created_at ?><br />
                    レス :
                    <a href="/reply/index/<?= $post->id ?>">1</a>
                        
                    </a><br />
                    <!--if文でパスワードが設定されていなかったら非表示 -->
                    <? if (!is_null($post->password) && is_null($post->user_id)) : ?>
                        <a href="/index/delete/<?= $post->id ?>">削除</a><br />
                    <? elseif (!is_null($post->user_id) && !is_null($LOGIN_USER) && $post->user_id === $LOGIN_USER->id) : ?>
                        <a href="/index/delete/<?= $post->id ?>">ユーザー削除</a><br />
                    <? endif ?>
                    <!--　ここまで　-->
                
                    ---------------------------------------------<br />
                </li>
            </foreach>
        </if>
        </ul>
        <!--ページング処理-->
        <partial name="shared/pager" />
        <!--ここまで-->
    <? endif ?>