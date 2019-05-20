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
        $paginator = new Paginator('Posts');
        
        $paginator->setDefaultOrder('id', 'desc');
        $this->paginator = $paginator->build(3, $this->GET_VARS);



        if ($this->isPost()) {
            $values = $_POST;

            exit;
            $_POST['name'];
            $_FILES['picture']['name'];
            //echo $this->name;
            //echo $this->comment;
            //echo $this->MAX_FILE_SIZE;
            //echo $this->picture['name'];
            //echo $_POST;
            //echo $this->color;
            //echo $this->password;
            header('Location: /index/send');
            exit;
        }
    }

    public function send()
    {
        $this->title = '投稿成功';
    }

    //delete

    //


}
