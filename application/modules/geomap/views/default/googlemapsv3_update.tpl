{strip}
<script>{literal}
	$(function(){
		var view_settings = {/literal}{json_encode data=$view_settings}{literal};
		var map_id = '{/literal}{$map_id}{literal}';
		var markers = {/literal}{json_encode data=$markers}{literal};
		var circle_settings = view_settings.circle_settings ? view_settings.circle_settings : {};
		var langs = view_settings.langs ? view_settings.langs : {};
		window[map_id].clear();
		
		if(circle_settings.set_position_manual == true) window[map_id].search_figure_editable = true;
		if(circle_settings.center_lat) window[map_id].circle_center_lat = circle_settings.center_lat;
		if(circle_settings.center_lon) window[map_id].circle_center_lon = circle_settings.center_lon;
		if(circle_settings.search_radius) window[map_id].circle_search_radius = circle_settings.search_radius;
		if(circle_settings.default_search_radius) window[map_id].default_circle_search_radius = circle_settings.default_search_radius;
		if(circle_settings.search_radius_unit) window[map_id].circle_search_radius_unit = circle_settings.search_radius_unit;
		if(circle_settings.use_search_by_area == true) window[map_id].use_search_by_area = true;
		if(langs) window[map_id].langs = langs;
		
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
		
		if(window[map_id].searchCircle && circle_settings != {}) {
			window[map_id].searchCircle.setMap(null);
			window[map_id].drawSearchCircle(circle_settings.search_radius, 
											  circle_settings.center_lat, 
											  circle_settings.center_lon);
		}
	});
</script>{/literal}
{/strip}