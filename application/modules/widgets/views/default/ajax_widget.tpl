	{$widget.content}
	<script>{literal}
		$(function(){
			setTimeout(function(){
				var parent_url = decodeURIComponent(document.location.hash.replace(/^#/, ''));
				$.postMessage({widget_{/literal}{$widget.gid}{literal}_height: $('body').outerHeight(true)}, parent_url, parent);
			}, 100);
		});
	{/literal}</script>
