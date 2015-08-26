{include file="header.tpl" load_type='ui'}

<div class="content-block">
	<div class="view small">
		<div class="image">
			<div class="pos-rel dimp100">
				{l i='profile_deleted' gid='users' assign='text_profile_deleted'}
				<img src="{$data.img}" alt="{$text_profile_deleted}" title="{$text_profile_deleted}" />
			</div>
		</div>
		<div class="info">
			<div class="body">
				<h1>{l i='profile_deleted' gid='users'}</h1>
			</div>
		</div>
	</div>
</div>

<div class="clr"></div>
{include file="footer.tpl"}
