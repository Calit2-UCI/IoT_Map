{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_moderation_menu'}
<div class="actions">&nbsp;</div>

<form method="post" action="{$form_save_link}" name="moder_sattings_save">
	<div class="edit-form n150">
		{foreach item=item key=key from=$moder_types}
		<div class="row{if $key is div by 2} zebra{/if}">
			<div class="h">{l i='mtype_'$item.name gid='moderation'}:</div>
			<div class="v">
				<input type="hidden" name="type_id[]" value="{$item.id}">
				{if $item.mtype >= 0}
				<input type="radio" name="mtype[{$item.id}]" value="2" id="mtype_{$item.id}_2"{if $item.mtype eq '2'}checked{/if}><label for="mtype_{$item.id}_2">{l i='mtype_2' gid='moderation'}</label><br>
				<input type="radio" name="mtype[{$item.id}]" value="1" id="mtype_{$item.id}_1"{if $item.mtype eq '1'}checked{/if}><label for="mtype_{$item.id}_1">{l i='mtype_1' gid='moderation'}</label><br>
				<input type="radio" name="mtype[{$item.id}]" value="0" id="mtype_{$item.id}_0"{if $item.mtype eq '0'}checked{/if}><label for="mtype_{$item.id}_0">{l i='mtype_0' gid='moderation'}</label><br>
				{else}
				<input type="hidden" value="mtype[{$item.id}]" value="{$item.mtype}">
				{/if}
				<input type="checkbox" name="check_badwords[{$item.id}]" value="1" {if $item.check_badwords eq '1'}checked{/if} id="chbw_{$item.id}"><label for="chbw_{$item.id}">{l i='check_badwords' gid='moderation'}</label>
			</div>
		</div>
		{/foreach}
	</div>
	<div class="btn"><div class="l"><input type="submit" name="sbmBtn" value="{l i='btn_save' gid='start' type='button'}"></div></div>
</form>


{include file="footer.tpl"}