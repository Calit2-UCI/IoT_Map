<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.json_encode.php');
$this->register_function("json_encode", "tpl_function_json_encode"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.capture.php');
$this->register_block("capture", "tpl_block_capture"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-03 02:03:22 Pacific Standard Time */ ?>

		<?php 
$this->assign('input_default', l('input_default', 'start', '', 'button', array()));
 ?>
		<form action="" method="post" id="write_form">
			<?php if ($this->_vars['type'] != 'short'): ?>
			<div class="r">
				<div class="f"><?php echo l('field_user', 'mailbox', '', 'text', array()); ?></div>
				<div class="v">
					<?php $this->_tag_stack[] = array('tpl_block_capture', array('assign' => 'user_callback')); tpl_block_capture(array('assign' => 'user_callback'), null, $this); ob_start();  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start();  echo '
						function(variable, value, data){
							$(\'#user_hidden\').val(variable.toString()).change();
							$(\'#user_text\').val(value);
						}
					';  $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_capture($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
					
					<?php if ($this->_vars['message']['recipient']['id'] > 0): ?>
					<?php $this->assign('id_to_user', $this->_vars['message']['id_to_user']); ?>
					<?php endif; ?>
					<input type="text" name="name_to_user" id="user_text" autocomplete="off" value="<?php if ($this->_vars['message']['recipient']['id']):  echo $this->_run_modifier($this->_vars['message']['recipient']['output_name'], 'strip_tags', 'PHP', 1); ?>
 <?php if ($this->_vars['message']['recipient']['output_name'] != $this->_vars['message']['recipient']['nickname']): ?>(<?php echo $this->_run_modifier($this->_vars['message']['recipient']['nickname'], 'strip_tags', 'PHP', 1); ?>
)<?php endif;  endif; ?>" class="long" placeholder="<?php echo l('input_default', 'start', '', 'button', array()); ?>">&nbsp;
					
					<?php echo tpl_function_block(array('name' => 'friend_input','module' => 'friendlist','id_user' => $this->_vars['id_to_user'],'var_user' => 'id_to_user','values_callback' => $this->_vars['user_callback']), $this);?>
					<input type="hidden" name="id_to_user" id="user_hidden" value="<?php echo $this->_run_modifier($this->_vars['id_to_user'], 'escape', 'plugin', 1); ?>
">
					<script><?php echo '
						$(function(){
							loadScripts(
								"';  echo tpl_function_js(array('file' => 'autocomplete_input.js','return' => 'path'), $this); echo '",
								function(){
									user_autocomplete = new autocompleteInput({
										siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
										dataUrl: \'users/ajax_get_users_data\',
										id_text: \'user_text\',
										id_hidden: \'user_hidden\',
										rand: \'';  echo $this->_vars['rand'];  echo '\',
										format_callback: function(data){
											return data.output_name + (data.nickname != data.output_name ? \' (\' + data.nickname + \')\' : \'\');
										}
									});
								},
								\'user_autocomplete\'
							);
						});
					'; ?>
</script>
				</div>
			</div>
			<?php endif;  $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
			<?php if (! $this->_vars['is_reply']): ?>
			<div class="r">
				<div class="f"><?php echo l('field_subject', 'mailbox', '', 'text', array()); ?></div>
				<div class="v"><input type="text" name="subject" value="<?php echo $this->_run_modifier($this->_vars['message']['subject'], 'escape', 'plugin', 1); ?>
" placeholder="<?php echo l('input_default', 'start', '', 'button', array()); ?>" autocomplete="false" <?php if ($this->_vars['type'] != 'short'): ?>class="long"<?php endif; ?>></div>
			</div>
			<?php endif; ?>
			<div class="r">
				<div class="f"><?php echo l('field_message', 'mailbox', '', 'text', array()); ?></div>
				<div class="v"><textarea name="message" row="5" cols="80" <?php if ($this->_vars['type'] != 'short'): ?>class="long"<?php endif; ?> autocomplete="false"><?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  echo $this->_run_modifier($this->_vars['message']['message'], 'escape', 'plugin', 1);  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?></textarea></div>
			</div><?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
			<?php if ($this->_vars['type'] != 'short' || $this->_vars['is_reply']): ?>
			<div class="r">
				<div class="f"><?php echo l('field_attach', 'mailbox', '', 'text', array()); ?></div>
				<div class="v">
					<input type="file" name="attach" class="attach" id="attach" multiple="true" size="40">
					<button id="btn_attach"><?php echo l('btn_upload', 'start', '', 'text', array()); ?></button>
					<script><?php echo '
						$(function(){
							var allowed_mimes = ';  echo tpl_function_json_encode(array('data' => $this->_vars['attach_settings']['allowed_mimes']), $this); echo ';
							loadScripts(
								"';  echo tpl_function_js(array('file' => 'uploader.js','return' => 'path'), $this); echo '", 
								function(){
									au = new uploader({
										siteUrl: site_url,
										zoneId: \'attachbox\',
										fileId: \'attach\',
										formId: \'write_form\',
										sendType: \'file\',
										sendId: \'btn_attach\',
										messageId: \'attach-input-error\',
										maxFileSize: \'';  echo $this->_vars['attach_settings']['max_size'];  echo '\',
										mimeType: allowed_mimes,
										cbOnSend: function(noFile){
											mb.save_';  if ($this->_vars['is_reply']): ?>reply<?php else: ?>message<?php endif;  echo '(function(data){
												var options = {uploadUrl: \'mailbox/upload_attach/\'+data.';  if ($this->_vars['is_reply']): ?>reply<?php else: ?>message<?php endif;  echo '_id};
												if(noFile){
													au.sendNoFileApi(options);
												}else{
													au.send(options);
												}
											}, true);
										},
										cbOnUpload: function(name, data){
											var attaches = $(\'#attaches\');
											attaches.find(\'ul\').append(\'<li><a href="\'+data.link+\'" target="_blank">\'+name+\'</a><br>\'+data.size+\'<div class="act"><a href="javascript:void(0);" class="btn_delete_upload fright" data-id="\'+data.id+\'"><i class="icon-remove icon-big"></i></a></div></li>\');
											attaches.show();
											error_object.show_error_block(\'';  echo l('success_attach_uploaded', 'mailbox', '', 'js', array());  echo '\', \'success\');
										},
										cbOnError: function(data){
											if(data.errors.length){
												error_object.show_error_block(data.errors, \'error\');
											}
										},
										cbOnProcessError: function(data){
											error_object.show_error_block(data, \'error\');
										}
									});
								},
								[\'au\'],
								{async: false}
							);
						});
					'; ?>
</script>
				</div>
			</div>
			<div class="attachbox <?php if (! $this->_vars['message']['attaches']): ?>hide<?php endif; ?>" id="attaches">
				<ul>
					<?php if (is_array($this->_vars['message']['attaches']) and count((array)$this->_vars['message']['attaches'])): foreach ((array)$this->_vars['message']['attaches'] as $this->_vars['item']): ?>
					<li>
						<a href="<?php echo $this->_vars['item']['link']; ?>
" target="_blank"><?php echo $this->_vars['item']['name']; ?>
</a><br><?php echo $this->_vars['item']['size']; ?>

						<div class="act"><a href="javascript:void(0);" class="btn_delete_upload fright" data-id="<?php echo $this->_vars['item']['id']; ?>
"><i class="icon-remove icon-big"></i></a></div>
					</li>
					<?php endforeach; endif; ?>
				</ul>
				<div class="clr"></div>
			</div>
			<?php endif; ?>
			<div class="b">
				<input type="button" name="btn_send" value="<?php echo l('btn_send', 'mailbox', '', 'button', array()); ?>" id="btn_send_message" class="btn">
				<?php if ($this->_vars['type'] == 'short' && ! $this->_vars['is_reply']): ?>
					<a href="javascript:void(0);" class="fright" id="write_message_full"><?php echo l('link_message_form', 'mailbox', '', 'text', array()); ?></a> 
				<?php elseif (! $this->_vars['write_message']): ?>
					<a href="<?php echo tpl_function_seolink(array('module' => 'mailbox','method' => $this->_vars['folder']), $this);?>" class="btn-link"><i class="icon-arrow-left icon-big edge hover"></i><i><?php echo l('link_back_to_'.$this->_vars['folder'], 'mailbox', '', 'text', array()); ?></i></a>
				<?php endif; ?>
			</div>
			<div class="clr"></div>
			<?php if ($this->_vars['type'] == 'short' && ! $this->_vars['is_reply']): ?>
			<input type="hidden" name="id_to_user" value="<?php echo $this->_vars['user_id']; ?>
">
			<?php endif; ?>
		</form>
		
