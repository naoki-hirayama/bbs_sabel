<?php

class Forms_Users extends Form_Model
{
    protected $displayNames = array(
        'name'             => '名前',
        'login_id'         => 'ログインID',
        'password'         => 'パスワード',
        'picture'          => '画像',
        'comment'          => '一言コメント',
        'confirm_password' => 'パスワード(確認)',
        'current_password' => 'パスワード(現在)',
        'new_password' => 'パスワード(新しい)',
        'new_confirm_password' => 'パスワード(new確認)',
    );

    protected $validators = array(
        'name'                      => array('validateNameLength'),
        'login_id'                  => array('alnum', 'validateLoginIdLength'),//第二引数でメソッドを指定
        'password,confirm_password' => array('same'),
        'picture'                   => array('image'),
        'comment'                   => array('validateCommentLength'),
        'new_password,new_confirm_password' => array('same'),

    );

    public function validateLoginIdLength($name, $value)
    {
        if (!is_empty($value)) {
            if (mb_strlen($value, 'UTF-8') < Users::MIN_LOGIN_ID_LENGTH) {
                return $this->getDisplayName($name) . "は" . Users::MIN_LOGIN_ID_LENGTH . "文字以上です。";
            } else if (mb_strlen($value) > Users::MAX_LOGIN_ID_LENGTH) {
                return $this->getDisplayName($name) . "は" . Users::MAX_LOGIN_ID_LENGTH . "文字以内です。";
            }
        }
    }

    public function validateNameLength($name, $value)
    {
        if (!is_empty($value)) {
            if (mb_strlen($value, 'UTF-8') === 0) {
                return $this->getDisplayName($name) . "を" . "記入してください";
            } else if (mb_strlen($value) > Users::MAX_NAME_LENGTH) {
                return $this->getDisplayName($name) . "は" . Users::MAX_NAME_LENGTH . "文字以内です。";
            }
        }
    }

    public function validateCommentLength($name, $value)
    {
        if (!is_empty($value)) {
            if (mb_strlen($value, 'UTF-8') > Users::MAX_COMMENT_LENGTH) {
                return $this->getDisplayName($name) . "は" . Users::MAX_COMMENT_LENGTH . "文字以内です。";
            } 
        }
    }
}
