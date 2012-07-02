<?php

namespace Zule\Models\Data;

require_once 'Zend/Db/Table/Abstract.php';

class Table extends \Zend_Db_Table_Abstract
{
    protected $model;
    protected $connection = '';
    protected $fieldsToHash = array();
    protected $siteHash = 'U^!K>9k=,oUoO>9&r},"*r[r-mXM-@';
    
    public function __construct($object)
    {
        $this->model = $object;
        $adapter = \Zule\Tools\DB::adapter($this->connection);
		parent::__construct($adapter);
    }
    
    // method that removes invalid columns from an array, based on table 
    public function matchColumns(array $data) {
        $cols = $this->info();
        $fields = [];
        foreach ($cols['cols'] as $col) {
            if (array_key_exists($col, $data))
            {
                $fields[$col] = $data[$col];
            }
        }
        return $fields;
	}
	
	public function insertSafe(array $data)
	{
	    return $this->insert($this->matchColumns($data));
	}
	
	public function insert(array $data) {
		return parent::insert($data);
	}

    public function delete($cond) {
		return parent::delete($cond);
	}
    
    public function updateSafe(array $data, $cond) {
        return $this->update($this->matchColumns($data), $cond);
    }
    
	public function update(array $data,$cond,$affectedNeeded=false) {
		return parent::update($data,$cond);
    }

	public function getTableName() {
		return $this->_name;
	}
}
