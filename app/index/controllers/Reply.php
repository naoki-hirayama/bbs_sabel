<?php

class Index_Controllers_Reply extends Index_Controllers_Base
{
    public function index()
    {
        $this->title = "レス一覧";
        
        $this->post = MODEL('Posts', $this->param);
        $this->user = MODEL('Users', $this->post->user_id);
        
        if (!$this->post->isSelected()) {
            $this->notFound();
            return;
        }

        $this->replies = finder('Replies')
                         ->eq('post_id', $this->param)
                         ->sort('id', 'desc')
                         ->fetchAll();

        $this->total_replies = count($this->replies);

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
            if ($this->IS_LOGIN) {
                $form->user_id = $this->LOGIN_USER->id;
            }
                

            $form->post_id = $this->post->id;

            $form->save();
            $this->redirect->to("a: index, param: {$this->post->id}");
            return;
        }
    }

    public function delete()
    {
        $this->title = "レス削除";

        $this->reply = MODEL('Replies', $this->param);

        if (!$this->reply->isSelected()) {
            $this->notFound();
            return;
        }

        if (is_empty($this->reply->password) && is_empty($this->reply->user_id)) {
            $this->notFound();
            return;
        }

        if ($this->IS_LOGIN && $this->reply->user_id !== $this->LOGIN_USER->id && is_empty($this->reply->password)) {
            $this->notFound();
            return;
        }

        if (!$this->IS_LOGIN && !is_empty($this->reply->user_id)) {
            $this->notFound();
            return;
        }

        if ($this->isPost()) {

            if ($this->reply->password !== $this->password_input) {
                $this->errors = ["パスワードが違います。"];
                return;
            }
            //トランザクション
            
            if (!is_empty($this->reply->picture)) {
                unlink("images/replies/{$this->reply->picture}");
            }
            $this->reply->delete();

            $this->redirect->to("a: deleted, param: {$this->reply->post_id}");
            return;
        }
    }

    public function deleted()
    {
        $this->title = "レス削除完了";
    }
}
