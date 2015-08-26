{include file="header.tpl"}
<div class="filter-form">
	{l i='text_module_select' gid='seo_advanced'}:
	<select name="module_gid" onchange="javascript: reload_this_page(this.value);">
	{foreach item=item key=key from=$modules}<option value="{$item.module_gid}" {if $module_gid eq $item.module_gid}selected{/if}>{$item.module_name} ({$item.module_gid})</option>{/foreach}
	</select>
</div>
{if $default_settings}
<table cellspacing="0" cellpadding="0" class="data">
<tr>
	<th class="first">{l i='target_field' gid='seo_advanced'}</th>
	<th class="w30">&nbsp;</th>
</tr>
{foreach item=item key=key from=$default_settings}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first">{$site_url}{$module_gid}/{$key}{if $item.url}<br>(<b>{l i='rewrite_url' gid='seo_advanced'}: </b><i>{$site_url}{$item.url|truncate:50:'...':true}</i>){/if}</td>
	<td class="icons">
		<a href="{$site_url}admin/seo_advanced/edit/{$module_gid}/{$key}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_settings' gid='seo_advanced'}" title="{l i='link_edit_settings' gid='seo_advanced'}"></a>
	</td>
</tr>
{/foreach}
</table>
{/if}
{include file="pagination.tpl"}

<script type="text/javascript">
var reload_link = "{$site_url}admin/seo_advanced/listing/";
{literal}
function reload_this_page(value){
	var link = reload_link + value ;
	location.href=link;
}
{/literal}
</script>
{include file="footer.tpl"}
