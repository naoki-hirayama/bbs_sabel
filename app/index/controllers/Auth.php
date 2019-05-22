<?php

class Index_Controllers_Auth extends Index_Controllers_Base
{
    public function login()
    {
        $this->title = "ログイン画面";
        $this->form = new Forms_Users();

        if ($this->IS_LOGIN) {
            $this->redirect->uri('/'); 
            return;
        }
        
        if ($this->isPost()) {
            
            $model = MODEL('Users');
            $model->setCondition('login_id', $this->login_id);
            $user = $model->selectOne();
            
            $errors = [];
            if (!$user->isSelected()) {
                $errors [] = "パスワードかログインIDが違います。h";
                $this->errors = $errors;
                return;
            } else {
                if (!password_verify($this->password, $user->password)) {
                    $errors[] = "パスワードかログインIDが違います。";
                    $this->errors = $errors;
                    return;
                } else {
                    $this->session->write('user_id', $user->id);
                    $this->redirect->uri('/');
                    return;
                }
            }
            
        }

    }

    public function logout()
    {
        $this->title = "ログアウト";

        if (!$this->IS_LOGIN) {
            $this->redirect->uri('/');
            return;
        }

        $this->session->destroy();
    }

    public function register()
    {
        $this->title = "登録画面";
        
        if ($this->IS_LOGIN) {
            $this->redirect->uri('/');
            return;
        }

        $this->form = $form = new Forms_Users();

        if ($this->isPost()) {
            $form->submit($this->POST_VARS, array(
                'name',
                'login_id',
                'password',
                'confirm_password',
            ));

            if (!$form->validate()) {
                $this->errors = $form->getErrors();
                return;
            }

            $this->form->remove('confirm_password');
            $this->form->getModel()->unsetValue('confirm_password');
            $form->password = password_hash($form->password, PASSWORD_DEFAULT);
            $form->save();
            
            $this->session->write('user_id', $this->form->getModel()->id);

            $this->redirect->to('a: registered');
            return;
        }
    }

    public function registered()
    {
        $this->title = "登録完了";
    }


    
}
