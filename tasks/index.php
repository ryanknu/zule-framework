<?php


$needsVital = !file_exists('../config/vital.php');

if ( $needsVital )
{
    require('config_vital.php');
    die;
}


?>
<a href="config.php">Config</a><br />
<a href="controllers.php">Controllers</a><br />
<a href="models.php">Models</a><br />

