<?php

date_default_timezone_set('America/Chicago');

require '../config/vital.php';
require 'zulg/data_store.php';

if ( isset($_GET['file']) )
{    
    $s = new \Smarty;
    $s->assign('file', $_GET['file']);
    $ds = new Zulg\DataStore;
    $ds->readFile($_GET['file']);
    $s->assign('form', $ds->toForm());
    $s->display( 'data_store.tpl' );
}

