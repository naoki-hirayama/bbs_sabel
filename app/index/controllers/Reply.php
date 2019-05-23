<?php

class Index_Controllers_Reply extends Index_Controllers_Base
{
    public function index()
    {
        $this->title = "レス一覧";

        $this->post = MODEL('Posts', $this->param);

        $model = MODEL('Replies');
        $model->setCondition('post_id', $this->param);
        $model->setOrderBy('id', 'desc');
        $this->reply_posts = $model->select();
        $this->total_replies = $model->setCondition('post_id', $this->param)->getCount();

        $this->form = $form = new Forms_Replies();

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
                $rename_file_path = 'images/replies/' . $rename_file;
                move_uploaded_file($posted_picture, $rename_file_path);
                $form->picture = $rename_file;
            }

            $form->user_id = $this->LOGIN_USER->id;
            $form->post_id = $this->post->id;

            $form->save();
            $this->redirect->to("reply/index/{$this->post->id}");
            return;
        }
    }

    public function delete()
    {
        $this->title = "レス削除";


        $this->post = MODEL('Replies', $this->param);

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
                unlink("images/replies/{$this->post->picture}");
            }

            $this->redirect->to("reply/deleted/{$this->post->post_id}");
            return;
        }
    }

    public function deleted()
    {
        $this->title = "レス削除完了";
    }
}
