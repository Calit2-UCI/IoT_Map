{js file='admin_lang_inline_editor.js' module='start'}
<script type="text/javascript">{literal}
var inlineEditor;
$(function(){
	inlineEditor = new langInlineEditor({
		siteUrl: '{/literal}{$site_url}{literal}',
		multiple: '{/literal}{$multiple}{literal}',
		{/literal}{if $textarea}textarea: true,{/if}{literal}
	});
});
{/literal}</script>
