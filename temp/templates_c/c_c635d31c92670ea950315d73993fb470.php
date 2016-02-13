<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seotag.php');
$this->register_function("seotag", "tpl_function_seotag"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-08 22:54:16 Pacific Standard Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="content">
<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="content-block mailbox">
	<h1><?php echo tpl_function_seotag(array('tag' => 'header_text'), $this);?></h1>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "mailbox". $this->module_templates.  $this->get_current_theme_gid('"default"', '"mailbox"'). "mailbox_menu.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
	<div id="mailbox_content" class="edit_block">
		<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
		<div class="tab-submenu">
			<div class="fleft">
				<ul>
					<li><s id="save_message" class="fa-save icon-big edge hover zoom20" title="<?php echo l('link_message_save', 'mailbox', '', 'button', array()); ?>"></s></li>
				</ul>
			</div>
			<div class="fright">
				<span id="save_status" class="hide"><?php echo l('text_message_saved', 'mailbox', '', 'text', array()); ?></a>
			</div>
		</div>
		<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>

		<div class="pt10">
			<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "mailbox". $this->module_templates.  $this->get_current_theme_gid('"default"', '"mailbox"'). "write_form.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
		</div>
		
	</div>
</div>
<div class="clr"></div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
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
					success_request: function(message){mb.save_message(function(){mb.send_message()}, true)},
					fail_request: function(message){error_object.show_error_block(message, \'error\');},
				});
			},
			[\'send_message_available_view\'],
			{async: false}
		);
		
		loadScripts(
			"';  echo tpl_function_js(array('file' => 'mailbox.js','module' => 'mailbox','return' => 'path'), $this); echo '", 
			function(){
				mb = new mailbox({
					siteUrl: site_url,
					folder: \'';  echo $this->_vars['folder'];  echo '\',
					sendAvailableView: send_message_available_view,
					writeMessage: true,
					';  if ($this->_vars['message']['id']): ?>messageId: <?php echo $this->_vars['message']['id']; ?>
,<?php endif;  echo '
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
