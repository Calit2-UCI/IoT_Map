<div class="load_content_controller">
	{if $operator_gid}
		{l i='admin_header_systems_operator_edit' gid='payments' assign='edit_operator_headline'}
	{else}
		{l i='admin_header_systems_operator_add' gid='payments' assign='edit_operator_headline'}
	{/if}
	<h1>{$edit_operator_headline}</h1>
	<div class="inside">
		<form method="post" action="" name="save_form">
			<div class="edit-form n150" id='operator_edit_block'>
				{foreach item=item key=key from=$lang_data}
				{counter print=false assign=counter}
				<div class="row{if $counter is div by 2} zebra{/if}">
					<div class="h">{$item.name}:&nbsp;* </div>
					<div class="v"><input type="text" name="name_{$key|escape}" value="{$item.value|escape}"></div>
				</div>
				{/foreach}
			</div>
			<div class="btn"><div class="l"><input type="button" name="btn_save" value="{l i='btn_save' gid='start' type='button'}" id="btn_save"></div></div>
			<a class="cancel" href="#" id="btn_cancel">{l i='btn_cancel' gid='start'}</a>
		</form>
	</div>
</div>
