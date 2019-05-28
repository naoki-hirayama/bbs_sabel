<h1>プロフィール編集ぺージ</h1>
<!--ログイン情報-->
<partial name="shared/info" />
<!-- エラーメッセージ -->
<partial name="shared/error" />
<form action="<?e uri('') ?>" method="post" enctype="multipart/form-data">
    <p><?= $form->n('login_id') ?>：</p>
    <?e $form->text('login_id') ?>

    <p><?= $form->n('name') ?>：</p>
    <?e $form->text('name') ?>

    <p><?= $form->n('picture') ?>：</p>
    <?e $form->file('picture') ?><br />
    <? if (!empty($LOGIN_USER->picture)) : ?>
        <img src="/images/users/<?= $LOGIN_USER->picture ?>" width="150" height="150"><br />
    <? else : ?>
        なし<br />
    <? endif ?>

    <p><?= $form->n('comment') ?>：</p>
    <?e $form->text('comment') ?><br />

    <input type="submit" name="submit" value="編集する">
</form>
<a href="<?e uri('a: password') ?>">パスワードを変える</a><br />    
<a href="<?e uri("c: profile, a: index, param: {$LOGIN_USER->id}") ?>">戻る</a>

