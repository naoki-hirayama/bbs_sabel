<?php

class Index_Controllers_Index extends Index_Controllers_Base
{
    /**
     * @trim name comment password
     */
    public function index()
    {
        $this->title = "投稿一覧";
        
        $per_page_records = 3;
        $paginator = new Paginator('Posts');
        $paginator->setDefaultOrder('id', 'desc');
        $this->paginator = $paginator->build($per_page_records, $this->GET_VARS);
        
        $user_ids = [];
        $post_ids = [];
        foreach ($paginator->results as $post) {
            $post_ids[] = $post->id;
            if (!is_empty($post->user_id)) {
                $user_ids[] = $post->user_id;
            }
        }
        
        if (!is_empty($user_ids)) {
            $this->user_names = finder('Users')
                     ->in('id', $user_ids)
                     ->sort('id', 'desc')
                     ->fetchArray('name');
        }

        if (!is_empty($post_ids)) {
            $tmp = db_query("SELECT post_id, COUNT(*) AS cnt FROM replies WHERE post_id IN (" . implode(',', $post_ids) . ") GROUP BY post_id");

            if (!is_empty($tmp)) {
                $this->reply_counts = array_column($tmp, 'cnt', 'post_id');
            }
        } 

        $this->form = $form = new Forms_Posts();
        if ($this->IS_LOGIN) {
            $form->name = $this->LOGIN_USER->name;
        }
        if ($this->isPost()) {

            $form->submit($this->POST_VARS, [
                'name',
                'comment',
                'color',
                'picture',
                'password',
            ]);

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

            if ($this->IS_LOGIN) {
                $form->user_id = $this->LOGIN_USER->id;
            }
            
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
        
        $this->post = MODEL('Posts', $this->param);
        
        
        if (!$this->post->isSelected()) {
            $this->notFound();
            return;
        }
        // パスワード未設定かつ特定のユーザーの投稿ではない場合は削除不可
        if (is_empty($this->post->password) && is_empty($this->post->user_id)) {
            $this->notFound();
            return; 
        }
        //user_idが存在する時で、ログインユーザーでない時、またはuser_idとログインユーザーのidが一致しない時
        if (!is_empty($this->post->user_id)) {
            if (!$this->IS_LOGIN || $this->post->user_id !== $this->LOGIN_USER->id) {
                $this->notFound();
                return;
            }
        }

        if ($this->isPost()) {

            if ($this->post->password !== $this->password_input) {
                $this->errors = ["パスワードが違います。"];
                return;
            }
            //トランザクション
            $replies = finder('Replies')
                       ->eq('post_id', $this->param)
                       ->fetchArray();
            
            if (!is_empty($replies)) {
                foreach ($replies as $reply) {
                    unlink("images/replies/{$reply['picture']}");
                }
            }
            
            $post_repleis = MODEL('Replies');
            $post_repleis->setCondition(eq('post_id', $this->param));
            $post_repleis->delete();

            if (!is_empty($this->post->picture)) {
                unlink("images/posts/{$this->post->picture}");
            }
            $this->post->delete();

            $this->redirect->to('a: deleted');
            return;
        }
    }

    public function deleted()
    {
        $this->title = '削除完了';
    }
}