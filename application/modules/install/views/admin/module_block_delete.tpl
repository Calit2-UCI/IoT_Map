<h3>Deleting {$module.install_name} V{$module.version}</h3>
<div>{$module.install_descr}</div>
<script>
{literal}$(function(){{/literal}
	product_install.delayed_request('{$next_step}');
{literal}});{/literal}
</script>
