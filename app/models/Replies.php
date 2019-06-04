<?php

class Replies extends Sabel_Db_Model
{
    const MAX_PASSWORD_LENGTH  = 15;
    const MIN_PASSWORD_LENGTH  = 4;
    const MAX_NAME_LENGTH      = 10;
    const MAX_COMMENT_LENGTH   = 50;
    const MAX_PICTURE_SIZE     = 1 * 1024 * 1024;

    public static function getSelectColorOptions()
    {
        return ['black' => '黒', 'red' => '赤', 'blue' => '青', 'yellow' => '黄', 'green' => '緑'];
    }

    public static function fetchReplyCountByPostIds($post_ids)
    {
        if (!is_empty($post_ids)) {
            $tmp = db_query("SELECT post_id, COUNT(*) AS cnt FROM replies WHERE post_id IN (" . implode(',', $post_ids) . ") GROUP BY post_id");

            if (!is_empty($tmp)) {
                return array_column($tmp, 'cnt', 'post_id');
            }
        }
        return null;
    }

    public static function fetchByPostIds($post_ids)
    {
        $replies = finder('Replies')
            ->in('post_id', $post_ids)
            ->fetchAll();
        return $replies;
    }

    public static function fetchByUserId($user_id)
    {
        $replies = finder('Replies')
            ->eq('user_id', $user_id)
            ->fetchAll();
        return $replies;
    }

    public static function fetchByPostId($post_id)
    {
        $replies = finder('Replies')
            ->eq('post_id', $post_id)
            ->fetchAll();
        return $replies;
    }
}