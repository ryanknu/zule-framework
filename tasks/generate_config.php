<?php

echo '<pre>';

mkdir('../tools/config');

// FIND SMARTY
$path = get_include_path();
$path .= ( PATH_SEPARATOR . $_POST['pts'] );
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once 'Smarty.class.php';

echo 'created directory ../tools/config' . PHP_EOL;

$s = new \Smarty;
foreach ($_POST as $key => $value)
{
    $s->assign($key, $value);
}
$s->assign('php_open', '<?php');

echo 'Generating config.xml file' . PHP_EOL;

$string = $s->fetch('new_config.tpl');

echo "Gateway file generated." . PHP_EOL;
        
$destFile = "../tools/config/config.xml";

echo "Creating file $destFile" . PHP_EOL;

$fh = fopen($destFile, 'x');

echo "created file $destFile" . PHP_EOL;

fwrite($fh, $string);
fclose($fh);

$bytes = strlen($string);
echo "wrote $bytes bytes to file $destFile" . PHP_EOL;



echo 'Generating libraries.php file' . PHP_EOL;

$string = $s->fetch('new_library.tpl');

echo "Gateway file generated." . PHP_EOL;
        
$destFile = "../tools/config/libraries.php";

echo "Creating file $destFile" . PHP_EOL;

$fh = fopen($destFile, 'x');

echo "created file $destFile" . PHP_EOL;

fwrite($fh, $string);
fclose($fh);

$bytes = strlen($string);
echo "wrote $bytes bytes to file $destFile" . PHP_EOL;

echo PHP_EOL . 'Completed generating config';
