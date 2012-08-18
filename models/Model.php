<?php

namespace Zule\Models;
use Zule\Tools\Mysql;

class Model
{
    protected $awake = no;
    protected $mysql = null;
    protected $modelTable = '';
    protected $primaryKey = '';
    
    // Tell the computer to always insert a row instead of update.
    private $forceInsert = no;
    
    public static function find($table, $arg)
    {
        $my = new Mysql;
        
        $where = [];
        if ( is_array($arg) )
        {
            foreach($arg as $col => $val)
            {
                $where[] = substr($col, 1) . ' = ' . $col;
            }
            
            $where = '(' . implode(') AND (', $where) . ')';
        }
        else
        {
            $where = $arg;
            $arg = [];
        }
        
        $q = "SELECT * FROM `$table` WHERE $where";
        $my->query($q, $arg);
        
        $results = [];
        while ($obj = $my->getObject(get_called_class()))
        {
            $results[] = $obj;
        }
        return $results;
    }
    
    public static function makeFromQuery($query, $args)
    {
    	$my = new Mysql;
    	$my->query($query, $args);
    	$model = $my->getObject(get_called_class());
    	if ( !$model )
    	{
    		$class = get_called_class();
    		$model = new $class;
    	}
    	return $model;
    }
    
    public function forceInsert()
    {
        $this->forceInsert = yes;
    }
    
    public function save()
    {
    	$my = new Mysql;
    	$data = $this->getData();
    	$pkKey = ':' . $this->primaryKey;
    	if ( $data[$pkKey] && !$this->forceInsert )
    	{
    		// UPDATE
			$where = $this->primaryKey . ' = :primary_key';
			$pkVal = $data[$pkKey];
			unset($data[$this->pkKey]);
			
			$set = '';
			foreach ( $data as $col => $val )
			{
			    $colName = substr($col, 1);
				$set .= ",`$colName` = $col";
			}
			$set = substr($set, 1);
			
			$q = "UPDATE `{$this->modelTable}` SET $set WHERE $where";
			
			$data[':primary_key'] = $pkVal;
			$my->query($q, $data);
        }
        else
        {
        	// INSERT
        	if ( !$this->forceInsert )
        	{
        	    // We will let Auto_Increment handle this
        	    unset($data[$pkKey]);
        	}
        	else
        	{
        	    $where = $this->primaryKey . ' = :primary_key';
        	    $q = "DELETE FROM `{$this->modelTable}` WHERE $where";
        	    $my->query($q, [':primary_key' => $data[$pkKey]]);
        	}
        	$cols = $vals = '';
        	foreach( $data as $col => $val )
        	{
        		$colName = substr($col, 1);
        		$cols .= ",`$colName`";
        		$vals .= ", $col ";
        	}
        	$cols = substr($cols, 1);
        	$vals = substr($vals, 1);
        	
        	$q = "INSERT INTO `{$this->modelTable}` ($cols) VALUES ($vals)";
        	$my->query($q, $data);
        }
    }
    
    public function getData()
    {
    	return [];
    }
}
