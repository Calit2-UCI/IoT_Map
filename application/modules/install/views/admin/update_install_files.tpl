<h3>Installing: {$module.update_name}</h3>
<div>{$module.update_description}</div>
<br>
<div class="bar-level2" id="module_bar"><div class="bar" style="width: {$current_module_percent}%">{$current_module_percent}%</div></div>
<br>
<b>Files</b><br><br>
{if $skip}
	<script>
	{literal}$(function(){{/literal}
		product_install.delayed_request('{$next_step}');
	{literal}});{/literal}
	</script>
{else}
	{if $errors}
	<div>{foreach item=item from=$errors}{$item}{/foreach}</div>
	<div class="filter-form">
		<h3>Please copy files into appliation/modules/{$module.module}, create folders and click "Refresh"</h3>
		<div class="form">
			<div class="row">
				<div class="h">Files:</div>
				<div>{foreach item=item from=$module.files}updates/{$path}/{$item.2}<br>{/foreach}</div>
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="button" onclick="javascript: product_install.request('files');" name="refresh_module" value="Refresh"></div></div>
	<div class="btn"><div class="l"><input type="button" onclick="javascript: product_install.request('chmod');" name="skip_module" value="Skip"></div></div>
	<div class="clr"></div>
	{else}
	<script>
	{literal}$(function(){{/literal}
		product_install.delayed_request('{$next_step}');
	{literal}});{/literal}
	</script>
	{/if}
{/if}
