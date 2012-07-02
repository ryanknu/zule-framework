<h2>Controllers in your application</h2>
<p>{foreach from=$controllers item=controller}{$controller}<br />{/foreach}</p>
<h2>Create a new controller</h2>
<form method="POST" action="generate_controller.php">
    Controller Name: <input type="text" name="name" /><br />
    Actions (CSV): <input type="text" name="actions" /><br />
    <input type="checkbox" name="make_index" /> Implement index action<br />
    <input type="checkbox" name="make_views" /> Make views for actions<br />
    <input type="submit" value="Generate Controller" />
</form>