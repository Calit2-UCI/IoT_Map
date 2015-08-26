{include file="header.tpl"}

	<div class="content-block">

		<h1>{l i='header_banner_form' gid='banners'}</h1>
		<div class="content-value">
			<form method="post" action="{$site_url}banners/edit" name="save_form" enctype="multipart/form-data">
			<div class="edit_block">
				<div class="r">
					<div class="f">{l i='field_name' gid='banners'}: </div>
					<div class="v"><input type="text" value="{$data.name|escape}" name="name"></div>
				</div>
				<div class="r">
					<div class="f">{l i='banner_place' gid='banners'}: </div>
					<div class="v">
						<select id="banner_place" name="banner_place_id">
							{foreach from=$places item=place}
							<option value="{$place.id}" {if $place.id eq $data.banner_place_id}selected{/if}>{$place.name} ({$place.width}x{$place.height})</option>
							{/foreach}
						</select>
					</div>
				</div>
				<div class="r">
					<div class="f">{l i='field_link' gid='banners'}: </div>
					<div class="v"><input type="text" value="{$data.link|escape}" name="link" class="long"></div>
				</div>
				<div class="r">
					<div class="f">{l i='field_alt_text' gid='banners'}: </div>
					<div class="v"><input type="text" value="{$data.alt_text|escape}" name="alt_text" class="long"></div>
				</div>
				<div class="r">
					<div class="f">{l i='field_image' gid='banners'}: </div>
					<div class="v">
						<input type="file" value="" name="banner_image_file">
					</div>
				</div>

			</div>
			<div class="b">
				<input type="submit" class='btn' value="{l i='btn_send' gid='start' type='button'}" name="btn_save">
			</div>
			</form>
			<div class="b outside">
				<a href="{$site_url}users/account/banners" class="btn-link"><i class="fa-arrow-left icon-big edge hover"></i><i>{l i='link_back_to_my_banners' gid='banners'}</i></a>
			</div>
		</div>
	</div>
<div class="clr"></div>
{include file="footer.tpl"}
