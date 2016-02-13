<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-10-17 00:54:19 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>



<div class="actions">
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li><a href="<?php echo $this->_vars['site_url']; ?>
admin/dynamic_blocks/area_blocks/<?php echo $this->_vars['area']['id']; ?>
"><?php echo l('filter_area_blocks', 'dynamic_blocks', '', 'text', array()); ?></a></li>
		<li><a href="<?php echo $this->_vars['site_url']; ?>
admin/dynamic_blocks/area_layout/<?php echo $this->_vars['area']['id']; ?>
"><?php echo l('filter_area_layout', 'dynamic_blocks', '', 'text', array()); ?></a></li>
		<li class="active"><a href="<?php echo $this->_vars['site_url']; ?>
admin/dynamic_blocks/area_preset/<?php echo $this->_vars['area']['id']; ?>
"><?php echo l('filter_area_preset', 'dynamic_blocks', '', 'text', array()); ?></a></li>
	</ul>
	&nbsp;
</div>



<div class="filter-form">
<form id="preset-form" action="" method="post">

<?php if (is_array($this->_vars['presets']) and count((array)$this->_vars['presets'])): foreach ((array)$this->_vars['presets'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => 'counter'), $this);?>
<div class="<?php if (!($this->_vars['counter'] % 2)): ?>right<?php else: ?>left<?php endif; ?>-side">
	<h3><?php echo $this->_vars['item']['name']; ?>
</h3>
	<div class="preset">
		<img src="<?php echo $this->_vars['item']['media']['logo']['file_url']; ?>
" alt="">
	</div>
	<div class="btn"><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/dynamic_blocks/set_preset/<?php echo $this->_vars['area']['id']; ?>
/<?php echo $this->_vars['item']['id']; ?>
"><?php echo l('btn_apply', 'start', '', 'text', array()); ?></a></div></div>
</div>
<?php if (!($this->_vars['counter'] % 2)): ?><div class="clr"></div><?php endif;  endforeach; else:  echo l('no_presets', 'dynamic_blocks', '', 'text', array());  endif; ?>
<div class="clr"></div>
</form>
</div>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
