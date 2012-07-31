<label for="{$name}">{$label}</label>
<input type="text" name="{$name}" id="{$name}" {if $default}value="{$default}" {/if} />
{$problem}

<script type="text/javascript">
$('#{$name}').blur(function(obj) { zf_form.validate(obj); });
</script>
