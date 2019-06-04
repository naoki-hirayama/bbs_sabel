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
        
        $replies_model = new Replies();
        $this->replies = $replies_model->fetchByPostId($this->param);
        
        $user_ids = [];
        foreach ($this->replies as $reply) {
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

            Sabel_Db_Transaction::activate();

            try {
                $this->post = MODEL('Posts', $this->post_id);
                $replies_model = new Replies;

                $replies = $replies_model->fetchByPostId($this->post_id);
                
                $post_repleis = MODEL('Replies');
                $post_repleis->setCondition(eq('post_id', $this->post_id));
                $post_repleis->delete();

                $this->post->delete();

                Sabel_Db_Transaction::commit();

                if (!is_empty($replies)) {
                    foreach ($replies as $reply) {
                        unlink("images/replies/{$reply->picture}");
                    }
                }

                if (!is_empty($this->post->picture)) {
                    unlink("images/posts/{$this->post->picture}");
                }
            } catch (Exception $e) {
                Sabel_Db_Transaction::rollback();
                throw $e;
            }

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

            Sabel_Db_Transaction::activate();

            try {
                $reply = MODEL('Replies', $this->reply_id);
                $reply->delete();

                Sabel_Db_Transaction::commit();
                if (!is_empty($reply->picture)) {
                    unlink("images/replies/{$reply->picture}");
                }
            } catch (Exception $e) {
                Sabel_Db_Transaction::rollback();
                throw $e;
            }
            
            $this->redirect->to("a: index, param: {$reply->post_id}");
            return;
        }
    }

    public function reply_get_ajax()
    {
        $reply = MODEL('Replies', $this->reply_id);

        if (!$reply->isSelected()) {
            $this->notFound();
            return;
        }

        return $reply->toArray();
    }
    
    /**
     * @trim name comment
     */
    public function reply_edit_ajax()
    {
        if ($this->isPost()) {
            $this->form = $form = new Forms_Replies($this->id);

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

            Sabel_Db_Transaction::activate();
            try {
                $form->save();
                Sabel_Db_Transaction::commit();

            } catch (Exception $e) {
                Sabel_Db_Transaction::rollback();
                throw $e;
            }
            $response = [
                'status' => true,
                'reply'  => $this->POST_VARS,
            ];
            return $response;
        }
    }

}