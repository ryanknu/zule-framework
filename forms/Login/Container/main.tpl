<form action="{$action}" method="POST">
{foreach from=$form_components item=element}
{$element['html']}
{if $element['type'] <> 207}<br />{/if}
{/foreach}
</form>
