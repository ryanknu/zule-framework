{$php_open}

$path = ROOT;

// ZEND
$ptz = '{$ptz}';
if ( $ptz )
    $path .= ( PATH_SEPARATOR . $ptz );

// Smarty
$pts = '{$pts}';
if ( $pts )
    $path .= ( PATH_SEPARATOR . $pts );

set_include_path(get_include_path() . PATH_SEPARATOR . $path);

