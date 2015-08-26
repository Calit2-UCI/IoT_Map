<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 01:47:40 Pacific Daylight Time */ ?>

Processing...
<script>
<?php echo '$(function(){'; ?>

	product_install.properties.currentModule='<?php echo $this->_vars['current_module']; ?>
';
	product_install.delayed_request('start_install');
	product_install.update_overall_progress(<?php echo $this->_vars['current_overall_percent']; ?>
);
<?php echo '});'; ?>

</script>