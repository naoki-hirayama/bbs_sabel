<?php if (!empty($_SESSION['user_id'])) : ?>
    <form action="auth/logout" method="get" >
        <input type="submit" name="logout" value="ログアウト">
    </form>
    <?php else : ?>
        <a href="/auth/register">登録はこちらから</sa><br />
        <a href="/auth/login">ログインはこちらから</a>
    <?php endif ?>
    <!--ログイン情報-->
    <partial name="shared/info" />
    <h1>投稿画面</h1>
    <!-- エラーメッセージ -->
    <partial name="shared/error" />
    <form action="/" method="post" enctype="multipart/form-data">
        <p>名前：<?php echo !empty($_SESSION['user_id']) ? h($user_info['name']) : ''; ?></p>
        <?php if (!empty($_SESSION['user_id'])) : ?>
            <input type="hidden" name="name" value="<?php echo h($user_info['name']) ?>">
        <?php else : ?>
            <input type="text" name="name" value="<?php echo !empty($_POST['name']) ? $_POST['name'] : '' ?>">
        <?php endif ?>
        <p>本文：</p>
        <textarea name="comment" rows="4" cols="20"><?php echo !empty($_POST['comment']) ? $_POST['comment'] : '' ?></textarea><br />
        <p>画像：</p>
        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $picture_max_size ?>">
        <input type="file" name="picture"><br />
        <select name="color">
        <?php foreach($select_color_options as $key => $value) : ?>
            <?php if (!empty($_POST['color'])) : ?>
                <option value="<?php echo $key ?>"<?php echo $key === $_POST['color'] ? 'selected' : ''; ?>>
            <?php else : ?>
                <option value="<?php echo $key ?>">
            <?php endif ?>
            <?php echo $value ?>
            </option>
        <?php endforeach ?>
        </select><br />
        <?php if (empty($_SESSION['user_id'])) : ?>
            <p>削除パスワード:</p>
            <input type="password" name="password"><br />
        <?php else : ?>
            <input type="hidden" name="password">
        <?php endif ?>
        <input type="submit" name="submit" value="投稿">
    </form>
    <?php if (empty($errors)) : ?>
        <h2>投稿一覧</h2>
        
        <ul>
        <if expr="$paginator->results">
            <foreach from="$paginator->results" value="$post">
                <li>
                    <?= $post->id ?>
                    ID : 
                    <?php echo $post->id ?><br />
                    名前：
                    <?php if (isset($post->user_id)) : ?>
                        <a href="profile.php?id=<?php echo $post->user_id ?>"><?php echo h($user_names[$post['user_id']]) ?></a><br />
                    <?php else : ?>
                        <?php echo h($post->name) ?><br />
                    <?php endif ?>
                    本文：
                    <font color="<?php echo $post->color ?>">
                        <?php echo h($post->comment) ?>
                    </font><br />
                    画像：
                    <?php if (!empty($post->picture)) : ?>
                        <img src="images/posts/<?php echo h($post->picture) ?>" width="300" height="200"><br />
                    <?php else : ?>
                        なし<br />
                    <?php endif ?>
                    時間：
                    <?php echo $post->created_at ?><br />
                    レス :
                    <a href="/reply/index">
                        
                    </a><br />
                    <!--if文でパスワードが設定されていなかったら非表示   -->
                    <?php if (!empty($post->password)) : ?>
                        <a href="/index/delete/<?php echo $post->id ?>">削除</a><br />
                    <?php elseif (isset($post->user_id) && isset($_SESSION['user_id']) && $post->user_id === $_SESSION['user_id']) : ?>
                        <a href="/index/delete/<?php echo $post->id ?>">ユーザー削除</a><br />
                    <?php endif ?>
                    <!--　ここまで　-->
                
                    ---------------------------------------------<br />
                </li>
            </foreach>
        </if>
        </ul>
        <!--ページング処理-->
        <partial name="shared/pager" />
        <!--ここまで-->
    <?php endif ?>