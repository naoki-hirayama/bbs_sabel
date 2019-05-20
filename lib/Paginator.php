<?php

/**
 * Paginator
 *
 * @category   DB
 * @package    lib
 * @author     Ebine Yutaka <ebine.yutaka@sabel.jp>
 * @copyright  2004-2008 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Paginator extends Sabel_Object
{
  /**
   * @var object Sabel_Db_Model or Sabel_Db_Join
   */
  protected $model = null;
  
  /**
   * @var boolean
   */
  protected $isJoin = false;
  
  /**
   * @var string
   */
  protected $method = "select";
  
  /**
   * @var array
   */
  protected $attributes = array();
  
  /**
   * @array
   */
  protected $defaultOrder = array();
  
  /**
   * @array or false
   */
  protected $orderColumns = false;
  
  public function __construct($model)
  {
    if (is_string($model)) {
      $model = MODEL($model);
    } elseif ($model instanceof Sabel_Db_Finder) {
      $model = $model->getRawInstance();
    }
    
    if (is_model($model)) {
      $model->autoReinit(false);
    } elseif ($model instanceof Sabel_Db_Join) {
      $model->getModel()->autoReinit(false);
      $this->isJoin = true;
    } else {
      $message = __METHOD__ . "() invalid instance.";
      throw new Sabel_Exception_Runtime($message);
    }
    
    $this->model = $model;
    $this->attributes["pageKey"] = "page";
  }
  
  public function __set($key, $value)
  {
    $this->attributes[$key] = $value;
  }
  
  public function __get($key)
  {
    if (isset($this->attributes[$key])) {
      return $this->attributes[$key];
    } else {
      return null;
    }
  }

  public function __isset($key)
  {
    return isset($this->attributes[$key]);
  }
  
  public function getPageNumber()
  {
    $pageKey = $this->attributes["pageKey"];
    return $this->$pageKey;
  }

  public function getCleanQuery()
  {
    $query = $this->attributes["query"];

    if ($this->orderColumns) {
      foreach ($this->orderColumns as $column) {
        unset($query[$column]);
      }
    }
    return $query;
  }
  
  public function getUriQuery($page, $clean = false)
  {
    $pageKey = $this->attributes["pageKey"];
    if (!isset($this->attributes["uriQuery"])) {
      return "{$pageKey}={$page}";
    } else {
      $query = $this->attributes["query"];
      if (empty($query) === true) {
        return "{$pageKey}={$page}";
      } else {
        if ($clean === true) {
          $query = $this->getCleanQuery();
          return http_build_query($query, "", "&");
        } else {
          return http_build_query($query, "", "&") . "&{$pageKey}=" . $page;
        }
      }
    }
  }
  
  public function setCondition($arg1, $arg2 = null)
  {
    $this->model->setCondition($arg1, $arg2);
    
    return $this;
  }
  
  public function setDefaultOrder($column, $mode = "asc")
  {
    $this->defaultOrder[$column] = $mode;
    
    return $this;
  }
  
  public function setOrderColumns($columns)
  {
    $this->orderColumns = $columns;
    
    return $this;
  }
  
  public function setMethod($method)
  {
    $this->method = $method;
    
    return $this;
  }
  
  public function build($limit, array $getValues = array())
  {
    $page = 1;
    $pageKey = $this->attributes["pageKey"];
    
    if (isset($getValues[$pageKey])) {
      $page = $getValues[$pageKey];
      if (!is_numeric($page) || $page < 1) $page = 1;
    }
    
    $model = $this->model;
    $attributes =& $this->attributes;
    
    unset($getValues[$pageKey]);
    
    if (class_exists("mb", false) && mb::is_mobile()) {
      if ($getValues && ($charset = mb::get_charset()) !== APP_ENCODING) {
        foreach ($getValues as $_k => $_v) {
          if (is_string($_v)) {
            $getValues[$_k] = mb_convert_encoding($_v, $charset, APP_ENCODING);
          }
        }
      }
    }
    
    $attributes["query"] = $getValues;
    $attributes["uriQuery"] = http_build_query($getValues, "", "&");
    $count = ($this->isJoin) ? $model->getCount(false) : $model->getCount();
    
    $attributes["count"] = $count;
    $attributes["limit"] = $limit;
    $attributes["page"]  = $page;
    
    $pager = new Sabel_View_Pager($count, $limit);
    $pager->setPageNumber($page);
    $attributes["viewer"] = new Sabel_View_PageViewer($pager);
    
    if ($count === 0) {
      $attributes["offset"]  = 0;
      $attributes["results"] = array();
      $model->clear();
    } else {
      $offset = $pager->getSqlOffset();
      $this->_setOrderBy($getValues);
      $model->setLimit($limit);
      $model->setOffset($offset);
      
      $attributes["offset"]  = $offset;
      $attributes["results"] = $model->{$this->method}();
    }
    
    return $this;
  }
  
  protected function _setOrderBy($getValues)
  {
    $orderValues  = array();
    $orderColumns = $this->orderColumns;
    
    if ($orderColumns !== false) {
      $oColNum = count($orderColumns);
      
      if ($this->isJoin) {
        $columns = $this->model->getModel()->getColumnNames();
      } else {
        $columns = $this->model->getColumnNames();
      }
      
      foreach ($getValues as $key => $val) {
        if (preg_match('/^[A-Z]/', $key{0}) === 1 && strpos($key, "_") !== false) {
          list ($mname, $cname) = explode("_", $key, 2);
          $key = $mname . "." . $cname;
        } else {
          if (!in_array($key, $columns, true)) continue;
        }
        
        if ($oColNum === 0 || in_array($key, $orderColumns, true)) {
          $orderValues[$key] = $val;
        }
      }
    }
    
    if (empty($orderValues)) {
      if (empty($this->defaultOrder)) {
        return;
      } else {
        $orderValues = $this->defaultOrder;
      }
    }
    
    $model = $this->model;
    
    if (empty($orderColumns)) {
      foreach ($orderValues as $column => $order) {
        $order = strtolower($order);
        if ($order !== "asc" && $order !== "desc") $order = "asc";
        $model->setOrderBy($column, $order);
      }
    } else {
      foreach ($orderColumns as $column) {
        if (!isset($orderValues[$column])) continue;
        
        $order = strtolower($orderValues[$column]);
        if ($order !== "asc" && $order !== "desc") $order = "asc";
        $model->setOrderBy($column, $order);
      }
    }
  }

  public function hasPrev()
  {
    return $this->viewer->hasPrevious();
  }

  public function hasNext()
  {
    return $this->viewer->hasNext();
  }
}
