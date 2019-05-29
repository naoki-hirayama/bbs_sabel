<?php

class Admin_Controllers_Postdetail extends Admin_Controllers_Base
{
    public function index()
    {
        $this->title = "投稿詳細画面";

        $this->post = MODEL('Posts', $this->param);
        $this->user = MODEL('Users', $this->post->user_id);

        if (!$this->post->isSelected()) {
            $this->notfound();
            return;
        }

        $this->replies = finder('Replies')
            ->eq('post_id', $this->param)
            ->sort('id', 'desc')
            ->fetchAll();
        
        $user_ids = [];
        $reply_ids = [];
        foreach ($this->replies as $reply) {
            $reply_ids[] = $reply->id;
            if (!is_empty($reply->user_id)) {
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

    public function delete()
    {
        if ($this->isPost()) {
            $this->post = MODEL('Posts', $this->post_id);

            $replies = finder('Replies')
                ->eq('post_id', $this->post_id)
                ->fetchArray();

            if (!is_empty($replies)) {
                foreach ($replies as $reply) {
                    unlink("images/replies/{$reply['picture']}");
                }
            }

            $post_repleis = MODEL('Replies');
            $post_repleis->setCondition(eq('post_id', $this->post_id));
            $post_repleis->delete();

            if (!is_empty($this->post->picture)) {
                unlink("images/posts/{$this->post->picture}");
            }
            $this->post->delete();

            $this->redirect->to("a: deleted, param: {$this->post_id}");
            return;
        }
    }

    public function deleted()
    {
        $this->title = "削除完了";
        $this->post = MODEL('Posts', $this->param);
        
        if ($this->post->isSelected()) {
            $this->notfound();
            return;
        }
    }


    public function reply_delete()
    {
        if ($this->isPost()) {
            $this->reply = MODEL('Replies', $this->reply_id);
            //トランザクション
            if (!is_empty($this->reply->picture)) {
                unlink("images/replies/{$this->reply->picture}");
            }
            $this->reply->delete();

            $this->redirect->to("a: index, param: {$this->reply->post_id}");
            return;
        }

    }

    public function reply_get_ajax()
    {
        $_reply = MODEL('Replies', $this->reply_id);
        $reply = [];
        $reply = [
            'id' => $_reply->id,
            'name' => $_reply->name,
            'comment' => $_reply->comment,
            'picture' => $_reply->picture,
            'color'  => $_reply->color,
        ];
        return $reply;
    }

    public function reply_edit_ajax()
    {
        if ($this->isPost()) {
            $this->form = $form = new Forms_Replies($this->id);

            $form->submit($this->POST_VARS, array(
                'name',
                'comment',
                'color',
            ));

            $response = [];
            if (!$form->validate()) {
                $response = [
                    'errors' => $form->getErrors(),
                    'status' => false,
                ];
                return $response;
            }

            $response = [
                'status' => true,
                'reply'   => $this->POST_VARS,
            ];

            $form->save();
            return $response;
        }
    }

}