<?php

class Admin_Controllers_Index extends Admin_Controllers_Base
{
    public function index()
    {
        $this->title = "管理画面";

        $per_page_records = 10;

        $this->form = $form = new Forms_Posts();
        
        $form->submit($this->GET_VARS, [
            'name',
            'comment',
            'color',
        ]);

        $finder = finder('Posts');
        
        if (!is_null($form->name)) {
            $finder->contains('name', $form->name);
        }
        if (!is_null($form->comment)) {
            $finder->contains('comment', $form->comment);
        }
        if (!is_null($form->color)) {
            $finder->eq('color', $form->color);
        }

        $finder->sort('id', 'desc');

        $paginator = new Paginator($finder);
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
    }

    public function get_ajax()
    {
        $post = MODEL('Posts', $this->id);

        if (!$post->isSelected()) {
            $this->notFound();
            return;
        }
        
        return $post->toArray();
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
