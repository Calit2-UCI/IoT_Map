{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">Module info</div>
		<div class="row">
			<div class="h"><b>Module:</b></div>
			<div class="v">{$module.module}&nbsp;</div>
		</div>
		<div class="row zebra">
			<div class="h"><b>Description:</b></div>
			<div class="v"><b>{$module.install_name}</b><br>{$module.install_descr}&nbsp;</div>
		</div>
		<div class="row">
			<div class="h"><b>Version:</b></div>
			<div class="v">{$module.version}&nbsp;</div>
		</div>
		<div class="row zebra">
			<div class="h"><b>Added:</b></div>
			<div class="v">{$install_data.date_add}&nbsp;</div>
		</div>
		<div class="row">
			<div class="h"><b>Latest update:</b></div>
			<div class="v">{if $install_data.date_update ne '0000-00-00 00:00:00'}{$install_data.date_update}{else}No updates{/if}&nbsp;</div>
		</div>
		<div class="row zebra">
			<div class="h"><b>Files:</b></div>
			<div class="v">
				{foreach item=item from=$module.files}
				{$item.2}<br>
				{/foreach}&nbsp;
			</div>
		</div>
		<div class="row">
			<div class="h"><b>Depends on:</b></div>
			<div class="v">
				{foreach item=item key=key from=$module.dependencies}{$key}<br>
				{foreachelse}No dependencies<br>{/foreach}&nbsp;
			</div>
		</div>
		<div class="row zebra">
			<div class="h"><b>Dependent modules:</b></div>
			<div class="v">
				{foreach item=item from=$depend_modules}<b>{$item.module}</b> ({$item.install_name})<br>
				{foreachelse}No dependencies<br>{/foreach}&nbsp;
			</div>
		</div>
		<div class="row">
			<div class="h"><b>Save module settings:</b></div>
			<div class="v">
				If default settings (e.g. languages) were changed, you can back up your changes.<br>
				<a href="{$site_url}admin/install/generate_install_module_settings/{$module.module}">Backup</a>
			</div>
		</div>
	</div>
</form>
<div class="clr"></div>


{include file="footer.tpl"}