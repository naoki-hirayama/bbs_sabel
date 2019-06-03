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
        if (!is_null($form->login_id)) {
            $finder->contains('login_id', $form->login_id);
        }

        $finder->sort('id', 'desc');

        $paginator = new Paginator($finder);
        $this->paginator = $paginator->build($per_page_records, $this->GET_VARS);

        $user_ids = [];
        foreach ($paginator->results as $user) {
            $user_ids[] = $user->id;
        }
        $post_repository = new Posts();
        $this->post_counts = $post_repository->fetchPostCountByUserIds($user_ids);
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
                    $replies_by_users_pictures = finder('Replies')
                        ->in('post_id', $post_ids)
                        ->fetchArray();
                    $replies_by_users = MODEL('Replies');
                    $replies_by_users->setCondition(in('post_id', $post_ids));
                    $replies_by_users->delete();
                }

                $replies_pictures = finder('Replies')
                    ->eq('user_id', $this->user_id)
                    ->fetchArray();
                $replies = MODEL('Replies');
                $replies->setCondition(eq('user_id', $this->user_id));
                $replies->delete();
                
                $posts = MODEL('Posts');
                $posts->setCondition(eq('user_id', $this->user_id));
                $posts->delete();
                
            
                $user = MODEL('Users', $this->user_id);
                $user->delete();
                
                Sabel_Db_Transaction::commit();
                if (!is_empty($user)) {
                    unlink("images/users/{$user->picture}");
                }
                
                if (!is_empty($replies_pictures)) {
                    foreach ($replies_pictures as $replies_picture) {
                        unlink("images/replies/{$replies_picture['picture']}");
                    }
                }

                if (!is_empty($posts)) {
                    foreach ($post_images as $post_image) {
                        unlink("images/posts/{$post_image}");
                    }
                }

                if (!is_empty($replies_by_users_pictures)) {
                    foreach ($replies_by_users_pictures as $replies_by_users_picture) {
                        unlink("images/replies/{$replies_by_users_picture['picture']}");
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

        $this->user = MODEL('Users', $this->param);

        if (!$this->user->isSelected()) {
            $this->notFound();
            return;
        }

        $this->form = $form = new Forms_Users($this->param);

        if ($this->isPost()) {
            if (is_empty($this->picture)) {
                $form->submit($this->POST_VARS, [
                    'name',
                    'login_id',
                    'comment',
                ]);
            } else {
                $form->submit($this->POST_VARS, [
                    'name',
                    'login_id',
                    'comment',
                    'picture',
                ]);
            }

            if (!$form->validate()) {
                $this->errors = $form->getErrors();
                return;
            }

            if (!is_empty($this->picture)) {
                $posted_picture = $form->picture->path;
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $picture_type = $finfo->file($posted_picture);
                $specific_num = uniqid(mt_rand());
                $rename_file = $specific_num . '.' . basename($picture_type);
                $rename_file_path = 'images/users/' . $rename_file;
                move_uploaded_file($posted_picture, $rename_file_path);

                if (!is_empty($this->LOGIN_USER->picture)) {
                    unlink("images/users/{$this->LOGIN_USER->picture}");
                }

                $form->picture = $rename_file;
            }

            $form->save();

            $this->redirect->to("a: edited, param: {$this->param}");
            return;
        }
    }

    public function edited()
    {
        $this->title = "ユーザー編集完了画面";
    }
    
}