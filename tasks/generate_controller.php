<?php

require_once '../tools/Loader.php';

$s = new Smarty;

$namespace = \Zule\Tools\Config::zc()->framework->application_namespace;
$system = 'Zule';
$controller = $_POST['name'];
$addActions = explode(',', $_POST['actions']);
if ( isset($_POST['make_index']) )
{
    $acts = [\Zule\Tools\Config::zc()->framework->index_action];
}
else
{
    $acts = [];
}
$acts = array_merge($acts, $addActions);
if ( isset($_POST['make_views']) )
{
    $s->assign('generateViews', 1);
}
else
{
    $s->assign('generateViews', 0);
}

$s->assign('actions', $acts);
$s->assign('actionString', '\'' . implode('\',\'', $acts) . '\'');
$s->assign('open_php', '<?php');
$s->assign('namespace', $namespace);
$s->assign('system', $system);
$s->assign('controller', $controller);

echo '<pre>';
echo 'Generating controller template...' . PHP_EOL;

$string = $s->fetch('new_controller.tpl');

echo 'Controller template created' . PHP_EOL;

$destFile = "../controllers/$controller.php";
$fh = fopen($destFile, 'x');

echo "created file $destFile" . PHP_EOL;

fwrite($fh, $string);
fclose($fh);

$bytes = strlen($string);
echo "wrote $bytes bytes to file $destFile" . PHP_EOL;

if ( $_POST['make_views'] )
{
    echo 'beginning create views routine' . PHP_EOL;
    
    $dir = "../views/$controller";
    mkdir($dir);
    $dir .= '/';
    
    echo "created directory $dir" . PHP_EOL;
    
    foreach($acts as $act)
    {
        $destFile = $dir . $act . '.tpl';
        $srcFile = './new_view.tpl';
        
        if ( copy($srcFile, $destFile) )
        {
            echo "copied $srcFile to $destFile" . PHP_EOL;
        }
    }
}

echo PHP_EOL;
echo 'Controller generation completed' . PHP_EOL;
echo '</pre>';
