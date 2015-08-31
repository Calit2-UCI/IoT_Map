<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 00:11:52 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "start". $this->module_templates.  $this->get_current_theme_gid('', '"start"'). "numerics_menu.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="actions">&nbsp;</div>

<?php if ($this->_vars['section'] == 'overview'): ?>
	<div class="right-side">
		<table cellspacing="0" cellpadding="0" class="data" width="100%">
			<?php if (is_array($this->_vars['settings_data']['other']) and count((array)$this->_vars['settings_data']['other'])): foreach ((array)$this->_vars['settings_data']['other'] as $this->_vars['module'] => $this->_vars['module_data']): ?>
				<tr>
					<th class="first" colspan=2><?php echo $this->_vars['module_data']['name']; ?>
</th>
				</tr>
				<?php if (is_array($this->_vars['module_data']['vars']) and count((array)$this->_vars['module_data']['vars'])): foreach ((array)$this->_vars['module_data']['vars'] as $this->_vars['key'] => $this->_vars['item']): ?>
					<tr<?php if (!($this->_vars['key'] % 2)): ?> class="zebra"<?php endif; ?>>
						<td class="first"><?php echo $this->_vars['item']['field_name']; ?>
</td>
						<td>
							<?php if ($this->_vars['item']['field_type'] == 'checkbox'): ?>
								<?php if ($this->_vars['item']['value']):  echo l('yes_str', 'start', '', 'text', array());  else:  echo l('no_str', 'start', '', 'text', array());  endif; ?>
							<?php elseif ($this->_vars['item']['field_type'] == 'select'): ?>
								<?php echo $this->_vars['item']['value_name']; ?>

							<?php else: ?>
								<?php echo $this->_vars['item']['value']; ?>

							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; endif; ?>
			<?php endforeach; endif; ?>
		</table>
	</div>

	<div class="left-side">
		<table cellspacing="0" cellpadding="0" class="data" width="100%">
			<?php if (is_array($this->_vars['settings_data']['numerics']) and count((array)$this->_vars['settings_data']['numerics'])): foreach ((array)$this->_vars['settings_data']['numerics'] as $this->_vars['module'] => $this->_vars['module_data']): ?>
				<tr>
					<th class="first" colspan=2><?php echo $this->_vars['module_data']['name']; ?>
</th>
				</tr>
				<?php if (is_array($this->_vars['module_data']['vars']) and count((array)$this->_vars['module_data']['vars'])): foreach ((array)$this->_vars['module_data']['vars'] as $this->_vars['key'] => $this->_vars['item']): ?>
					<tr<?php if (!($this->_vars['key'] % 2)): ?> class="zebra"<?php endif; ?>>
						<td class="first"><?php echo $this->_vars['item']['field_name']; ?>
</td>
						<td class="w30">
							<?php if ($this->_vars['item']['field_type'] == 'checkbox'): ?>
								<?php if ($this->_vars['item']['value']):  echo l('yes_str', 'start', '', 'text', array());  else:  echo l('no_str', 'start', '', 'text', array());  endif; ?>
							<?php elseif ($this->_vars['item']['field_type'] == 'select'): ?>
								<?php echo $this->_vars['item']['value_name']; ?>

							<?php else: ?>
								<?php echo $this->_vars['item']['value']; ?>

							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; endif; ?>
			<?php endforeach; endif; ?>
		</table>
	</div>
	<div class="clr"><a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/start/menu/system-items"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a></div>
<?php elseif ($this->_vars['section'] == 'numerics'): ?>
	<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
		<div class="edit-form n250">
			<?php if (is_array($this->_vars['settings_data']) and count((array)$this->_vars['settings_data'])): foreach ((array)$this->_vars['settings_data'] as $this->_vars['module'] => $this->_vars['module_data']): ?>
				<div class="row header"><?php echo $this->_vars['module_data']['name']; ?>
</div>
				<?php if (is_array($this->_vars['module_data']['vars']) and count((array)$this->_vars['module_data']['vars'])): foreach ((array)$this->_vars['module_data']['vars'] as $this->_vars['key'] => $this->_vars['item']): ?>
					<div class="row<?php if (!($this->_vars['key'] % 2)): ?> zebra<?php endif; ?>">
						<div class="h"><?php echo $this->_vars['item']['field_name']; ?>
:</div>
						<div class="v"><input type="text" name="settings[<?php echo $this->_vars['module']; ?>
][<?php echo $this->_vars['item']['field']; ?>
]" value="<?php echo $this->_run_modifier($this->_vars['item']['value'], 'escape', 'plugin', 1); ?>
" class="short"></div>
					</div>
					<br>
				<?php endforeach; endif; ?>
			<?php endforeach; endif; ?>
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
		<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/start/menu/system-items"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
	</form>
<?php elseif ($this->_vars['section'] == 'date_formats'): ?>
	<table cellspacing="0" cellpadding="0" class="data" width="100%">
		<tr>
			<th class="w150 first"><?php echo $this->_vars['settings_data']['name']; ?>
</th>
			<th class="w200"><?php echo l('example', 'start', '', 'text', array()); ?></th>
			<th><?php echo l('date_formats_used_in', 'start', '', 'text', array()); ?></th>
			<th class="w50 center">&nbsp;</th>
		</tr>
		<?php if (is_array($this->_vars['settings_data']['vars']) and count((array)$this->_vars['settings_data']['vars'])): foreach ((array)$this->_vars['settings_data']['vars'] as $this->_vars['key'] => $this->_vars['var']): ?>
			<?php $this->assign('field', $this->_vars['var']['field']); ?>
			<?php if ($this->_vars['date_formats_pages'][$this->_vars['field']]): ?>
				<tr>
					<td><?php echo $this->_vars['var']['field_name']; ?>
</td>
					<td><?php echo $this->_vars['var']['value']; ?>
</td>
					<td>
						<span id="<?php echo $this->_vars['field']; ?>
" class="tooltip">
							<?php echo l('date_formats_'.$this->_vars['field'].'_description', 'start', '', 'text', array()); ?>
						</span>
						<span id="tt_<?php echo $this->_vars['field']; ?>
" class="hide">
							<div class="tooltip-info">
								<?php if (is_array($this->_vars['date_formats_pages'][$this->_vars['field']]) and count((array)$this->_vars['date_formats_pages'][$this->_vars['field']])): foreach ((array)$this->_vars['date_formats_pages'][$this->_vars['field']] as $this->_vars['page']): ?>
									<?php echo $this->_vars['site_url'];  echo $this->_vars['page']; ?>
<br/>
								<?php endforeach; endif; ?>
							</div>
						</span>
					</td>
					<td class="center">
						<a href="<?php echo $this->_vars['site_url']; ?>
admin/start/date_formats/<?php echo $this->_vars['field']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_date_format', 'start', '', 'text', array()); ?>" title="<?php echo l('link_edit_date_format', 'start', '', 'text', array()); ?>"></a>
					</td>
				</tr>
			<?php endif; ?>
		<?php endforeach; else: ?>
			<tr><td colspan="8" class="center"><?php echo l('no_date_formats', 'start', '', 'text', array()); ?></td></tr>
		<?php endif; ?>
	</table>
	<?php echo tpl_function_js(array('file' => 'easyTooltip.min.js'), $this);?>
	<?php echo '
	<script type="text/javascript">
		$(function(){
			$(".tooltip").each(function(){
				$(this).easyTooltip({
					useElement: \'tt_\'+$(this).attr(\'id\'),
					yOffset: $(\'#tt_\'+$(this).attr(\'id\')).height()/2,
					clickRemove: true
				});
			});
		});
	</script>
	'; ?>

<?php else: ?>
	<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
		<div class="edit-form n250">
			<div class="row header"><?php echo $this->_vars['settings_data']['name']; ?>
</div>
			<?php if (is_array($this->_vars['settings_data']['vars']) and count((array)$this->_vars['settings_data']['vars'])): foreach ((array)$this->_vars['settings_data']['vars'] as $this->_vars['key'] => $this->_vars['item']): ?>
				<div class="row<?php if (!($this->_vars['key'] % 2)): ?> zebra<?php endif; ?>">
				<?php if ($this->_vars['section'] == 'countries'): ?>
					<div class="h"><?php echo $this->_vars['item']['field_name']; ?>
:</div>
					<div class="v"><input type="text" name="settings[<?php echo $this->_vars['item']['field']; ?>
]" value="<?php echo $this->_run_modifier($this->_vars['item']['value'], 'escape', 'plugin', 1); ?>
" class="middle"><br><i><?php echo l($this->_vars['item']['field'].'_settings_descr', 'countries', '', 'text', array()); ?></i></div>
				<?php else: ?>
					<div class="h"><?php echo $this->_vars['item']['field_name']; ?>
:</div>
					<div class="v">
						<?php if ($this->_vars['item']['field_type'] == 'text' || ! $this->_vars['item']['field_type']): ?>
							<input type="text" name="settings[<?php echo $this->_vars['item']['field']; ?>
]" value="<?php echo $this->_run_modifier($this->_vars['item']['value'], 'escape', 'plugin', 1); ?>
" class="short">
						<?php elseif ($this->_vars['item']['field_type'] == 'checkbox'): ?>
							<input type="checkbox" name="settings[<?php echo $this->_vars['item']['field']; ?>
]" value="1" <?php if ($this->_vars['item']['value']): ?>checked<?php endif; ?> class="short">
						<?php elseif ($this->_vars['item']['field_type'] == 'select'): ?>
							<select name="settings[<?php echo $this->_vars['item']['field']; ?>
]" class="short">
								<?php if (is_array($this->_vars['item']['field_values']) and count((array)$this->_vars['item']['field_values'])): foreach ((array)$this->_vars['item']['field_values'] as $this->_vars['key'] => $this->_vars['field_value']): ?>
									<option value="<?php echo $this->_vars['key']; ?>
"<?php if ($this->_vars['item']['value'] == $this->_vars['key']): ?> selected<?php endif; ?>><?php echo l($this->_vars['section'].'_'.$this->_vars['item']['field'].'_'.$this->_vars['field_value'].'_value', 'start', '', 'text', array()); ?></option>
								<?php endforeach; endif; ?>
							</select>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				</div>
			<?php endforeach; endif; ?>
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
		<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/start/menu/system-items"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
	</form>
<?php endif; ?>
<div class="clr"></div>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>