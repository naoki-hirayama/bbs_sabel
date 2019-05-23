<?php

class Forms_Replies extends Form_Model
{
    protected $displayNames = array(
        'name'             => '名前',
        'comment'          => '本文',
        'picture'          => '写真',
        'color'            => '文字色',
        'password'         => 'パスワード',
    );

    protected $validators = array(
        'name'                  => array('-strwidth', 'strlen(' . Replies::MAX_NAME_LENGTH . ')'),
        'comment'               => array('-strwidth', 'strlen(' . Replies::MAX_COMMENT_LENGTH . ')'),
        'picture'               => array('-strwidth', 'image("' . (Replies::MAX_PICTURE_SIZE / 1024 / 1024) . 'M")'),
        'color'                 => array('validateColor'),
        'password'              => array('-strwidth', 'validatePasswordLength', 'alnum'),
    );

    //パスワード
    public function validatePasswordLength($name, $value)
    {
        if (!is_empty($value)) {
            if (mb_strlen($value, 'UTF-8') < Replies::MIN_PASSWORD_LENGTH) {
                return $this->getDisplayName($name) . "は" . Replies::MIN_PASSWORD_LENGTH . "文字以上です。";
            } else if (mb_strlen($value) > Replies::MAX_PASSWORD_LENGTH) {
                return $this->getDisplayName($name) . "は" . Replies::MAX_PASSWORD_LENGTH . "文字以内です。";
            }
        }
    }

    //文字色
    public function validateColor($name, $value)
    {
        if (!array_key_exists($value, Replies::getSelectColorOptions())) {
            return "文字色が不正です";
        }
    }
}