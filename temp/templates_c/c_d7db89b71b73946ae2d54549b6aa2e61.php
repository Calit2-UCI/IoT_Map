<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 01:47:43 Pacific Daylight Time */ ?>

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
<b>Requirements</b><br><br>
<?php if ($this->_vars['requirements']): ?>
<div class="tbl">
	<?php if (is_array($this->_vars['requirements']) and count((array)$this->_vars['requirements'])): foreach ((array)$this->_vars['requirements'] as $this->_vars['item']): ?>
	<div class="row <?php if ($this->_vars['item']['result']): ?>green<?php else: ?>red<?php endif; ?>">
		<div class="value"><?php echo $this->_vars['item']['value']; ?>
</div>
		<div class="name"><?php echo $this->_vars['item']['name']; ?>
</div>
		<div class="clr"></div>
	</div>
	<?php endforeach; endif; ?>
</div>
<div class="btn"><div class="l"><input type="button" onclick="javascript: product_install.request('requirements');" name="refresh_module" value="Refresh"></div></div>
<div class="clr"></div>
<?php else: ?>
<script>
<?php echo '$(function(){'; ?>

	product_install.delayed_request('<?php echo $this->_vars['next_step']; ?>
');
<?php echo '});'; ?>

</script>
<?php endif; ?>