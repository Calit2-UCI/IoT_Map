{if $field_type eq 'checkbox'}
		<div class="row">
			<div class="h">{l i='field_checkbox_by_default' gid='field_editor'}: </div>
			<div class="v">
				<input type="hidden" name="settings_data[default_value]" value="0">
				<input type="checkbox" name="settings_data[default_value]" value="1"{if $data.settings_data_array.default_value} checked{/if}>
			</div>
		</div>
{elseif $field_type eq 'text'}
		<div class="row">
			<div class="h">{l i='field_text_by_default' gid='field_editor'}: </div>
			<div class="v"><input type="text" name="settings_data[default_value]" value="{$data.settings_data_array.default_value}"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_text_min_char' gid='field_editor'}: </div>
			<div class="v"><input type="text" name="settings_data[min_char]" value="{$data.settings_data_array.min_char}" class="short"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_text_max_char' gid='field_editor'}: </div>
			<div class="v"><input type="text" name="settings_data[max_char]" value="{$data.settings_data_array.max_char}" class="short"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_text_template' gid='field_editor'}: </div>
			<div class="v">
			<select name="settings_data[template]">
			{foreach item=item from=$initial.template.options}
			<option value="{$item}"{if $data.settings_data_array.template eq $item} selected{/if}>{l i="text_template_$item" gid='field_editor'}</option>
			{/foreach}
			</select>
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_text_format' gid='field_editor'}: </div>
			<div class="v">
			<select name="settings_data[format]">
			{foreach item=item from=$initial.format.options}
			<option value="{$item}"{if $data.settings_data_array.format eq $item} selected{/if}>{l i="text_format_$item" gid='field_editor'}</option>
			{/foreach}
			</select>
			</div>
		</div>
{elseif $field_type eq 'textarea'}
		<div class="row">
			<div class="h">{l i='field_textarea_by_default' gid='field_editor'}: </div>
			<div class="v"><input type="text" name="settings_data[default_value]" value="{$data.settings_data_array.default_value}"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_textarea_min_char' gid='field_editor'}: </div>
			<div class="v"><input type="text" name="settings_data[min_char]" value="{$data.settings_data_array.min_char}" class="short"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_textarea_max_char' gid='field_editor'}: </div>
			<div class="v"><input type="text" name="settings_data[max_char]" value="{$data.settings_data_array.max_char}" class="short"></div>
		</div>
{elseif $field_type eq 'select'}
		<div class="row">
			<div class="h">{l i='field_select_view_type' gid='field_editor'}: </div>
			<div class="v">
			<select name="settings_data[view_type]">
			{foreach item=item from=$initial.view_type.options}
			<option value="{$item}"{if $data.settings_data_array.view_type eq $item} selected{/if}>{l i="select_view_type_$item" gid='field_editor'}</option>
			{/foreach}
			</select>
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_select_empty_option' gid='field_editor'}: </div>
			<div class="v">
				<input type="hidden" name="settings_data[empty_option]" value="0">
				<input type="checkbox" name="settings_data[empty_option]" value="1"{if $data.settings_data_array.empty_option} checked{/if}>
			</div>
		</div>

		<div class="row">
			<div class="h">{l i='field_select_options' gid='field_editor'}: </div>
			<div class="v">
				<div id="hidden_block"></div>
				<a href="#" id="add_option_link">{l i='link_add_new_option' gid='field_editor'}</a>
				<div class="select-options" id="select_options_block">
				{$options_block}
				</div>
			</div>
		</div>
		{js module='field_editor' file='admin-field-editor-select.js'}
		<script type='text/javascript'>{literal}
			var sOptions;
			$(function(){
				sOptions =  new fieldEditorSelect({
					siteUrl: '{/literal}{$site_url}{literal}',
					fieldID: '{/literal}{$data.id}{literal}',
					defaultMultiple: false,
					defaultValues: [{/literal}{$data.settings_data_array.default_value}{literal}]
				});
			});
		{/literal}</script>
{elseif $field_type eq 'multiselect'}
		<div class="row">
			<div class="h">{l i='field_select_view_type' gid='field_editor'}: </div>
			<div class="v">
			<select name="settings_data[view_type]">
			{foreach item=item from=$initial.view_type.options}
			<option value="{$item}"{if $data.settings_data_array.view_type eq $item} selected{/if}>{l i="select_view_type_$item" gid='field_editor'}</option>
			{/foreach}
			</select>
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_select_options' gid='field_editor'}: </div>
			<div class="v">
				<div id="hidden_block"></div>
				<a href="#" id="add_option_link">{l i='link_add_new_option' gid='field_editor'}</a>
				<div class="select-options" id="select_options_block">
				{$options_block}
				</div>
			</div>
		</div>
		{js module='field_editor' file='admin-field-editor-select.js'}
		<script type='text/javascript'>{literal}
			var sOptions;
			$(function(){
				sOptions =  new fieldEditorSelect({
					siteUrl: '{/literal}{$site_url}{literal}',
					fieldID: '{/literal}{$data.id}{literal}',
					defaultMultiple: true,
					defaultValues: {/literal}{json_encode data=$data.settings_data_array.default_value}{literal}
				});
			});
		{/literal}</script>
{elseif $field_type eq 'range'}
		<div class="row">
			<div class="h">{l i='field_text_min_val' gid='field_editor'}: </div>
			<div class="v"><input type="text" name="settings_data[min_val]" value="{$data.settings_data_array.min_val}" class="short"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_text_max_val' gid='field_editor'}: </div>
			<div class="v"><input type="text" name="settings_data[max_val]" value="{$data.settings_data_array.max_val}" class="short"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_text_template' gid='field_editor'}: </div>
			<div class="v">
			<select name="settings_data[template]">
			{foreach item=item from=$initial.template.options}
			<option value="{$item}"{if $data.settings_data_array.template eq $item} selected{/if}>{l i="text_template_$item" gid='field_editor'}</option>
			{/foreach}
			</select>
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_text_format' gid='field_editor'}: </div>
			<div class="v">
			<select name="settings_data[format]">
			{foreach item=item from=$initial.format.options}
			<option value="{$item}"{if $data.settings_data_array.format eq $item} selected{/if}>{l i="text_format_$item" gid='field_editor'}</option>
			{/foreach}
			</select>
			</div>
		</div>
{/if}