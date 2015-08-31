<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 19:47:35 Pacific Daylight Time */ ?>

<span id="block_user_<?php echo $this->_vars['id_dest_user']; ?>
">
	<a class="link-r-margin add_to_blacklist<?php if ($this->_vars['action'] == 'remove'): ?> hide<?php endif; ?>" data-userId="<?php echo $this->_vars['id_dest_user']; ?>
" 
	   href="<?php echo tpl_function_seolink(array('module' => 'blacklist','method' => 'add','destination_user_id' => $this->_vars['id_dest_user']), $this);?>" 
	   data-pjax="0" onclick="event.preventDefault();" class="link-r-margin" 
	   title="<?php echo l('action_add', 'blacklist', '', 'text', array()); ?>">
		<i class="fa-lock icon-big edge hover zoom30"></i>
	</a>
	<a class="link-r-margin remove_from_blacklist<?php if ($this->_vars['action'] == 'add'): ?> hide<?php endif; ?>" data-userId="<?php echo $this->_vars['id_dest_user']; ?>
" 
	   href="<?php echo tpl_function_seolink(array('module' => 'blacklist','method' => 'remove','destination_user_id' => $this->_vars['id_dest_user']), $this);?>" 
	   data-pjax="0" onclick="event.preventDefault();" class="link-r-margin" 
	   title="<?php echo l('action_remove', 'blacklist', '', 'text', array()); ?>">
		<i class="fa-lock icon-big edge hover zoom30">
			<i class="fa-mini-stack icon-remove"></i>
		</i>
	</a>
</span>
<script><?php echo '
	$(function() {
		loadScripts(
				["';  echo tpl_function_js(array('file' => 'blacklist.js','module' => 'blacklist','return' => 'path'), $this); echo '"],
				function() {
					blacklistObj = new blacklist({
						siteUrl: site_url
					});
				},
				\'blacklistObj\'
				);
	});
</script>'; ?>

