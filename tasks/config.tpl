<h2>Application Configuration</h2>
<p>Note this is a simple configuration that only allows one MySQL database. <br />
You can configure more databases by hand. See /tools/DB.php to see how <br />
adapter connections are configured. The project Model generator can currently<br />
only work with tables stored in the default database.</p>
<form method="POST" action="generate_config.php">
    <p>Environment Paths</p>
    <input type="text" name="pts" /> Path to Smarty<br />
    <input type="text" name="ptz" /> Path to Zend
    
    <p>Application</p>
    <input type="text" name="namespace" /> Namespace <br />
    
    <p>Database settings</p>
    <input type="text" name="host" /> Host <br />
    <input type="text" name="port" /> Port <br />
    <input type="text" name="user" /> Username <br />
    <input type="text" name="pass" /> Password <br />
    <input type="text" name="database" /> Database <br />
    
    <p><input type="Submit" value="generate config" />
</form>