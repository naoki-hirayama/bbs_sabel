<h1>ユーザー管理画面</h1>

<h2>ユーザー一覧</h2>

<form action="<?e uri('') ?>" method="get">
    <p><strong>検索フォーム</strong></p>
    <?= $form->n('name') ?>：
    <?e $form->text('name') ?><br />
    <?= $form->n('login_id') ?>：
    <?e $form->text('login_id') ?><br />
    <input type="submit" value="検索"><br />
</form>

<? if ($paginator->results) : ?>
    <table border="2">
        <tr>
            <th>ユーザーID</th>
            <th>登録日時</th>
            <th>loginID</th>
            <th>名前</th>
            <th>一言コメント</th>
            <th>投稿数</th>
            <th>画像</th>
            <th>編集リンク</th>
            <th>削除</th>
        </tr>
        <foreach from="$paginator->results" value="$user">
            <tr>
                <td>
                    <?= $user->id ?>
                </td>
                <td>
                    <?= $user->created_at ?>
                </td>
                <td>
                    <?= $user->login_id ?>
                </td>
                <td>
                    <?= $user->name ?>
                </td>
                <td>
                    <?= $user->comment ?>
                </td>
                <td>

                </td>
                <td>
                    <? if (!is_empty($user->picture)) : ?>
                        画像あり
                    <? else : ?>
                        なし
                    <? endif ?>
                </td>
                <td>
                    <a href=""  <?e uri("c: user, a: edit, param: {$user->id}") ?>">編集</a>
                </td>
                <td>
                    <a href=""  <?e uri("c: user, a: delete, param: {$user->id}") ?>">削除</a>
                </td>
            </tr>
        </foreach>
    </table>
<? else : ?>
    <p><strong>結果無し</strong></p>
<? endif ?>

<!--ページング処理-->
<partial name="shared/pager" />