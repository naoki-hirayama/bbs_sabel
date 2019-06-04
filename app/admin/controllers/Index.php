<?php

class Admin_Controllers_Index extends Admin_Controllers_Base
{
    public function index()
    {
        $this->title = "投稿管理画面";

        $per_page_records = 10;

        $this->form = $form = new Forms_Posts();

        $form->submit($this->GET_VARS, [
            'name',
            'comment',
            'color',
        ]);

        $finder = finder('Posts')
            ->leftJoin('Users', ['user_id' => 'id', 'fkey' => 'user_id']);
        
        if (!is_null($form->name)) {
            $finder->contains('Posts.name', $form->name);
        }
        if (!is_null($form->comment)) {
            $finder->contains('Posts.comment', $form->comment);
        }
        if (!is_null($form->color)) {
            $finder->eq('Posts.color', $form->color);
        }

        $finder->sort('Posts.id', 'desc');
    

        $paginator = new Paginator($finder);
        $this->paginator = $paginator->build($per_page_records, $this->GET_VARS);

        $post_ids = [];
        foreach ($paginator->results as $post) {
            $post_ids[] = $post->id;
        }
        
        $this->reply_counts = Replies::fetchReplyCountByPostIds($post_ids);
    }

    public function get_ajax()
    {
        $post = MODEL('Posts', $this->id);

        if (!$post->isSelected()) {
            $this->notfound();
            return;
        }
        
        return $post->toArray();
    }
    /**
     * @trim name comment
     */
    public function edit_ajax()
    {
        if ($this->isPost()) {
            $this->form = $form = new Forms_Posts($this->id);

            $form->submit($this->POST_VARS, [
                'name',
                'comment',
                'color',
            ]);

            $response = [];
            if (!$form->validate()) {
                $response = [
                    'errors' => $form->getErrors(),
                    'status' => false,
                ];
                return $response;
            }

            $form->save();
            
            $response = [
                'status' => true,
                'post'   => $this->POST_VARS,
            ];
            return $response;
        }
    }


}
