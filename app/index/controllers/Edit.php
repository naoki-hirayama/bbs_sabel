<?php

class Index_Controllers_Edit extends Index_Controllers_Base
{
    public function index()
    {
        $this->title = "プロフィール編集画面";
        
    }

    public function edit()
    {
        header('Location: profile/index');
        exit;
    }

    public function changePassword()
    {
        $this->title = "パスワード編集";
    }
}
