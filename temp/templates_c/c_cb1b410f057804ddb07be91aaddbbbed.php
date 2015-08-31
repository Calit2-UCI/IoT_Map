<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 17:34:56 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">
			<?php if ($this->_vars['data']['id']): ?>
				<?php echo l('admin_header_region_change', 'countries', '', 'text', array()); ?>
			<?php else: ?>
				<?php echo l('admin_header_region_add', 'countries', '', 'text', array()); ?>
			<?php endif; ?>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_country', 'countries', '', 'text', array()); ?>: </div>
			<div class="v"><?php echo $this->_vars['country']['name']; ?>
 (<?php echo $this->_vars['country']['code']; ?>
)</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_region_name', 'countries', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="hidden" value="<?php echo $this->_vars['data']['name']; ?>
" name="name" />
				<input type="text" value="<?php if ($this->_vars['validate_lang']):  echo $this->_vars['validate_lang'][$this->_vars['cur_lang']];  else:  echo $this->_vars['data']['name'];  endif; ?>" name="langs[<?php echo $this->_vars['cur_lang']; ?>
]">
				<?php if ($this->_vars['languages_count'] > 1): ?>
					&nbsp;&nbsp;<a href="#" onclick="showLangs('name_langs');
							return false;"><?php echo l('others_languages', 'services', '', 'text', array()); ?></a><br>
					<div id="name_langs" class="hide p-top2">
						<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['lang_id'] => $this->_vars['item']): ?>
							<?php if ($this->_vars['lang_id'] != $this->_vars['cur_lang']): ?>
								<input type="text" value="<?php if ($this->_vars['validate_lang']):  echo $this->_vars['validate_lang'][$this->_vars['lang_id']];  else:  echo $this->_vars['data']['name'];  endif; ?>" name="langs[<?php echo $this->_vars['lang_id']; ?>
]">&nbsp;|&nbsp;<?php echo $this->_vars['item']['name']; ?>
<br>
							<?php endif; ?>
						<?php endforeach; endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="btn">
		<div class="l">
			<input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>">
		</div>
	</div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/countries/country/<?php echo $this->_vars['country']['code']; ?>
"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<div class="clr"></div>
<script><?php echo '
	$(function() {
		$("div.row:odd").addClass("zebra");
	});
	function showLangs(divId) {
		$(\'#\' + divId).slideToggle();
	}
'; ?>
</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>