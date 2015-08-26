{include file="header.tpl"}

<form method="post" action="{$data.action}">
	<div class="filter-form">
		<h3>Database settings</h3>
		<div class="form">
			<div class="row">
				<div class="h">Permissions for config file:</div>
				<div class="v">{if $data.config_writeable}<font class="success">file <b>'{$data.config_file}'</b> <br>is writable</font>{else}<font class="error">Please change file permissions to 777 <br><b>'{$data.config_file}'</b></font>{/if}</div>
			</div>
			<br>
			<div class="row">
				<div class="h">DB host: </div>
				<div class="v"><input type="text" value="{$data.db_host}" name="db_host"></div>
			</div>
			<div class="row">
				<div class="h">DB name: </div>
				<div class="v"><input type="text" value="{$data.db_name}" name="db_name"></div>
			</div>
			<div class="row">
				<div class="h">DB user: </div>
				<div class="v"><input type="text" value="{$data.db_user}" name="db_user"></div>
			</div>
			<div class="row">
				<div class="h">DB password: </div>
				<div class="v"><input type="text" value="{$data.db_password}" name="db_password"></div>
			</div>
			<div class="row">
				<div class="h">DB prefix: </div>
				<div class="v"><input type="text" value="{$data.db_prefix}" name="db_prefix"></div>
			</div>
		</div>
		<br><br>
		<h3>Server info</h3>
		<div class="form">
			<div class="row">
				<div class="h">Server Name: </div>
				<div class="v"><input type="text" value="{$data.server}" name="server" class="long"></div>
			</div>
			<div class="row">
				<div class="h">Site path: </div>
				<div class="v"><input type="text" value="{$data.site_path}" name="site_path" class="long"></div>
			</div>
			<div class="row">
				<div class="h">Subfolder: </div>
				<div class="v"><input type="text" value="{$data.subfolder}" name="subfolder" class="long"></div>
			</div>
		</div>
	</div>
	{if $data.config_writeable}
	<div class="btn"><div class="l"><input type="submit" name="save_install_db" value="Next"></div></div>
	<div class="btn gray fright"><div class="l"><a href="{$site_url}admin/install/install_database">Refresh</a></div></div>
	{else}
	<div class="btn gray"><div class="l"><input type="button" name="save_install_db" value="Next" disabled></div></div>
	<div class="btn fright"><div class="l"><a href="{$site_url}admin/install/install_database">Refresh</a></div></div>
	{/if}
</form>
<div class="clr"></div>
{include file="footer.tpl"}