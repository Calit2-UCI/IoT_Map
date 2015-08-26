<script>{literal}
	var geocoder;
	geocoder_loader = function(){
		geocoder = new YandexMapsv2_Geocoder({});
	}
{/literal}</script>

{if !$geomap_js_loaded}
	<script>{literal}
		$(function() {
			loadScripts(
				[
					"{/literal}{js file='yandexmapsv2.js' module='geomap' return='path'}{literal}", 
					"http://api-maps.yandex.ru/2.0/?load=package.full&mode=debug&lang=ru-RU"
				],
				function(){
					ymaps.ready(geocoder_loader);
				},
				'geocoder',
				{crossDomain: true}
			);
		});
	</script>{/literal}
{else}
	<script>geocoder_loader();</script>
{/if}