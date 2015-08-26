<select name="{$properties_helper_data.name}{if $properties_helper_data.multi}[]{/if}" {if $properties_helper_data.multi}multiple{/if}>
{if $properties_helper_data.empty_option}<option value="0">...</option>{/if}
{foreach item=item key=key from=$properties_helper_data.options}
<option value="{$key}"{if $properties_helper_data.selected[$key] eq '1'}selected{/if}>{$item}</option>
{/foreach}
</select>
