	<h3>The module is successfully updated! </h3>
	<div>
	{foreach item=item from=$messages}
	<b>{$item}</b><br><br>
	{/foreach}
	</div>
	<br><br>
	<div class="btn"><div class="l"><input type="button" onclick="javascript: location.href='{$site_url}admin/install/updates';" name="finish_install" value="Finish"></div></div>
	<div class="clr"></div>
<script>
{literal}$(function(){{/literal}
	product_install.update_overall_progress({$current_overall_percent});
{literal}});{/literal}
</script>