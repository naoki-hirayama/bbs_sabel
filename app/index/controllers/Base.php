<?php

class Index_Controllers_Base extends Sabel_Controller_Page
{
    public function initialize()
    {
        //全部の処理に共通
        $login_user = $this->fetchLoginUser();
        if (!is_null($login_user)) {
            $this->IS_LOGIN   = true;
            $this->LOGIN_USER = $login_user;
        } else {
            $this->IS_LOGIN = false;
        }
    }

    protected function fetchLoginUser()
    {
        $user_id = $this->session->read('user_id');
        if (is_empty($user_id)) {
            return null;
        }

        $user = new Users($user_id);
        if (!$user->isSelected()) {
            return null;
        }

        return $user;
    }
}
