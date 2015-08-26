{strip}
<script>{literal}
	$(function(){
		var view_settings = {/literal}{json_encode data=$view_settings}{literal};
		var map_id = '{/literal}{$map_id}{literal}';
		var markers = {/literal}{json_encode data=$markers}{literal};
		window[map_id].clear();
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
	});
</script>{/literal}
{/strip}