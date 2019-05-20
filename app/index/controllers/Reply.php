<?php

class Index_Controllers_Reply extends Index_Controllers_Base
{
    public function index()
    {
        $this->title = "レス一覧";
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
