{strip}
{l i='select_default' gid='start' assign='default_select_lang'}
{l i='filter_all' gid='users' assign='all_select_lang'}
{l i='field_search_country' gid='users' assign='location_lang'}
<form action="{$form_settings.action}" method="POST" id="main_search_form_{$form_settings.form_id}">
	<div class="search-form {$form_settings.type}">
		{if $form_settings.type eq 'line'}
			<div class="inside">
				<div id="line-search-form_{$form_settings.form_id}">
					<input type="text" name="search" placeholder="{l i='search_people' gid='start'}" />
					<button type="submit" id="main_search_button_{$form_settings.form_id}" class="search"><i class="fa-search w"></i></button>
				</div>
			</div>
		{elseif $form_settings.type eq 'index'}
			<div class="fields-block aligned-fields">
				<input type="text" name="search" placeholder="{l i='search_people' gid='start'}" style="width: 183px;"/>
				<button type="submit" id="main_search_button_{$form_settings.form_id}" name="search_button" >
					{l i='btn_search' gid='start' type='button'}
				</button>
			</div>
		{else}
			<div class="inside">
				<div class="btn-block">
					<div class="search-btn">
						{if $form_settings.use_advanced}
							<span class="collapse-links">
								<a href="#" class="hide btn-link" id="more-options-link_{$form_settings.form_id}"><i>{l i='link_more_options' gid='start'}</i><i class="fa-caret-down icon-big text-icon"></i></a>
								<a href="#" class="hide btn-link" id="less-options-link_{$form_settings.form_id}"><i>{l i='link_less_options' gid='start'}</i><i class="fa-caret-up icon-big text-icon"></i></a>
							</span>
							&nbsp;&nbsp;&nbsp;
						{/if}
						<button type="submit" id="main_search_button_{$form_settings.form_id}" name="search_button">
							{if $form_settings.object == 'perfect_match'}{l i='btn_refresh' gid='start' type='button'}{else}{l i='btn_search' gid='start' type='button'}{/if}
						</button>
					</div>
				</div>
				<div class="fields-block aligned-fields">
					<div id="short-search-form_{$form_settings.form_id}">
						<div class="search-field">
							{selectbox input='user_type' id='looking_user_type' value=$user_types.option selected=$data.user_type default=$all_select_lang}
						</div>
						<!--remove search age-->
						<!--div class="search-field age"-->
							<!--div-->
								<!--span class="inline vmiddle">{l i='field_age' gid='users'}&nbsp;{l i='from' gid='users'}&nbsp;</span><div class="ib vmiddle">{selectbox input='age_min' id='age_min' value=$age_range selected=$data.age_min}</div-->
								<!--span class="inline vmiddle">&nbsp;{l i='to' gid='users'}&nbsp;</span><div class="ib vmiddle">{selectbox input='age_max' id='age_max' value=$age_range selected=$data.age_max}</div-->
							<!--/div-->
						<!--/div-->
						<!---->
						<div class="search-field country">
							{block name='location_select' module='countries' select_type='city' placeholder=$location_lang id_country=$data.id_country id_region=$data.id_region id_city=$data.id_city}
						</div>
					</div>
					<div class="clr"></div>
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
		{/if}
	</div>
</form>
{/strip}
