<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 19:36:25 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form">
	<div class="edit-form n150">
		<div class="row header"><?php echo l('admin_header_global_seo_settings_editing', 'seo', '', 'text', array()); ?> : <?php if ($this->_vars['controller'] == 'admin'):  echo l('default_seo_admin_field', 'seo', '', 'text', array());  else:  echo l('default_seo_user_field', 'seo', '', 'text', array());  endif; ?></div>
		<div class="row zebra">
			<div class="h"><b><?php echo l('field_title_default', 'seo', '', 'text', array()); ?></b></div>
			<div class="v"><?php echo l('text_help_title', 'seo', '', 'text', array()); ?></div>
		</div>
		<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<?php $this->assign('section_gid', 'meta_'.$this->_vars['key']); ?>
		<div class="row">
			<div class="h"><?php echo $this->_vars['item']['name']; ?>
: </div>
			<div class="v"><input type="text" name="title[<?php echo $this->_vars['key']; ?>
]" value="<?php echo $this->_run_modifier($this->_vars['user_settings'][$this->_vars['section_gid']]['title'], 'escape', 'plugin', 1); ?>
" class="long"></div>
		</div>
		<?php endforeach; endif; ?>
		<br>

		<div class="row zebra">
			<div class="h"><b><?php echo l('field_keyword_default', 'seo', '', 'text', array()); ?></b></div>
			<div class="v"><?php echo l('text_help_keyword', 'seo', '', 'text', array()); ?></div>
		</div>
		<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<?php $this->assign('section_gid', 'meta_'.$this->_vars['key']); ?>
		<div class="row">
			<div class="h"><?php echo $this->_vars['item']['name']; ?>
: </div>
			<div class="v"><textarea name="keyword[<?php echo $this->_vars['key']; ?>
]" rows="5" cols="80" class="long pb2"><?php echo $this->_run_modifier($this->_vars['user_settings'][$this->_vars['section_gid']]['keyword'], 'escape', 'plugin', 1); ?>
</textarea></div>
		</div>
		<?php endforeach; endif; ?>
		<br>
	
		<div class="row zebra">
			<div class="h"><b><?php echo l('field_description_default', 'seo', '', 'text', array()); ?></b></div>
			<div class="v"><?php echo l('text_help_description', 'seo', '', 'text', array()); ?></div>
		</div>
		<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<?php $this->assign('section_gid', 'meta_'.$this->_vars['key']); ?>
		<div class="row">
			<div class="h"><?php echo $this->_vars['item']['name']; ?>
: </div>
			<div class="v"><textarea name="description[<?php echo $this->_vars['key']; ?>
]" rows="5" cols="80" class="long pb2"><?php echo $this->_run_modifier($this->_vars['user_settings'][$this->_vars['section_gid']]['description'], 'escape', 'plugin', 1); ?>
</textarea></div>
		</div>
		<?php endforeach; endif; ?>
		<br>	
		
		<div class="row zebra">
			<div class="h"><b><?php echo l('header_section_og', 'seo', '', 'text', array()); ?></b></div>
			<div class="v"><?php echo l('text_help_og', 'seo', '', 'text', array()); ?></div>
		</div>
		<br>
		
		<div class="row zebra">
			<div class="h"><b><?php echo l('field_og_title_default', 'seo', '', 'text', array()); ?></b></div>
			<div class="v"><?php echo l('text_help_og_title', 'seo', '', 'text', array()); ?></div>
		</div>
		<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<?php $this->assign('section_gid', 'og_'.$this->_vars['key']); ?>
		<div class="row">
			<div class="h"><?php echo l('field_og_title', 'seo', '', 'text', array()); ?>(<?php echo $this->_vars['item']['name']; ?>
): </div>
			<div class="v"><input type="text" name="og_title[<?php echo $this->_vars['key']; ?>
]" class="long" value="<?php echo $this->_run_modifier($this->_vars['user_settings'][$this->_vars['section_gid']]['og_title'], 'escape', 'plugin', 1); ?>
"></div>
		</div>
		<?php endforeach; endif; ?>
		<br>
			
		<div class="row zebra">
			<div class="h"><b><?php echo l('field_og_type_default', 'seo', '', 'text', array()); ?></b></div>
			<div class="v"><?php echo l('text_help_og_type', 'seo', '', 'text', array()); ?></div>
		</div>
		<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<?php $this->assign('section_gid', 'og_'.$this->_vars['key']); ?>
		<div class="row">
			<div class="h"><?php echo $this->_vars['item']['name']; ?>
: </div>
			<div class="v"><input type="text" name="og_type[<?php echo $this->_vars['key']; ?>
]" class="long" value="<?php echo $this->_run_modifier($this->_vars['user_settings'][$this->_vars['section_gid']]['og_type'], 'escape', 'plugin', 1); ?>
"></div>
		</div>
		<?php endforeach; endif; ?>
		<br>
			
		<div class="row zebra">
			<div class="h"><b><?php echo l('field_og_description_default', 'seo', '', 'text', array()); ?></b></div>
			<div class="v"><?php echo l('text_help_og_description', 'seo', '', 'text', array()); ?></div>
		</div>
		<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<?php $this->assign('section_gid', 'og_'.$this->_vars['key']); ?>
		<div class="row">
			<div class="h"><?php echo $this->_vars['item']['name']; ?>
:</div>
			<div class="v"><textarea name="og_description[<?php echo $this->_vars['key']; ?>
]" rows="5" cols="80" class="long pb2"><?php echo $this->_run_modifier($this->_vars['user_settings'][$this->_vars['section_gid']]['og_description'], 'escape', 'plugin', 1); ?>
</textarea></div>
		</div>
		<?php endforeach; endif; ?>
		<br>
		
		<?php if ($this->_vars['controller'] == 'user'): ?>
		<div class="row zebra">
			<div class="h"><b><?php echo l('field_header_default', 'seo', '', 'text', array()); ?></b></div>
			<div class="v"><?php echo l('text_help_header', 'seo', '', 'text', array()); ?></div>
		</div>
		<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<?php $this->assign('section_gid', 'meta_'.$this->_vars['key']); ?>
		<div class="row">
			<div class="h"><?php echo $this->_vars['item']['name']; ?>
: </div>
			<div class="v"><input type="text" name="header[<?php echo $this->_vars['key']; ?>
]" class="long" value="<?php echo $this->_run_modifier($this->_vars['user_settings'][$this->_vars['section_gid']]['header'], 'escape', 'plugin', 1); ?>
"></div>
		</div>
		<?php endforeach; endif; ?>
		<br>
		<?php endif; ?>
		
		<div class="row zebra">
			<div class="h"><?php echo l('field_lang_in_url', 'seo', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="checkbox" value="1" name="lang_in_url" <?php if ($this->_vars['user_settings']['lang_in_url']): ?>checked<?php endif; ?> id="lang_in_url">
			</div>
		</div>
		<?php if ($this->_vars['controller'] == 'user'): ?>
		<div class="row">
			<div class="h"><?php echo l('field_lang_canonical', 'seo', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="hidden" name="lang_canonical" value="0">
				<input type="checkbox" name="lang_canonical" value="1" <?php if ($this->_vars['user_settings']['lang_canonical']): ?>checked<?php endif; ?>>
			</div>
		</div>
		<?php endif; ?>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/seo/index"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<div class="clr"></div>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
