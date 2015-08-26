<h3>Installing: {$module.install_name} V{$module.version}</h3>
<div>{$module.install_descr}</div>
<br>
<div class="bar-level2" id="module_bar"><div class="bar" style="width: {$current_module_percent}%">{$current_module_percent}%</div></div>
<br>
<b>Module settings</b><br><br>
{if $settings}
	<form name="settings_submit_form" id="settings-submit-form" method="post">
		<input type="hidden" name="submit_btn" value='1'>
		{$settings}
		<div class="clr"></div>
		<div class="btn"><div class="l"><input type="submit" name="send_btn" value="Save"></div></div>
		<div class="clr"></div>
	</form>
	<script>
		{literal}$(function(){
			$('.form input:first', '#settings-submit-form').focus().select();
			$('#settings-submit-form').bind('submit', function(e) {
				e.preventDefault();
				product_install.submit_settings();
			});
		});{/literal}
	</script>
{else}
	<script>
	{literal}$(function(){{/literal}
		product_install.delayed_request('{$next_step}');
	{literal}});{/literal}
	</script>
{/if}