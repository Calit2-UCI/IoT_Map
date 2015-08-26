<script>{literal}
	var geocoder;
	geocoder_loader = function(){
		geocoder = new GoogleMapsv3_Geocoder({});
	}
{/literal}</script>

{if !$geomap_js_loaded}
	<script>{literal}
		$(function() {
			loadScripts(
				[
					"{/literal}{js file='googlemapsv3.js' module='geomap' return='path'}{literal}", 
					"https://maps.google.com/maps/api/js?v=3.9&libraries=places&sensor=true&key={/literal}{$map_reg_key}{literal}&callback=geocoder_loader"
				],
				function(){
					geocoder_loader();
				},
				'geocoder',
				{crossDomain: true}
			);
		});
	</script>{/literal}
{else}
	<script>geocoder_loader();</script>
{/if}