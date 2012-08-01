<?php

namespace Zulg;

class ModelSettings
{
    // Determines if we should use Redis or MySQL
    private $useRedis;
    
    // Determines if the generator should add in generic setXXX($val)
    // methods. These are not typically required to make most applications
    // and can foster bad habits, moving logic to places that work but may
    // not be logical (exposing internal data to the public). Seeing it
    // is one thing, allowing anyone to set it is another.
    private $useUnsafeSetters;
    
    // Tables array contains table information for tables to generate.
    private $tables;
    
    // Redis columns
    private $redisColumns;
    
    public function __construct()
    {
        $this->useRedis = true;
        $this->useUnsafeSetters = false;
        $this->tables = [];
        $this->redisColumns = [];
        $this->awaken();
    }
    
    public function awaken()
    {
        // should we use Redis?
        $this->useRedis = isset($_POST['use_redis']);
        
        if ( !$this->useRedis )
        {
            // SQL not supported with Zend DB adapter anymore.
            // get list of tables
            $db = \Zule\Tools\DB::zdb();
            $dbTables = $db->listTables();
            foreach( $dbTables as $table )
            {
                if ( in_array( $table, array_keys($_POST) ) )
                {
                    $this->tables[] = new ModelTable($table);
                }
            }
            return $this;
        }
        else
        {
            // not sure what to do here.
            return $this;
        }
    }
    
    public function useSql()
    {
        return !$this->useRedis;
    }
    
    public function getTables()
    {
        return $this->tables;
    }
}
