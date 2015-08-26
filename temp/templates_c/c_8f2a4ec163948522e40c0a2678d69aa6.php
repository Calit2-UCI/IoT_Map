<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:13:25 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<script src="<?php echo $this->_vars['editor_params']['script_src']; ?>
"></script>
<script src="<?php echo $this->_vars['editor_params']['jquery_adapter_script_src']; ?>
"></script>
<div class="actions">
	<ul>
		<li><div class="l"><a href="#" onclick="javascript: mlSorter.update_sorting(); return false;"><?php echo l('link_save_block_sorting', 'dynamic_blocks', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li class="active"><a href="<?php echo $this->_vars['site_url']; ?>
admin/dynamic_blocks/area_blocks/<?php echo $this->_vars['area']['id']; ?>
"><?php echo l('filter_area_blocks', 'dynamic_blocks', '', 'text', array()); ?></a></li>
		<li><a href="<?php echo $this->_vars['site_url']; ?>
admin/dynamic_blocks/area_layout/<?php echo $this->_vars['area']['id']; ?>
"><?php echo l('filter_area_layout', 'dynamic_blocks', '', 'text', array()); ?></a></li>
		<?php if ($this->_vars['presets_count'] > 1): ?><li><a href="<?php echo $this->_vars['site_url']; ?>
admin/dynamic_blocks/area_preset/<?php echo $this->_vars['area']['id']; ?>
"><?php echo l('filter_area_preset', 'dynamic_blocks', '', 'text', array()); ?></a></li><?php endif; ?>
	</ul>
	&nbsp;
</div>

<div class="filter-form">
	<form action="" method="post">
		<h3><?php echo l('add_area_block_header', 'dynamic_blocks', '', 'text', array()); ?>:</h3>
		<select name="id_block">
			<option value="0">...</option>
			<?php if (is_array($this->_vars['blocks']) and count((array)$this->_vars['blocks'])): foreach ((array)$this->_vars['blocks'] as $this->_vars['block_id'] => $this->_vars['item']): ?>
				<option value="<?php echo $this->_vars['block_id']; ?>
"><?php echo l($this->_vars['item']['name_i'], $this->_vars['item']['lang_gid'], '', 'text', array()); ?></option>
			<?php endforeach; endif; ?>
		</select>
		<input type="submit" name="add_block" value="<?php echo l('link_add_block', 'dynamic_blocks', '', 'button', array()); ?>">
	</form>
</div>

<div id="area_blocks">
	<ul name="parent_0" class="sort connected" id="clsr0ul">
		<?php if (is_array($this->_vars['area_blocks']) and count((array)$this->_vars['area_blocks'])): foreach ((array)$this->_vars['area_blocks'] as $this->_vars['item']): ?>
			<li id="item_<?php echo $this->_vars['item']['id']; ?>
">
				<div class="icons">
					<a href='#' onclick="if (confirm('<?php echo l('note_delete_area_block', 'dynamic_blocks', '', 'js', array()); ?>')) mlSorter.deleteItem(<?php echo $this->_vars['item']['id']; ?>
);return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" alt="<?php echo l('btn_delete', 'start', '', 'text', array()); ?>" title="<?php echo l('btn_delete', 'start', '', 'text', array()); ?>"></a>
				</div>

				<form id="form_item_<?php echo $this->_vars['item']['id']; ?>
">
					<h3><?php echo l($this->_vars['item']['block_data']['name_i'], $this->_vars['item']['block_data']['lang_gid'], '', 'text', array()); ?></h3>
					<div class="edit-form n150">
						<?php if (is_array($this->_vars['item']['block_data']['params_data']) and count((array)$this->_vars['item']['block_data']['params_data'])): foreach ((array)$this->_vars['item']['block_data']['params_data'] as $this->_vars['param']): ?>
							<?php $this->assign('param_gid', $this->_vars['param']['gid']); ?>
							<div class="row" id="row_<?php echo $this->_vars['item']['id']; ?>
_<?php echo $this->_vars['param']['gid']; ?>
">
								<div class="h"><?php echo l($this->_vars['param']['i'], $this->_vars['item']['block_data']['lang_gid'], '', 'text', array()); ?>: </div>
								<div class="v">
																		<?php switch($this->_vars['param']['type']): case 'textarea':  ?>
										<textarea name="params[<?php echo $this->_vars['param_gid']; ?>
]" rows="5" cols="80"><?php echo $this->_run_modifier($this->_vars['item']['params'][$this->_vars['param_gid']], 'escape', 'plugin', 1); ?>
</textarea>
									<?php break; case 'text':  ?>
										<?php if (is_array($this->_vars['langs']) and count((array)$this->_vars['langs'])): foreach ((array)$this->_vars['langs'] as $this->_vars['lang_id'] => $this->_vars['lang_item']): ?>
											
											<?php $this->assign('value_code', $this->_vars['param_gid'].'_'.$this->_vars['lang_item']['id']); ?>
											<input type="<?php if ($this->_vars['lang_id'] == $this->_vars['current_lang_id']): ?>text<?php else: ?>hidden<?php endif; ?>" 
												   name="params[<?php echo $this->_vars['param_gid']; ?>
_<?php echo $this->_vars['lang_id']; ?>
]" 
												   value="<?php echo $this->_run_modifier($this->_run_modifier($this->_vars['item']['params'][$this->_vars['value_code']], 'strval', 'PHP', 1), 'escape', 'plugin', 1); ?>
" 
												   lang-editor="value" lang-editor-type="params-<?php echo $this->_vars['param_gid']; ?>
" 
												   lang-editor-lid="<?php echo $this->_vars['lang_id']; ?>
" class="middle">
										<?php endforeach; endif; ?>
										<a href="#" lang-editor="button" lang-editor-type="params-<?php echo $this->_vars['param_gid']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-translate.png" width="16" height="16"></a>

									<?php break; case 'int':  ?>
										<input type="text" class="middle" value="<?php echo $this->_run_modifier($this->_run_modifier($this->_vars['item']['params'][$this->_vars['param_gid']], 'intval', 'PHP', 1), 'escape', 'plugin', 1); ?>
" name="params[<?php echo $this->_vars['param_gid']; ?>
]">
									<?php break; case 'checkbox':  ?>
										<input type="hidden" value="0" name="params[<?php echo $this->_vars['param_gid']; ?>
]" />
										<label><input type="checkbox" value="1" name="params[<?php echo $this->_vars['param_gid']; ?>
]"<?php if ($this->_vars['item']['params'][$this->_vars['param_gid']]): ?> checked<?php endif; ?> /></label>
									<?php break; case 'wysiwyg':  ?>
										<div class="wysiwyg-wrapper">
											<dl>
												<?php if (is_array($this->_vars['langs']) and count((array)$this->_vars['langs'])): foreach ((array)$this->_vars['langs'] as $this->_vars['lng']): ?>
													<dt	onclick="$(this).parent().find('dt').removeClass('active'); 
																$(this).addClass('active'); 
																$(this).parent().find('dd').hide().filter('[data-lang=<?php echo $this->_vars['lng']['id']; ?>
]').show();"
														<?php if ($this->_vars['lng']['id'] == $this->_vars['current_lang_id']): ?> class="active"<?php endif; ?>>
														<?php echo $this->_vars['lng']['name']; ?>

													</dt>
												<?php endforeach; endif; ?>
												<?php if (is_array($this->_vars['langs']) and count((array)$this->_vars['langs'])): foreach ((array)$this->_vars['langs'] as $this->_vars['lng']): ?>
													<?php $this->assign('lang_code', $this->_vars['param_gid'].'_'.$this->_vars['lng']['id']); ?>
													<dd data-lang="<?php echo $this->_vars['lng']['id']; ?>
"<?php if ($this->_vars['lng']['id'] != $this->_vars['current_lang_id']): ?> class="hide"<?php endif; ?>>
														<textarea name="params[<?php echo $this->_vars['param_gid']; ?>
_<?php echo $this->_vars['lng']['id']; ?>
]" data-id-block="<?php echo $this->_vars['item']['id']; ?>
"><?php echo $this->_vars['item']['params'][$this->_vars['lang_code']]; ?>
</textarea>
													</dd>
												<?php endforeach; endif; ?>
											</dl>
											
										</div>
									<?php break; default:  ?>
										<input type="text" value="<?php echo $this->_run_modifier($this->_vars['item']['params'][$this->_vars['param_gid']], 'escape', 'plugin', 1); ?>
" name="params[<?php echo $this->_vars['param_gid']; ?>
]">
									<?php break; endswitch; ?>
								</div>
							</div>
						<?php endforeach; endif; ?>
						<div class="row">
							<div class="h"><?php echo l('field_view', 'dynamic_blocks', '', 'text', array()); ?>: </div>
							<div class="v">
								<select name="view_str" class="middle">
								<?php if (is_array($this->_vars['item']['block_data']['views_data']) and count((array)$this->_vars['item']['block_data']['views_data'])): foreach ((array)$this->_vars['item']['block_data']['views_data'] as $this->_vars['view']): ?>
									<?php $this->assign('view_gid', $this->_vars['view']['gid']); ?>
									<option value="<?php echo $this->_vars['view_gid']; ?>
" <?php if ($this->_vars['view_gid'] == $this->_vars['item']['view_str']): ?>selected<?php endif; ?>><?php echo l($this->_vars['view']['i'], $this->_vars['item']['block_data']['lang_gid'], '', 'text', array()); ?></option>
								<?php endforeach; endif; ?>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="h"><?php echo l('field_cache_time', 'dynamic_blocks', '', 'text', array()); ?>: </div>
							<div class="v"><input type="text" value="<?php echo $this->_vars['item']['cache_time']; ?>
" name="cache_time" class="short"> <i><?php echo l('field_cache_time_text', 'dynamic_blocks', '', 'text', array()); ?></i></div>
						</div>
						<div class="row">
							<div class="h">&nbsp;</div>
							<div class="v"><input type="button" name="save_data" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>" onclick="javascript: saveBlock('<?php echo $this->_vars['item']['id']; ?>
');"></div>
						</div>
						<div class="clr"></div>
					</div>
				</form>
			</li>
		<?php endforeach; endif; ?>
	</ul>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  echo tpl_function_block(array('name' => 'lang_inline_editor','module' => 'start','multiple' => '1'), $this);?>
<script >
	var save_url = '<?php echo $this->_vars['site_root']; ?>
admin/dynamic_blocks/ajax_save_area_block/';
<?php echo '
	cke_params = {'; ?>

		language: '<?php echo $this->_vars['editor_params']['language']; ?>
',
		filebrowserImageUploadUrl: '<?php echo $this->_vars['editor_params']['upload_url']; ?>
',
		filebrowserFlashUploadUrl: '<?php echo $this->_vars['editor_params']['upload_url']; ?>
',
		resize_enabled: false,
		toolbar: <?php echo $this->_vars['editor_params']['toolbars']['Middle']; ?>

	<?php echo '}

	$(\'#area_blocks\').find(\'.wysiwyg-wrapper textarea\').each(function(){
		var params = $.extend(true, {}, cke_params);
		params.filebrowserImageUploadUrl += $(this).data(\'id-block\');
		params.filebrowserFlashUploadUrl += $(this).data(\'id-block\');
		$(this).ckeditor(params);
	});
	
	var mlSorter;
	$(function(){
		mlSorter = new multilevelSorter({
			siteUrl: \'';  echo $this->_vars['site_url'];  echo '\', 
			urlSaveSort: \'admin/dynamic_blocks/save_area_block_sorter/';  echo $this->_vars['area']['id'];  echo '\',
			urlDeleteItem: \'admin/dynamic_blocks/ajax_delete_area_block/\',
			onStart: function(event, ui){
				ui.item.find(\'.wysiwyg-wrapper textarea\').each(function(){
					$(this).ckeditorGet().destroy();
                });
			},
			onStop: function(event, ui){
				ui.item.find(\'.wysiwyg-wrapper textarea\').each(function(){
					var params = $.extend(true, {}, cke_params);
					params.filebrowserImageUploadUrl += $(this).data(\'id-block\');
					params.filebrowserFlashUploadUrl += $(this).data(\'id-block\');
					$(this).ckeditor(params);
				});
			}
		});
	});

	function saveBlock(id){
		$.ajax({
			url: save_url + id, 
			type: \'POST\',
			data: $(\'#form_item_\'+id).serialize(), 
			cache: false,
			success: function(data){
				error_object.show_error_block(\'';  echo l("success_update_area_block", "dynamic_blocks", '', "js", array());  echo '\', \'success\');
			}
		});
	}
'; ?>
</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
