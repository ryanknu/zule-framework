<?php

require_once '../tools/Loader.php';

$s = new Smarty;
$s->assign('models', glob('../models/*'));

$db = \Zule\Tools\DB::zdb();

$s->assign('tables', $db->listTables());

$s->display('models.tpl');
