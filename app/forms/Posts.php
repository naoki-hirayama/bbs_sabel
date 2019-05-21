<?php

class Forms_Posts extends  Form_Object
{
    protected $displayNames = array(
        'name'             => '名前',
        'comment'          => '本文',
        'picture'          => '写真',//使えない
        'MAX_FILE_SIZE'    => '画像サイズ',
        'color'            => '文字色',
        'password'         => 'パスワード',
    );

    protected $validators = array(
        'name'                  => array('required', 'validatePostNameLength'),
        'comment'               => array('required', 'validatePostCommentLength'),
        'picture'               => array('validateImage'),//サイズのバリデーション
        'color'                 => array('alnum'),
        'password'              => array('alnum' ,'validatePasswordLength'),
    );
    //名前のバリデーション
    public function validatePostNameLength($name, $value)
    {
        if (mb_strlen($value) > Posts::MAX_NAME_LENGTH) {
            return $this->getDisplayName($name) . "は" . Posts::MAX_NAME_LENGTH . "文字以内です。";
        }
        
    }
    //本文のバリデーション
    public function validatePostCommentLength($name, $value) 
    {
        if (mb_strlen($value, 'UTF-8') > Posts::MAX_COMMENT_LENGTH) {
            return $this->getDisplayName($name) . "は" . Posts::MAX_COMMENT_LENGTH . "文字以内です。";
        }
    }
    //パスワードのバリデーション
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
    public function validateImage($name, $value)
    {
        if(!is_empty($value)) {
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

    //文字色
    public function validateColor($name, $value)
    {
        if (!array_key_exists($value, self::getSelectColorOptions())) {
            return "文字色が不正です";
        }
    }
        
    
}
