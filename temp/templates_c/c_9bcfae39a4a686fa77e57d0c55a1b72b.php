<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-26 10:59:30 Pacific Daylight Time */ ?>

<div id="amenity_select_<?php echo $this->_vars['amenity_helper_data']['rand']; ?>
" class="controller-select">

	<?php if ($this->_vars['amenity_helper_data']['max'] == '1'): ?>
	<span id="amenity_text_<?php echo $this->_vars['amenity_helper_data']['rand']; ?>
"><?php echo $this->_vars['amenity_helper_data']['selected_data']; ?>
</span>
	<a href="#" id="amenity_open_<?php echo $this->_vars['amenity_helper_data']['rand']; ?>
"><?php echo l('link_select_amenity', 'geomap', '', 'text', array()); ?></a>
	<?php else: ?>
	<a href="#" id="amenity_open_<?php echo $this->_vars['amenity_helper_data']['rand']; ?>
"><?php echo l('link_select_amenities', 'geomap', '', 'text', array()); ?></a>
	<span id="amenity_list_<?php echo $this->_vars['amenity_helper_data']['rand']; ?>
"><?php echo $this->_vars['amenity_helper_data']['selected_text']; ?>
</span>
	<i>(<span id="amenity_text_<?php echo $this->_vars['amenity_helper_data']['rand']; ?>
"><?php echo $this->_vars['amenity_helper_data']['selected_data']; ?>
</span> from <?php echo $this->_vars['amenity_helper_data']['max']; ?>
 selected)</i>
	<?php endif; ?>

	<?php if (is_array($this->_vars['amenity_helper_data']['selected_all']) and count((array)$this->_vars['amenity_helper_data']['selected_all'])): foreach ((array)$this->_vars['amenity_helper_data']['selected_all'] as $this->_vars['key'] => $this->_vars['item']): ?>
	<input type="hidden" name="<?php if ($this->_vars['amenity_helper_data']['var']):  echo $this->_vars['amenity_helper_data']['var'];  else: ?>id_amenity<?php endif;  if ($this->_vars['amenity_helper_data']['max'] > 1): ?>[]<?php endif; ?>" value="<?php echo $this->_vars['key']; ?>
" id="sel_<?php echo $this->_vars['amenity_helper_data']['rand']; ?>
_<?php echo $this->_vars['key']; ?>
" >
	<?php endforeach; endif; ?>

</div>
<?php echo tpl_function_js(array('module' => 'geomap','file' => 'geomap-amenity-select.js'), $this);?>
<script><?php echo '
';  if ($this->_vars['amenity_helper_data']['var_js_name']): ?>var <?php echo $this->_vars['amenity_helper_data']['var_js_name']; ?>
;<?php endif;  echo '
$(function(){
	';  if ($this->_vars['amenity_helper_data']['var_js_name']):  echo $this->_vars['amenity_helper_data']['var_js_name']; ?>
 = <?php endif;  echo 'new geomapAmenitySelect({
		'; ?>

		siteUrl: '<?php echo $this->_vars['site_url']; ?>
',
		rand: '<?php echo $this->_vars['amenity_helper_data']['rand']; ?>
',
		<?php if ($this->_vars['amenity_helper_data']['var']): ?>hidden_name: '<?php echo $this->_vars['amenity_helper_data']['var']; ?>
',<?php endif; ?>
		amenities: <?php echo $this->_vars['amenity_helper_data']['selected_all_json']; ?>
,
		raw_data: <?php echo $this->_vars['amenity_helper_data']['raw_data_json']; ?>
,
		max: <?php echo $this->_vars['amenity_helper_data']['max']; ?>
,
		gid: '<?php echo $this->_vars['amenity_helper_data']['gid']; ?>
',
		output: '<?php echo $this->_vars['amenity_helper_data']['output']; ?>
'
		<?php echo '
	});
});
'; ?>
</script>
