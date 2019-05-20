<?php

class Index_Controllers_Index extends Index_Controllers_Base
{
    public function index()
    {
        $this->title = "投稿一覧";

        //user情報
        $model = MODEL('Users');
        $model->setCondition('id', $this->session->read('user_id'));
        $user_info = $model->selectOne()->toArray();
        $this->user_info = $user_info;

        $this->select_color_options = ['black' => '黒', 'red' => '赤', 'blue' => '青', 'yellow' => '黄', 'green' => '緑'];
        $this->picture_max_size = 1 * 1024 * 1024;
        $paginator = new Paginator('Posts');
        
        $paginator->setDefaultOrder('id', 'desc');
        $this->paginator = $paginator->build(3, $this->GET_VARS);

        $this->form = new Forms_Posts();

        if ($this->isPost()) {
            //$post = $this->POST_VARS;

            $this->form->submit($this->POST_VARS, array(
                'name',
                'comment',
                'picture',
                'MAX_FILE_SIZE',
                'color',
                'password',
            ));

            if (!$this->form->validate()) {
                $this->errors = $this->form->getErrors();
                return;
            }
            $this->form->remove( 'MAX_FILE_SIZE');
            $this->form->getModel()->unsetValue('MAX_FILE_SIZE');
            $this->form->save();
            
        }
    }

    public function send()
    {
        $this->title = '投稿成功';
    }

    //delete

    //


}
