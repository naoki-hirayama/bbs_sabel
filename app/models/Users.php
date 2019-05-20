<?php

class Users extends Sabel_Db_Model
{
    const MAX_PASSWORD_LENGTH        = 30;
    const MIN_PASSWORD_LENGTH        = 4;
    const MAX_NAME_LENGTH            = 10;
    const MAX_COMMENT_LENGTH         = 50;
    const MAX_LOGIN_ID_LENGTH        = 15;
    const MIN_LOGIN_ID_LENGTH        = 4;
    const MAX_PICTURE_SIZE           = 1 * 1024 * 1024;

    public function validate($values, $id = null)
    {
        $errors = [];
        if (empty($values)) {
            $errors[] = 'エラーが発生しました。画像が大きすぎます。';
        } else {
            $values = $this->trimValues($values);
            if (isset($values['name'])) {
                if ($this->getStringLength($values['name']) === 0) {
                    $errors[] = "名前は入力必須です。";
                } else {
                    if ($this->getStringLength($values['name']) > self::MAX_NAME_LENGTH) {
                        $errors[] = "名前は" . self::MAX_NAME_LENGTH . "文字以内です。";
                    }
                }
            }

            if (isset($values['login_id'])) {
                if ($this->getStringLength($values['login_id']) === 0) {
                    $errors[] = "ログインIDは入力必須です。";
                } else {
                    if (!$this->validateAlphaNumeric($values['login_id'])) {
                        $errors[] = "ログインIDは半角英数字です。";
                    } else if ($this->getStringLength($values['login_id']) < self::MIN_LOGIN_ID_LENGTH) {
                        $errors[] = "ログインIDは" . self::MIN_LOGIN_ID_LENGTH . "文字以上です。";
                    } else if ($this->getStringLength($values['login_id']) > self::MAX_LOGIN_ID_LENGTH) {
                        $errors[] = "ログインIDは" . self::MAX_LOGIN_ID_LENGTH . "文字以内です。";
                    } else {
                        // $tmp_user = db_query("SELECT * FROM users WHERE login_id");
                        // var_dump($tmp_user);
                        // if ($tmp_user !== false) {
                        //     if (is_null($id)) {
                        //         $errors[] = "このログインIDはすでに存在します。";
                        //     } else if ($tmp_user['id'] !== $id) {
                        //         $errors[] = "このログインIDはすでに存在します。";
                        //     }
                        // }
                    }
                }
            }

            if (isset($values['password'])) {
                if ($this->getStringLength($values['password']) === 0) {
                    $errors[] = "パスワードは入力必須です。";
                } else {
                    if (!$this->validateAlphaNumeric($values['password'])) {
                        $errors[] = "パスワードは半角英数字です。";
                    } else if ($this->getStringLength($values['password']) < self::MIN_PASSWORD_LENGTH) {
                        $errors[] = "パスワードは" . self::MIN_PASSWORD_LENGTH . "文字以上です。";
                    } else if ($this->getStringLength($values['password']) > self::MAX_PASSWORD_LENGTH) {
                        $errors[] = "パスワードが長すぎます。";
                    } else if ($values['password'] !== $values['confirm_password']) {
                        $errors[] = "パスワードが一致しません。";
                    }
                }
            }

            if (isset($values['picture'])) {
                if (strlen($values['picture']['name']) > 0) {
                    if ($values['picture']['error'] === UPLOAD_ERR_FORM_SIZE) {
                        $errors[] = "サイズが" . number_format(self::MAX_PICTURE_SIZE) . "MBを超えています。";
                    } else if ($values['picture']['size'] > self::MAX_PICTURE_SIZE) {
                        $errors[] = "不正な操作です。";
                    } else {
                        // 画像ファイルのMIMEタイプチェック
                        $posted_picture = $values['picture']['tmp_name'];
                        $finfo = new finfo(FILEINFO_MIME_TYPE);
                        $picture_type = $finfo->file($posted_picture);

                        $vaild_picture_types = [
                            'image/png',
                            'image/gif',
                            'image/jpeg'
                        ];

                        if (!in_array($picture_type, $vaild_picture_types)) {
                            $errors[] = "画像が不正です。";
                        }
                    }
                }
            }

            if (isset($values['comment'])) {
                if ($this->getStringLength($values['comment']) > self::MAX_COMMENT_LENGTH) {
                    $errors[] = "本文は" . self::MAX_COMMENT_LENGTH . "文字以内です。";
                }
            }
        }
        return $errors;
    }

    protected function trimValues($values)
    {
        if (isset($values['name'])) {
            $values['name'] = $this->trimString($values['name']);
        }

        if (isset($values['login_id'])) {
            $values['login_id'] = $this->trimString($values['login_id']);
        }

        if (isset($values['password'])) {
            $values['password'] = $this->trimString($values['password']);
        }

        if (isset($values['new_password'])) {
            $values['new_password'] = $this->trimString($values['new_password']);
        }

        if (isset($values['confirm_password'])) {
            $values['confirm_password'] = $this->trimString($values['confirm_password']);
        }

        if (isset($values['current_password'])) {
            $values['current_password'] = $this->trimString($values['current_password']);
        }

        if (isset($values['comment'])) {
            $values['comment'] = $this->trimString($values['comment']);
        }
        return $values;
    }

    protected function validateAlphaNumeric($string)
    {
        if (!preg_match("/^[a-zA-Z0-9]+$/", $string)) {
            return false;
        } else {
            return true;
        }
    }

    protected function getStringLength($string)
    {
        return mb_strlen($string, 'UTF-8');
    }

    protected function trimString($string)
    {
        return trim(mb_convert_kana($string, 's'));
    }
}