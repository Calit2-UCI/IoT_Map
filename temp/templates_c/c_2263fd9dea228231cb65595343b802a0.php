<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2016-02-11 00:45:38 Pacific Standard Time */ ?>

<script><?php echo '
	var geocoder;
	geocoder_loader = function(){
		geocoder = new GoogleMapsv3_Geocoder({});
	}
'; ?>
</script>

<?php if (! $this->_vars['geomap_js_loaded']): ?>
	<script><?php echo '
		$(function() {
			loadScripts(
				[
					"';  echo tpl_function_js(array('file' => 'googlemapsv3.js','module' => 'geomap','return' => 'path'), $this); echo '", 
					"https://maps.google.com/maps/api/js?v=3.9&libraries=places&sensor=true&key=';  echo $this->_vars['map_reg_key'];  echo '&callback=geocoder_loader"
				],
				function(){
					geocoder_loader();
				},
				\'geocoder\',
				{crossDomain: true}
			);
		});
	</script>'; ?>

<?php else: ?>
	<script>geocoder_loader();</script>
<?php endif; ?>