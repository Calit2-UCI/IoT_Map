<h3>Installing: {$module.install_name} V{$module.version}</h3>
<div>{$module.install_descr}</div>
<br>
<div class="bar-level2" id="module_bar"><div class="bar" style="width: {$current_module_percent}%">{$current_module_percent}%</div></div>
<br>
<b>Check files permissions</b><br><br>
{if $errors}
<div>
	{foreach item=item from=$errors}<b>{$item.file}</b> {$item.msg}<br>{/foreach}
</div>
<div class="btn"><div class="l"><input type="button" onclick="javascript: product_install.request('permissions');" name="refresh_module" value="Refresh"></div></div>
<div class="clr"></div>
{else}
<script>
{literal}$(function(){{/literal}
	product_install.delayed_request('{$next_step}');
{literal}});{/literal}
</script>
{/if}