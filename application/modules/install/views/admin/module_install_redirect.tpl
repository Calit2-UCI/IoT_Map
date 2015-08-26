Processing...
<script>
{literal}$(function(){{/literal}
	product_install.properties.currentModule='{$current_module}';
	product_install.delayed_request('start_install');
	product_install.update_overall_progress({$current_overall_percent});
{literal}});{/literal}
</script>