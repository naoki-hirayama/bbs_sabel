<?php

class Index_Controllers_Delete extends Index_Controllers_Base
{
    public function index()
    {
        $this->title = "削除画面";
        $page = $this->param;
        
        $post = db_query("SELECT * FROM posts WHERE id = {$page}");
        $this->post = $post[0];
        //var_dump($this->post);
        
    }
    
    public function deleted()
    {
        $this->title = "削除完了";
    }
}
