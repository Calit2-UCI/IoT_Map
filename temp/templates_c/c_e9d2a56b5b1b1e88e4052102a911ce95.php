<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 19:47:35 Pacific Daylight Time */ ?>

<span id="fav_<?php echo $this->_vars['id_dest_user']; ?>
">
	<a class="add_to_fav<?php if ($this->_vars['action'] == 'remove'): ?> hide<?php endif; ?> link-r-margin" data-userId="<?php echo $this->_vars['id_dest_user']; ?>
" 
	   href="<?php echo tpl_function_seolink(array('module' => 'favourites','method' => 'add','destination_user_id' => $this->_vars['id_dest_user']), $this);?>" 
	   data-pjax="0" onclick="event.preventDefault();" class="link-r-margin" 
	   title="<?php echo l('action_add', 'favourites', '', 'text', array()); ?>">
		<i class="fa-heart icon-big edge hover"></i>
	</a>
	<a class="remove_from_fav<?php if ($this->_vars['action'] == 'add'): ?> hide<?php endif; ?> link-r-margin" data-userId="<?php echo $this->_vars['id_dest_user']; ?>
" 
	   href="<?php echo tpl_function_seolink(array('module' => 'favourites','method' => 'remove','destination_user_id' => $this->_vars['id_dest_user']), $this);?>" 
	   data-pjax="0" onclick="event.preventDefault();" class="link-r-margin" 
	   title="<?php echo l('action_remove', 'favourites', '', 'text', array()); ?>">
		<i class="fa-heart icon-big edge hover">
			<i class="fa-mini-stack icon-remove"></i>
		</i>
	</a>
</span>
<script><?php echo '
	$(function() {
		loadScripts(
				["';  echo tpl_function_js(array('file' => 'favourites.js','module' => 'favourites','return' => 'path'), $this); echo '"],
				function() {
					favouritesObj = new favourites({
						siteUrl: site_url
					});
				},
				\'favouritesObj\'
				);
	});
</script>'; ?>

