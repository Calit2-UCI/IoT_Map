<h3>Installing: {$module.install_name} V{$module.version}</h3>
<div>{$module.install_descr}</div>
<br>
<div class="bar-level2" id="module_bar"><div class="bar" style="width: {$current_module_percent}%">{$current_module_percent}%</div></div>
<br>
<b>Check dependent modules</b><br><br>
{if $errors || $lib_errors}
	{if $errors}
	<h3>Required modules: </h3>
	<div>
		{foreach item=item from=$errors}
		{$item.module_gid} V{$item.module_version} - <font class="req">{$item.info}</font><br>
		{/foreach}
	</div>
	{/if}
	{if $lib_errors}
	<h3>Required libraries: </h3>
	<div>
		{foreach item=item from=$lib_errors}
		<b>{$item.gid}</b> {$item.name}<br>
		{/foreach}
	</div>
	{/if}

	<div class="btn"><div class="l"><input type="button" onclick="javascript: product_install.request('dependencies');" name="refresh_module" value="Refresh"></div></div>
	<div class="clr"></div>

{else}
<script>
{literal}$(function(){{/literal}
	product_install.delayed_request('{$next_step}');
{literal}});{/literal}
</script>
{/if}