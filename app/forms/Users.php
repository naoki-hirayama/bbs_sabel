<?php

class Forms_Users extends Form_Model
{
    protected $displayNames = [
        'name'             => '名前',
        'login_id'         => 'ログインID',
        'password'         => 'パスワード',
        'confirm_password' => 'パスワード(確認)',
        'picture'          => '画像',
        'comment'          => '一言コメント',
    ];

    protected $validators = [
        'name'                      => ['-strwidth', 'strlen(' . Users::MAX_NAME_LENGTH . ')'],
        'login_id'                  => ['-strwidth', 'strlen(' . Users::MAX_LOGIN_ID_LENGTH . ')', 'alnum'], 
        'password,confirm_password' => ['same'],
        'confirm_password'          => ['required'],
        'picture'                   => ['-strwidth', 'image("' . (Users::MAX_PICTURE_SIZE / 1024 / 1024) . 'M")'],
        'comment'                   => ['-strwidth', 'strlen(' . Users::MAX_COMMENT_LENGTH . ')'],
    ];
}
