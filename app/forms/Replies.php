<?php

class Forms_Replies extends Form_Object
{
    protected $displayNames = array(
        'name'             => '名前',
        // 'comment'          => '本文',
        'picture'          => '写真', 
        // 'MAX_FILE_SIZE'    => '画像サイズ',
        // 'color'            => '文字色',
        // 'password'         => 'パスワード',
    );

    protected $validators = array(
        'name'                  => array('required', 'validatePostNameLength'),
        // 'comment'               => array('validatePostCommentLength'),
        'picture'               => array('image'), //サイズのバリデーション
        // 'color'                 => array('alnum'),
        // 'password'              => array('alnum', 'validatePasswordLength'),

    );

    //名前のバリデーション
    public function validatePostNameLength($name, $value)
    {
        //テーブルの定義を変える　usersに合わせる
            if (mb_strlen($value, 'UTF-8') === 0) {
                return $this->getDisplayName($name) . "を" . "記入してください";
            } else if (mb_strlen($value) > Posts::MAX_NAME_LENGTH) {
                return $this->getDisplayName($name) . "は" . Posts::MAX_NAME_LENGTH . "文字以内です。";
            }
    }
}