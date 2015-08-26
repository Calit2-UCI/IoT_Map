<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 01:57:30 Pacific Daylight Time */ ?>

<?php if ($this->_vars['errors']): ?>
	<h3>Config rewriting</h3>
	<div>
	<?php if (is_array($this->_vars['errors']) and count((array)$this->_vars['errors'])): foreach ((array)$this->_vars['errors'] as $this->_vars['item']): ?>
	<font class="req"><?php echo $this->_vars['item']; ?>
</font><br>
	<?php endforeach; endif; ?>
	</div>
	<br><br>
	<div class="btn"><div class="l"><input type="button" onclick="javascript: product_install.request('overall_product');" name="refresh_install" value="Refresh"></div></div>
	<div class="clr"></div>
<?php else: ?>
	<h3>The site is successfully installed! </h3>
	<div>
	<?php if (is_array($this->_vars['messages']) and count((array)$this->_vars['messages'])): foreach ((array)$this->_vars['messages'] as $this->_vars['item']): ?>
	<b><?php echo $this->_vars['item']; ?>
</b><br><br>
	<?php endforeach; endif; ?>
	</div>
	<br><br>
	<div class="btn"><div class="l"><input type="button" onclick="javascript: location.href='<?php echo $this->_vars['site_url']; ?>
';" name="finish_install" value="Finish"></div></div>
	<div class="clr"></div>
	<script type="text/javascript" >
	<?php echo '
	$(function(){
		window.open(\'http://www.pilotgroup.net/connect.php\');
	});
	'; ?>

	</script>
<?php endif; ?>
<script>
<?php echo '$(function(){'; ?>

	product_install.update_overall_progress(<?php echo $this->_vars['current_overall_percent']; ?>
);
<?php echo '});'; ?>

</script>