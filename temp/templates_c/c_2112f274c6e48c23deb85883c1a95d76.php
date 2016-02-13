<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-11 19:35:36 Pacific Standard Time */ ?>

<div class="content-block load_content" id="mailbox_content">
	<h1><?php echo l('header_message_write', 'mailbox', '', 'text', array()); ?></h1>
	<div class="inside edit_block">
		<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "mailbox". $this->module_templates.  $this->get_current_theme_gid('"default"', '"mailbox"'). "write_form.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
	</div>
</div>
<script><?php echo '
	$(function(){
		loadScripts(
			"';  echo tpl_function_js(array('file' => 'available_view.js','return' => 'path'), $this); echo '", 
			function(){
				send_message_available_view = new available_view({
					siteUrl: site_url,
					checkAvailableAjaxUrl: \'mailbox/ajax_available_send_message/\',
					buyAbilityAjaxUrl: \'mailbox/ajax_activate_send_message/\',
					buyAbilityFormId: \'ability_form\',
					buyAbilitySubmitId: \'ability_form_submit\',
					success_request: function(message){mb_content.save_message(function(){mb_content.send_message()}, true)},
					fail_request: function(message){error_object.show_error_block(message, \'error\')},
				});
			},
			[\'send_message_available_view\'],
			{async: false}
		);
		
		loadScripts(
			"';  echo tpl_function_js(array('file' => 'mailbox.js','module' => 'mailbox','return' => 'path'), $this); echo '", 
			function(){
				mb_content = new mailbox({
					siteUrl: site_url,
					accessAvailableView: access_available_view,
					sendAvailableView: send_message_available_view,
					writeMessage: true,
				});
			},
			[\'mb_content\'],
			{async: false}
		);
	});
</script>'; ?>

