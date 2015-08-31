<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.capture.php');
$this->register_block("capture", "tpl_block_capture"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 20:06:00 Pacific Daylight Time */ ?>

<h2 class="line top bottom linked">
	<?php echo l('table_header_personal', 'users', '', 'text', array()); ?>
</h2>
<div class="view-section">
	<?php 
$this->assign('no_info_str', l('no_information', 'users', '', 'text', array()));
 ?>
	<div class="r">
		<div class="f"><?php echo l('field_user_type', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['user_type_str']; ?>
</div>
	</div>
	<?php if ($this->_vars['data']['looking_user_type_str']): ?>
	<div class="r">
		<div class="f"><?php echo l('field_looking_user_type', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['looking_user_type_str']; ?>
</div>
	</div>
	<?php endif; ?>
	<?php if ($this->_vars['data']['age_min']): ?>
	<div class="r">
		<div class="f"><?php echo l('field_partner_age', 'users', '', 'text', array()); ?> <?php echo l('from', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['age_min']; ?>
</div>
	</div>
	<?php endif; ?>
	<?php if ($this->_vars['data']['age_max']): ?>
	<div class="r">
		<div class="f"><?php echo l('field_partner_age', 'users', '', 'text', array()); ?> <?php echo l('to', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['age_max']; ?>
</div>
	</div>
	<?php endif; ?>
	<div class="r">
		<div class="f"><?php echo l('field_nickname', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['nickname']; ?>
</div>
	</div>
	<?php if ($this->_vars['data']['fname']): ?>
		<div class="r">
			<div class="f"><?php echo l('field_fname', 'users', '', 'text', array()); ?>:</div>
			<div class="v"><?php echo $this->_vars['data']['fname']; ?>
</div>
		</div>
	<?php endif; ?>
	<?php if ($this->_vars['data']['sname']): ?>
		<div class="r">
			<div class="f"><?php echo l('field_sname', 'users', '', 'text', array()); ?>:</div>
			<div class="v"><?php echo $this->_vars['data']['sname']; ?>
</div>
		</div>
	<?php endif; ?>
	<div class="r">
		<div class="f"><?php echo l('birth_date', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['birth_date']; ?>
</div>
	</div>
	<?php if ($this->_vars['data']['location']): ?>
	<div class="r">
		<div class="f"><?php echo l('field_region', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['location']; ?>
</div>
	</div>
	<?php endif; ?>
</div>

<?php if (is_array($this->_vars['sections']) and count((array)$this->_vars['sections'])): foreach ((array)$this->_vars['sections'] as $this->_vars['item']): ?>
	
	<?php $this->_tag_stack[] = array('tpl_block_capture', array('assign' => 'view_fields')); tpl_block_capture(array('assign' => 'view_fields'), null, $this); ob_start(); ?>
		<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('', '"users"'). "custom_view_fields.tpl", array('fields_data' => $this->_vars['item']['fields'],'load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
	<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_capture($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
	<?php if ($this->_run_modifier($this->_vars['view_fields'], 'trim', 'PHP', 1)): ?>
		<h2 class="line top bottom linked">
			<?php echo $this->_vars['item']['name']; ?>

		</h2>
		<div class="view-section"><?php echo $this->_vars['view_fields']; ?>
</div>
	<?php endif; ?>
	
<?php endforeach; endif; ?>
