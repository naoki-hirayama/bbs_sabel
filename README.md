Sabel Project Base 
=====

Sabel を使ったプロジェクトを新規開発する時に持ってくるベース。

Sabel を使用したプロジェクトで、Sabel のバグを見つけたり、使い辛い点を直したりしたらこのリポジトリにも PR を投げましょう。

## セットアップ手順

### プロジェクトのセットアップ

このリポジトリを取得して、新しいプロジェクト用に git リポジトリを作りプロジェクトのセットアップをして下さい。

```
$ git clone git@bitbucket.org:ibg_media/sabel-project-base.git <project_name>
$ cd <project_name>
$ rm -rf .git
$ vim README.md
$ git init
$ git add .
$ git commit -m "init"
```

### Composer

開発用のパッケージを composer.json の require-dev に登録してあるので、以下のコマンドを実行してインストールして下さい。
また、プロジェクトで使用するパッケージがあれば、composer.json の require に追加してから実行すると良いと思います。

```
$ ./bin/composer.phar install
```

### Git のコミットフックの設定

PHP CS Fixer 用の pre-commit hook があるのでそれを設置します。

```
$ cp scripts/git_hooks_pre-commit .git/hooks/pre-commit
```

これで、`git commit` した時にコーディング規約に沿ってないコードは自動的に修正されて diff を表示してコミットが中止されます。
diff を見て特に動作に問題なさそうであれば `git add` して、`git commit` をやり直して下さい。
