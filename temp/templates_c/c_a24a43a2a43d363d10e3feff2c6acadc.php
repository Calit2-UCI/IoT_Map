<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 17:47:47 Pacific Daylight Time */ ?>

<form id="network-form" method="post" action="<?php echo $this->_vars['site_url']; ?>
admin/network/index" name="save-form">
	<div class="edit-form">
		<h2 class="row header">
			<?php echo l('admin_header_data', 'network', '', 'text', array()); ?>
		</h2>
		
		<div class="row">
			<div class="h">
				<?php echo l('admin_field_domain', 'network', '', 'text', array()); ?>
			</div>
			<div class="v">
				<input id="network-domain"  type="text" value="<?php echo $this->_vars['data']['domain']; ?>
" name="domain" class="long">
			</div>
		</div>
		
		<div class="row">
			<div class="h">
				<?php echo l('admin_field_key', 'network', '', 'text', array()); ?>:
			</div>
			<div class="v">
				<input id="network-key" required class="long" type="text" value="<?php echo $this->_vars['data']['key']; ?>
" name="key">
			</div>
		</div>
        
        <div class="row">
			<div class="h">
				<?php echo l('admin_field_is_upload_photos', 'network', '', 'text', array()); ?>:
			</div>
			<div class="v">
                <input type="hidden" name="is_upload_photos" value="0">
				<input id="network-is-upload-photos" type="checkbox" value="1" name="is_upload_photos" <?php if ($this->_vars['data']['is_upload_photos']): ?>checked<?php endif; ?>>
			</div>
		</div>
		<h2 class="row header">
			<?php echo l('admin_header_filter', 'network', '', 'text', array()); ?>:
		</h2>
		
		<?php if (is_array($this->_vars['form_fields']) and count((array)$this->_vars['form_fields'])): foreach ((array)$this->_vars['form_fields'] as $this->_vars['fields']): ?>
			<div class="row">
				<?php echo tpl_function_block(array('name' => 'multiselect','module' => 'start','fields' => $this->_vars['fields'],'selected' => $this->_vars['selected_options'],'limits' => $this->_vars['form_limits'],'all_value' => 'all'), $this);?>
			</div>
		<?php endforeach; endif; ?>
		
		<div class="row">
			<div class="h">
				<?php echo l('admin_field_age', 'network', '', 'text', array()); ?>:
			</div>
			<div class="v">
				<?php echo l('admin_field_age_min', 'network', '', 'text', array()); ?>
				<input class="short" type="number" min="<?php echo $this->_vars['age_min']; ?>
" max="<?php echo $this->_vars['age_max']; ?>
" name="min_age" value="<?php echo $this->_vars['data']['min_age']; ?>
">
				<?php echo l('admin_field_age_max', 'network', '', 'text', array()); ?>
				<input class="short" type="number" min="<?php echo $this->_vars['age_min']; ?>
" max="<?php echo $this->_vars['age_max']; ?>
" name="max_age" value="<?php echo $this->_vars['data']['max_age']; ?>
">
			</div>
		</div>
	</div>
	<div class="btn">
		<div class="l">
			<input type="submit" name="btn-save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>">
		</div>
	</div>
	<a class="cancel" href="<?php echo $this->_vars['back_url']; ?>
"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
