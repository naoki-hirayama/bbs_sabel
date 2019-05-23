<?php

class Index_Controllers_Index extends Index_Controllers_Base
{
    public function index()
    {
        $this->title = "投稿一覧";
        
        $paginator = new Paginator('Posts');
        
        $paginator->setDefaultOrder('id', 'desc');
        $this->paginator = $paginator->build(4, $this->GET_VARS);

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
            $this->redirect->to('a: send');
            return;
        }
    }

    public function send()
    {
        $this->title = '投稿成功';
    }

    public function delete()
    {
        $this->title = '削除ページ';

        $this->post = MODEL('Posts', $this->param);

        if (!$this->post->isSelected()) {
            $this->redirect->uri('/');
            return;
        }

        if ($this->post->user_id !== $this->LOGIN_USER->id && $this->post->password === null) {
            $this->redirect->uri('/');
            return;
        }
        
        if ($this->isPost()) {

            if ($this->post->password !== $this->POST_VARS['password_input']) {

                $errors = [];
                $errors[] = "パスワードが違います。";
                $this->errors = $errors;
                return;
            }

            $this->post->delete();

            if (!is_null($this->post->picture)) {
                unlink("images/posts/{$this->post->picture}");
            }

            $post_repleis = MODEL('Replies');
            $post_repleis->setCondition(eq('post_id', $this->param));
            $post_repleis->delete();

            $this->redirect->to('a: deleted');
            return;
        }
    }

    public function deleted()
    {
        $this->title = '削除完了';
    }
}
