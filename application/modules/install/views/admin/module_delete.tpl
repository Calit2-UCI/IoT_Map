{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">Uninstall module</div>
		<div class="row">
			<div class="h"><b>Module:</b></div>
			<div class="v">{$module.module}</div>
		</div>
		<div class="row zebra">
			<div class="h"><b>Description:</b></div>
			<div class="v"><b>{$module.install_name}</b><br>{$module.install_descr}</div>
		</div>
		<div class="row">
			<div class="h"><b>Version:</b></div>
			<div class="v">{$module.version}</div>
		</div>
		{if $deinstalled}
		<div class="row zebra">
			<div class="h"><b>Files to be deleted:</b></div>
			<div class="v">
				{foreach item=item from=$module.files}
				{$item.2}<br>
				{/foreach}
			</div>
		</div>
		{if $messages}
		<div class="row">
			<div class="h"><b>Messages:</b></div>
			<div class="v">
				{foreach item=item from=$messages}
				{$item}<br>
				{/foreach}
			</div>
		</div>
		{/if}
		{else}
		<div class="row zebra">
			<div class="h"><b>Dependent modules:</b></div>
			<div class="v">
				{foreach item=item from=$depend_modules}<b>{$item.module}</b> ({$item.install_name})<br>
				{foreachelse}No dependencies<br>{/foreach}
				<br>A module can only be removed if it does not have a dependent module
			</div>
		</div>
		<div class="row">
			<div class="h"><b>Save module settings:</b></div>
			<div class="v">
				If default settings (e.g. languages) were changed, you can back up your changes.<br>
				<a href="{$site_url}admin/install/generate_install_module_settings/{$module.module}">Backup</a>
			</div>
		</div>
		{/if}
	</div>
	{if $deinstalled}

	{elseif $depend_modules}
	<div class="btn gray"><div class="l"><input type="button" name="submit_btn" value="Delete" disabled></div></div>
	{else}
	<div class="btn"><div class="l"><input type="submit" name="submit_btn" value="Delete" onclick="if(!confirm('Are you sure you want to uninstall the module?')) return false"></div></div>
	{/if}
	<a class="cancel" href="{$site_url}admin/install/modules">Сancel</a>
</form>
<div class="clr"></div>


{include file="footer.tpl"}