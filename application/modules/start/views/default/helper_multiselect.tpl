<div id="multiselect_{$multiselect_helper_data.rand}" class="multiselect">
	{assign var="active" value=$multiselect_helper_data.active_field}
	<div class="header">
		{$multiselect_helper_data.fields[$active].header}
	</div>
	<div class="selected">
		<ul class="selected-items">
			{if !empty($multiselect_helper_data.selected[$active])}
				{if !empty($multiselect_helper_data.all_selected[$active])}
					<input type="hidden" name="{$active}[]" value="{$multiselect_helper_data.all_value}">
					{foreach item=selectedItem key=selectedKey from=$multiselect_helper_data.fields[$active].option}
						<li class="item {$active}-selected-{$selectedKey}">{$multiselect_helper_data.fields[$active].option[$selectedKey]|trim}</li>
					{/foreach}
				{else}
					{foreach item=selectedItem key=selectedKey from=$multiselect_helper_data.selected[$active]}
						<li class="item {$active}-selected-{$selectedKey}"><input type="hidden" name="{$active}[]" value="{$selectedKey}">{$multiselect_helper_data.fields[$active].option[$selectedKey]}</li>
					{/foreach}
				{/if}
			{/if}
		</ul>
		<a class="options_open" href="javascript:void(0);">{l i='multiselect_change' gid='start'}</a>
	</div>
	<div class="options hide">
		<header>
			<ul class="tabs">
				{foreach item=field key=fieldKey from=$multiselect_helper_data.fields}
					<li data-tab="{$fieldKey}" class="tab_{$fieldKey} tab{if $fieldKey eq $active} active{/if}">
						<a href="javascript:void(0);">{$field.header}</a>
					</li>
				{/foreach}
			</ul>
			<a class="pop-up-close close" href="javascript:void(0);"><i class="fright icon-remove edge icon-big hover w"></i></a>
		</header>
		{foreach item=field key=fieldKey from=$multiselect_helper_data.fields}
			<div class="options_{$fieldKey} tab-content{if $fieldKey ne $active} hide{/if}">
				<ul class="items">
					{foreach item=item key=optionKey name=f from=$multiselect_helper_data.fields[$fieldKey].option}
						<li title="{$item}" data-value="{$optionKey}" 
							class="{$fieldKey}-option-{$optionKey} item
							{if $active eq $fieldKey && (!empty($multiselect_helper_data.all_selected[$active]) || !empty($multiselect_helper_data.selected_keys[$fieldKey][$optionKey]))} selected{/if}"
							>
							{$item}
						</li>
					{/foreach}
				</ul>
			</div>
		{/foreach}
		<footer>
			<div class="limit {if !empty($multiselect_helper_data.all_selected[$active])} hide{/if}">
				<span class="selected_num">0</span> 
				{l i='multiselect_of' gid='start'}
				<span class="max_num"></span><br>
				{l i='multiselect_selected' gid='start'}
			</div>
			<div class="options-selected">
				<ul class="options-selected-items">
					{if !empty($multiselect_helper_data.selected[$active])}
						{if !empty($multiselect_helper_data.all_selected[$active])}
							<li data-value="{$multiselect_helper_data.all_value}" class="item remove-selected item {$active}-selected-{$multiselect_helper_data.all_value}">
								{$multiselect_helper_data.all_text}<i class="fa fa-times"></i>
							</li>
						{else}
							{foreach item=selectedItem key=selectedKey from=$multiselect_helper_data.selected[$active]}
								<li data-value="{$selectedKey}" class="item remove-selected item {$active}-selected-{$selectedKey}">
									{$multiselect_helper_data.fields[$active].option[$selectedKey]}<i class="fa fa-times"></i>
								</li>
							{/foreach}
						{/if}
					{/if}
				</ul>
			</div>
			<div class="buttons">
				<a class="btn_select_all" href="javascript:void(0);">{l i='multiselect_select_all' gid='start'}</a>
				<div class="btn">
					<div class="l">
						<input class="btn_apply" type="button" name="btn_save" value="{l i='btn_apply' gid='start'}">
					</div>
				</div>
			</div>
		</footer>
	</div>
	{js module='start' file='multiselect.js'}
	<script type='text/javascript'>
		{literal}
		$(function () {
			{/literal}{if isset($multiselect_helper_data.var_js_name)}var {$multiselect_helper_data.var_js_name} = {/if}{literal}new options({
				fields: {/literal}{json_encode data=$multiselect_helper_data.fields}{literal},
				allSelected: {/literal}{json_encode data=$multiselect_helper_data.all_selected}{literal},
				allText: '{/literal}{$multiselect_helper_data.all_text}{literal}',
				allValue: '{/literal}{$multiselect_helper_data.all_value}{literal}',
				limits: {/literal}{json_encode data=$multiselect_helper_data.limits}{literal},
				rand: '{/literal}{$multiselect_helper_data.rand}{literal}',
				selectedField: '{/literal}{$multiselect_helper_data.active_field}{literal}',
				selectedItems: {/literal}{json_encode data=$multiselect_helper_data.selected}{literal},
				siteUrl: '{/literal}{$site_url}{literal}',
				alertCantSaveEmpty: '{/literal}{l i='multiselect_please_select' gid='start'}{literal}'
			});
		});
	{/literal}</script>
</div>