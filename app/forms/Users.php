<?php

class Forms_Users extends Form_Model
{
    protected $displayNames = array(
        'name'             => '名前',
        'login_id'         => 'ログインID',
        'password'         => 'パスワード',
        'confirm_password' => 'パスワード(確認)',
        'picture'          => '画像',
        'comment'          => '一言コメント',
    );

    protected $validators = array(
        'name'                      => array('-strwidth', 'strlen(' . Users::MAX_NAME_LENGTH . ')'),
        'login_id'                  => array('-strwidth', 'strlen(' . Users::MAX_LOGIN_ID_LENGTH . ')', 'alnum'), //第二引数でメソッドを指定
        'password,confirm_password' => array('same'),
        'confirm_password'          => array('required'),
        'picture'                   => array('-strwidth', 'image("' . (Users::MAX_PICTURE_SIZE / 1024 / 1024) . 'M")'),
        'comment'                      => array('-strwidth', 'strlen(' . Users::MAX_COMMENT_LENGTH . ')'),
    );
}
