<?php

class Forms_Posts extends  Form_Model
{
    protected $displayNames = array(
        'name'             => '名前',
        'comment'          => '本文',
        'picture'          => '写真',
        'color'            => '文字色',
        'password'         => 'パスワード',
    );

    protected $validators = array(
        'name'                  => array( '-strwidth', 'strlen(' . Posts::MAX_NAME_LENGTH . ')'),
        'comment'               => array( '-strwidth', 'strlen(' . Posts::MAX_COMMENT_LENGTH . ')'),
        'picture'               => array( '-strwidth', 'image("' . (Posts::MAX_PICTURE_SIZE / 1024 / 1024) . 'M")'),
        'color'                 => array('validateColor'),
        'password'              => array( '-strwidth', 'validatePasswordLength', 'alnum'),
    );
    
    //パスワード
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
    
    //文字色
    public function validateColor($name, $value)
    {
        if (!array_key_exists($value, Posts::getSelectColorOptions())) {
            return "文字色が不正です";
        }
    }
        
    
}
