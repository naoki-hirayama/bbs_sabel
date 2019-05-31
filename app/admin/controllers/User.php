<?php

class Admin_Controllers_User extends Admin_Controllers_Base
{
    public function index()
    {
        $this->title = "投稿管理画面";

        $per_page_records = 5;

        $this->form = $form = new Forms_Users();

        $form->submit($this->GET_VARS, [
            'name',
            'login_id',
        ]);

        $finder = finder('Users');

        if (!is_null($form->name)) {
            $finder->contains('name', $form->name);
        }
        if (!is_null($form->comment)) {
            $finder->contains('login-id', $form->login_id);
        }

        $paginator = new Paginator($finder);
        $this->paginator = $paginator->build($per_page_records, $this->GET_VARS);
    }
}