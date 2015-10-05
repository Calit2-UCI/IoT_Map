<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-29 03:57:50 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_js(array('file' => 'easyTooltip.min.js'), $this);?>

<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header"><?php if ($this->_vars['data']['id']):  echo l('admin_header_config_change', 'file_uploads', '', 'text', array());  else:  echo l('admin_header_config_add', 'file_uploads', '', 'text', array());  endif; ?></div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_name', 'file_uploads', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['name']; ?>
" name="name" class="long"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_gid', 'file_uploads', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['gid']; ?>
" name="gid" class="long"></div>
		</div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_max_size', 'file_uploads', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['max_size']; ?>
" name="max_size" class="short"> b <i><?php echo l('int_unlimit_condition', 'file_uploads', '', 'text', array()); ?></i></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_name_format', 'file_uploads', '', 'text', array()); ?>: </div>
			<div class="v">
				<select name="name_format" id="name_format" class="long"><?php if (is_array($this->_vars['lang_name_format']['option']) and count((array)$this->_vars['lang_name_format']['option'])): foreach ((array)$this->_vars['lang_name_format']['option'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['key']; ?>
" <?php if ($this->_vars['key'] == $this->_vars['data']['name_format']): ?>selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option><?php endforeach; endif; ?></select>
			</div>
		</div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_file_formats', 'file_uploads', '', 'text', array()); ?>: </div>
			<div class="v">
				<?php if (is_array($this->_vars['formats']) and count((array)$this->_vars['formats'])): foreach ((array)$this->_vars['formats'] as $this->_vars['category_key'] => $this->_vars['category_data']): ?>
				<div class="column">
					<input type="checkbox" class="category" id="cat-<?php echo $this->_vars['category_key']; ?>
">
					<label for="cat-<?php echo $this->_vars['category_key']; ?>
"><?php echo l($this->_vars['category_key'], 'file_uploads', '', 'text', array()); ?></label><br />
					<ul>
						<?php if (is_array($this->_vars['category_data']) and count((array)$this->_vars['category_data'])): foreach ((array)$this->_vars['category_data'] as $this->_vars['key'] => $this->_vars['item']): ?>
						<li>
						<input type="checkbox" name="file_formats[]" value="<?php echo $this->_vars['item']; ?>
" <?php if ($this->_vars['data']['enable_formats'][$this->_vars['item']]): ?>checked<?php endif; ?> id="frm_<?php echo $this->_vars['item']; ?>
">
						<label for="frm_<?php echo $this->_vars['item']; ?>
"><?php echo $this->_vars['item']; ?>
</label>
						</li>
						<?php endforeach; endif; ?>
					</ul>
				</div>
				<?php endforeach; endif; ?>
				<div class="clr"></div>
			</div>
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
		<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/file_uploads/configs"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
	</div>
</form>
<script type='text/javascript'>
<?php echo '
	$(function(){
		$(".tooltip").each(function(){
			$(this).easyTooltip({
				useElement: \'tt_\'+$(this).attr(\'id\')
			});
		});

		$(\'input.category\').bind(\'click\', function(){
			var checked = $(this).is(\':checked\');
			if(checked){
				$(this).parent().find(\'ul > li > input[type=checkbox]\').prop(\'checked\', true);
			}else{
				$(this).parent().find(\'ul > li > input[type=checkbox]\').prop(\'checked\', false);
			}
		});
	});
'; ?>

</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>