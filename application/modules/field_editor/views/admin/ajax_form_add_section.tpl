<div class="load_content_controller">
	<h1>{if $section_gid}{l i='header_edit_form_section' gid='field_editor'}{else}{l i='header_add_form_section' gid='field_editor'}{/if}</h1>
	<div class="inside">
	<form id="save_section_name">
	<div class="edit-form n150">
		<div class="row">
			<div class="h">{l i='field_name' gid='field_editor'}: </div>
			<div class="v"><input type="text" value="{$data[$cur_lang]}" name="langs[{$cur_lang}]"></div>
		</div>
		{foreach item=item key=lang_id from=$languages}
		{counter print=false assign=counter}
		{if $lang_id ne $cur_lang}
		<div class="row{if $counter is div by 2} zebra{/if}">
			<div class="h">{$item.name}: </div>
			<div class="v"><input type="text" value="{$data[$lang_id]}" name="langs[{$lang_id}]"></div>
		</div>
		{/if}
		{/foreach}
	</div>
	<input type="hidden" name="section_gid" value="{$section_gid}" id="ajax_section_gid">
	<div class="btn"><div class="l"><input type="button" name="btn_save" value="{l i='btn_save' gid='start' type='button'}" id="save_section_btn"></div></div>
	<a class="cancel" href="#" id="cancel_save_section" onclick="return false;">{l i='btn_cancel' gid='start'}</a>
	</form>
	</div>
</div>