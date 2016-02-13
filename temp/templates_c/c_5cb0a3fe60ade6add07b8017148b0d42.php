<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-09 00:47:20 Pacific Standard Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header"><?php if ($this->_vars['data']['id']):  echo l('admin_header_service_change', 'services', '', 'text', array());  else:  echo l('admin_header_service_add', 'services', '', 'text', array());  endif; ?></div>
		<div class="row">
			<div class="h"><?php echo l('field_gid', 'services', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['gid']; ?>
" name="gid" class="middle_long"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_name', 'services', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="text"  class="middle_long" value="<?php if ($this->_vars['validate_lang']['name']):  echo $this->_vars['validate_lang']['name'][$this->_vars['cur_lang']];  else:  echo $this->_vars['data']['name'];  endif; ?>" name="langs[name][<?php echo $this->_vars['cur_lang']; ?>
]">
				<?php if ($this->_vars['languages_count'] > 1): ?>
					&nbsp;&nbsp;<a href="#" onclick="showLangs('name_langs'); return false;"><?php echo l('others_languages', 'services', '', 'text', array()); ?></a><br>
					<div id="name_langs" class="hide p-top2">
						<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['lang_id'] => $this->_vars['item']): ?>
							<?php if ($this->_vars['lang_id'] != $this->_vars['cur_lang']): ?>
								<input type="text" class="middle_long" value="<?php if ($this->_vars['validate_lang']['name']):  echo $this->_vars['validate_lang']['name'][$this->_vars['lang_id']];  else:  echo $this->_vars['data']['name'];  endif; ?>" name="langs[name][<?php echo $this->_vars['lang_id']; ?>
]">&nbsp;|&nbsp;<?php echo $this->_vars['item']['name']; ?>
<br>
							<?php endif; ?>
						<?php endforeach; endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_description', 'services', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="text"  class="middle_long" value="<?php if ($this->_vars['validate_lang']['description']):  echo $this->_vars['validate_lang']['description'][$this->_vars['cur_lang']];  else:  echo $this->_vars['data']['description'];  endif; ?>" name="langs[description][<?php echo $this->_vars['cur_lang']; ?>
]">
				<?php if ($this->_vars['languages_count'] > 1): ?>
					&nbsp;&nbsp;<a href="#" onclick="showLangs('description_langs'); return false;"><?php echo l('others_languages', 'services', '', 'text', array()); ?></a><br>
					<div id="description_langs" class="hide p-top2">
						<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['lang_id'] => $this->_vars['item']): ?>
							<?php if ($this->_vars['lang_id'] != $this->_vars['cur_lang']): ?>
								<input type="text" value="<?php if ($this->_vars['validate_lang']['description']):  echo $this->_vars['validate_lang']['description'][$this->_vars['lang_id']];  else:  echo $this->_vars['data']['description'];  endif; ?>" name="langs[description][<?php echo $this->_vars['lang_id']; ?>
]" />&nbsp;|&nbsp;<?php echo $this->_vars['item']['name']; ?>
<br>
							<?php endif; ?>
						<?php endforeach; endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		
		<div class="row">
			<div class="h"><?php echo l('field_pay_type', 'services', '', 'text', array()); ?>: </div>
			<div class="v"><select name="pay_type" class="middle_long">
				<?php if (is_array($this->_vars['pay_type_lang']['option']) and count((array)$this->_vars['pay_type_lang']['option'])): foreach ((array)$this->_vars['pay_type_lang']['option'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['key']; ?>
"<?php if ($this->_vars['key'] == $this->_vars['data']['pay_type']): ?> selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option><?php endforeach; endif; ?>
			</select></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_status', 'services', '', 'text', array()); ?>: </div>
			<div class="v"><input type="checkbox" value="1" <?php if ($this->_vars['data']['status']): ?>checked<?php endif; ?> name="status"></div>
		</div>

		<div class="row">
			<div class="h"><?php echo l('field_template', 'services', '', 'text', array()); ?>: </div>
			<div class="v">
			<?php if ($this->_vars['template']): ?>
				<?php echo $this->_vars['template']['name']; ?>
<input type="hidden" name="template_gid" value="<?php echo $this->_vars['data']['template_gid']; ?>
">
			<?php else: ?>
				<select name="template_gid" onchange="javascript: load_param_block(this.value);">
					<?php if (is_array($this->_vars['templates']) and count((array)$this->_vars['templates'])): foreach ((array)$this->_vars['templates'] as $this->_vars['item']): ?><option value="<?php echo $this->_vars['item']['gid']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</option><?php endforeach; endif; ?>
				</select>
			<?php endif; ?>
			</div>
		</div>

		<div id="admin_params">
		<?php echo $this->_vars['template_block']; ?>

		</div>
		<div id="lds_params">
		<?php echo $this->_vars['lds_block']; ?>

		</div>

	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/services"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<div class="clr"></div>
<script><?php echo '
$(function(){
	$("div.row:odd").addClass("zebra");
});
function showLangs(divId){
	$(\'#\'+divId).slideToggle();
}

function load_param_block(id){
	$(\'#admin_params\').load(\'';  echo $this->_vars['site_url']; ?>
admin/services/ajax_get_template_admin_param_block/<?php echo '\'+id);
	$("div.row:odd").addClass("zebra");
}

'; ?>
</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
