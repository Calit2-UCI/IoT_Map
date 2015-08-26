{include file="header.tpl"}
<form method="post" action="{$langs.action}" name="save_form">
	<table class="data" width="100%" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<th class="first">Name</th>
			<th class="w150">Status</th>
			<th class="w50">Install</th>
			<th class="w50">Uninstall</th>
			<th class="w50">Export</th>
			<th class="w50">Update</th>
		</tr>
		{foreach item=lang key=key from=$langs}
		{counter print=false assign=counter}
		<tr{if $counter is div by 2} class="zebra"{/if}>
			<td class="first">
				{if $lang.is_default}<b>{$lang.name} (default)</b>{else}{$lang.name}{/if}
			</td>
			<td class="center">
				{if !$lang.setup}Not installed
				{elseif $lang.setup && !$lang.update}Created manually
				{else}Installed{/if}</td>
			<td class="center">
				{if !$lang.setup}
					<a href="{$site_root}admin/install/langs/install/{$key}"><img width="16" height="16" border="0" alt="Install lang" src="{$site_root}{$img_folder}/icon-add.png"></a>
				{/if}
			</td>
			<td class="center">
				{if $lang.setup && $lang.update && !$lang.is_default && $langs_count > 1}
					<a href="{$site_root}admin/install/langs/delete/{$lang.id}"><img width="16" height="16" border="0" alt="Uninstall lang" src="{$site_root}{$img_folder}/icon-clear.png"></a>
				{/if}
			</td>
			<td class="center">
				{if $lang.setup}
					<a href="{$site_root}admin/install/langs/export/{$lang.id}"><img width="16" height="16" border="0" alt="Export lang" src="{$site_root}{$img_folder}/icon-export.png"></a>
				{/if}
			</td>
			<td class="center">
				{if $lang.setup && $lang.update}
					<a href="{$site_root}admin/install/langs/update/{$lang.id}"><img width="16" height="16" border="0" alt="Update lang" src="{$site_root}{$img_folder}/icon-refresh.png"></a>
				{/if}
			</td>
		</tr>
		{/foreach}
	</tbody>
	</table>
</form>
<div class="clr"></div>

{include file="footer.tpl"}
