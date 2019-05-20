<?php

class Forms_Users extends Form_Model
{
  protected $displayNames = array(
    'name'             => '名前',
    'login_id'         => 'ログインID',
    'password'         => 'パスワード',
    'confirm_password' => 'パスワード(確認)',
  );

  protected $validators = array(
      'login_id'                  => array('alnum', 'validateLoginIdLength'),
      'password,confirm_password' => array('same'),
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
}
