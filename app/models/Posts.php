<?php

class Posts extends Sabel_Db_Model
{
    const MAX_PASSWORD_LENGTH  = 15;
    const MIN_PASSWORD_LENGTH  = 4;
    const MAX_NAME_LENGTH      = 10;
    const MAX_COMMENT_LENGTH   = 100;
    const MAX_PICTURE_SIZE     = 1 * 1024 * 1024;

    public static function getSelectColorOptions()
    {
        return ['black' => '黒', 'red' => '赤', 'blue' => '青', 'yellow' => '黄', 'green' => '緑'];
    }

    public static function fetchPostCountByUserIds($user_ids)
    {
        if (!is_empty($user_ids)) {
            $tmp = db_query("SELECT user_id, COUNT(*) AS cnt FROM posts WHERE user_id IN (" . implode(',', $user_ids) . ") GROUP BY user_id");
            
            if (!is_empty($tmp)) {
                return array_column($tmp, 'cnt', 'user_id');
            }
        }
        return null;
    }
}