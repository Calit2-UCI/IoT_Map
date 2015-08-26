	<h3>The site is successfully updated! </h3>
	<br><br>
	<div class="btn"><div class="l"><input type="button" onclick="javascript: location.href='{$site_url}admin/install/product_updates';" name="finish_install" value="Finish"></div></div>
	<div class="clr"></div>
<script>
{literal}$(function(){{/literal}
	product_install.update_overall_progress({$current_overall_percent});
{literal}});{/literal}
</script>