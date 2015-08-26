{strip}
<div class="tab-submenu bg-highlight_bg" id="mailbox_top_menu">
	<div class="fleft">
		<ul>
			<li>
				<input type="text" name="name_to_user" id="user_text" autocomplete="off" 
					   class="long w400" placeholder="{l i='search_ph' gid='winks'}">&nbsp;
				<script>{literal}
					$(function(){
						loadScripts(
							"{/literal}{js file='autocomplete_input.js' return='path'}{literal}",
							function(){
								user_autocomplete = new autocompleteInput({
									siteUrl: '{/literal}{$site_url}{literal}',
									dataUrl: 'winks/ajax_get_users_data',
									id_text: 'user_text',
									id_hidden: 'user_hidden',
									rand: '{/literal}{$rand}{literal}',
									format_callback: function(data){
										return data.output_name;
									},
									select_callback: function(data) {
										winksObj.addUserToList(data);
										$('#user_text').val('');
									}
								});
							},
							'user_autocomplete'
						);
					});
				{/literal}</script>
			</li>
		</ul>
	</div>
</div>
{/strip}