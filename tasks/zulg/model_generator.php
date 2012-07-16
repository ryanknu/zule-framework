<?php

namespace Zulg;

require_once 'zulg/generator.php';
require_once 'zulg/model_table.php';
require_once 'zulg/model_settings.php';

define('ZULG_MODEL_TPL', '../generator/model.tpl');
define('ZULG_GATEWAY_TPL', '../generator/gateway.tpl');

class ModelGenerator
{
    // The name of the model to generate
    private $modelName;
    
    // The generator object
    private $generator;
    
    // The settings object
    private $settings;
    
    public function __construct()
    {
        $this->settings = new ModelSettings();
    }
    
    public function generate()
    {
        $namespace = \Zule\Tools\Config::zc()->framework->application_namespace;
        $system = 'Zule';
        
        $tables = $this->settings->getTables();
        foreach ($tables as $table)
        {
            $s = new \Smarty;
            
            $tableName = $table->getName();
            
            // generate model
            $s->assign('model_name', $_POST["class_$tableName"]);
            $s->assign('namespace', $namespace);
            $s->assign('system', $system);
            $s->assign('class_name', $_POST["class_$tableName"]);
            $s->assign('extend_path', '\\Zule\\Models\\Model');
            $s->assign('impl_date', date('Y-m-d H:i:s'));
            $s->assign('use_unsafe_setters', 0);
            $s->assign('table_name', $tableName);
            
            $columns = $table->getColumns();
            $s->assign('columns', $columns);
            $s->assign('primary_key', $table->getPrimaryKey());
            
            $gen = new Generator("../models/" . $_POST["class_$tableName"] . '.php');
            $gen->generate($s, 'model');
            
            $gen = new Generator("../models/Data/" . $_POST["class_$tableName"] . '.php');
            $gen->generate($s, 'gateway');
        }
    }
}
