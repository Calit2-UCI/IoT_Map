{js file='lang_inline_editor.js' module='start'}
<script type="text/javascript">{literal}
var inlineEditor{/literal}{$rand}{literal};
$(function(){
	inlineEditor{/literal}{$rand}{literal} = new langInlineEditor({
		siteUrl: '{/literal}{$site_url}{literal}',
	});
});
{/literal}</script>
