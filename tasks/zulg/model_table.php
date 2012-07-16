<?php

namespace Zulg;

class ModelTable
{
    // Table name corresponds to a table name in the database.
    private $tableName;
    
    // Columns refer to the columns for which code should be generated.
    private $columns;
    
    // Primary keys are the columns on which the table is primary keyed.
    private $primaryKeys;
    
    public function __construct($tableName)
    {
        $this->tableName = $tableName;
        $this->columns = [];
        $this->primaryKeys = [];
        $this->awaken();
    }
    
    public function awaken()
    {
        // Get database columns & primary keys
        $db = \Zule\Tools\DB::zdb();
        foreach ( $db->describeTable($this->tableName) as $column )
        {
            if (in_array($this->tableName . '_' . $column['COLUMN_NAME'], 
                array_keys($_POST)))
            {
                // column wants getters/setters
                $col = $column['COLUMN_NAME'];
                $this->columns[$col] = [
                    'name' => $col,
                    'camel' => camel($col),
                    'l_camel' => lCamel($col),
                ];
                if ( $column['PRIMARY'] )
                {
                    $this->primaryKeys[] = $col;
                }
            }
            else if ( $column['PRIMARY'] )
            {
                // no getter/setter set for primary, but gateway still exists
                $this->primaryKeys[] = $column['COLUMN_NAME'];
            }
        }
        
        return $this;
    }
    
    public function getName()
    {
        return $this->tableName;
    }
    
    public function getColumns()
    {
        return $this->columns;
    }
    
    // RK: for now, only supports non-composite pks
    public function getPrimaryKey()
    {
        return $this->primaryKeys[0];
    }
}
