<?php

namespace Zule\Models\Data;

require_once 'Zend/Db/Table/Abstract.php';

class Table extends \Zend_Db_Table_Abstract
{
    protected $model;
    protected $connection = '';
    protected $awake = no;
    
    public function __construct($object)
    {
        $this->model = $object;
        $adapter = \Zule\Tools\DB::adapter($this->connection);
		parent::__construct($adapter);
    }
}
