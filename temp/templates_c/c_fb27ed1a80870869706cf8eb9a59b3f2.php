<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 01:47:45 Pacific Daylight Time */ ?>

<h3>Installing: <?php echo $this->_vars['module']['install_name']; ?>
 V<?php echo $this->_vars['module']['version']; ?>
</h3>
<div><?php echo $this->_vars['module']['install_descr']; ?>
</div>
<br>
<div class="bar-level2" id="module_bar"><div class="bar" style="width: <?php echo $this->_vars['current_module_percent']; ?>
%"><?php echo $this->_vars['current_module_percent']; ?>
%</div></div>
<br>
<b>Module settings</b><br><br>
<?php if ($this->_vars['settings']): ?>
	<form name="settings_submit_form" id="settings-submit-form" method="post">
		<input type="hidden" name="submit_btn" value='1'>
		<?php echo $this->_vars['settings']; ?>

		<div class="clr"></div>
		<div class="btn"><div class="l"><input type="submit" name="send_btn" value="Save"></div></div>
		<div class="clr"></div>
	</form>
	<script>
		<?php echo '$(function(){
			$(\'.form input:first\', \'#settings-submit-form\').focus().select();
			$(\'#settings-submit-form\').bind(\'submit\', function(e) {
				e.preventDefault();
				product_install.submit_settings();
			});
		});'; ?>

	</script>
<?php else: ?>
	<script>
	<?php echo '$(function(){'; ?>

		product_install.delayed_request('<?php echo $this->_vars['next_step']; ?>
');
	<?php echo '});'; ?>

	</script>
<?php endif; ?>