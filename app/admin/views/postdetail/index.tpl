<h1>投稿詳細</h1>

<ul>
    <li>
        投稿ID:
        <?= $post->id ?><br />
        名前 :
        <div id="post_name">
            <?= $post->name ?>
        </div>
        ユーザー :
        <? if (!is_empty($post->user_id)) : ?>
            <?= $user->name ?><br />
        <? else : ?>
            無し<br />
        <? endif ?>
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
        <a href="<?e uri('c:index, a:index') ?>">戻る</a>
        ---------------------------------------------<br />
    </li>
</ul>

<form action="<?e uri('a: delete') ?>" method="post" id="deleteform">
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
        <th>ユーザー</th>
        <th>編集リンク</th>
        <th>削除ボタン</th>
    </tr>
    <? foreach ($replies as $reply) : ?>
        <tr>
            <td>
                <?= $reply->id ?>
            </td>
            <td>
                <?= $reply->created_at ?>
            </td>
            <td id="reply_name_<?= $reply->id ?>">
                <?= $reply->name ?>
            </td>
            <td>
                <font id="reply_font_<?= $reply->id ?>" color="<?= $reply->color ?>">
                    <?= $reply->comment ?>
                </font>
            </td>
            <td>
                <? if (!is_empty($reply->user_id)) : ?>
                    <?= $user_names[$reply->user_id] ?>
                <? else : ?>
                    無し
                <? endif ?>
            </td>
            <td>
                <input type="button" value="レス編集" class="show-reply-modal" data-reply="<?= $reply->id ?>">
            </td>
            <td>
                <form action="<?e uri('a: reply_delete') ?>" method="post" class="delete_reply_form">
                    <input type="hidden" value="<?= $reply->id ?>" name="reply_id">
                    <input type="submit" value="削除">
                </form>
            </td>
        </tr>
    <? endforeach ?>
</table>
<!--投稿モーダル-->
<div id="modalwin" class="modalwin hide">
    <a herf="#" class="modal-close"></a>
    <h1>投稿編集</h1>
    <div class="modalwin-contents">
        <input id="input_id" type="hidden" name="name" value="">
        <input id="input_name" type="text" name="name" value=""><br />
        <textarea id="input_comment" name="comment" rows="4" cols="20"></textarea><br />
        <img id="img" src="" width="30" height="30"><br />
        <select id="input_color" name="color">
            <? foreach (Posts::getSelectColorOptions() as $key => $value ): ?>
                <option value="<?= $key ?>"><?= $value ?></option>
            <? endforeach ?>
        </select>
        <br />
        <button id="ajax">編集</button>
        <br />
        <button id="close">閉じる</button>
    </div>
</div>
<!--レスモーダル-->
<div id="modalwin2" class="modalwin hide">
    <a herf="#" class="modal-close"></a>
    <h1>レス編集</h1>
    <div class="modalwin-contents">
        <input id="reply_id" type="hidden" name="name" value="">
        <input id="reply_name" type="text" name="name" value="">
        <br />
        <textarea id="reply_comment" name="comment" rows="4" cols="20"></textarea><br />
        <img id="reply_img" src="" width="30" height="30"><br />
        <select id="reply_color" name="color">
            <? foreach (Replies::getSelectColorOptions() as $key => $value ): ?>
                <option value="<?= $key ?>"><?= $value ?></option>
            <? endforeach ?>
        </select>
        <br />
        <button id="reply_ajax">編集</button>
        <br />
        <button id="reply_close">閉じる</button>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $('.show-modal').on('click', function() {

            var id = $(this).data('id');

            $.ajax({
                url: '<?e uri('c: index, a: get_ajax ') ?>',
                type: 'GET',
                data: {
                    'id': id,
                },
                dataType: 'json',
            }).done(function(post) {
                $("#input_id").val(post.id);
                $("#input_name").val(post.name);
                $("#input_comment").val(post.comment);
                if (post.picture !== null) {
                    $("#img").attr('src', '/images/posts/' + post.picture);
                } else {
                    $("#img").attr('src', '/images/posts/noimage.png');
                }
                $("#input_color").val(post.color);
            }).fail(function() {
                alert("通信に失敗しました");
            });
        });
        $('#ajax').on('click', function() {

            $.ajax({
                url: '<?e uri('c: index, a: edit_ajax ') ?>',
                type: 'POST',
                data: {
                    'id': $("#input_id").val(),
                    'name': $("#input_name").val(),
                    'comment': $("#input_comment").val(),
                    'color': $("#input_color").val(),
                },
                dataType: 'json',
            }).done(function(response) {
                if (response['status'] === true) {
                    alert("編集しました。");
                    var post = response['post'];
                    $('#post_name').text(post['name']);
                    $('#post_color').text(post['comment']);
                    $('#post_color').attr('color', post['color']);
                } else {
                    alert(response['errors']);
                }

            }).fail(function() {
                alert("通信に失敗しました");
            });
        });


        $('.show-reply-modal').on('click', function() {

            var reply_id = $(this).data('reply');

            $.ajax({
                url: '<?e uri('c: postdetail, a: reply_get_ajax') ?>',
                type: 'GET',
                data: {
                    'reply_id': reply_id,
                },

                dataType: 'json',
            }).done(function(reply) {

                $("#reply_id").val(reply.id);
                $("#reply_name").val(reply.name);
                $("#reply_comment").val(reply.comment);
                if (reply.picture !== null) {
                    $("#reply_img").attr('src', '/images/replies/' + reply.picture);
                } else {
                    $("#reply_img").attr('src', '/images/replies/noimage.png');
                }
                $("#reply_color").val(reply.color);
            }).fail(function() {
                alert("通信に失敗しました");
            });
        });

        $('#reply_ajax').on('click', function() {

            $.ajax({
                url: '<?e uri('c: postdetail, a: reply_edit_ajax ') ?>',
                type: 'POST',
                data: {
                    'id': $("#reply_id").val(),
                    'name': $("#reply_name").val(),
                    'comment': $("#reply_comment").val(),
                    'color': $("#reply_color").val(),
                },
                dataType: 'json',
            }).done(function(response) {
                if (response['status'] === true) {
                    alert("編集しました。");
                    var reply = response['reply']
                    $('#reply_name_' + reply['id']).text(reply['name']);
                    $('#reply_font_' + reply['id']).text(reply['comment']);
                    $('#reply_font_' + reply['id']).attr('color', reply['color']);
                } else {
                    alert(response['errors']);
                }

            }).fail(function() {
                alert("通信に失敗しました");
            });
        });
    });
</script>