{if $count_active > 1}
<select onchange="location.href = '{$site_url}languages/change_lang/'+this.value">
{foreach item=item from=$languages}{if $item.status eq '1'}<option value="{$item.id}" {if $item.id eq $current_lang} selected{/if}>{$item.name}</option>{/if}{/foreach}
</select>
{/if}