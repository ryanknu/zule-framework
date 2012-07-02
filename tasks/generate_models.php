<?php

require_once '../tools/Loader.php';

echo '<pre>';

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

function lCamel($name)
{
    while ( $pos = strpos( $name, '_' ) )
    {
        $f = substr($name, 0, $pos);
        $l = substr($name, $pos + 1);
        $l{0} = strtoupper($l{0});
        $name = $f . $l;
    }
    return $name;
}

$namespace = \Zule\Tools\Config::zc()->framework->application_namespace;
$system = 'Zule';

$db = \Zule\Tools\DB::zdb();
$dbTables = $db->listTables();
foreach ($_POST as $table => $useless)
{
    if (in_array($table, $dbTables))
    {
        echo "Generating model file for $table" . PHP_EOL;
        
        // $table is scheduled for generation
        $s = new \Smarty;
        $model = $_POST["class_$table"];
        $s->assign('model_name', $model);
        $s->assign('namespace', $namespace);
        $s->assign('system', $system);
        $s->assign('php_open', '<?php');
        $columns = [];
        $camels = [];
        $lCamels = [];
        foreach ( $db->describeTable($table) as $column )
        {
            if (in_array($table . '_' . $column['COLUMN_NAME'], array_keys($_POST)))
            {
                // column wants getters/setters
                $col = $column['COLUMN_NAME'];
                $columns[] = $col;
                $camels[$col] = camel($col);
                $lCamels[$col] = lCamel($col);
                if ( $column['PRIMARY'] )
                {
                    $primaryKey = $col;
                }
            }
            else if ( $column['PRIMARY'] )
            {
                // no getter/setter set for primary, but gateway still exists
                $primaryKey = $column['COLUMN_NAME'];
            }
        }
        $s->assign('generate_gateway', isset($_POST['make_gateway'])?yes:no);
        $s->assign('columns', $columns);
        $s->assign('camels', $camels);
        $s->assign('lCamels', $lCamels);
        
        $string = $s->fetch('new_model.tpl');
        
        echo "Model file generated." . PHP_EOL;
        
        $destFile = "../models/$model.php";
        
        echo "Creating file $destFile" . PHP_EOL;
        
        $fh = fopen($destFile, 'x');
        
        echo "created file $destFile" . PHP_EOL;
        
        fwrite($fh, $string);
        fclose($fh);
        
        $bytes = strlen($string);
        echo "wrote $bytes bytes to file $destFile" . PHP_EOL;
        
        if ( isset($_POST['make_gateway']) )
        {
            echo "generating gateway template for $table" . PHP_EOL;
            $s = new \Smarty;
            $s->assign('model_name', $model);
            $s->assign('namespace', $namespace);
            $s->assign('system', $system);
            $s->assign('primary_key', $primaryKey);
            $s->assign('columns', $columns);
            $s->assign('camels', $camels);
            $s->assign('table_name', $table);
            $s->assign('php_open', '<?php');
            $string = $s->fetch('new_data.tpl');
            echo "Gateway file generated." . PHP_EOL;
        
            $destFile = "../models/Data/$model.php";
            
            echo "Creating file $destFile" . PHP_EOL;
            
            $fh = fopen($destFile, 'x');
            
            echo "created file $destFile" . PHP_EOL;
            
            fwrite($fh, $string);
            fclose($fh);
            
            $bytes = strlen($string);
            echo "wrote $bytes bytes to file $destFile" . PHP_EOL;
            
        }
        
        echo "fully generated files for $table" . PHP_EOL;
    }
} 

echo PHP_EOL;
echo 'Model generation completed' . PHP_EOL;
echo '</pre>';
