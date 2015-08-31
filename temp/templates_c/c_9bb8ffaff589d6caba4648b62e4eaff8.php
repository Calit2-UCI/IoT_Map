<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seotag.php');
$this->register_function("seotag", "tpl_function_seotag");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 00:02:15 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "start". $this->module_templates.  $this->get_current_theme_gid('', '"start"'). "left_panel.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="rc">
	<div class="content-block">
		<h1>
			<?php echo tpl_function_seotag(array('tag' => 'header_text'), $this);?>
		</h1>
		<?php if (is_array($this->_vars['pages']) and count((array)$this->_vars['pages'])): foreach ((array)$this->_vars['pages'] as $this->_vars['item']): ?>
			<div class="content">
				<div class="links">
					<a href="<?php echo tpl_function_seolink(array('module' => 'content','method' => 'view','data' => $this->_vars['item']), $this);?>"><?php echo $this->_vars['item']['title']; ?>
</a>
				</div>
				<div class="clr"></div>
			</div>
		<?php endforeach; else: ?>
			<div class="empty"><?php echo l("no_content_yet_header", 'content', '', 'text', array()); ?></div>
		<?php endif; ?>
	</div>
</div>
<div class="clr"></div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
