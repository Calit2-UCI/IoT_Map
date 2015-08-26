{include file="header.tpl"}
<h1>{seotag tag="header_text"}</h1>
<div class="content-block" id="like_me-block">
	<div class="edit_block">
		<div class="tabs tab-size-15 noPrint">
			<ul>
				{if $data.play_local_used}
				<li{if $action eq 'play_global'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='like_me' method='index' action='play_global'}">{l i='header_play_global' gid='like_me'}</a></li>
				<li{if $action == 'play_local'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='like_me' method='index' action='play_local'}">{l i='header_play_local' gid='like_me'}</a></li>
				{else}
				<li{if $action eq 'play_global'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='like_me' method='index' action='play_global'}">{l i='header_play' gid='like_me'}</a></li>
				{/if}
				<li{if $action eq 'matches'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='like_me' method='index' action='matches'}">{l i='header_matches' gid='like_me'}</a></li>
			</ul>
		</div>
		<div id="action-block"></div>
	</div>
</div>
<script>
{if !empty($user_data.have_more)}
	var all_loaded = {if $user_data.have_more}0{else}1{/if};
{else}
	var all_loaded = 0;
{/if}
{literal}
$(function(){
	loadScripts(
		["{/literal}{js module='like_me' file='like_me.js' return='path'}{literal}", "{/literal}{js module='like_me' file='match_me.js' return='path'}{literal}"],
		function(){
			var action_id = '{/literal}{$action}{literal}';
			like_me = new LikeMe({
				siteUrl: site_url,
				action_id: action_id,
			});
			match_me = new MatchMe({
				siteUrl: site_url,
				all_loaded: all_loaded,
				show_more_lang: "{/literal}{l i='button_show_more' gid='like_me'}{literal}",
			});
		},
		['like_me', 'match_me'],
		{async: true}
	);
});
</script>{/literal}
{include file="footer.tpl"}