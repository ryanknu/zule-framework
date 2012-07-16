<h2>Create Models</h2>
<p>Select tables from which to generate models.<br />
Select columns for which to make getter and setter methods.</p>
<form method="POST" action="generate_models2.php">
{foreach from=$tables item=descr key=table}
<p>Selected Table: <input type="checkbox" name="{$table}" value="1" checked /> <strong>{$table}</strong><br />
Class name: <input type="text" name="class_{$table}" value="{$names[$table]}" /></p>

<div>
{foreach from=$descr item=column}
<input type="checkbox" name="{$table}.{$column}" value="1" checked /> {$table}.{$column} <br />
{/foreach}
</div>

{/foreach}
<p>Options</p>
<p><input type="checkbox" name="make_gateway" /> Generate load/save gateway</p>
<input type="submit" value="create models" />
</form>