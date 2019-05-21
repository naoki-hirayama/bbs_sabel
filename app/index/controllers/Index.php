<?php

class Index_Controllers_Index extends Index_Controllers_Base
{
    public function index()
    {
        $this->title = "投稿一覧";

        //user情報
        $model = MODEL('Users');
        //var_dump($_SESSION['user_id']);  
        $model->setCondition('id', $this->session->read('user_id'));
        $user_info = $model->selectOne()->toArray(); 
        $this->user_info = $user_info; 
        //var_dump($model->selectOne()->toArray());
        
        $this->select_color_options = ['black' => '黒', 'red' => '赤', 'blue' => '青', 'yellow' => '黄', 'green' => '緑'];
        $this->picture_max_size = 1 * 1024 * 1024;
        $paginator = new Paginator('Posts');
        
        $paginator->setDefaultOrder('id', 'desc');
        $this->paginator = $paginator->build(4, $this->GET_VARS);

        $this->form = new Forms_Posts();

        if ($this->isPost()) {
            /*
            画像処理
            $picture = $this->POST_VARS['picture']->toArray();
            var_dump($picture); 
            var_dump($_FILES);
            */
            // var_dump($_FILES);
            //var_dump($this->POST_VARS['picture']->path); 
            $this->form->submit($this->POST_VARS, array(
                'name',
                'comment',
                'picture',
                'MAX_FILE_SIZE',
                'color',
                'password',
            ));
            //var_dump($this->form->picture->toArray());
            //画像投稿の処理 形式チェックはいらない
            //バリデーションの使い方
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
            $model->picture = $rename_file;;
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
                //↑２行コメントアウトすると全部消える
                $model->delete();
                if (!empty($this->post['picture'])) {
                    unlink("images/posts/{$this->post['picture']}");
                }
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
