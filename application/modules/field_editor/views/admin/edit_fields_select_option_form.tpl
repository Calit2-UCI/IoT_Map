<div class="load_content_controller">
	<h1>{if $option_gid}{l i='header_change_select_option' gid='field_editor'}{else}{l i='header_add_select_option' gid='field_editor'}{/if}</h1>
	<div class="inside">
		<form method="post" action="" name="save_form">
			<div class="edit-form n150" id='change_option_block'>
				{foreach item=item key=key from=$lang_data}
				{counter print=false assign=counter}
				<div class="row{if $counter is div by 2} zebra{/if}">
					<div class="h">{$item.name}: </div>
					<div class="v"><input type="text" name="{$key}" value="{$item.value}">&nbsp;<span>({$item.field_name})</span></div>
				</div>
				{/foreach}
			</div>
			<div class="btn"><div class="l"><input type="button" name="btn_save" value="{l i='btn_save' gid='start' type='button'}" id="btn_save"></div></div>
			<a class="cancel" href="#" id="btn_cancel">{l i='btn_cancel' gid='start'}</a>
		</form>
	</div>
</div>