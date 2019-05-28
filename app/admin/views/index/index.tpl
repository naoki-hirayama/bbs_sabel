
<h1>管理画面</h1>
<form action="<?e uri('') ?>" method="get">
    <p><strong>検索フォーム</strong></p>
    <?= $form->n('name') ?>：<?e $form->text('name') ?><br />
    <?= $form->n('comment') ?>：<?e $form->password('comment') ?><br />
    <?e $form->select('color', Posts::getSelectColorOptions()) ?>
    <input type="submit" value="検索"><br />
</form>

<?php if (isset($result_records)) : ?>
    <a href="index.php">戻る</a>
    <h2>検索結果<?php echo $result_records ?>件</h2>
    <ul>
        <li>名前：<?php echo ($_GET['name'] !== '') ? $_GET['name'] : '指定なし' ?></li>
        <li>本文：<?php echo ($_GET['comment'] !== '') ? $_GET['comment'] : '指定なし' ?></li>
        <li>色：<?php echo ($_GET['color'] !== '') ? $select_color_options[$_GET['color']] : '指定なし' ?></li>
    </ul>
    <?php if ($result_records == 0) : ?>
        <?php exit; ?>
    <?php endif ?>
<?php else : ?>
    <h2>投稿一覧</h2>
<?php endif ?>

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
                <a href="<?e uri("c: postdetail a: index, param: {$post->id}") ?>">投稿詳細</a>
            </td>
        </tr>
    </foreach>
</table>
<!--ページング処理-->
<partial name="shared/pager" />
<? endif ?>

