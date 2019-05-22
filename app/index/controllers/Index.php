<?php

class Index_Controllers_Index extends Index_Controllers_Base
{
    public function index()
    {
        $this->title = "投稿一覧";

        //user情報
        $model = MODEL('Users');
        $model->setCondition('id', $this->session->read('user_id'));
        $user_info = $model->selectOne()->toArray(); 
        $this->user_info = $user_info; 
        
        $this->select_color_options = ['black' => '黒', 'red' => '赤', 'blue' => '青', 'yellow' => '黄', 'green' => '緑'];
        $this->picture_max_size = 1 * 1024 * 1024;
        $paginator = new Paginator('Posts');
        
        $paginator->setDefaultOrder('id', 'desc');
        $this->paginator = $paginator->build(4, $this->GET_VARS);

        //var_dump($paginator->build(4, $this->GET_VARS)->toArray());
        /*
            $user_ids = [];
            $post_ids = [];
            foreach ($posts as $post) {
                $post_ids[] = $post['id'];
                if (isset($post['user_id'])) {
                    $user_ids[] = $post['user_id'];
                }
            }
            
            if (!empty($user_ids)) {
                $users = $user_repository->fetchByIds($user_ids);
                $user_names = array_column($users, 'name', 'id');
            }
            
            $reply_counts = $reply_repository->fetchCountByPostIds($post_ids);
        */
        //$count = Model('User')->getCount();
        $this->form = new Forms_Posts();

        if ($this->isPost()) {
            
            $this->form->submit($this->POST_VARS, array(
                'name',
                'comment',
                'MAX_FILE_SIZE',
                'color',
                'password',
                'picture',
            ));
            
            if (!$this->form->validate()) {
                $this->errors = $this->form->getErrors();
                return;
            }

            if (strlen($this->POST_VARS['picture']->name) > 0) {

                $posted_picture = $this->POST_VARS['picture']->path;
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $picture_type = $finfo->file($posted_picture);
                $specific_num = uniqid(mt_rand());
                $rename_file = $specific_num . '.' . basename($picture_type);
                $rename_file_path = 'images/posts/' . $rename_file;
                move_uploaded_file($this->POST_VARS['picture']->path, $rename_file_path);

            }

            $model = MODEL('Posts');
            $model->name = $this->POST_VARS['name'];
            $model->comment = $this->POST_VARS['comment'];
            $model->picture = $rename_file;
            $model->color = $this->POST_VARS['color'];
            $model->password = $this->POST_VARS['password'];
            $model->user_id = $this->session->read('user_id');

            $model->save();

            $this->redirect->to('a: send');
            return;
        }
    }

    public function send()
    {
        $this->title = '投稿成功';

        //user情報
        $model = MODEL('Users');
        $model->setCondition('id', $this->session->read('user_id'));
        $user_info = $model->selectOne()->toArray();
        $this->user_info = $user_info; 
    }

    public function delete()
    {
        $this->title = '削除ページ';
        //ユーザー情報
        $model = MODEL('Users');
        $model->setCondition('id', $this->session->read('user_id'));
        $this->user_info = $model->selectOne()->toArray();
        
        $model = MODEL('Posts');
        $model->setCondition('id', $this->param);
        $this->post = $model->selectOne()->toArray();

        if ($this->post['user_id'] !== $this->session->read('user_id') && $this->post['password'] === null) {
            $this->redirect->uri('/');
            return;
        }

        $this->form = new Forms_Posts();
        
        if ($this->isPost()) {

            if ($this->post['password'] === $this->form->password_input) {
                $model = MODEL('Posts');
                $model->setCondition('id', $this->param);

                $model->delete();
                if (!empty($this->post['picture'])) {
                    unlink("images/posts/{$this->post['picture']}");
                }

                $model = MODEL('Replies');
                $model->setCondition(eq('post_id', $this->param));
                $model->delete();


                $this->redirect->to('a: deleted');
                return;
            } else {
                $errors = [];
                $errors[] = "パスワードが違います。";
                $this->errors = $errors;
                return;
            }
        }


    }

    public function deleted()
    {
        $this->title = '削除完了';
    }
}
