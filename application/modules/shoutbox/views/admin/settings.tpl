{include file="header.tpl"}

{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_shoutbox_menu'}
<div class="actions">&nbsp;</div>


<form method="post" action="" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n250">
		<div class="row header">{$settings_data.name}</div>
		{foreach item=item key=key from=$settings_data.vars}
			<div class="row{if $key is div by 2} zebra{/if}">
				<div class="h">{$item.field_name}:</div>
				<div class="v">
					{if $item.field_type == 'text' || !$item.field_type}
						<input type="text" name="settings[{$item.field}]" value="{$item.value|escape}" class="short">
					{elseif $item.field_type == 'checkbox'}
						<input type="checkbox" name="settings[{$item.field}]" value="1" {if $item.value}checked{/if} class="short">
					{/if}
				</div>
			</div>
		{/foreach}
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
</form>


{include file="footer.tpl"}
