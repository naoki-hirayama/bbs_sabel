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

    /**
     * @trim name login_id comment
     */
    public function edit()
    {
        if (!$this->IS_LOGIN) {
            $this->redirect->to('c:auth, a: login');
            return;
        }

        $this->title = "プロフィール編集";

        $this->form = $form = new Forms_Users($this->LOGIN_USER->id);
        
        if ($this->isPost()) {
            if (is_empty($this->picture)) {
                $form->submit($this->POST_VARS, [
                    'name',
                    'login_id',
                    'comment',
                ]);
            } else {
                $form->submit($this->POST_VARS, [
                    'name',
                    'login_id',
                    'comment',
                    'picture',
                ]);
            }

            if (!$form->validate()) {
                $this->errors = $form->getErrors();
                return;
            }
            
            

            Sabel_Db_Transaction::activate();
            
            try {
                if (!is_empty($this->picture)) {
                    $posted_picture = $form->picture->path;
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $picture_type = $finfo->file($posted_picture);
                    $specific_num = uniqid(mt_rand());
                    $rename_file = $specific_num . '.' . basename($picture_type);
                    $rename_file_path = 'images/users/' . $rename_file;
                    
                    if (!move_uploaded_file($posted_picture, $rename_file_path)) {
                        throw new Exception('Can not upload image');
                    }
                    
                    if (!is_empty($this->LOGIN_USER->picture)) {
                        unlink("images/users/{$this->LOGIN_USER->picture}");
                    }

                    $form->picture = $rename_file;
                }

                $form->save();
                Sabel_Db_Transaction::commit();
            } catch (Exception $e) {
                Sabel_Db_Transaction::rollback();
                throw $e;
            }

            $this->redirect->to("a: index, param: {$this->LOGIN_USER->id}");
            return;
        }
    }

    /**
     * @trim current_password password cofirm_password
     */
    public function password()
    {
        if (!$this->IS_LOGIN) {
            $this->redirect->to('c:auth, a: login');
            return;
        }

        $this->title = "パスワード変更";

        $this->form = $form = new Forms_Users($this->LOGIN_USER->id);
        $form->setDisplayNames([
            'current_password' => '現在のパスワード',
            'password'         => '新しいパスワード',
            'confirm_password' => '新しいパスワード(確認)',
        ]);

        $form->password = '';

        if ($this->isPost()) {
            $errors = [];

            $form->submit($this->POST_VARS, [
                'current_password',
                'password',
                'confirm_password',
            ]);

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
            
            $this->redirect->to('a: edit');
            return;
        }
    }
}
