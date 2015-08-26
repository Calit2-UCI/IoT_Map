{strip}
<div class="content-block load_content">
	<h1>{l i='edit_album' gid='media'}</h1>
	<div class="inside popup-form">
		<form id="album_form" action="" method="post" enctype="" name="item_form">
			<input type="hidden" name="alb_id" id="alb_id" value="{$album.id}">
			<div class="r">
				<div class="f">{l i='field_name' gid='media'}:</div>
				<div class="v">
					<input class="wp100 box-sizing" type='text' name='name' value='{$album.name}'>
				</div>
			</div>
			<div class="r">
				<div class="f">{l i='field_permitted_for' gid='media'}:</div>
				<div class="v">
					{ld gid='media' i='permissions'}
					<select class="wp100 box-sizing" name="permissions">
					{foreach item=item key=key from=$ld_permissions.option}
						<option value="{$key}"{if $album.permissions eq $key} selected{/if}>{$item}</option>
					{/foreach}
					</select>
				</div>
			</div>
			<div class="r">
				<div class="f">{l i='field_description' gid='media'}:</div>
				<div class="v text"><textarea class="box-sizing" name="description">{/strip}{$album.description}{strip}</textarea></div>
			</div>
			<div class="r">
				<div class="v"><input type="button" value="{l i='btn_save' gid='start' type='button'}" name="btn_upload" id="save_album_form" onclick="javascript:albums_list.edit_album({$album.id})"></div>
			</div>
		</form>
	</div>
	<div class="clr"></div>
</div>
{/strip}