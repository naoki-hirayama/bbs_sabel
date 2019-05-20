<?php

define('FIXED_SALT', '1416aec6f122c82c9726760a1c1a49823a37c29f');
define('STRETCH_COUNT', 2500);

function get_salt($id)
{
  // IDがない時に速度が変わってしまう対策
  if ($id === null) {
    $id = sha1(microtime());
  }

  return $id . pack('H*', FIXED_SALT);
}

function get_password_hash($id, $password)
{
  $salt = get_salt($id);
  $hash = '';
  for ($i = 0; $i < STRETCH_COUNT; $i++) {
    $hash = hash('sha256', $hash . $password . $salt);
  }
  return $hash;
}
