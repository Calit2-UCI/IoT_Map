{include file="header.tpl"}

	<div class="edit-form n150">
		<div class="row header">Module info</div>
		<div class="row">
			<div class="h"><b>Module:</b></div>
			<div class="v">{$module.module_gid}</div>
		</div>
		<div class="row zebra">
			<div class="h"><b>Description:</b></div>
			<div class="v"><b>{$module.module_name}</b><br>{$module.module_description}</div>
		</div>
		<div class="row">
			<div class="h"><b>Version:</b></div>
			<div class="v">{$module.version}</div>
		</div>
		<div class="row zebra">
			<div class="h"><b>Added:</b></div>
			<div class="v">{$module.date_add}</div>
		</div>
		<div class="row">
			<div class="h"><b>Latest update:</b></div>
			<div class="v">{if $module.date_update ne '0000-00-00 00:00:00'}{$module.date_update}{else}No updates{/if}</div>
		</div>
		<div class="row zebra">
			<div class="h"><b>Changes since latest update:</b></div>
			<div class="v">
				{foreach item=item from=$files_changes}
				{$item.date} {$item.file}<br>
				{foreachelse}
				no changed files
				{/foreach}
			</div>
		</div>
		<div class="row">
			<div class="h"><b>Backup:</b></div>
			<div class="v">
				<a href="{$site_url}admin/install/generate_module_files_backup/{$module.module_gid}">Files backup</a><br>
				<a href="{$site_url}admin/install/generate_install_module_settings/{$module.module_gid}">Module settings (langs & settings)</a>
			</div>
		</div>
		<br>
		<div class="row header">Update info</div>
		<div class="row">
			<div class="h"><b>Description:</b></div>
			<div class="v"><b>{$update.update_name}</b><br>{$update.update_description}</div>
		</div>
		<div class="row zebra">
			<div class="h"><b>Old version:</b></div>
			<div class="v">{$update.version_from}</div>
		</div>
		<div class="row">
			<div class="h"><b>New version:</b></div>
			<div class="v">{$update.version_to}</div>
		</div>
		<div class="row zebra">
			<div class="h"><b>Files to be updated:</b></div>
			<div class="v">
				{foreach item=item from=$update.files}
				{$item.2}<br>
				{foreachelse}
				{/foreach}
			</div>
		</div>
		<div class="row">
			<div class="h"><b>Base update:</b></div>
			<div class="v">{if $update.base_update}Yes{else}No{/if}</div>
		</div>
		<div class="row zebra">
			<div class="h"><b>Languages update:</b></div>
			<div class="v">{if $update.lang_update}Yes{else}No{/if}</div>
		</div>
		<div class="row">
			<div class="h"><b>Dependencies:</b></div>
			<div class="v">
				{foreach item=item key=key from=$update.dependencies}
					{$key} V{$item.version}
					{if $item.installed_version == 0}
						<font style="color: red">(Not installed)</font>
					{else}
						<font {if $item.installed_version < $item.version} style="color: red"{/if}>
							(V{$item.installed_version} installed)
						</font>
					{/if}<br>
				{foreachelse}No dependencies
				{/foreach}
			</div>
		</div>
	</div>
{if $update.allow_to_install}
<div class="btn"><div class="l"><a href="{$site_url}admin/install/module_update/{$module.module_gid}/{$update_path}">Install update</a></div></div>
{/if}
<a class="cancel" href="{$site_url}admin/install/updates">{l i='btn_cancel' gid='start'}</a>

<div class="clr"></div>

{include file="footer.tpl"}