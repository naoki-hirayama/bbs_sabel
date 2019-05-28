<?php

class Admin_Controllers_Index extends Admin_Controllers_Base
{
    public function index()
    {
        $this->title = "管理画面";

        $per_page_records = 10;
        $paginator = new Paginator('Posts');
        $paginator->setDefaultOrder('id', 'desc');
        $this->paginator = $paginator->build($per_page_records, $this->GET_VARS);

        $user_ids = [];
        $post_ids = [];
        foreach ($paginator->results as $post) {
            $post_ids[] = $post->id;
            if (!is_empty($post->user_id)) {
                $user_ids[] = $post->user_id;
            }
        }

        if (!is_empty($user_ids)) {
            $this->user_names = finder('Users')
                ->in('id', $user_ids)
                ->sort('id', 'desc')
                ->fetchArray('name');
        }

        if (!is_empty($post_ids)) {
            $tmp = db_query("SELECT post_id, COUNT(*) AS cnt FROM replies WHERE post_id IN (" . implode(',', $post_ids) . ") GROUP BY post_id");

            if (!is_empty($tmp)) {
                $this->reply_counts = array_column($tmp, 'cnt', 'post_id');
            }
        }

        $this->form = $form = new Forms_Posts();


    }
}
