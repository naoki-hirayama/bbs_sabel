<?php

class Admin_Controllers_Postdetail extends Admin_Controllers_Base
{
    public function index()
    {
        $this->title = "投稿詳細画面";

        $this->post = MODEL('Posts', $this->param);
        $this->user = MODEL('Users', $this->post->user_id);

        if (!$this->post->isSelected()) {
            $this->notFound();
            return;
        }

        $this->replies = finder('Replies')
            ->eq('post_id', $this->param)
            ->sort('id', 'desc')
            ->fetchAll();

        $user_ids = [];
        $reply_ids = [];
        foreach ( $this->replies as $reply) {
            $reply_ids[] = $reply->id;
            if (!is_empty( $reply->user_id)) {
                $user_ids[] = $reply->user_id;
            }
        }

        if (!is_empty($user_ids)) {
            $this->user_names = finder('Users')
                ->in('id', $user_ids)
                ->sort('id', 'desc')
                ->fetchArray('name');
        }
    }
}