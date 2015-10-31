{strip}
{l i='select_default' gid='start' assign='default_select_lang'}
{l i='filter_all' gid='users' assign='all_select_lang'}
{l i='field_search_country' gid='users' assign='location_lang'}
<form action="{$form_settings.action}" method="POST" id="main_search_form_{$form_settings.form_id}">
	<div >
		{if $form_settings.type eq 'line'}         <!--The one on the right top of the user/search page-->
			<div class="search-form {$form_settings.type}">
			<div class="inside">
				<div id="line-search-form_{$form_settings.form_id}">
					<input type="text" name="search" placeholder="{l i='search_people' gid='start'}" />
					<button type="submit" id="main_search_button_{$form_settings.form_id}" class="search"><i class="fa-search w"></i></button>
				</div>
			</div>
			</div>
		{elseif $form_settings.type eq 'index'}    <!--The one on the homepage-->
			{/strip}{include file="homepage_header.html" module="users"}{strip}


			
		{else}									   <!--The one on the middle of the user/search page-->
			<div class="search-form {$form_settings.type}">
			<div>
				<div class="fields-block aligned-fields">
					{/strip}{include file="search.html" module="users"}{strip}
				
				
					<div id="full-search-form_{$form_settings.form_id}" {if $form_settings.type eq 'short'}class="hide"{/if}>
						{if $form_settings.use_advanced}
							<div class="clr"></div>
							{foreach from=$advanced_form item=item}
								{if $item.type eq 'section'}
									{foreach from=$item.section.fields item=field key=key}
										<div class="search-field custom {$field.field.type} {$field.settings.search_type}">
											<p>{$field.field_content.name}</p>
											{/strip}{include file="helper_search_field_block.tpl" module="users" field=$field field_name=$field.field_content.field_name}{strip}
										</div>
									{/foreach}
								{else}
									<div class="search-field custom {$item.field.type} {$item.settings.search_type}">
										<p>{$item.field_content.name}</p>
										{/strip}{include file="helper_search_field_block.tpl" module="users" field=$item field_name=$item.field_content.field_name}{strip}
									</div>
								{/if}
							{/foreach}
						{/if}
					</div>
				</div>
			</div>
			</div>
		{/if}
	</div>
</form>
{/strip}

