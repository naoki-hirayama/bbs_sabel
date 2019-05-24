<?php

class Index_Controllers_Profile extends Index_Controllers_Base
{
    public function index()
    {
        $this->title = "プロフィール";

        $this->user = MODEL('Users', $this->param);

        if (!$this->user->isSelected()) {
            $this->notFound();
            return;
        }
    }

    public function edit()
    {
        if (!$this->IS_LOGIN) {
            $this->badRequest();
            return;
        }

        $this->title = "プロフィール編集";

        $this->form = $form = new Forms_Users($this->LOGIN_USER->id);
        
        if ($this->isPost()) {
            dump(is_null($this->hoge));
            dump(is_null($this->comment));
            dump(is_null($this->picture));
            dump(is_null($form->picture));
            dump($this->picture);//post
            dump($form->picture);//db
            dump($this->name);//post
            dump($form->name);//db
            // exit;
            if (is_null($this->picture)) {
                $form->submit($this->POST_VARS, array(
                    'name',
                    'login_id',
                    'comment',
                ));
            } else {
                $form->submit($this->POST_VARS, array(
                    'name',
                    'login_id',
                    'comment',
                    'picture',
                ));
            }

            if (!$form->validate()) {
                $this->errors = $form->getErrors();
                return;
            }
            // dump($this->POST_VARS);
            //dump(empty($this->picture));exit;//画像を投稿してもnullになる
            if (!is_null($this->picture)) {
                $posted_picture = $form->picture->path;
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $picture_type = $finfo->file($posted_picture);
                $specific_num = uniqid(mt_rand());
                $rename_file = $specific_num . '.' . basename($picture_type);
                $rename_file_path = 'images/users/' . $rename_file;
                move_uploaded_file($posted_picture, $rename_file_path);

                if (!is_null($this->LOGIN_USER->picture)) {
                    unlink("images/users/{$this->LOGIN_USER->picture}");
                }

                $form->picture = $rename_file;
            } else {
                $form->picture = $this->LOGIN_USER->picture;
            }

            $form->save();

            $this->redirect->to("profile/index/{$this->LOGIN_USER->id}");
            return;
        }
    }

    public function password()
    {
        if (!$this->IS_LOGIN) {
            $this->badRequest();
            return;
        }

        $this->title = "パスワード変更";

        $this->form = $form = new Forms_Users($this->LOGIN_USER->id);
        $form->setDisplayNames([
            'current_password' => '現在のパスワード',
            'password'         => '新しいパスワード',
            'confirm_password' => '新しいパスワード(確認)',
        ]);

        if ($this->isPost()) {
            $errors = [];

            $form->submit($this->POST_VARS, array(
                'current_password',
                'password',
                'confirm_password',
            ));

            if (!password_verify($form->current_password, $this->LOGIN_USER->password)) {
                $errors[] = $form->n('current_password') . "が一致しません";
            }

            if (!$form->validate()) {
                $errors = array_merge($errors, $form->getErrors());
            }

            if ($errors) {
                $this->errors = $errors;
                return;
            }

            $form->remove('current_password');
            $form->getModel()->unsetValue('current_password');
            $form->remove('confirm_password');
            $form->getModel()->unsetValue('confirm_password');
            $form->password = password_hash($form->password, PASSWORD_DEFAULT);
            $form->save();

            $this->redirect->to('profile/edit');
            return;
        }
    }
}
