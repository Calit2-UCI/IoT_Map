<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:13:35 Pacific Daylight Time */ ?>

<input name="region_name" type="text" id="country_text_<?php echo $this->_vars['country_helper_data']['rand']; ?>
"
	   value="<?php echo $this->_vars['country_helper_data']['location_text']; ?>
" 
	   autocomplete="off" 
	   placeholder="<?php echo $this->_vars['country_helper_data']['placeholder']; ?>
">
<span id="country_msg_<?php echo $this->_vars['country_helper_data']['rand']; ?>
" 
	  class="hide pginfo msg region_name info"><?php echo l('text_select_from_list', 'countries', '', 'text', array()); ?></span>
<input name="<?php echo $this->_vars['country_helper_data']['var_country_name']; ?>
" type="hidden"
	   id="country_hidden_<?php echo $this->_vars['country_helper_data']['rand']; ?>
" 
	   value="<?php echo $this->_vars['country_helper_data']['country']['code']; ?>
">
<input name="<?php echo $this->_vars['country_helper_data']['var_region_name']; ?>
" type="hidden"
	   id="region_hidden_<?php echo $this->_vars['country_helper_data']['rand']; ?>
" 
	   value="<?php echo $this->_vars['country_helper_data']['region']['id']; ?>
">
<input name="<?php echo $this->_vars['country_helper_data']['var_city_name']; ?>
" type="hidden"
	   id="city_hidden_<?php echo $this->_vars['country_helper_data']['rand']; ?>
" 
	   value="<?php echo $this->_vars['country_helper_data']['city']['id']; ?>
">
<script type='text/javascript'>

<?php echo '
$(function(){
	loadScripts(
		"';  echo tpl_function_js(array('module' => 'countries','file' => 'location-autocomplete.js','return' => 'path'), $this); echo '",
		function(){
			new locationAutocomplete({
				siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
				rand: \'';  echo $this->_vars['country_helper_data']['rand'];  echo '\',
				id_country: \'';  echo $this->_vars['country_helper_data']['country']['code'];  echo '\',
				id_region: \'';  echo $this->_vars['country_helper_data']['region']['id'];  echo '\',
				id_city: \'';  echo $this->_vars['country_helper_data']['city']['id'];  echo '\'
			});
		},
		\'region_';  echo $this->_vars['country_helper_data']['rand'];  echo '\'
	);
});
'; ?>
</script>