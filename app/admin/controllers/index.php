<?php

class Admin_Controllers_Index extends Admin_Controllers_Base
{
    public function index()
    {
        $this->title = "管理画面";

        $this->form = $form = new Forms_Posts();
        $per_page_records = 10;
        $paginator = new Paginator('Posts');
        
        if (!is_null($this->name)) {
            $paginator->setCondition(contains('name', $this->name));
            $form->name = $this->name;
            $this->result = true;
        }
        if (!is_null($this->comment)) {
            $paginator->setCondition(contains('comment', $this->comment));
            $form->comment = $this->comment;
            $this->result = true;
        }
        if (!is_null($this->color)) {
            $paginator->setCondition(eq('color', $this->color));
            $form->color = $this->color;
            $this->result = true;
        }
        
        $paginator->setDefaultOrder('id', 'desc');
        $this->paginator = $paginator->build($per_page_records, $this->GET_VARS);
        $this->records = Count($paginator->results);
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
    }

    public function delete()
    {
        if ($this->isPost()) {
            $this->post = MODEL('Posts', $this->post_id);

            //トランザクション
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

            $this->redirect->to('c: index');
            return;
        }
    }

    public function get_ajax()
    {
        //レイアウトを使用しない
        $this->layout = false;

        $post_id = $_GET['id'];
        $_post = MODEL('Posts', $post_id);
        $post = [
            'id' => $_post->id,
            'name' => $_post->name,
            'comment' => $_post->comment,
            'picture' => $_post->picture,
            'color'  => $_post->color,
        ];
        return $post;
    }

    public function edit_ajax()
    {
        $this->layout = false;
        if ($this->isPost()) {
            $this->form = $form = new Forms_Posts($this->id);

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
                'post'   => $this->POST_VARS,
            ];

            $form->save();
            return $response;
        }
    }


}
