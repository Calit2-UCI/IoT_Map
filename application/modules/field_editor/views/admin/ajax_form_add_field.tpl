<div class="load_content_controller">
	<h1>{l i='header_add_form_field' gid='field_editor'}</h1>
	<div class="inside">
		{if $form_type eq 'sections'}
			<b>{l i='text_add_form_select_section' gid='field_editor'}:</b>
			<ul id="section_select" class="form-fields">
			{foreach item=item from=$sections}
				<li gid="{$item.gid}">{$item.name}</li>
			{/foreach}
			</ul>
		{else}
			<b>{l i='text_add_form_select_field' gid='field_editor'}</b> {l i='text_add_form_or' gid='field_editor'} <a href="#" id="fields_back" onclick="javascript: return false;">{l i='text_add_form_select_another_s' gid='field_editor'}</a><br>
			<ul id="field_select" class="form-fields">
			{foreach item=item from=$fields}
				<li gid="{$item.gid}">{$item.name}</li>
			{/foreach}
			</ul>
		{/if}
		<a class="cancel" href="#" id="fields_close" onclick="javascript: return false;">{l i='btn_cancel' gid='start'}</a>
	</div>
</div>