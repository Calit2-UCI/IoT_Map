{if !$geomap_js_loaded && !$only_load_content}
	<link href="{$site_root}application/modules/geomap/views/default/css/yandexv2.css" rel="stylesheet" type="text/css" />
	<script>{literal}
		$(function() {
			loadScripts(
				[
					"{/literal}{js file='yandexmapsv2.js' module='geomap' return='path'}{literal}", 
					"http://api-maps.yandex.ru/2.0/?load=package.full&mode=debug&lang=ru-RU"
				],
				function(){
					ymaps.ready(map_loader);
				},
				'{/literal}{$map_id}{literal}',
				{crossDomain: true}
			);
		});
	</script>{/literal}
{else}
	<script>{literal}$(function(){map_loader();});{/literal}</script>
{/if}
<script>map_loader = new Function();</script>

{if !$only_load_scripts}
	{if !$map_id}{assign var='map_id' value='map_'+$rand}{/if}
	<div id="{$map_id}" class="{$view_settings.class} map_container">&nbsp;{l i='loading' gid='geomap'}</div>
	{if $view_settings.use_router}
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
			if(settings.use_router == true){
				map_properties.use_router = true;
				map_properties.routes_container = 'routes_'+rand;
			}
			if(settings.use_tools == true) map_properties.use_tools = true;
			if(settings.use_clusterer == true) map_properties.use_clusterer = true;
			if(settings.use_click_zoom == true) map_properties.use_click_zoom = true;

			window[map_id] = new YandexMapsv2(map_properties);

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
