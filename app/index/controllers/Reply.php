<?php

class Index_Controllers_Reply extends Index_Controllers_Base
{
    public function index()
    {
        $this->title = "レス一覧";

        //user情報
        $model = MODEL('Users'); 
        $model->setCondition('id', $this->session->read('user_id'));
        $user_info = $model->selectOne()->toArray();
        $this->user_info = $user_info;

        $this->select_color_options = ['black' => '黒', 'red' => '赤', 'blue' => '青', 'yellow' => '黄', 'green' => '緑'];
        $this->picture_max_size = 1 * 1024 * 1024;

        $model = MODEL('Posts');
        $model->setCondition('id', $this->param);
        $this->post = $model->selectOne()->toArray();
        //var_dump($this->post['id']);
        $this->form = new Forms_Replies();

        $model = MODEL('Replies');
        $model->setCondition('post_id', $this->param);
        $model->setOrderBy('id', 'desc');
        $this->reply_posts = $model->select();
        //var_dump($this->reply_posts);

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
                $rename_file_path = 'images/replies/' . $rename_file;
                move_uploaded_file($this->POST_VARS['picture']->path, $rename_file_path);
            }

            $model = MODEL('Replies');
            $model->name = $this->POST_VARS['name'];
            $model->comment = $this->POST_VARS['comment'];
            $model->picture = $rename_file;
            $model->color = $this->POST_VARS['color'];
            $model->password = $this->POST_VARS['password'];
            $model->user_id = $this->session->read('user_id');
            $model->post_id = $this->post['id'];
            $model->save();

            $this->redirect->to("reply/index/{$this->param}");
            return;
        }
    }

    public function delete()
    {
        $this->title = "レス削除";
        $model = MODEL('Users');
        $model->setCondition('id', $this->session->read('user_id'));
        $user_info = $model->selectOne()->toArray();
        $this->user_info = $user_info;

        $model = MODEL('Replies');
        $model->setCondition('id', $this->param);
        $this->reply_post = $model->selectOne()->toArray();

        if ($this->reply_post['user_id'] !== $this->session->read('user_id') && $this->reply_post['password'] === null) {
            $this->redirect->uri('/');
            return;
        }

        $this->form = new Forms_Replies();

        if ($this->isPost()) {

            if ($this->reply_post['password'] === $this->form->password_input) {
                $model = MODEL('Replies');
                $model->setCondition('id', $this->param);

                $model->delete();
                if (!empty($this->replies_post['picture'])) {
                    unlink("images/replies/{$this->replies['picture']}");
                }
                $this->redirect->to("reply/deleted/{$this->reply_post['post_id']}");
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
        $this->title = "レス削除完了";
        $model = MODEL('Users');
        $model->setCondition('id', $this->session->read('user_id'));
        $user_info = $model->selectOne()->toArray();
        $this->user_info = $user_info;
        
    }
}
