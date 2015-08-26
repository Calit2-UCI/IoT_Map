<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 01:47:40 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_js(array('module' => 'install','file' => 'product_install.js'), $this); echo '
<script>
var product_install;
$(function(){
	product_install=new productInstall({siteUrl: \'';  echo $this->_vars['site_root'];  echo '\'});
});
</script>
'; ?>


<div class="filter-form">
	<div class="install_main_window">
		<div class="pad">
			<h3>Installation status</h3>
			<div class="bar-level1" id="overall_bar"><div class="bar">0%</div></div>
			<br>
			<div id="modules_reload">
				<?php echo $this->_vars['start_html']; ?>

			</div>
		</div>
	</div>
</div>
<div class="clr"></div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
