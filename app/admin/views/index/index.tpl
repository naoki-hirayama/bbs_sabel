
<h1>管理画面</h1>
<form action="<?e uri('') ?>" method="get">
    <p><strong>検索フォーム</strong></p>
    <?= $form->n('name') ?>：<?e $form->text('name') ?><br />
    <?= $form->n('comment') ?>：<?e $form->text('comment') ?><br />
    <?e $form->select('color', Posts::getSelectColorOptionsInSearch()) ?>
    <input type="submit" value="検索"><br />
</form>

<? if ($result) : ?>
    <a href="<?e uri('') ?>">戻る</a>
    <h2>検索結果</h2>
    <ul>
        <li>名前：<?= ($form->name !== null) ? $form->name : '指定なし' ?></li>
        <li>本文：<?= ($form->comment !== null) ? $form->comment : '指定なし' ?></li>
        <li>色：<?= ($form->color !== '') ? Posts::getSelectColorOptionsInSearch()[$form->color]: '指定なし' ?></li>
    </ul>
    
    <? if ($records === 0) : ?>
        <p><strong>結果無し</strong></p>
        <? exit; ?>
    <? endif ?>
<? else : ?>
    <h2>投稿一覧</h2>
<? endif ?>

<table border="2">
    <tr>
        <th>投稿ID</th>
        <th>投稿日時</th>
        <th>名前</th>
        <th>本文</th>
        <th>レス数</th>
        <th>編集リンク</th>
        <th>詳細リンク</th>
    </tr>
<if expr="$paginator->results">
    <foreach from="$paginator->results" value="$post">
        <tr>
            <td>
                <?= $post->id ?>
            </td>
            <td>
                <?=  $post->created_at ?>
            </td>
            <td id="edit_name_<?= $post->id ?>">
                <? if (!is_empty($post->user_id)) : ?>
                    <?= $user_names[$post->user_id] ?>
                <?php else : ?>
                    <?= $post->name ?>
                <?php endif ?>
            </td>
            <td>
                <font id="font_<?= $post->id ?>" color="<?= $post->color ?>">
                    <?= $post->comment ?>
                </font>
            </td>
            <td>
                <? if (!empty($reply_counts[$post->id])) : ?>
                    <?= $reply_counts[$post->id] ?>件
                <? else : ?>
                    0件
                <? endif ?>
            </td>
            <td>
                <input type="button" id="btn" value="編集" class="show-modal" data-id="<?= $post->id ?>">
            </td>
            <td>
                <a href="<?e uri("c: postdetail, a: index, param: {$post->id}") ?>">投稿詳細</a>
            </td>
        </tr>
    </foreach>
</table>
<!--ページング処理-->
<partial name="shared/pager" />
<? endif ?>

<div id="modalwin" class="modalwin hide">
    <a herf="#" class="modal-close"></a>
    <h1>投稿編集</h1>
    <div class="modalwin-contents">
        <input id="input_id" type="hidden" name="name" value="">
        <input id="input_name" type="text" name="name" value="">
        <br />
        <textarea id="input_comment" name="comment" rows="4" cols="20"></textarea><br />
        <img id="img"src="" width="30" height="30"><br />
        <select id="input_color" name="color">
        <? foreach(Posts::getSelectColorOptions() as $key => $value) : ?>
            <option value="<?= $key ?>"><?= $value ?></option>
        <? endforeach ?>
        </select>
        <br />
        <button id="ajax">編集</button>
        <br />
        <button id="close">閉じる</button>
    </div>
</div>

<script type="text/javascript">
$(function() {
    $('.show-modal').on('click', function() {
        
        var id = $(this).data('id');
        
        $.ajax({
            url: '<?e uri('c: index, a: get_ajax') ?>',
            type:'GET',
            data:{
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
        }).fail(function()  {
            alert("通信に失敗しました");
        }); 
    });
        
    $('#ajax').on('click', function() {
        
        $.ajax({
            url:'<?e uri('c: index, a: edit_ajax') ?>',
            type:'POST',
            data:{
                'id':$("#input_id").val(),
                'name':$("#input_name").val(),
                'comment':$("#input_comment").val(),
                'color':$("#input_color").val(),
            },
            dataType: 'json',
        }).done(function(response) {
            if (response['status'] === true) {
                alert("編集しました。");
                var post = response['post'];
                $('#edit_name_' + post['id']).text(post['name']);
                $('#font_' + post['id']).text(post['comment']);
                $('#font_' + post['id']).attr('color',　post['color']);
            } else {
                alert(response['errors']);
            }
            
        }).fail(function()  {
            alert("通信に失敗しました");
        });
    });
});
</script>

