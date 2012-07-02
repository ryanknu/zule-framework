<?php

require_once '../tools/Loader.php';

$s = new Smarty;
$s->assign('controllers', glob('../controllers/*'));

$s->display('controllers.tpl');
