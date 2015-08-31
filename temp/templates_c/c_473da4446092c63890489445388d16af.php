<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 10:18:34 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form">
	<div class="edit-form n150">
		<div class="row header"><?php if ($this->_vars['data']['id']):  echo l('admin_header_menu_item_change', 'menu', '', 'text', array());  else:  echo l('admin_header_menu_item_add', 'menu', '', 'text', array());  endif; ?></div>
		<div class="row">
			<div class="h"><?php echo l('field_menu_item_gid', 'menu', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['gid']; ?>
" name="gid" class="middle"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_menu_item_link', 'menu', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="radio" value="out" name="link_type" id="link_type_out"<?php if ($this->_vars['data']['link_out']): ?> checked<?php endif; ?>><input type="text" value="<?php echo $this->_vars['data']['link_out']; ?>
" name="link_out" class="long_long" onclick="javascript: check('out');"></label><br><br>
				<input type="radio" value="in" name="link_type" id="link_type_in"<?php if ($this->_vars['data']['link_in']): ?> checked<?php endif; ?>><?php echo $this->_vars['site_url']; ?>
 <input type="text" value="<?php echo $this->_vars['data']['link_in']; ?>
" class="middle" name="link_in" onclick="javascript: check('in');">
			</div>
		</div>
		<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['lang_id'] => $this->_vars['item']): ?>
		<div class="row">
			<div class="h"><?php echo l('field_menu_item_value', 'menu', '', 'text', array()); ?>(<?php echo $this->_vars['item']['name']; ?>
): </div>
			<div class="v">
				<input type="text" class="middle" name="langs[<?php echo $this->_vars['lang_id']; ?>
]" value="<?php echo $this->_vars['data']['langs'][$this->_vars['lang_id']]; ?>
">
			</div>
		</div>
		<?php endforeach; endif; ?>
		<?php if ($this->_vars['indicators']): ?>
		<div class="row">
			<div class="h"><?php echo l('field_indicator', 'menu', '', 'text', array()); ?>: </div>
			<div class="v">
				<select name="indicator_gid" id="indicator" class="middle">
					<option value="0"><?php echo l('no_indicator', 'menu', '', 'text', array()); ?></option>
					<?php if (is_array($this->_vars['indicators']) and count((array)$this->_vars['indicators'])): foreach ((array)$this->_vars['indicators'] as $this->_vars['indicator_gid'] => $this->_vars['indicator']): ?>
						<?php if ($this->_vars['indicator']['name']): ?>
						<option value="<?php echo $this->_vars['indicator_gid']; ?>
"<?php if ($this->_vars['data']['indicator_gid'] == $this->_vars['indicator_gid']): ?> selected="selected"<?php endif; ?>><?php echo $this->_vars['indicator']['name']; ?>
</option>
						<?php endif; ?>
					<?php endforeach; endif; ?>
				</select>
			</div>
		</div>
		<?php endif; ?>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/menu/items/<?php echo $this->_vars['menu_id']; ?>
"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<div class="clr"></div>
<script><?php echo '
function check(type){
	$(\'#link_type_\'+type).attr(\'checked\', \'checked\');
}
$(function(){
	$("div.row:odd").addClass("zebra");
});
'; ?>
</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>