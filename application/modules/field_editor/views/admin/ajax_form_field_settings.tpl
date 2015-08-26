<div class="load_content_controller">
	<h1>{l i='header_change_field_settings' gid='field_editor'}</h1>
	<div class="inside">
		<form id="save_section_name">
			<div class="edit-form n150">
				{if $field_type eq 'select'}
					<div class="row">
						<div class="h">{l i='field_select_search_type' gid='field_editor'}: </div>
						<div class="v">
							<select name="search_type" id="field_search_type">
								<option value="one" {if $field.settings.search_type eq 'one'}selected{/if}>{l i='field_select_search_type_one' gid='field_editor'}</option>			
								<option value="many" {if $field.settings.search_type eq 'many'}selected{/if}>{l i='field_select_search_type_many' gid='field_editor'}</option>			
							</select>
						</div>
					</div>
					<div class="row zebra{if $field.settings.search_type eq 'many'} hide{/if}" id="field_view_type_one">
						<div class="h">{l i='field_select_view_type_header' gid='field_editor'}: </div>
						<div class="v">
							<select name="view_type[one]">
								<option value="select" {if $field.settings.view_type eq 'select'}selected{/if}>{l i='field_select_view_type_select' gid='field_editor'}</option>			
								<option value="radio" {if $field.settings.view_type eq 'radio'}selected{/if}>{l i='field_select_view_type_radio' gid='field_editor'}</option>			
							</select>
						</div>
					</div>
					<div class="row zebra{if $field.settings.search_type eq 'one' || !$field.settings.search_type} hide{/if}" id="field_view_type_many">
						<div class="h">{l i='field_select_view_type_header' gid='field_editor'}: </div>
						<div class="v">
							<select name="view_type[many]">
								<option value="select" {if $field.settings.view_type eq 'select'}selected{/if}>{l i='field_select_view_type_multi' gid='field_editor'}</option>			
								<option value="radio" {if $field.settings.view_type eq 'radio'}selected{/if}>{l i='field_select_view_type_checkbox' gid='field_editor'}</option>			
								<option value="slider" {if $field.settings.view_type eq 'slider'}selected{/if}>{l i='field_select_view_type_slider' gid='field_editor'}</option>
							</select>
						</div>
					</div>
				{elseif  $field_type eq 'text'}
					<div class="row">
						<div class="h">{l i='field_text_search_type' gid='field_editor'}: </div>
						<div class="v">
							<select name="search_type" id="field_search_type">
								<option value="text" {if $field.settings.search_type eq 'text'}selected{/if}>{l i='field_search_type_text' gid='field_editor'}</option>			
								<option value="number" {if $field.settings.search_type eq 'number'}selected{/if}>{l i='field_search_type_number' gid='field_editor'}</option>			
							</select>
						</div>
					</div>
					<div class="row zebra{if $field.settings.search_type eq 'number'} hide{/if}" id="field_view_type_text">
						<div class="h">{l i='field_text_view_type_header' gid='field_editor'}: </div>
						<div class="v">
							<select name="view_type[text]">
								<option value="equal" {if $field.settings.view_type eq 'equal'}selected{/if}>{l i='field_text_view_type_exact' gid='field_editor'}</option>			
								<option value="range" {if $field.settings.view_type eq 'range'}selected{/if}>{l i='field_text_view_type_like' gid='field_editor'}</option>			
							</select>
						</div>
					</div>
					<div class="row zebra{if $field.settings.search_type eq 'text' || !$field.settings.search_type} hide{/if}" id="field_view_type_number">
						<div class="h">{l i='field_text_view_type_header' gid='field_editor'}: </div>
						<div class="v">
							<select name="view_type[number]">
								<option value="equal" {if $field.settings.view_type eq 'equal'}selected{/if}>{l i='field_text_view_type_exact' gid='field_editor'}</option>			
								<option value="range" {if $field.settings.view_type eq 'range'}selected{/if}>{l i='field_text_view_type_range' gid='field_editor'}</option>			
							</select>
						</div>
					</div>
				{elseif  $field_type eq 'range'}
					<div class="row">
						<div class="h">{l i='field_text_search_type' gid='field_editor'}: </div>
						<div class="v">
							<select name="search_type" id="field_search_type">
								<option value="range" {if $field.settings.search_type eq 'range'}selected{/if}>{l i='field_search_type_range' gid='field_editor'}</option>			
								<option value="number" {if $field.settings.search_type eq 'number'}selected{/if}>{l i='field_search_type_number' gid='field_editor'}</option>			
							</select>
						</div>
					</div>
				{/if}
			</div>
			<script>{literal}
				$(function(){
					$('#field_search_type').bind('change', function(){
						$(this).find('option').each(function(){
							$('#field_view_type_'+$(this).val()).hide();			
						});
						$('#field_view_type_'+$(this).val()).show();
					});
				});
			</script>{/literal}
			<input type="hidden" name="section_gid" value="{$section_gid}" id="ajax_section_gid">
			<div class="btn"><div class="l"><input type="button" name="btn_save" value="{l i='btn_save' gid='start' type='button'}" id="save_settings_btn"></div></div>
			<a class="cancel" href="#" id="cancel_save_settings" onclick="return false;">{l i='btn_cancel' gid='start'}</a>
		</form>
	</div>
</div>