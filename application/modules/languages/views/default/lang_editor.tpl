<div id="lang_editor_js_block">
	<div id="lang_handle">{l i='lang_editor' gid='start'}</div>
	<div id="lang_editor_content">
	{foreach item=item from=$lang_editor}
	<div class="ledit" langid="{$item.module_gid}_{$item.gid}">
		<div class="name" alt="{$item.gid}" title="{$item.gid}">{$item.gid}</div>
		<div class="value" langid="{$item.module_gid}_{$item.gid}">{$item.value}</div>
	</div>
	{/foreach}
</div>

<script type='text/javascript'>
var lang_editor_data = new Object;
var lang_editor;
{foreach item=item from=$lang_editor}
lang_editor_data.{$item.module_gid}_{$item.gid} = {literal}{{/literal} module_gid: '{$item.module_gid}', gid: '{$item.gid}', lang_id: '{$item.lang_id}', edit_type: '{$item.edit_type}' {literal}}{/literal};
{/foreach}
{literal}
$(function(){
	loadScripts(
		["{/literal}{js module='languages' file='lang-edit.js' return='path'}{literal}", "{/literal}{js file='jquery.jeditable.mini.js' return='path'}{literal}"],
		function(){
			lang_editor = new langEditor({siteUrl:'{/literal}{$site_url}{literal}', data: lang_editor_data});
		},
		'lang_editor'
	);
});
{/literal}
</script>