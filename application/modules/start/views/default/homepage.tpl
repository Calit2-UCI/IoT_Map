{include file="header.tpl"}
<div class="content-block">
    <div class="content-value fltl w650">
		<div class="view-user">{block name='wall_block' module='wall_events' place='homepage' id_wall=$user_id}</div>
    </div>
    <div class="fltr active_block">
		{block name='shoutbox_form' module='shoutbox'}
        <div id="active_users">{block name='active_users_block' module='users' count=16}</div>
        <div id="recent_photo">{block name='recent_media_block' module='media' upload_gid='photo' count=16}</div>
    </div>
</div>
{include file="footer.tpl"}

