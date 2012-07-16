<?php

namespace Zulg;

class ModelSettings
{
    // Determines if we should generate the Models\Data table gateway
    // classes. Provides automatic abstraction of the data layer to the
    // application developer.
    private $makeGateways;
    
    // Determines if the generator should add in generic setXXX($val)
    // methods. These are not typically required to make most applications
    // and can foster bad habits, moving logic to places that work but may
    // not be logical (exposing internal data to the public). Seeing it
    // is one thing, allowing anyone to set it is another.
    private $useUnsafeSetters;
    
    // Tables array contains table information for tables to generate.
    private $tables;
    
    public function __construct()
    {
        $this->makeGateways = true;
        $this->useUnsafeSetters = false;
        $this->tables = [];
        $this->awaken();
    }
    
    public function awaken()
    {
        // should we make gateways or not
        // rk 15 jul: gateways are not optional
        $this->makeGateways = isset($_POST['make_gateway']);
        
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
    
    public function getTables()
    {
        return $this->tables;
    }
}
