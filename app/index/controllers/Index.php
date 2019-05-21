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
            //$post = $this->POST_VARS;
            //var_dump($this->POST_VARS['name']);
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
            
            $this->form->remove('MAX_FILE_SIZE');
            $this->form->user_id = $this->session->read('user_id');
            $this->form->getModel()->unsetValue('MAX_FILE_SIZE');
            $this->form->save();
            $this->redirect->to('a: index');
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
