{strip}
<div class="highlight p10 mb20 fltl" id="shoutbox_block">
	<h1>{l i='block_header' gid='shoutbox'} <span id="shoutbox_counter_nm"></span></h1>
	<div id="shoutbox" class="shoutbox">
		<div class="shoutbox-content box-sizing" style="height:{$shoutbox_data.height_block_messages}px">
			<div class="shoutbox-scroller box-sizing shoutbox-scroll"></div>
			<i class="hide icon-chevron-up w edge-shout shoutbox-in-top" style="top:-{$shoutbox_data.top_top_icon}px;"></i>
		</div>
		<div class="shoutbox-bottom box-sizing">
			<div class="ptb10"><textarea class="box-sizing wp100"></textarea></div>
			<div id="shoutbox_msg_btns" class="table-div vmiddle wp100">
				<div>
				<input id="shoutbox_btn_send" type="button" name="sendbtn" value="{l i='btn_send' gid='shoutbox'}" />
				<span class="fright" id="shoutbox_msg_count">{$shoutbox_data.msg_max_length}</span>
				</div>
			</div>
		</div>
	</div>
</div>
{/strip}

{js file='jquery-slimscroll.js'}
<script>{literal}
	loadScripts(
		"{/literal}{js module='shoutbox' file='shoutbox.js' return='path'}{literal}",
		function(){
			var data = {/literal}{$shoutbox_json_data}{literal};
			shoutbox = new Shoutbox({
				site_url: site_url,
				new_msgs: data.new_msgs,
				id_user: parseInt(data.id_user),
				max_id: parseInt(data.max_id),
				min_id: parseInt(data.min_id),
				{/literal}{if $_LANG.rtl eq 'rtl'}{literal}position: 'left',{/literal}{/if}{literal}
				msg_max_length: parseInt(data.msg_max_length),
				user_name: data.user_name,
				site_status: parseInt(typeof data.user_status !== 'undefined' ? data.user_status.site_status : 0),
			});
		},
		'',
		{async: false}
	);

</script>{/literal}