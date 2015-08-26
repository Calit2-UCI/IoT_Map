<script type='text/javascript'>{literal}
	var banners;
	$(function(){
		loadScripts(
			"{/literal}{js module='banners' file='banners.js' return='path'}{literal}",
			function(){
				banners = new Banners;
			},
			'banners'
		);
	});
{/literal}</script>