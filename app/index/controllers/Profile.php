<?php

class Index_Controllers_Profile extends Index_Controllers_Base
{
    public function index()
    {
        //存在しないidの場合リダイレクト
        $this->title = "プロフィール";
        //user情報
        $model = MODEL('Users');  
        $model->setCondition('id', $this->session->read('user_id'));
        $user_info = $model->selectOne()->toArray();
        $this->user_info = $user_info;
        //パラメーターからuser情報を取得
        $model = MODEL('Users');
        $model->setCondition('id', $this->param);
        $this->user = $model->selectOne()->toArray();
        //存在しないuserにアクセスされた時
        if (!$this->user) {
            $this->redirect->uri('/');
            return;
        }
        
    }

    public function edit()
    {
        if (!$this->session->read('user_id')) {
            $this->redirect->uri('/');
            return;
        }

        $this->title = "プロフィール編集";
        $model = MODEL('Users');
        $model->setCondition('id', $this->session->read('user_id'));
        $user_info = $model->selectOne()->toArray();
        $this->user_info = $user_info;

        $this->form = new Forms_Users();
        if ($this->isPost()) {
            if ($this->POST_VARS['login_id'] === $this->user_info['login_id']) {
                
                $this->form->submit($this->POST_VARS, array(
                    'name',
                    'comment',
                    'picture',
                ));
            } else {

                $this->form->submit($this->POST_VARS, array(
                    'name',
                    'login_id',
                    'comment',
                    'picture',
                ));
            }

            if (!$this->form->validate()) {
                $this->errors = $this->form->getErrors();
                return;
            }

            $model = MODEL('Users', $this->session->read('user_id'));
            $model->name = $this->POST_VARS['name'];
            $model->picture = $this->POST_VARS['picture'];
            $model->comment = $this->POST_VARS['comment'];
            $model->login_id = $this->POST_VARS['login_id'];

            $model->save();

            $this->redirect->to('a: index');
            return;
        }


    }

    public function password()
    {
        if (!$this->session->read('user_id')) {
            $this->redirect->uri('/');
            return;
        }
        

        $this->title = "パスワード変更";
        $model = MODEL('Users');
        //var_dump($_SESSION['user_id']);  
        $model->setCondition('id', $this->session->read('user_id'));
        $user_info = $model->selectOne()->toArray();
        $this->user_info = $user_info;

    }

}
