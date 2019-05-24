<?php

class Index_Controllers_Reply extends Index_Controllers_Base
{
    public function index()
    {
        $this->title = "レス一覧";
        $this->select_color_options = ['black' => '黒', 'red' => '赤', 'blue' => '青', 'yellow' => '黄', 'green' => '緑'];
        $this->post = MODEL('Posts', $this->param);

        if (!$this->post->isSelected()) {
            $this->notFound();
            return;
        }

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
        $this->select_color_options = ['black' => '黒', 'red' => '赤', 'blue' => '青', 'yellow' => '黄', 'green' => '緑'];

        $this->post = MODEL('Replies', $this->param);

        if (!$this->post->isSelected()) {
            $this->notFound();
            return;
        }

        if (!is_null($this->post->user_id)) {
            if ($this->post->user_id !== $this->LOGIN_USER->id && $this->post->password === null) {
                $this->badRequest();
                return;
            }
        }

        if ($this->isPost()) {

            if ($this->post->password !== $this->password_input) {
                $this->errors = ["パスワードが違います。"];
                return;
            }
            //トランザクション
            
            if (!is_null($this->post->picture)) {
                unlink("images/replies/{$this->post->picture}");
            }
            $this->post->delete();

            $this->redirect->to("reply/deleted/{$this->post->post_id}");
            return;
        }
    }

    public function deleted()
    {
        $this->title = "レス削除完了";
    }
}
