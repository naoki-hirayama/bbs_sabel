<h1>投稿詳細</h1>
<ul>
    <li>
        投稿ID:
        <?= $post->id ?><br />
        名前：
        <div id="post_name">
            <? if (!is_empty($post->user_id)) : ?>
                <?= $user->name ?><br />
            <?php else : ?>
                <?= $post->name ?><br />
            <?php endif ?>
        </div>
        本文 :
        <font id="post_color" color="<?= $post->color ?>">
            <?= $post->comment ?>
        </font><br />
        画像：
        <? if (!is_empty($post->picture)) : ?>
            <img src="/images/posts/<?= $post->picture ?>" width="300" height="200"><br />
        <? else : ?>
            なし<br />
        <? endif ?>
        時間：
        <?= $post->created_at ?><br />
        ---------------------------------------------<br />
    </li>
</ul>
<form action="delete" method="post" id="deleteform">
    <input type="hidden" value="<?= $post->id ?>" name="post_id">
    <input type="submit" value="投稿削除">
</form>
<input type="button" id="btn" value="投稿編集" class="show-modal" data-id="<?= $post->id ?>">
<h2>レス一覧</h2>
<table border="2">
    <tr>
        <th>ID</th>
        <th>投稿日時</th>
        <th>名前</th>
        <th>本文</th>
        <th>編集リンク</th>
        <th>削除ボタン</th>
    </tr>
    <?php foreach ($replies as $reply) : ?>
        <tr>
            <td>
                <?= $reply->id ?>
            </td>
            <td>
                <?= $reply->created_at ?>
            </td>

            <td id="reply_name_<?= $reply->id ?>">
                <?php if (!is_empty($reply->user_id)) : ?>
                    <?= $user_names[$reply->user_id] ?>
                <?php else : ?>
                    <?= $reply->name ?>
                <?php endif ?>
            </td>
            <td>
                <font id="reply_font_<?= $reply->id ?>" color="<?= $reply->color ?>">
                    <?= $reply->comment ?>
                </font>
            </td>
            <td>
                <input type="button" id="edit_reply_btn" value="レス編集" class="show-reply-modal" data-reply="<?= $reply->id ?>">
            </td>
            <td>
                <form action="reply_delete" method="post" id="delete_reply_form">
                    <input type="hidden" value="<?= $reply->id ?>" name="reply_id">
                    <input type="hidden" value="<?= $reply->id ?>" name="post_id">
                    <input type="submit" value="削除">
                </form>
            </td>
        </tr>
    <?php endforeach ?>
</table>