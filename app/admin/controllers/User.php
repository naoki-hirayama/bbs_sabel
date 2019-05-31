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
                
                foreach ($posts as $post) {
                    $post_ids[] = $post['id'];
                }
                
                if (!is_empty($post_ids)) {
                    $replies = MODEL('Replies');
                    $replies->setCondition(in('post_id', $post_ids));
                    $replies->delete();
                }

                $replies = MODEL('Replies');
                $replies->setCondition(eq('user_id', $this->user_id));
                $replies->delete();

                $posts = MODEL('Posts');
                $posts->setCondition(eq('user_id', $this->user_id));
                $posts->delete();
                
                $user = MODEL('Users', $this->user_id);
                $user->delete();
                
                Sabel_Db_Transaction::commit();
                // if (!is_empty($user)) {
                //     unlink("images/users/{$user['picture']}");
                // }

                // if (!is_empty($replies)) {
                //     foreach ($replies as $reply) {
                //         unlink("images/replies/{$reply['picture']}");
                //     }
                // }
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