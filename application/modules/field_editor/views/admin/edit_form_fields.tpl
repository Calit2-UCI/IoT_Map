{include file="header.tpl"  load_type='ui'}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_fields_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="#" id="add_field_form" onclick="javascript: return false;">{l i='link_add_form_field' gid='field_editor'}</a></div></li>
		<li><div class="l"><a href="#" id="add_section_form" onclick="javascript: return false;">{l i='link_add_form_section' gid='field_editor'}</a></div></li>
		{if $data.fields_data}<li><div class="l"><a href="#" id="sorting_form" onclick="javascript: return false;">{l i='link_save_sorting' gid='field_editor'}</a></div></li>{/if}
	</ul>
	&nbsp;
</div>

<div class="edit-form">
	<div class="row header">{l i='form_name' gid='field_editor'}: {$data.name}</div>
</div>

<div id="menu_items">
	<ul name="form_root" id="form_root" class="sortable"></ul>
</div>

<a class="cancel" href="{$site_url}admin/field_editor/forms/{$data.editor_type_gid}">{l i='btn_cancel' gid='start'}</a>
{js module='field_editor' file='admin-form-fields.js'}
<script type='text/javascript'>
var field_data = {if $data.field_data_json}{$data.field_data_json}{else}[]{/if};
var field_names = {if $data.field_names_json}{$data.field_names_json}{else}[]{/if};
{literal}

	$(function(){
		new formFields({
			siteUrl: '{/literal}{$site_url}{literal}',
			field_data: field_data,
			field_names: field_names,
			formId: '{/literal}{$data.id}{literal}',
			empty_fields: '{/literal}{l i="error_form_name_incorrect" gid="field_editor"}{literal}'
//			urlSaveSort: 'admin/field_editor/ajax_section_sort'
		});
	});
{/literal}
</script>


{include file="footer.tpl"}