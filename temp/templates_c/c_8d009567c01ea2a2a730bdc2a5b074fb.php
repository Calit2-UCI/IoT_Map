<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.start_search_form.php');
$this->register_function("start_search_form", "tpl_function_start_search_form"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seotag.php');
$this->register_function("seotag", "tpl_function_seotag");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-10-06 00:02:19 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<h1><?php echo tpl_function_seotag(array('tag' => 'header_text'), $this);?></h1>

<div class="pos-rel">
	<?php echo tpl_function_start_search_form(array('type' => 'full','show_data' => '1','object' => 'perfect_match'), $this);?>
</div>
<div class="content-block">
	<div id="main_users_results">
		<?php echo $this->_vars['block']; ?>

	</div>

	<script type="text/javascript"><?php echo '
		$(function(){
			loadScripts("';  echo tpl_function_js(array('module' => 'users','file' => 'users-list.js','return' => 'path'), $this); echo '",
				function(){
					users_list = new usersList({
						siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
						viewUrl: \'';  echo tpl_function_seolink(array('module' => 'users','method' => 'perfect_match'), $this); echo '\',
						viewAjaxUrl: \'ajax_perfect_match\',
						listBlockId: \'main_users_results\',
						tIds: [\'pages_block_1\', \'pages_block_2\', \'sorter_block\']
					});
				},
				\'users_list\'
			);
		});
	'; ?>
</script>		
</div>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
