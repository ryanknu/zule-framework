<?php

require 'zulg/data_store.php';
require '/Users/ryan/apache/library/Smarty/Smarty.class.php';

$ds = new Zulg\DataStore();

$ds->readFile('this.json');
echo $ds->toForm();