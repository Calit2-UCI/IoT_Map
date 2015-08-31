<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 19:17:57 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form"  enctype="multipart/form-data">

	<div class="filter-form">
		<div class="form">
			<b><?php echo $this->_vars['theme']['name']; ?>
</b><br><?php echo $this->_vars['theme']['description']; ?>
<br><br>
			<?php if ($this->_vars['theme']['img']): ?><img src="<?php echo $this->_vars['theme']['img']; ?>
"><br><br><?php endif; ?>
			<?php echo l('field_default', 'themes', '', 'text', array()); ?>: <?php if ($this->_vars['theme']['default']):  echo l('status_default_yes', 'themes', '', 'text', array());  else:  echo l('status_default_no', 'themes', '', 'text', array());  endif; ?><br>
			<?php echo l('field_active', 'themes', '', 'text', array()); ?>: <?php if ($this->_vars['theme']['active']):  echo l('status_active_yes', 'themes', '', 'text', array());  else:  echo l('status_active_no', 'themes', '', 'text', array());  endif; ?><br><br>
		</div>
	</div>
	<br>
	
	<h2><?php echo l('admin_header_logo_editor', 'themes', '', 'text', array()); ?></h2>
	
	<div class="edit-form n150">
		<div class="row header"><?php echo l('admin_header_logo_settings', 'themes', '', 'text', array()); ?></div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_logo_width', 'themes', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['theme']['logo_width']; ?>
" name="logo_width" class="short"> <?php echo l('size_px', 'themes', '', 'text', array()); ?></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_logo_height', 'themes', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['theme']['logo_height']; ?>
" name="logo_height" class="short"> <?php echo l('size_px', 'themes', '', 'text', array()); ?></div>
		</div>
	</div>
	<br />
	<div class="edit-form n150">
		<div class="row header"><?php echo l('admin_header_mini_logo_settings', 'themes', '', 'text', array()); ?></div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_logo_width', 'themes', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['theme']['mini_logo_width']; ?>
" name="mini_logo_width" class="short"> <?php echo l('size_px', 'themes', '', 'text', array()); ?></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_logo_height', 'themes', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['theme']['mini_logo_height']; ?>
" name="mini_logo_height" class="short"> <?php echo l('size_px', 'themes', '', 'text', array()); ?></div>
		</div>
	</div>
	<br />
	<div class="menu-level3">
		<ul id="edit_tabs">
			<?php if (is_array($this->_vars['langs']) and count((array)$this->_vars['langs'])): foreach ((array)$this->_vars['langs'] as $this->_vars['key'] => $this->_vars['item']): ?>
			<li<?php if ($this->_vars['item']['id'] == $this->_vars['lang_id']): ?> class="active"<?php endif; ?>><a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/view_installed/<?php echo $this->_vars['theme']['id']; ?>
/<?php echo $this->_vars['item']['id']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</a></li>
			<?php endforeach; endif; ?>
		</ul>
		&nbsp;
	</div>	
	<div class="edit-form n150">
		<div class="row header"><?php echo l('admin_header_logo_upload', 'themes', '', 'text', array()); ?></div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_logo_file', 'themes', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="file" value="" name="logo">
				<br><br>
				<img src="<?php echo $this->_vars['theme']['logo_url']; ?>
" width="<?php echo $this->_vars['theme']['logo_width']; ?>
" height="<?php echo $this->_vars['theme']['logo_height']; ?>
" />
			</div>
			<?php if ($this->_vars['theme']['logo']): ?>
			<div class="row zebra">
				<div class="h"><?php echo l('field_logo_delete', 'themes', '', 'text', array()); ?>: </div>
				<div class="v"><input type="checkbox" name="logo_delete" value="1"></div>
			</div>
			<?php endif; ?>
		</div>
		<div class="row ">
			<div class="h"><?php echo l('field_mini_logo_file', 'themes', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="file" value="" name="mini_logo">
				<br><br>
				<img src="<?php echo $this->_vars['theme']['mini_logo_url']; ?>
" width="<?php echo $this->_vars['theme']['mini_logo_width']; ?>
" height="<?php echo $this->_vars['theme']['mini_logo_height']; ?>
" />
			</div>
			<?php if ($this->_vars['theme']['mini_logo']): ?>
			<div class="row">
				<div class="h"><?php echo l('field_logo_delete', 'themes', '', 'text', array()); ?>: </div>
				<div class="v"><input type="checkbox" name="mini_logo_delete" value="1"></div>
			</div>
			<?php endif; ?>
		</div>
	</div>	
	
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/themes/installed_themes/<?php echo $this->_vars['theme']['type']; ?>
"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>

</form>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>