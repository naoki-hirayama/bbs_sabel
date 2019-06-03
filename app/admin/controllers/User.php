<?php

class Admin_Controllers_User extends Admin_Controllers_Base
{
    public function index()
    {
        $this->title = "ユーザー管理画面";

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

        $user_ids = [];
        foreach ($paginator->results as $user) {
            $user_ids[] = $user->id;
        }

        $this->post_counts = Posts::fetchPostCountByUserIds($user_ids);
    }

    public function delete()
    {
        $this->title = "ユーザー削除画面";

        if ($this->isPost()) {

            Sabel_Db_Transaction::activate();

            try {
                $posts = finder('Posts')
                    ->eq('user_id', $this->user_id)
                    ->fetchArray();

                $post_ids = [];
                $post_images = [];
                foreach ($posts as $post) {
                    $post_ids[] = $post['id'];
                    $post_images[] = $post['picture'];
                }
                
                if (!is_empty($post_ids)) {
                    $replies_by_users = MODEL('Replies');
                    $replies_by_users->setCondition(in('post_id', $post_ids));
                    $replies_by_users->delete();
                }

                $replies = MODEL('Replies');
                $replies->setCondition(eq('user_id', $this->user_id));
                $replies->delete();
                
                $posts = MODEL('Posts');
                $posts->setCondition(eq('user_id', $this->user_id));
                $posts->delete();
                
            
                $user = MODEL('Users', $this->user_id);
                $user->delete();
                //dump($user->name);exit;
                Sabel_Db_Transaction::commit();
                if (!is_empty($user)) {
                    unlink("images/users/{$user->picture}");
                }
                
                if (!is_empty($replies)) {
                    foreach ($replies as $reply) {
                        unlink("images/replies/{$reply['picture']}");
                    }
                }

                if (!is_empty($posts)) {
                    foreach ($post_images as $post_image) {
                        unlink("images/posts/{$post_image}");
                    }
                }
                
            } catch (Exception $e) {
                Sabel_Db_Transaction::rollback();
                throw $e;
            }

            $this->redirect->to("a: index");
            return;
        }
    }

    public function edit()
    {
        $this->title = "ユーザー編集画面";
    }
}