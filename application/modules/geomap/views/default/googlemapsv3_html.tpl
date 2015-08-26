<link href="{$site_root}application/modules/geomap/views/default/css/googlev3.css" rel="stylesheet" type="text/css">

<script>{literal}
	var geomap_js_loaded = '{/literal}{$geomap_js_loaded}{literal}';
	var only_load_content = '{/literal}{$only_load_content}{literal}';
	
	if(!geomap_js_loaded && !only_load_content) {
		$(function() {	
			loadScripts(
				[
					"{/literal}{js file='googlemapsv3.js' module='geomap' return='path'}{literal}", 
					"https://maps.google.com/maps/api/js?v=3.8&libraries=places&sensor=true&key={/literal}{$map_reg_key}{literal}&callback=map_loader"
				],
				function(){},
				'{/literal}{$map_id}{literal}',
				{crossDomain: true}
			);
		});
	} else {
		$(function(){setTimeout(map_loader, 100);});
	}
	map_loader = new Function();
</script>{/literal}

{if !$only_load_scripts}
	{if !$map_id}{assign var='map_id' value='map_'+$rand}{/if}
	<div id="{$map_id}" class="{$view_settings.class} map_container">&nbsp;{l i='loading' gid='geomap'}</div>
	{if $settings.use_panorama && !$view_settings.panorama_container}
		<div id="pano_container_{$rand}" class="pano_container"></div>
	{/if}
	{if $settings.use_router}
		<div id="routes_{$rand}" class="routes"></div>
		<div id="route_links_{$rand}">
			<a href="javascript:void(0);" id="add_route_btn_{$rand}">{l i='route_add' gid='geomap'}</a>
			<a href="javascript:void(0);" id="remove_route_btn_{$rand}" class="hide">{l i='route_delete' gid='geomap'}</a>
		</div>
	{/if}
	
	<script>{literal}
		var {/literal}{$map_id}{literal};
		map_loader = function(){
			var settings = {/literal}{json_encode data=$settings}{literal};
			var view_settings = {/literal}{json_encode data=$view_settings}{literal};
			var map_id = '{/literal}{$map_id}{literal}';
			var circle_settings = view_settings.circle_settings ? view_settings.circle_settings : {};
			var langs = view_settings.langs ? view_settings.langs : {};
			
			var rand = '{/literal}{$rand}{literal}';
			var markers = {/literal}{json_encode data=$markers}{literal};
			var map_properties = {
				map_container: map_id,
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
			if(view_settings.zoom_listener) map_properties.zoom_listener = {/literal}{if $view_settings.zoom_listener}{$view_settings.zoom_listener}{else}''{/if}{literal};
			if(view_settings.type_listener) map_properties.type_listener = {/literal}{if $view_settings.type_listener}{$view_settings.type_listener}{else}''{/if}{literal};
			if(view_settings.geocode_listener) map_properties.geocode_listener = {/literal}{if $view_settings.geocode_listener}{$view_settings.geocode_listener}{else}''{/if}{literal};
			if(settings.use_type_selector == true) map_properties.use_type_selector = true;
			if(settings.search_radius_default == true) map_properties.search_radius_default = true;

			if(circle_settings.set_position_manual == true) map_properties.search_figure_editable = true;
			if(circle_settings.center_lat) map_properties.circle_center_lat = circle_settings.center_lat;
			if(circle_settings.center_lon) map_properties.circle_center_lon = circle_settings.center_lon;
			if(circle_settings.search_radius) map_properties.circle_search_radius = circle_settings.search_radius;
			if(circle_settings.search_radius_unit) map_properties.circle_search_radius_unit = circle_settings.search_radius_unit;
			if(circle_settings.default_search_radius) map_properties.default_circle_search_radius = circle_settings.default_search_radius;
			if(circle_settings.use_search_by_area == true) map_properties.use_search_by_area = true;
			if(langs) map_properties.langs = langs;

			if(settings.use_router){
				map_properties.use_router = true;
				map_properties.routes_container = 'routes_'+rand;
			}
			if(settings.use_panorama && !view_settings.panorama_disable){
				map_properties.use_panorama = true;
				map_properties.panorama_container = view_settings.panorama_container ? view_settings.panorama_container : 'pano_container_'+rand;
			}
			if(settings.use_search_radius) map_properties.use_search_radius = true;
			if(settings.use_show_details) map_properties.use_show_details = true;
			if(settings.use_amenities && settings.amenities && settings.amenities.length){
				map_properties.use_amenities = true;
				map_properties.amenities = settings.amenities;
				map_properties.amenities_names = settings.amenities_names;
			}

			window[map_id] = new GoogleMapsv3(map_properties);

			if(settings.use_router){
				$('#add_route_btn_'+rand).bind('click', function(){
					$('#add_route_btn_'+rand).hide();
					$('#remove_route_btn_'+rand).show();
					window[map_id].createRoute();
				});

				$('#remove_route_btn_'+rand).bind('click', function(){
					$('#remove_route_btn_'+rand).hide();
					$('#add_route_btn_'+rand).show();
					window[map_id].deleteRoute();
				});
			}

			if(markers && typeof markers === 'object'){
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
	</script>{/literal}
{/if}
