<?php

class Index_Controllers_Index extends Index_Controllers_Base
{
    public function index()
    {
        $this->title = "投稿一覧";
        $this->select_color_options = Posts::getSelectColorOptions();
        $per_page_records = 4;
        $paginator = new Paginator('Posts');
        $paginator->setDefaultOrder('id', 'desc');
        $this->paginator = $paginator->build($per_page_records, $this->GET_VARS);

        $user_ids = [];
        $post_ids = [];
        foreach ($paginator->results as $post) {
            $post_ids[] = $post->id;
            if (!is_null($post->user_id)) {
                $user_ids[] = $post->user_id;
            }
        }

        if (!empty($user_ids)) {
            $users = finder('Users')
            ->in('id', $user_ids)
            ->sort('id', 'desc')
            ->fetchArray();

            $this->user_names = array_column($users, 'name', 'id');
        }

        $tmp = db_query("SELECT post_id, COUNT(*) AS cnt FROM replies WHERE post_id IN (" . implode(',', $post_ids) . ") GROUP BY post_id");

        if (!empty($tmp)) {
            $this->reply_counts = array_column($tmp, 'cnt', 'post_id');
        }

        $this->form = $form = new Forms_Posts();

        if ($this->isPost()) {

            $form->submit($this->POST_VARS, array(
                'name',
                'comment',
                'color',
                'picture',
                'password',
            ));

            if (!$form->validate()) {
                $this->errors = $form->getErrors();
                return;
            }

            if (!is_empty($form->picture)) {

                $posted_picture = $form->picture->path;
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $picture_type = $finfo->file($posted_picture);
                $specific_num = uniqid(mt_rand());
                $rename_file = $specific_num . '.' . basename($picture_type);
                $rename_file_path = 'images/posts/' . $rename_file;
                move_uploaded_file( $posted_picture, $rename_file_path);
                $form->picture = $rename_file;
            }
            
            $form->user_id = $this->LOGIN_USER->id;
            $form->save();
            $this->redirect->to('a: sent');
            return;
        }
    }

    public function sent()
    {
        $this->title = '投稿成功';
    }

    public function delete()
    {
        $this->title = '削除ページ';
        $this->select_color_options = Posts::getSelectColorOptions();
        $this->post = MODEL('Posts', $this->param);

        if (!$this->post->isSelected()) {
            $this->redirect->to('/');
            return;
        }

        if ($this->post->user_id !== $this->LOGIN_USER->id && $this->post->password === null) {
            $this->badRequest();
            return;
        }

        if ($this->isPost()) {

            if ($this->post->password !== $this->password_input) {
                $this->errors = ["パスワードが違います。"];
                return;
            }
            //トランザクション
            if (!is_empty($this->post->picture)) {
                unlink("images/posts/{$this->post->picture}");
            }

            $replies = finder('Replies')->eq('post_id', $this->param)->fetchArray();
            
            if (!is_empty($replies)) {
                foreach ($replies as $reply) {
                    unlink("images/replies/{$reply['picture']}");
                }
            }
            
            $post_repleis = MODEL('Replies');
            $post_repleis->setCondition(eq('post_id', $this->param));
            $post_repleis->delete();
            $this->post->delete();

            
            //ここまで
            $this->redirect->to('a: deleted');
            return;
        }
    }

    public function deleted()
    {
        $this->title = '削除完了';
    }
}