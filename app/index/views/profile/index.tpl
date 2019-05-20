<body>
    <h1>プロフィール編集ぺージ</h1>
    <!--ログイン情報-->
    <?php  include('views/layouts/loginuserinfo.php') ?>
    <!-- エラーメッセージ -->
    <?php  include('views/layouts/errormessage.php') ?>
    
    <form action="edit.php" method="post" enctype="multipart/form-data">
        <p>ログインID：</p>
        <input type="text" name="login_id" value="<?php echo h($user_info['login_id']) ?>">
        <p>名前：</p>
        <input type="text" name="name" value="<?php echo h($user_info['name']) ?>"><br />
        <p>画像：</p>
        <?php if (!empty($user_info['picture'])) : ?>
            <img src="images/users/<?php echo h($user_info['picture']) ?>" width="150" height="150"><br />
        <?php else : ?>
            なし<br />
        <?php endif ?>
        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $picture_max_size ?>">
        <input type="file" name="picture"><br />
        <p>一言コメント：</p>
        <?php if (!empty($user_info['comment'])) : ?>
             <input type="text" name="comment" value="<?php echo h($user_info['comment']) ?>"><br />
        <?php else : ?>
            <input type="text" name="comment" ><br />
        <?php endif ?>
        <input type="submit" name="submit" value="編集する">
    </form>
    <a href="password.php">パスワードを変える</a><br />    
    <a href="profile.php?id=<?php echo h($user_info['id']) ?>">戻る</a>
</body>