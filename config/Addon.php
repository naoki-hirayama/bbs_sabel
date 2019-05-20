<?php

class Config_Addon implements Sabel_Config
{
    public function configure()
    {
        return [
            'acl',
            'extroller',
            'renderer',
        ];
    }
}
