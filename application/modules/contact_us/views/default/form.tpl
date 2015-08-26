{include file="header.tpl"}
<div class="contact_us content-block">
	<h1>{seotag tag='header_text'}</h1>
	<div class="content-value fltl w650">
		<p class="maxwp100 mb10">{l i='text_contact_form_edit' gid='contact_us'}</p>
		<div class="edit_block">
			<form action="" method="post">
				{if $reasons}
					<div class="r">
						<div class="f">{l i='field_reason' gid='contact_us'}: </div>
						<div class="v">
							<select name="id_reason">
								{foreach item=item from=$reasons}
									<option value="{$item.id}" {if $data.id_reason eq $item.id}selected{/if}>{$item.name}</option>
								{/foreach}
							</select><br>
							<span class="pginfo msg reason"></span>
						</div>
					</div>
				{/if}
				<div class="r">
					<div class="f">{l i='field_user_name' gid='contact_us'}: </div>
					<div class="v">
						<input type="text" name="user_name" value="{$data.user_name}"><br>
						<span class="pginfo msg user_name"></span>
					</div>
				</div>
				<div class="r">
					<div class="f">{l i='field_user_email' gid='contact_us'}: </div>
					<div class="v">
						<input type="text" name="user_email" value="{$data.user_email}"><br>
						<span class="pginfo msg user_email"></span>
					</div>
				</div>
				<div class="r">
					<div class="f">{l i='field_subject' gid='contact_us'}: </div>
					<div class="v">
						<input type="text" name="subject" value="{$data.subject}"><br>
						<span class="pginfo msg subject"></span>
					</div>
				</div>
				<div class="r">
					<div class="f">{l i='field_message' gid='contact_us'}: </div>
					<div class="v">
						<textarea name="message">{$data.message}</textarea><br>
						<span class="pginfo msg message"></span>
					</div>
				</div>
				<div class="captcha r">
					<div class="f">{l i='field_security_code' gid='contact_us'}: </div>
					<div class="v">
						{$data.captcha}<br>
						<input type="text" name="captcha_code" value=""><br>
						<span class="pginfo msg captcha_code"></span>
					</div>
				</div>
				<br>
				<div class="r">
					<div class="l"><input type="submit" class='btn' value="{l i='btn_send' gid='start' type='button'}" name="btn_save"></div>
					<div class="b">&nbsp;</div>
				</div>
			</form>
		</div>
	</div>
	<div class="fltr">
		{helper func_name='show_banner_place' module='banners' func_param='big-right-banner'}
		{helper func_name='show_banner_place' module='banners' func_param='right-banner'}
	</div>
</div>
{block name='show_social_networks_like' module='social_networking'}
{block name='show_social_networks_share' module='social_networking'}
{block name='show_social_networks_comments' module='social_networking'}
<br><br><br>
<div class="clr"></div>
{include file="footer.tpl"}
