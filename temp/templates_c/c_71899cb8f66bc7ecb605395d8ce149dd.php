<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 23:57:48 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<?php if ($this->_vars['data']['id']):  /*
<div class="menu-level3">
	<ul>
		<li class="<?php if ($this->_vars['section_gid'] == 'text'): ?>active<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/content/edit/<?php echo $this->_vars['current_lang']; ?>
/<?php echo $this->_vars['parent_id']; ?>
/<?php echo $this->_vars['data']['id']; ?>
/text"><?php echo l('filter_section_text', 'content', '', 'text', array()); ?></a></li>
		<li class="<?php if ($this->_vars['section_gid'] == 'seo'): ?>active<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/content/edit/<?php echo $this->_vars['current_lang']; ?>
/<?php echo $this->_vars['parent_id']; ?>
/<?php echo $this->_vars['data']['id']; ?>
/seo"><?php echo l('filter_section_seo', 'seo', '', 'text', array()); ?></a></li>
	</ul>
	&nbsp;
</div>
*/  endif; ?>

	<?php switch($this->_vars['section_gid']): case 'text':  ?>
		<form method="post" action="" name="save_form" enctype="multipart/form-data">
			<div class="edit-form n150">
				<div class="row header"><?php if ($this->_vars['data']['id']):  echo l('admin_header_page_change', 'content', '', 'text', array());  else:  echo l('admin_header_page_add', 'content', '', 'text', array());  endif; ?></div>
				<?php if ($this->_vars['data']['id']): ?>
				<div class="row">
					<div class="h"><?php echo l('field_view_link', 'content', '', 'text', array()); ?>: </div>
					<div class="v">
						<a href="<?php echo tpl_function_seolink(array('module' => 'content','method' => 'view','data' => $this->_vars['data']), $this);?>">
							<?php echo tpl_function_seolink(array('module' => 'content','method' => 'view','data' => $this->_vars['data']), $this);?>
						</a>&nbsp;
					</div>
				</div>
				<?php endif; ?>
				<div class="row">
					<div class="h"><?php echo l('field_lang', 'content', '', 'text', array()); ?>: </div>
					<div class="v">
						<select name="lang_id">
							<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['item']): ?>
							<option value="<?php echo $this->_vars['item']['id']; ?>
" <?php if ($this->_vars['item']['id'] == $this->_vars['data']['lang_id'] || $this->_vars['item']['id'] == $this->_vars['current_lang'] && ! $this->_vars['data']['lang_id']): ?>selected<?php endif; ?>><?php echo $this->_vars['item']['name']; ?>
</option>
							<?php endforeach; endif; ?>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="h"><?php echo l('field_gid', 'content', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" value="<?php echo $this->_vars['data']['gid']; ?>
" name="gid" class="long"></div>
				</div>
				<div class="row">
					<div class="h"><?php echo l('field_icon', 'content', '', 'text', array()); ?>: </div>
					<div class="v">
						<input type="file" name="page_icon">
						<?php if ($this->_vars['data']['img']): ?>
						<br><img src="<?php echo $this->_vars['data']['media']['img']['thumbs']['small']; ?>
"  hspace="2" vspace="2"><br>
						<input type="checkbox" name="page_icon_delete" value="1" id="uichb">
						<label for="uichb"><?php echo l('field_icon_delete', 'content', '', 'text', array()); ?></label>
						<?php endif; ?>
					</div>
				</div>
				<div class="row">
					<div class="h"><?php echo l('field_title', 'content', '', 'text', array()); ?>:&nbsp;* </div>
					<div class="v">
						<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['lang_id'] => $this->_vars['lang_item']): ?>
						<?php $this->assign('title', 'title_'.$this->_vars['lang_id']); ?>
						<input type="<?php if ($this->_vars['lang_id'] == $this->_vars['current_lang']): ?>text<?php else: ?>hidden<?php endif; ?>" name="title_<?php echo $this->_vars['lang_id']; ?>
" value="<?php echo $this->_run_modifier($this->_vars['data'][$this->_vars['title']], 'escape', 'plugin', 1); ?>
" lang-editor="value" lang-editor-type="data-name" lang-editor-lid="<?php echo $this->_vars['lang_id']; ?>
" class="long" />
						<?php endforeach; endif; ?>
						<a href="#" lang-editor="button" lang-editor-type="data-name"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-translate.png" width="16" height="16"></a>
					</div>
				</div>
				<div class="row">
					<div class="h"><?php echo l('field_annotation', 'content', '', 'text', array()); ?>:&nbsp;* </div>
					<div class="v">
						<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['lang_id'] => $this->_vars['lang_item']): ?>
						<?php $this->assign('annotation', 'annotation_'.$this->_vars['lang_id']); ?>
						<?php if ($this->_vars['lang_id'] == $this->_vars['current_lang']): ?>
						<textarea name="annotation_<?php echo $this->_vars['lang_id']; ?>
" rows="10" cols="80" class="long" 
							lang-editor="value" lang-editor-type="data-annotation" 
							lang-editor-lid="<?php echo $this->_vars['lang_id']; ?>
"><?php echo $this->_run_modifier($this->_vars['data'][$this->_vars['annotation']], 'escape', 'plugin', 1); ?>
</textarea>
						<?php else: ?>
						<input type="hidden" name="annotation_<?php echo $this->_vars['lang_id']; ?>
" value="<?php echo $this->_run_modifier($this->_vars['data'][$this->_vars['annotation']], 'escape', 'plugin', 1); ?>
" 
							lang-editor="value" lang-editor-type="data-annotation" lang-editor-lid="<?php echo $this->_vars['lang_id']; ?>
">
						<?php endif; ?>
						<?php endforeach; endif; ?>
						<a href="#" lang-editor="button" lang-editor-type="data-annotation" lang-field-type="textarea">
							<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-translate.png" width="16" height="16">
						</a>
					</div>
				</div>
			</div>
			<br>
			<div class="menu-level3">
				<ul id="info_lang">
					<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['lang_id'] => $this->_vars['item']): ?>
					<li<?php if ($this->_vars['lang_id'] == $this->_vars['current_lang']): ?> class="active"<?php endif; ?> id="info_lang_<?php echo $this->_vars['lang_id']; ?>
"><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/content/edit/<?php echo $this->_vars['lang_id']; ?>
/<?php echo $this->_vars['data']['id']; ?>
" data-id="<?php echo $this->_vars['lang_id']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</a></div></li>
					<?php endforeach; endif; ?>
				</ul>
			</div>
			<div class="edit-form n150">
				<div class="row header"><?php echo l('field_content', 'content', '', 'text', array()); ?></div>
				<div class="row" id="info_content">
					<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['lang_id'] => $this->_vars['lang_item']): ?>
					<div id="info_content_<?php echo $this->_vars['lang_id']; ?>
" class="info_content <?php if ($this->_vars['lang_id'] != $this->_vars['current_lang']): ?>hide<?php endif; ?>">
						<?php echo $this->_vars['data']['content_fck'][$this->_vars['lang_id']]; ?>

					</div>
					<?php endforeach; endif; ?>
				</div>
			</div>
			<?php echo tpl_function_block(array('name' => 'lang_inline_editor','module' => 'start'), $this);?>
			<div class="clr"></div>
			<script><?php echo '
				$(function(){
					$(\'#info_lang\').find(\'li a\').bind(\'click\', function(){
						var lang_id = $(this).data(\'id\');
						$(\'#info_lang\').find(\'li\').removeClass(\'active\');
						$(\'#info_content\').find(\'.info_content\').hide();
						$(\'#info_lang_\'+lang_id).addClass(\'active\');
						$(\'#info_content_\'+lang_id).show();
						return false;
					});
				});
			'; ?>
</script>
			<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
			<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/content/index/<?php echo $this->_vars['current_lang']; ?>
"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
		</form>
		
	<?php break; case 'seo':  ?>
		<?php /*
		<?php if (is_array($this->_vars['seo_fields']) and count((array)$this->_vars['seo_fields'])): foreach ((array)$this->_vars['seo_fields'] as $this->_vars['key'] => $this->_vars['section']): ?>
		<form method="post" action="<?php echo $this->_run_modifier($this->_vars['data']['action'], 'escape', 'plugin', 1); ?>
" name="seo_<?php echo $this->_vars['section']['gid']; ?>
_form">
		<div class="edit-form n150">
			<div class="row header"><?php echo $this->_vars['section']['name']; ?>
</div>		
			<?php if ($this->_vars['section']['tooltip']): ?>
			<div class="row">
				<div class="h">&nbsp;</div>
				<div class="v"><?php echo $this->_vars['section']['tooltip']; ?>
</div>
			</div>
			<?php endif; ?>
			<?php if (is_array($this->_vars['section']['fields']) and count((array)$this->_vars['section']['fields'])): foreach ((array)$this->_vars['section']['fields'] as $this->_vars['field']): ?>
			<div class="row">
				<div class="h"><?php echo $this->_vars['field']['name']; ?>
: </div>
				<div class="v">
					<?php $this->assign('field_gid', $this->_vars['field']['gid']); ?>
											<?php switch($this->_vars['field']['type']): case 'checkbox':  ?>
							<input type="hidden" name="<?php echo $this->_vars['section']['gid']; ?>
[<?php echo $this->_vars['field_gid']; ?>
]" value="0">
							<input type="checkbox" name="<?php echo $this->_vars['section']['gid']; ?>
[<?php echo $this->_vars['field_gid']; ?>
]" value="1" <?php if ($this->_vars['seo_settings'][$this->_vars['field_gid']]): ?>checked<?php endif; ?>>
						<?php break; case 'text':  ?>
							<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['lang_id'] => $this->_vars['lang_item']): ?>
							<?php $this->assign('section_gid', $this->_vars['section']['gid'].'_'.$this->_vars['lang_id']); ?>
							<input type="<?php if ($this->_vars['lang_id'] == $this->_vars['current_lang_id']): ?>text<?php else: ?>hidden<?php endif; ?>" name="<?php echo $this->_vars['section']['gid']; ?>
[<?php echo $this->_vars['field_gid']; ?>
][<?php echo $this->_vars['lang_id']; ?>
]" value="<?php echo $this->_run_modifier($this->_vars['seo_settings'][$this->_vars['section_gid']][$this->_vars['field_gid']], 'escape', 'plugin', 1); ?>
" class="long" lang-editor="value" lang-editor-type="<?php echo $this->_vars['section']['gid']; ?>
_<?php echo $this->_vars['field_gid']; ?>
" lang-editor-lid="<?php echo $this->_vars['lang_id']; ?>
">
							<?php endforeach; endif; ?>
							<a href="#" lang-editor="button" lang-editor-type="<?php echo $this->_vars['section']['gid']; ?>
_<?php echo $this->_vars['field_gid']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-translate.png" width="16" height="16" alt="<?php echo l('note_types_translate', 'start', '', 'button', array()); ?>" title="<?php echo l('note_types_translate', 'start', '', 'button', array()); ?>"></a>
						<?php break; case 'textarea':  ?>
							<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['lang_id'] => $this->_vars['lang_item']): ?>
								<?php $this->assign('section_gid', $this->_vars['section']['gid'].'_'.$this->_vars['lang_id']); ?>
								<?php if ($this->_vars['lang_id'] == $this->_vars['current_lang_id']): ?>
								<textarea name="<?php echo $this->_vars['section']['gid']; ?>
[<?php echo $this->_vars['field_gid']; ?>
][<?php echo $this->_vars['lang_id']; ?>
]" rows="5" cols="80" class="long" lang-editor="value" lang-editor-type="<?php echo $this->_vars['section']['gid']; ?>
_<?php echo $this->_vars['field_gid']; ?>
" lang-editor-lid="<?php echo $this->_vars['lang_id']; ?>
"><?php echo $this->_run_modifier($this->_vars['seo_settings'][$this->_vars['section_gid']][$this->_vars['field_gid']], 'escape', 'plugin', 1); ?>
</textarea>
								<?php else: ?>
								<input type="hidden" name="<?php echo $this->_vars['section']['gid']; ?>
[<?php echo $this->_vars['field_gid']; ?>
][<?php echo $this->_vars['lang_id']; ?>
]" value="<?php echo $this->_run_modifier($this->_vars['seo_settings'][$this->_vars['section_gid']][$this->_vars['field_gid']], 'escape', 'plugin', 1); ?>
" lang-editor="value" lang-editor-type="<?php echo $this->_vars['section']['gid']; ?>
_<?php echo $this->_vars['field_gid']; ?>
" lang-editor-lid="<?php echo $this->_vars['lang_id']; ?>
">
								<?php endif; ?>
							<?php endforeach; endif; ?>
							<a href="#" lang-editor="button" lang-editor-type="<?php echo $this->_vars['section']['gid']; ?>
_<?php echo $this->_vars['field']['gid']; ?>
" lang-field-type="textarea"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-translate.png" width="16" height="16" alt="<?php echo l('note_types_translate', 'start', '', 'button', array()); ?>" title="<?php echo l('note_types_translate', 'start', '', 'button', array()); ?>"></a>					
					<?php break; endswitch; ?><br><?php echo $this->_vars['field']['tooltip']; ?>
					
				</div>
			</div>
			<?php endforeach; endif; ?>	
		</div>			
		<br>
		<div class="menu-level3">
			<ul id="info_lang">
				<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['lang_id'] => $this->_vars['item']): ?>
				<li<?php if ($this->_vars['lang_id'] == $this->_vars['current_lang']): ?> class="active"<?php endif; ?> id="info_lang_<?php echo $this->_vars['lang_id']; ?>
"><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/content/edit/<?php echo $this->_vars['lang_id']; ?>
/<?php echo $this->_vars['data']['id']; ?>
" data-id="<?php echo $this->_vars['lang_id']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</a></div></li>
				<?php endforeach; endif; ?>
			</ul>
		</div>
		<div class="edit-form n150">
			<div class="row header"><?php echo l('field_content', 'content', '', 'text', array()); ?></div>
			<div class="row" id="info_content">
				<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['lang_id'] => $this->_vars['lang_item']): ?>
				<div id="info_content_<?php echo $this->_vars['lang_id']; ?>
" class="info_content <?php if ($this->_vars['lang_id'] != $this->_vars['current_lang']): ?>hide<?php endif; ?>">
					<?php echo $this->_vars['data']['content_fck'][$this->_vars['lang_id']]; ?>

				</div>
				<?php endforeach; endif; ?>
			</div>
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save_<?php echo $this->_vars['section']['gid']; ?>
" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
		<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/content/index/<?php echo $this->_vars['current_lang']; ?>
"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>	
		<input type="hidden" name="btn_save" value="1">
		</form>
		<div class="clr"></div>
		<?php endforeach; endif; ?>
		<?php echo tpl_function_block(array('name' => 'lang_inline_editor','module' => 'start'), $this);?>
		*/  break; endswitch; ?>

<script><?php echo '
	$(function(){
		$("div.row:visible:odd").addClass("zebra");
	});
'; ?>
</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
