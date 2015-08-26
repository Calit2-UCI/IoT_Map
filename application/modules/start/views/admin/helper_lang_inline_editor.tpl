<div class="load_content_controller">
	<h1>{l i='header_inline_lang_edit' gid='start'}</h1>
	<div class="inside">
		<form name="lang_edit" class="edit-form n100">
			<div class="popup">
			
			{foreach item=item key=key from=$langs}
			{counter print=false assign=counter}
				<div class="row{if $counter is div by 2} zebra{/if}">
					<div class="h"><label>{$item.name}</label>: </div>
					<div class="v">
						{if $is_textarea}
						<textarea name="field{$key}" rows="10" cols="80" lang-editor="redactor" lang-id="{$key}"></textarea>
						{else}
						<input type="text" name="field{$key}" value="" lang-editor="redactor" lang-id="{$key}" />
						{/if}
					</div>
				</div>
			{/foreach}
			
			</div>
			<div class="btn"><div class="l"><input type="button" id="lie_save" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
			<a class="cancel" href="#" id="lie_close">{l i='btn_cancel' gid='start'}</a>
		</form>
	</div>
</div>
