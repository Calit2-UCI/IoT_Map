<script>{literal}
	var geocoder;
	geocoder_loader = function(){
		geocoder = new BingMapsv7_Geocoder({});
	}
{/literal}</script>

{if !$geomap_js_loaded}
	<script>{literal}
		$(function() {
			loadScripts(
				[
					"{/literal}{js file='bingmapsv7.js' module='geomap' return='path'}{literal}", 
					"http://ecn.dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=7.0&onScriptLoad=geocoder_loader"
				],
				function(){},
				'geocoder',
				{crossDomain: true}
			);
		});
	</script>{/literal}
{else}
	<script>geocoder_loader();</script>
{/if}