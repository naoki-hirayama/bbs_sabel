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
}