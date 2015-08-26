{strip}
<script type="text/javascript">
	selects = [];
	checkboxes = [];
	hlboxes = [];
</script>
<div class="search-box {$form_settings.type}">
	<div id="search-form-block_{$form_settings.form_id}">{/strip}{$form_block}{strip}</div>
</div>
<script type="text/javascript">{literal}
	$(function(){
		loadScripts(
			[
				"{/literal}{js module='start' file='search.js' return='path'}{literal}",
				"{/literal}{js module='start' file='selectbox.js' return='path'}{literal}",
				"{/literal}{js module='start' file='checkbox.js' return='path'}{literal}",
				"{/literal}{js module='start' file='hlbox.js' return='path'}{literal}"
			],
			function(){
				{/literal}{$form_settings.object}{$form_settings.type}{literal} = new search({
					siteUrl: '{/literal}{$site_url}{literal}',
					currentForm: '{/literal}{$form_settings.object}{literal}',
					currentFormType: '{/literal}{$form_settings.type}{literal}',
					hide_popup: {/literal}{if $form_settings.hide_popup}true{else}false{/if}{literal},
					popup_autoposition: {/literal}{if $form_settings.popup_autoposition}true{else}false{/if}{literal}
				});
			},
			'{/literal}{$form_settings.object}{$form_settings.type}{literal}',
			{async: false}
		);
	});
{/literal}</script>
{/strip}