<form action='generate_models2.php' method='POST'>
<input type='hidden' name='use_redis' value='1' />
<input type='text' name='model_name' /> Model Name
<input type='checkbox' name='count_total' checked /> Count all <br />


{section name=foo loop=9} 
<input type='text' name='column[{$smarty.section.foo.iteration}]' /> Column
<input type='checkbox' name='suggest[{$smarty.section.foo.iteration}]' /> Suggestable 
<input type='text' name='suggest_depth[{$smarty.section.foo.iteration}]' value='2' /> Suggest depth <br />
 
{/section}

<input type='submit' />
</form>