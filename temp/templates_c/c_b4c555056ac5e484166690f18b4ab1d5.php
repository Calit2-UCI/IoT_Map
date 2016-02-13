<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.json_encode.php');
$this->register_function("json_encode", "tpl_function_json_encode"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2016-02-10 23:02:38 Pacific Standard Time */ ?>

<?php if (! $this->_vars['geomap_js_loaded'] && ! $this->_vars['only_load_content']): ?>
	<link href="<?php echo $this->_vars['site_root']; ?>
application/modules/geomap/views/default/css/bingmapsv7.css" rel="stylesheet" type="text/css" />
	<script><?php echo '
		$(function() {
			loadScripts(
				[
					"';  echo tpl_function_js(array('file' => 'bingmapsv7.js','module' => 'geomap','return' => 'path'), $this); echo '",
					"http://ecn.dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=7.0&onScriptLoad=map_loader"
				],
				function(){},
				\'';  echo $this->_vars['map_id'];  echo '\',
				{crossDomain: true, cache: false}
			);
		});
	</script>'; ?>

<?php else: ?>
	<script><?php echo '$(function(){map_loader();});'; ?>
</script>
<?php endif; ?>
<script>map_loader = new Function();</script>

<?php if (! $this->_vars['only_load_scripts']): ?>
	<?php if (! $this->_vars['map_id']):  $this->assign('map_id', 'map_'.$this->_vars['rand']);  endif; ?>
	<div id="<?php echo $this->_vars['map_id']; ?>
" class="<?php echo $this->_vars['view_settings']['class']; ?>
 map_container">&nbsp;<?php echo l('loading', 'geomap', '', 'text', array()); ?></div>
	<?php if ($this->_vars['settings']['use_router']): ?>
		<div id="routes_<?php echo $this->_vars['rand']; ?>
" class="routes"></div>
		<div id="route_links_<?php echo $this->_vars['rand']; ?>
">
			<a href="javascript:void(0);" id="add_route_btn_<?php echo $this->_vars['rand']; ?>
"><?php echo l('route_add', 'geomap', '', 'text', array()); ?></a>
			<a href="javascript:void(0);" id="remove_route_btn_<?php echo $this->_vars['rand']; ?>
" class="hide"><?php echo l('route_delete', 'geomap', '', 'text', array()); ?></a>
		</div>
	<?php endif; ?>

	<script><?php echo '
		var ';  echo $this->_vars['map_id'];  echo ';
		map_loader = function(){
			var settings = ';  echo tpl_function_json_encode(array('data' => $this->_vars['settings']), $this); echo ';
			var view_settings = ';  echo tpl_function_json_encode(array('data' => $this->_vars['view_settings']), $this); echo ';
			var map_id = \'';  echo $this->_vars['map_id'];  echo '\';
			var map_reg_key = \'';  echo $this->_vars['map_reg_key'];  echo '\';
			var rand = \'';  echo $this->_vars['rand'];  echo '\';
			var markers = ';  echo tpl_function_json_encode(array('data' => $this->_vars['markers']), $this); echo ';
			var map_properties = {
				map_container: map_id,
				map_key: map_reg_key,
				default_zoom: settings.zoom,
				width: view_settings.width,
				height: view_settings.height,
				lat: settings.lat,
				lon: settings.lon
			};
			if(settings.view_type) map_properties.default_map_type = settings.view_type;
			if(settings.media && settings.media.icon) map_properties.icon = settings.media.icon.thumbs.small;
			if(!view_settings.disable_smart_zoom && settings.use_smart_zoom == true) map_properties.use_smart_zoom = true;
			if(settings.use_searchbox == true) map_properties.use_searchbox = true;
			if(view_settings.zoom_listener) map_properties.zoom_listener = ';  if ($this->_vars['view_settings']['zoom_listener']):  echo $this->_vars['view_settings']['zoom_listener'];  else: ?>''<?php endif;  echo ';
			if(view_settings.type_listener) map_properties.type_listener = ';  if ($this->_vars['view_settings']['type_listener']):  echo $this->_vars['view_settings']['type_listener'];  else: ?>''<?php endif;  echo ';
			if(view_settings.geocode_listener) map_properties.geocode_listener = ';  if ($this->_vars['view_settings']['geocode_listener']):  echo $this->_vars['view_settings']['geocode_listener'];  else: ?>''<?php endif;  echo ';
			if(settings.use_type_selector == true) map_properties.use_type_selector = true;
			if(settings.use_router == true){
				map_properties.use_router = true;
				map_properties.routes_container = \'routes_\'+rand;
			}
				
			window[map_id] = new BingMapsv7(map_properties);

			if(settings.use_router){
				$(\'#add_route_btn_\'+rand).bind(\'click\', function(){
					$(\'#add_route_btn_\'+rand).hide();
					$(\'#remove_route_btn_\'+rand).show();
					window[map_id].startRoute();
				});

				$(\'#remove_route_btn_\'+rand).bind(\'click\', function(){
					$(\'#remove_route_btn_\'+rand).hide();
					$(\'#add_route_btn_\'+rand).show();
					window[map_id].deleteRoute();
				});
			}

			if(markers && typeof markers === \'object\'){
				var markers_properties;
				for(var i in markers){
					markers_properties = {};
					if(markers[i].gid) markers_properties.gid = markers[i].gid;
					if(markers[i].dragging){
						markers_properties.draggable = true;
						if(view_settings.drag_listener) markers_properties.drag_listener = view_settings.drag_listener;
					}
					if(markers[i].info) markers_properties.info = markers[i].info;
					window[map_id].addMarker(markers[i].lat, markers[i].lon, markers_properties);
				}
			}
		};
	</script>'; ?>

<?php endif; ?>
