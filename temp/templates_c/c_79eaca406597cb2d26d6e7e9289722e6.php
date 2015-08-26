<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 01:47:42 Pacific Daylight Time */ ?>

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
<b>Check dependent modules</b><br><br>
<?php if ($this->_vars['errors'] || $this->_vars['lib_errors']): ?>
	<?php if ($this->_vars['errors']): ?>
	<h3>Required modules: </h3>
	<div>
		<?php if (is_array($this->_vars['errors']) and count((array)$this->_vars['errors'])): foreach ((array)$this->_vars['errors'] as $this->_vars['item']): ?>
		<?php echo $this->_vars['item']['module_gid']; ?>
 V<?php echo $this->_vars['item']['module_version']; ?>
 - <font class="req"><?php echo $this->_vars['item']['info']; ?>
</font><br>
		<?php endforeach; endif; ?>
	</div>
	<?php endif; ?>
	<?php if ($this->_vars['lib_errors']): ?>
	<h3>Required libraries: </h3>
	<div>
		<?php if (is_array($this->_vars['lib_errors']) and count((array)$this->_vars['lib_errors'])): foreach ((array)$this->_vars['lib_errors'] as $this->_vars['item']): ?>
		<b><?php echo $this->_vars['item']['gid']; ?>
</b> <?php echo $this->_vars['item']['name']; ?>
<br>
		<?php endforeach; endif; ?>
	</div>
	<?php endif; ?>

	<div class="btn"><div class="l"><input type="button" onclick="javascript: product_install.request('dependencies');" name="refresh_module" value="Refresh"></div></div>
	<div class="clr"></div>

<?php else: ?>
<script>
<?php echo '$(function(){'; ?>

	product_install.delayed_request('<?php echo $this->_vars['next_step']; ?>
');
<?php echo '});'; ?>

</script>
<?php endif; ?>