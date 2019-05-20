<?php

class Forms_Posts extends Form_Model
{
    protected $displayNames = array(
        'name'             => '名前',
        'comment'          => '本文',
        'picture'          => '写真',
        'MAX_FILE_SIZE'    => '画像サイズ',
        'color'            => '文字色',
        'password'         => 'パスワード',
    );

    protected $validators = array(
        'name'                  => array('validatePostNameLength'),
        'comment'               => array('validatePostCommentLength'),
        'picture'               => array('image'),
        'color'                 => array('alnum'),
        'password'              => array('alnum' ,'validatePasswordLength'),

    );
    //名前は全角5文字以内で入力してください ←どこからくる？
    //本文は全角50文字以内で入力してください
    public function validatePostNameLength($name, $value)
    {   
        if (!is_empty($value)) {
            if (mb_strlen($value, 'UTF-8') > Posts::MAX_NAME_LENGTH) {
                return $this->getDisplayName($name) . "は" . Posts::MAX_NAME_LENGTH . "文字以内です。";
            }
        }
    }
    //本文のバリデーション
    public function validatePostCommentLength($name, $value) 
    {
        if (!is_empty($value)) {
            if (mb_strlen($value, 'UTF-8') > Posts::MAX_COMMENT_LENGTH) {
                return $this->getDisplayName($name) . "は" . Posts::MAX_COMMENT_LENGTH . "文字以内です。";
            }
        }
    }
    public function validatePasswordLength($name, $value)
    {
        if (!is_empty($value)) {
            if (mb_strlen($value, 'UTF-8') < Posts::MIN_PASSWORD_LENGTH) {
                return $this->getDisplayName($name) . "は" . Posts::MIN_PASSWORD_LENGTH . "文字以上です。";
            } else if (mb_strlen($value) > Posts::MAX_PASSWORD_LENGTH) {
                return $this->getDisplayName($name) . "は" . Posts::MAX_PASSWORD_LENGTH . "文字以内です。";
            }
        }
    }    
    //写真のバリデーション
    
}
