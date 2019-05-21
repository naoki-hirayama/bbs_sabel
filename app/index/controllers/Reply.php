<?php

class Index_Controllers_Reply extends Index_Controllers_Base
{
    public function index()
    {
        $this->title = "レス一覧";

        //user情報
        $model = MODEL('Users'); 
        $model->setCondition('id', $this->session->read('user_id'));
        $user_info = $model->selectOne()->toArray();
        $this->user_info = $user_info;

        $this->select_color_options = ['black' => '黒', 'red' => '赤', 'blue' => '青', 'yellow' => '黄', 'green' => '緑'];
        $this->picture_max_size = 1 * 1024 * 1024;

        $model = MODEL('Posts');
        $model->setCondition('id', $this->param);
        $this->post = $model->selectOne()->toArray();

        $this->form = new Forms_Replies();
        if ($this->isPost()) {
            $this->form->submit($this->POST_VARS, array(
                'name',
                'picture' 
            ));

            if (!$this->form->validate()) {
                $this->errors = $this->form->getErrors();
                return;
            }

            // $this->form->remove('confirm_password');
            // $this->form->getModel()->unsetValue('confirm_password');
            // $this->form->password = password_hash($this->form->password, PASSWORD_DEFAULT);
            // $this->form->save();

            // $this->session->write('user_id', $this->form->getModel()->id);

            $this->redirect->to('a: registered');
            return;
        }
    }

    public function delete()
    {
        $this->title = "レス削除";
    }

    public function deleted()
    {
        $this->title = "レス削除完了";
    }
}
