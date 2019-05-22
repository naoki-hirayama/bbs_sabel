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
        'new_confirm_password' => 'パスワード(確認)',
    );

    protected $validators = array(
        'name'                      => array('required', 'validateNameLength'),
        'login_id'                  => array('-strwidth', 'strlen(' . Users::MAX_LOGIN_ID_LENGTH . ')', 'alnum'),
        'password,confirm_password' => array('same'),
        'confirm_password'          => array('required'),
        'picture'                   => array('validateImage'),
        'comment'                   => array('validateCommentLength'),
        'new_password,new_confirm_password' => array('same'),

    );

    public function validateLoginIdLength($name, $value)
    {
        
        if (mb_strlen($value) > Users::MAX_LOGIN_ID_LENGTH) {
            return $this->getDisplayName($name) . "は" . Users::MAX_LOGIN_ID_LENGTH . "文字以内です。";
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

    //写真のバリデーション
    public function validateImage($name, $value)
    {
        if (!is_empty($value)) {
            if (strlen($value->name) > 0) {
                if ($value->size > Posts::MAX_PICTURE_SIZE) {
                    return "サイズが" . number_format(Posts::MAX_PICTURE_SIZE) . "MBを超えています。";
                } else {
                    // 画像ファイルのMIMEタイプチェック
                    $posted_picture = $value->path;
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $picture_type = $finfo->file($posted_picture);

                    $vaild_picture_types = [
                        'image/png',
                        'image/gif',
                        'image/jpeg'
                    ];

                    if (!in_array($picture_type, $vaild_picture_types)) {
                        return "画像が不正です。";
                    }
                }
            }
        }
    }
}
