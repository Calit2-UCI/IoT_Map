<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 19:47:37 Pacific Daylight Time */ ?>

<?php if (! $this->_vars['ajax']): ?>
	<script type='text/javascript'><?php echo '
		$(function(){
			loadScripts(
				"';  echo tpl_function_js(array('module' => 'comments','file' => 'comments.js','return' => 'path'), $this); echo '", 
				function(){
					comments = new Comments({
						siteUrl: site_url,
						lng: $.parseJSON(\'';  echo $this->_run_modifier($this->_vars['js_lng'], 'escape', 'plugin', 1, 'javascript');  echo '\')
					});
				},
				\'comments\',
				{async: false, cache: false}
			);
		});
	</script>'; ?>


<div class="comments"<?php if (isset ( $this->_vars['comments']['max_height'] )): ?> style="max-height: <?php echo $this->_vars['comments']['max_height']; ?>
; overflow-y: auto;"<?php endif; ?> id="comments_<?php echo $this->_vars['comments']['gid']; ?>
_<?php echo $this->_vars['comments']['id_obj']; ?>
">
<?php endif;  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
	<?php if ($this->_vars['comments']['hidden']): ?>
		<div>
			<?php if ($this->_vars['comments']['show_form'] || $this->_vars['comments']['count_all'] || ! $this->_vars['comments']['calc_count']): ?>
				<a href="#" onclick="comments.loadComments('<?php echo $this->_run_modifier($this->_vars['comments']['gid'], 'escape', 'plugin', 1, 'javascript'); ?>
', '<?php echo $this->_run_modifier($this->_vars['comments']['id_obj'], 'escape', 'plugin', 1, 'javascript'); ?>
', $('#comments_<?php echo $this->_run_modifier($this->_vars['comments']['gid'], 'escape', 'plugin', 1, 'javascript'); ?>
_<?php echo $this->_run_modifier($this->_vars['comments']['id_obj'], 'escape', 'plugin', 1, 'javascript'); ?>
')); event.preventDefault();"><?php echo l('comments', 'comments', '', 'text', array());  if ($this->_vars['comments']['calc_count']): ?>&nbsp;(<span class="counter"><?php echo $this->_vars['comments']['count_all']; ?>
</span>)<?php endif; ?></a>
			<?php else: ?>
				<?php echo l('comments', 'comments', '', 'text', array());  if ($this->_vars['comments']['calc_count']): ?>&nbsp;(<span class="counter"><?php echo $this->_vars['comments']['count_all']; ?>
</span>)<?php endif; ?>
			<?php endif; ?>
		</div>
	<?php else: ?>
		<div id="comments_form_cont_<?php echo $this->_vars['comments']['gid']; ?>
_<?php echo $this->_vars['comments']['id_obj']; ?>
" class="form_wrapper" gid="<?php echo $this->_vars['comments']['gid']; ?>
" id_obj="<?php echo $this->_vars['comments']['id_obj']; ?>
">
			<div>
				<?php if ($this->_vars['comments']['show_form'] || $this->_vars['comments']['count_all']): ?>
					<a href="#" onclick="$('#comments_slider_<?php echo $this->_vars['comments']['gid']; ?>
_<?php echo $this->_vars['comments']['id_obj']; ?>
').slideToggle(150); event.preventDefault();"><?php echo l('comments', 'comments', '', 'text', array()); ?>&nbsp;(<span class="counter"><?php echo $this->_vars['comments']['count_all']; ?>
</span>)</a>
				<?php else: ?>
					<?php echo l('comments', 'comments', '', 'text', array()); ?>&nbsp;(<span class="counter"><?php echo $this->_vars['comments']['count_all']; ?>
</span>)
				<?php endif; ?>
			</div>
			
			<div id="comments_slider_<?php echo $this->_vars['comments']['gid']; ?>
_<?php echo $this->_vars['comments']['id_obj']; ?>
" class="comments_slider">
				<?php if ($this->_vars['comments']['show_form']): ?>
					<div>
						<div class="edit_block post-form wide resize">
							<?php if ($this->_vars['user_session_data']['auth_type'] != 'user'): ?>
								<div class="b" style="height: 0;"><input type="text" value="" name="email" autocomplete="off" /></div>
							<?php endif; ?>
							<div>
								<div class="form-input">
									<div class="table-div">
										<?php if ($this->_vars['user_session_data']['auth_type'] != 'user'): ?>
											<div class="input"><input type="text" value="" name="user_name" placeholder="<?php echo l('your_name', 'comments', '', 'text', array()); ?>" /></div>
										<?php endif; ?>
										<div class="text"><textarea maxcount="<?php echo $this->_vars['comments_type']['settings']['char_count']; ?>
" placeholder="<?php echo l('add_comment', 'comments', '', 'text', array()); ?>"></textarea></div>
										<div class="char-counter"><span class="char_counter"><?php echo $this->_vars['comments_type']['settings']['char_count']; ?>
</span></div>
										<div><input type="button" value="<?php echo l('btn_send', 'start', '', 'text', array()); ?>" onclick="comments.addComment('<?php echo $this->_vars['comments']['gid']; ?>
', '<?php echo $this->_vars['comments']['id_obj']; ?>
');" /></div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
				<?php endif; ?>
				<div id="comments_cont_<?php echo $this->_vars['comments']['gid']; ?>
_<?php echo $this->_vars['comments']['id_obj']; ?>
" class="comments_wrapper">
					<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "comments". $this->module_templates.  $this->get_current_theme_gid('', '"comments"'). "comments_block.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
				</div>
				<?php if ($this->_vars['comments']['bd_min_id'] != $this->_vars['comments']['min_id']): ?>
					<div class="more_button">
						<input type="button" value="<?php echo l('show_more', 'comments', '', 'text', array()); ?>" onclick="comments.loadComments('<?php echo $this->_vars['comments']['gid']; ?>
', '<?php echo $this->_vars['comments']['id_obj']; ?>
');" />
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif;  if (! $this->_vars['ajax']): ?>
</div>
<?php endif;  $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>