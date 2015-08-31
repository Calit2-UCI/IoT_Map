<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.json_encode.php');
$this->register_function("json_encode", "tpl_function_json_encode"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 17:47:47 Pacific Daylight Time */ ?>

<div id="multiselect_<?php echo $this->_vars['multiselect_helper_data']['rand']; ?>
" class="multiselect">
	<?php $this->assign('active', $this->_vars['multiselect_helper_data']['active_field']); ?>
	<div class="header">
		<?php echo $this->_vars['multiselect_helper_data']['fields'][$this->_vars['active']]['header']; ?>

	</div>
	<div class="selected">
		<ul class="selected-items">
			<?php if (! empty ( $this->_vars['multiselect_helper_data']['selected'][$this->_vars['active']] )): ?>
				<?php if (! empty ( $this->_vars['multiselect_helper_data']['all_selected'][$this->_vars['active']] )): ?>
					<input type="hidden" name="<?php echo $this->_vars['active']; ?>
[]" value="<?php echo $this->_vars['multiselect_helper_data']['all_value']; ?>
">
					<?php if (is_array($this->_vars['multiselect_helper_data']['fields'][$this->_vars['active']]['option']) and count((array)$this->_vars['multiselect_helper_data']['fields'][$this->_vars['active']]['option'])): foreach ((array)$this->_vars['multiselect_helper_data']['fields'][$this->_vars['active']]['option'] as $this->_vars['selectedKey'] => $this->_vars['selectedItem']): ?>
						<li class="item <?php echo $this->_vars['active']; ?>
-selected-<?php echo $this->_vars['selectedKey']; ?>
"><?php echo $this->_run_modifier($this->_vars['multiselect_helper_data']['fields'][$this->_vars['active']]['option'][$this->_vars['selectedKey']], 'trim', 'PHP', 1); ?>
</li>
					<?php endforeach; endif; ?>
				<?php else: ?>
					<?php if (is_array($this->_vars['multiselect_helper_data']['selected'][$this->_vars['active']]) and count((array)$this->_vars['multiselect_helper_data']['selected'][$this->_vars['active']])): foreach ((array)$this->_vars['multiselect_helper_data']['selected'][$this->_vars['active']] as $this->_vars['selectedKey'] => $this->_vars['selectedItem']): ?>
						<li class="item <?php echo $this->_vars['active']; ?>
-selected-<?php echo $this->_vars['selectedKey']; ?>
"><input type="hidden" name="<?php echo $this->_vars['active']; ?>
[]" value="<?php echo $this->_vars['selectedKey']; ?>
"><?php echo $this->_vars['multiselect_helper_data']['fields'][$this->_vars['active']]['option'][$this->_vars['selectedKey']]; ?>
</li>
					<?php endforeach; endif; ?>
				<?php endif; ?>
			<?php endif; ?>
		</ul>
		<a class="options_open" href="javascript:void(0);"><?php echo l('multiselect_change', 'start', '', 'text', array()); ?></a>
	</div>
	<div class="options hide">
		<header>
			<ul class="tabs">
				<?php if (is_array($this->_vars['multiselect_helper_data']['fields']) and count((array)$this->_vars['multiselect_helper_data']['fields'])): foreach ((array)$this->_vars['multiselect_helper_data']['fields'] as $this->_vars['fieldKey'] => $this->_vars['field']): ?>
					<li data-tab="<?php echo $this->_vars['fieldKey']; ?>
" class="tab_<?php echo $this->_vars['fieldKey']; ?>
 tab<?php if ($this->_vars['fieldKey'] == $this->_vars['active']): ?> active<?php endif; ?>">
						<a href="javascript:void(0);"><?php echo $this->_vars['field']['header']; ?>
</a>
					</li>
				<?php endforeach; endif; ?>
			</ul>
			<a class="pop-up-close close" href="javascript:void(0);"><i class="fright icon-remove edge icon-big hover w"></i></a>
		</header>
		<?php if (is_array($this->_vars['multiselect_helper_data']['fields']) and count((array)$this->_vars['multiselect_helper_data']['fields'])): foreach ((array)$this->_vars['multiselect_helper_data']['fields'] as $this->_vars['fieldKey'] => $this->_vars['field']): ?>
			<div class="options_<?php echo $this->_vars['fieldKey']; ?>
 tab-content<?php if ($this->_vars['fieldKey'] != $this->_vars['active']): ?> hide<?php endif; ?>">
				<ul class="items">
					<?php if (is_array($this->_vars['multiselect_helper_data']['fields'][$this->_vars['fieldKey']]['option']) and count((array)$this->_vars['multiselect_helper_data']['fields'][$this->_vars['fieldKey']]['option'])): foreach ((array)$this->_vars['multiselect_helper_data']['fields'][$this->_vars['fieldKey']]['option'] as $this->_vars['optionKey'] => $this->_vars['item']): ?>
						<li title="<?php echo $this->_vars['item']; ?>
" data-value="<?php echo $this->_vars['optionKey']; ?>
" 
							class="<?php echo $this->_vars['fieldKey']; ?>
-option-<?php echo $this->_vars['optionKey']; ?>
 item
							<?php if ($this->_vars['active'] == $this->_vars['fieldKey'] && ( ! empty ( $this->_vars['multiselect_helper_data']['all_selected'][$this->_vars['active']] ) || ! empty ( $this->_vars['multiselect_helper_data']['selected_keys'][$this->_vars['fieldKey']][$this->_vars['optionKey']] ) )): ?> selected<?php endif; ?>"
							>
							<?php echo $this->_vars['item']; ?>

						</li>
					<?php endforeach; endif; ?>
				</ul>
			</div>
		<?php endforeach; endif; ?>
		<footer>
			<div class="limit <?php if (! empty ( $this->_vars['multiselect_helper_data']['all_selected'][$this->_vars['active']] )): ?> hide<?php endif; ?>">
				<span class="selected_num">0</span> 
				<?php echo l('multiselect_of', 'start', '', 'text', array()); ?>
				<span class="max_num"></span><br>
				<?php echo l('multiselect_selected', 'start', '', 'text', array()); ?>
			</div>
			<div class="options-selected">
				<ul class="options-selected-items">
					<?php if (! empty ( $this->_vars['multiselect_helper_data']['selected'][$this->_vars['active']] )): ?>
						<?php if (! empty ( $this->_vars['multiselect_helper_data']['all_selected'][$this->_vars['active']] )): ?>
							<li data-value="<?php echo $this->_vars['multiselect_helper_data']['all_value']; ?>
" class="item remove-selected item <?php echo $this->_vars['active']; ?>
-selected-<?php echo $this->_vars['multiselect_helper_data']['all_value']; ?>
">
								<?php echo $this->_vars['multiselect_helper_data']['all_text']; ?>
<i class="fa fa-times"></i>
							</li>
						<?php else: ?>
							<?php if (is_array($this->_vars['multiselect_helper_data']['selected'][$this->_vars['active']]) and count((array)$this->_vars['multiselect_helper_data']['selected'][$this->_vars['active']])): foreach ((array)$this->_vars['multiselect_helper_data']['selected'][$this->_vars['active']] as $this->_vars['selectedKey'] => $this->_vars['selectedItem']): ?>
								<li data-value="<?php echo $this->_vars['selectedKey']; ?>
" class="item remove-selected item <?php echo $this->_vars['active']; ?>
-selected-<?php echo $this->_vars['selectedKey']; ?>
">
									<?php echo $this->_vars['multiselect_helper_data']['fields'][$this->_vars['active']]['option'][$this->_vars['selectedKey']]; ?>
<i class="fa fa-times"></i>
								</li>
							<?php endforeach; endif; ?>
						<?php endif; ?>
					<?php endif; ?>
				</ul>
			</div>
			<div class="buttons">
				<a class="btn_select_all" href="javascript:void(0);"><?php echo l('multiselect_select_all', 'start', '', 'text', array()); ?></a>
				<div class="btn">
					<div class="l">
						<input class="btn_apply" type="button" name="btn_save" value="<?php echo l('btn_apply', 'start', '', 'text', array()); ?>">
					</div>
				</div>
			</div>
		</footer>
	</div>
	<?php echo tpl_function_js(array('module' => 'start','file' => 'multiselect.js'), $this);?>
	<script type='text/javascript'>
		<?php echo '
		$(function () {
			';  if (isset ( $this->_vars['multiselect_helper_data']['var_js_name'] )): ?>var <?php echo $this->_vars['multiselect_helper_data']['var_js_name']; ?>
 = <?php endif;  echo 'new options({
				fields: ';  echo tpl_function_json_encode(array('data' => $this->_vars['multiselect_helper_data']['fields']), $this); echo ',
				allSelected: ';  echo tpl_function_json_encode(array('data' => $this->_vars['multiselect_helper_data']['all_selected']), $this); echo ',
				allText: \'';  echo $this->_vars['multiselect_helper_data']['all_text'];  echo '\',
				allValue: \'';  echo $this->_vars['multiselect_helper_data']['all_value'];  echo '\',
				limits: ';  echo tpl_function_json_encode(array('data' => $this->_vars['multiselect_helper_data']['limits']), $this); echo ',
				rand: \'';  echo $this->_vars['multiselect_helper_data']['rand'];  echo '\',
				selectedField: \'';  echo $this->_vars['multiselect_helper_data']['active_field'];  echo '\',
				selectedItems: ';  echo tpl_function_json_encode(array('data' => $this->_vars['multiselect_helper_data']['selected']), $this); echo ',
				siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
				alertCantSaveEmpty: \'';  echo l('multiselect_please_select', 'start', '', 'text', array());  echo '\'
			});
		});
	'; ?>
</script>
</div>