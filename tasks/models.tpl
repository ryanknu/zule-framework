<h2>Models in your application</h2>
<p>{foreach from=$models item=model}{$model}<br />{/foreach}</p>
<h2>Create a new model</h2>
<p>Tables listed from your default database configuration. <br />
There is currently no support for choosing an alternate configuration.</p>
<form method="POST" action="models2.php">
    {foreach from=$tables item=table}
    <input name="{$table}" value="1" type="checkbox" /> {$table}<br />
    {/foreach}
    <input type="Submit" value="Start" />
</form>