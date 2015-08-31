<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 17:27:30 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form">
	<div class="edit-form n150">
		<div class="row header"><?php if ($this->_vars['add_flag']):  echo l('admin_header_ds_item_add', 'properties', '', 'text', array());  else:  echo l('admin_header_ds_item_change', 'properties', '', 'text', array());  endif; ?>&nbsp;[ID = <?php echo $this->_vars['option_gid']; ?>
]</div>
		<?php if (is_array($this->_vars['langs']) and count((array)$this->_vars['langs'])): foreach ((array)$this->_vars['langs'] as $this->_vars['lang_id'] => $this->_vars['item']): ?>
			<div class="row striped">
				<div class="h"><?php echo $this->_vars['item']['name']; ?>
: </div>
				<div class="v"><input type="text" value="<?php echo $this->_run_modifier($this->_vars['lang_data'][$this->_vars['lang_id']], 'escape', 'plugin', 1); ?>
" name="lang_data[<?php echo $this->_vars['lang_id']; ?>
]" class="long"></div>
			</div>
		<?php endforeach; endif; ?>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/properties/property/<?php echo $this->_vars['current_gid']; ?>
/<?php echo $this->_vars['current_lang_id']; ?>
/"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<div class="clr"></div>
<script><?php echo '
	$("div.row:odd").addClass("zebra");
'; ?>
</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>