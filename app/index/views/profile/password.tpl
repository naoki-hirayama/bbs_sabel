<body>
    <h1>パスワードを編集</h1>
    <!--ログイン情報-->
    <partial name="shared/info" />
    <!-- エラーメッセージ -->
    <partial name="shared/error" />
    <!-- ここまで -->
    <form action="password.php" method="post">
        <p>現在のパスワード:</p>
        <input type="password" name="current_password"><br />
        <p>新しいパスワード:</p>
        <input type="password" name="new_password"><br />
        <p>確認用パスワード:</p>
        <input type="password" name="confirm_password"><br />
        <input type="submit" value="変更する"/><br />
    </form>
    <a href="/profile/edit">戻る</a><br />
</body>