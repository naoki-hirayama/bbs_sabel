<?php

class BaseModel extends Sabel_Db_Model
{
    public function show($key)
    {
        $method = 'show' . ucfirst($key);

        if (method_exists($this, $method)) {
            return $this->$method();
        } else {
            return $this->$key;
        }
    }
}
