<?php

date_default_timezone_set('America/Chicago');

$needsVital = !file_exists('../config/vital.php');

if ( $needsVital )
{
    require('config_vital.php');
}
else
{
    require '../tools/Main.php';
    $s = new \Smarty;
    
    try
    {
        $cnf = new \Zule\Tools\Config;
        header('Location:data_store.php?file=' . $cnf->getFilename());
    }
    catch (Exception $e)
    {
        // no config!
        require 'zulg/data_store.php';
        $ds = new Zulg\DataStore;
        $ds->setData([
            'namespace' => 'Zule',
            'index:controller' => 'IndexController',
            'index:action' => 'IndexAction',
            'dev' => 'yes',
            'mysql' => [
                'host' => '127.0.0.1',
                'port' => '3307',
                'user' => 'root',
                'pass' => 'root',
            ],
            'redis' => [
                'host' => '127.0.0.1',
                'port' => '6379',
                'password' => '',
            ],
        ]);
        $ds->saveJson();
        $ds->setFilename('../config/config.json');
        $ds->writeFile();
        header('Location:data_store.php?file=../config/config.json');
    }
    
    require 'zulg/data_store.php';
    $ds = new Zulg\DataStore;
    $s->display('config.tpl');
}
