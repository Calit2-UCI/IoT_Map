{strip}
<div class="content-block load_content">
	<h1>{l i='header_wall_settings' gid='wall_events'}&nbsp;</h1>

	{if $user_perm}
		<div class="inside wall-perm-form">
			<form action="{$site_url}wall_events/save_user_permissions" method="post">
				<input type="hidden" value="{$redirect_url}" name="redirect_url" />
				<div class="r">
					{if $user_perm.wall_post}
						<div class="r">
							<input type="hidden" name="perm[wall_post][post_allow]" value="0" />
							<label>
								<input type="checkbox" name="perm[wall_post][post_allow]" value="1"{if $user_perm.wall_post.post_allow}checked{/if} />
								&nbsp;{l i='post_allow' gid='wall_events'}
							</label>
						</div>
					{/if}
					<table class="list">
						<tr>
							<th>{l i='events' gid='wall_events'}</th>
							<th>{l i='my_events_show' gid='wall_events'}</th>
							<th>{l i='friends_events_show' gid='wall_events'}</th>
						</tr>
						{foreach from=$user_perm item=perm key=gid}
							<tr>
								<td>{l i='wetype_'$gid gid='wall_events'}</td>
								<td>
									<select name="perm[{$gid}][permissions]">
										<option value="0"{if $perm.permissions==0} selected{/if}>{l i='value_for_me' gid='wall_events'}</option>
										<option value="1"{if $perm.permissions==1} selected{/if}>{l i='value_for_friends' gid='wall_events'}</option>
										<option value="2"{if $perm.permissions==2} selected{/if}>{l i='value_for_registered' gid='wall_events'}</option>
										<option value="3"{if $perm.permissions==3} selected{/if}>{l i='value_for_all' gid='wall_events'}</option>
									</select>
								</td>
								<td class="center"><input type="hidden" name="perm[{$gid}][feed]" value="0" /><input type="checkbox" name="perm[{$gid}][feed]"{if $perm.feed} checked{/if} value="1" /></td>
							</tr>
						{/foreach}
					</table>
				</div>
				<div>
					<input type="submit" value="{l i='btn_save' gid='start' type='button'}" name="btn_save">
				</div>
			</form>
		</div>
	{else}
		<div class="p20">{l i='no_wall_events_types' gid='wall_events'}</div>
	{/if}
	<div class="clr"></div>
</div>
{/strip}