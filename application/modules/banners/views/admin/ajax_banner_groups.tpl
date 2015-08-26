{if $groups}
<div class="row">
	<div class="h">{l i='field_groups' gid='banners'}: </div>
	<div class="v">
	{foreach from=$groups item=group}
		<input type="checkbox" name="banner_groups[]" value="{$group.id}" {if $banner_groups and $group.id|in_array:$banner_groups}checked{/if} {if $groups_disabled}disabled{/if} id="groups_{$group.id}" /><label  for="groups_{$group.id}">{$group.name}</label><br/>
	{/foreach}
	</div>
</div>
{/if}
