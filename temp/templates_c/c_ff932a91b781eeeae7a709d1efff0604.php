<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 19:47:35 Pacific Daylight Time */ ?>

<span id="friendlist_<?php echo $this->_vars['friendlist_button_rand']; ?>
">
	<span id="friendlist_links_<?php echo $this->_vars['friendlist_button_rand']; ?>
">
		<?php if (is_array($this->_vars['buttons']) and count((array)$this->_vars['buttons'])): foreach ((array)$this->_vars['buttons'] as $this->_vars['btn_name'] => $this->_vars['btn']): ?>
			<a href="<?php echo tpl_function_seolink(array('module' => 'friendlist','method' => $this->_vars['btn']['method'],'destination_user_id' => $this->_vars['id_dest_user']), $this);?>" 
			   class="link-r-margin"
			   data-pjax="0" method="<?php echo $this->_vars['btn']['method']; ?>
" onclick="event.preventDefault();" 
			   title="<?php echo l('action_'.$this->_vars['btn_name'], 'friendlist', '', 'text', array()); ?>" data-user_id="<?php echo $this->_vars['id_dest_user']; ?>
"><i class="icon-<?php echo $this->_vars['btn']['icon']; ?>
 icon-big edge hover zoom30">
					<?php if ($this->_vars['btn']['icon_stack']): ?><i class="fa-mini-stack icon-<?php echo $this->_vars['btn']['icon_stack']; ?>
"></i><?php endif; ?></i></a>
		<?php endforeach; endif; ?>
	</span>
	<script><?php echo '
		$(function() {
			loadScripts(
				"';  echo tpl_function_js(array('module' => 'friendlist','file' => 'lists_links.js','return' => 'path'), $this); echo '",
				function() {
					var id_dest_user = parseInt(\'';  echo $this->_vars['id_dest_user'];  echo '\');
					var button_rand = parseInt(\'';  echo $this->_vars['friendlist_button_rand'];  echo '\');
					lists_links = new ListsLinks({
						siteUrl: site_url,
						id_dest_user: id_dest_user,
						button_rand: button_rand,
						url: \'friendlist/\'
					});
				},
				\'lists_links\',
				{async: false}
			);
		});
	</script>'; ?>

</span>
