<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seotag.php');
$this->register_function("seotag", "tpl_function_seotag"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-08 22:53:33 Pacific Standard Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="content">
<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="content-block mailbox">
	<h1><?php echo tpl_function_seotag(array('tag' => 'header_text'), $this);?></h1>
	<div id="mailbox_content"><?php echo $this->_vars['content']; ?>
</div>
</div>
<div class="clr"></div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
<script><?php echo '
	$(function(){
		loadScripts(
			"';  echo tpl_function_js(array('file' => 'available_view.js','return' => 'path'), $this); echo '", 
			function(){
				read_message_available_view = new available_view({
					siteUrl: site_url,
					checkAvailableAjaxUrl: \'mailbox/ajax_available_read_message/\',
					buyAbilityAjaxUrl: \'mailbox/ajax_activate_read_message/\',
					buyAbilityFormId: \'ability_form\',
					buyAbilitySubmitId: \'ability_form_submit\',
					success_request: function(message){mb.read_message();},
					fail_request: function(message){error_object.show_error_block(message, \'error\');},
				});
			},
			[\'read_message_available_view\'],
			{async: false}
		);
		loadScripts(
			"';  echo tpl_function_js(array('file' => 'mailbox.js','module' => 'mailbox','return' => 'path'), $this); echo '", 
			function(){
				mb = new mailbox({
					siteUrl: site_url,
					folder: \'';  echo $this->_vars['folder'];  echo '\',
					readAvailableView: read_message_available_view,
				});
			},
			[\'mb\'],
			{async: false}
		);
	});
</script>'; ?>

</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
