<?php

require_once '../tools/Loader.php';

$s = new Smarty;

function camel($name)
{
    $name{0} = strtoupper($name{0});
    while ( $pos = strpos( $name, '_' ) )
    {
        $f = substr($name, 0, $pos);
        $l = substr($name, $pos + 1);
        $l{0} = strtoupper($l{0});
        $name = $f . $l;
    }
    return $name;
}

$db = \Zule\Tools\DB::zdb();
$dbTables = $db->listTables();
$tables = [];
$names = [];
foreach ($_POST as $table => $useless)
{
    if (in_array($table, $dbTables))
    {
        $names[$table] = camel($table);
        $tables[$table] = [];
        foreach ( $db->describeTable($table) as $column )
        {
            $tables[$table][] = $column['COLUMN_NAME'];
        }
    }
}

$s->assign('tables', $tables);
$s->assign('names', $names);

$s->display('models2.tpl');
