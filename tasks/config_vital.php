<?php

if (!file_exists('../config'))
{
    mkdir('../config');
}

$error = '';
if (isset($_POST['pts']))
{
    $pts = $_POST['pts'];
    if ( file_exists($pts) )
    {
        require $pts;
        if ( class_exists( 'Smarty' ) )
        {
            $out = '<?php require \'' . $pts . '\';';
            $file = new \SplFileObject('../config/vital.php', 'w');
            $bytes = $file->fwrite($out);
            header('Location:config.php');
        }
    }
    $error = 'Something wasn\'t right';
}

?>

<h2>Vital Config</h2>
<p>Welcome to your ZF powered site. I have determined that you do not have
a vital config file. This file is required so that I can find Smarty. Even
if Smarty is in your include path, I'd really like you to fill out the vital
config form and we can move on.</p>
<p><?php echo $error; ?></p>
<form action="config_vital.php" method="POST">

Full path to Smarty: <input type="text" name="pts" /><br />
<input type="submit" />
</form>
