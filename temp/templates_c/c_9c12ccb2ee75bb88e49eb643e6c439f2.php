<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 00:08:54 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_moderation_menu'), $this);?>
<div class="actions">&nbsp;</div>

<form method="post" action="<?php echo $this->_vars['form_save_link']; ?>
" name="moder_sattings_save">
	<div class="edit-form n150">
		<?php if (is_array($this->_vars['moder_types']) and count((array)$this->_vars['moder_types'])): foreach ((array)$this->_vars['moder_types'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<div class="row<?php if (!($this->_vars['key'] % 2)): ?> zebra<?php endif; ?>">
			<div class="h"><?php echo l('mtype_'.$this->_vars['item']['name'], 'moderation', '', 'text', array()); ?>:</div>
			<div class="v">
				<input type="hidden" name="type_id[]" value="<?php echo $this->_vars['item']['id']; ?>
">
				<?php if ($this->_vars['item']['mtype'] >= 0): ?>
				<input type="radio" name="mtype[<?php echo $this->_vars['item']['id']; ?>
]" value="2" id="mtype_<?php echo $this->_vars['item']['id']; ?>
_2"<?php if ($this->_vars['item']['mtype'] == '2'): ?>checked<?php endif; ?>><label for="mtype_<?php echo $this->_vars['item']['id']; ?>
_2"><?php echo l('mtype_2', 'moderation', '', 'text', array()); ?></label><br>
				<input type="radio" name="mtype[<?php echo $this->_vars['item']['id']; ?>
]" value="1" id="mtype_<?php echo $this->_vars['item']['id']; ?>
_1"<?php if ($this->_vars['item']['mtype'] == '1'): ?>checked<?php endif; ?>><label for="mtype_<?php echo $this->_vars['item']['id']; ?>
_1"><?php echo l('mtype_1', 'moderation', '', 'text', array()); ?></label><br>
				<input type="radio" name="mtype[<?php echo $this->_vars['item']['id']; ?>
]" value="0" id="mtype_<?php echo $this->_vars['item']['id']; ?>
_0"<?php if ($this->_vars['item']['mtype'] == '0'): ?>checked<?php endif; ?>><label for="mtype_<?php echo $this->_vars['item']['id']; ?>
_0"><?php echo l('mtype_0', 'moderation', '', 'text', array()); ?></label><br>
				<?php else: ?>
				<input type="hidden" value="mtype[<?php echo $this->_vars['item']['id']; ?>
]" value="<?php echo $this->_vars['item']['mtype']; ?>
">
				<?php endif; ?>
				<input type="checkbox" name="check_badwords[<?php echo $this->_vars['item']['id']; ?>
]" value="1" <?php if ($this->_vars['item']['check_badwords'] == '1'): ?>checked<?php endif; ?> id="chbw_<?php echo $this->_vars['item']['id']; ?>
"><label for="chbw_<?php echo $this->_vars['item']['id']; ?>
"><?php echo l('check_badwords', 'moderation', '', 'text', array()); ?></label>
			</div>
		</div>
		<?php endforeach; endif; ?>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="sbmBtn" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
</form>


<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>