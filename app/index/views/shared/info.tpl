<? if ($IS_LOGIN) : ?>
    <? if (!is_empty($LOGIN_USER->picture)) : ?>
        <img src="/images/users/<?= $LOGIN_USER->picture ?>" width="50" height="50"><br />
    <? endif ?>
    <p>ようこそ！<a href="<?e uri("c: profile, a:index, param: {$LOGIN_USER->id}") ?>"><?= $LOGIN_USER->name ?></a>さん(<?= $LOGIN_USER->login_id ?>)</p>
<? else : ?>
    <p>ようこそ!ゲストさん</p>
<? endif ?>