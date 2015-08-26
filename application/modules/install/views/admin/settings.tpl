{include file="header.tpl"}

<form method="post" action="{$data.action}">
	<div class="filter-form">
		<h3>Panel settings</h3>
		<div class="form">
			<div class="row">
				<div class="h">Permissions for config file:</div>
				<div class="v">{if $data.config_writeable}<font class="success">file <b>'{$data.config_file}'</b> <br>is writeable</font>{else}<font class="error">Please change file permissions to 777 <br><b>'{$data.config_file}'</b></font>{/if}</div>
			</div>
			<br>
			<div class="row">
				<div class="h">Login: </div>
				<div class="v"><input type="text" value="{$data.config_login}" name="install_login"></div>
			</div>
			<div class="row">
				<div class="h">Password: </div>
				<div class="v"><input type="text" value="{$data.config_password}" name="install_password"></div>
			</div>
		</div>
		{if $data.ftp}
		<br>
		<h3>FTP access info</h3>
		<div class="form">
			<i>(necessary to update modules)</i><br>
			<div class="row">
				<div class="h">FTP host: </div>
				<div class="v"><input type="text" value="{$data.ftp_host}" name="ftp_host"></div>
			</div>
			<div class="row">
				<div class="h">FTP path to site subfolder: </div>
				<div class="v"><input type="text" value="{$data.ftp_path}" name="ftp_path"></div>
			</div>
			<div class="row">
				<div class="h">FTP user: </div>
				<div class="v"><input type="text" value="{$data.ftp_user}" name="ftp_user"></div>
			</div>
			<div class="row">
				<div class="h">FTP password: </div>
				<div class="v"><input type="password" value="{$data.ftp_password}" name="ftp_password"></div>
			</div>
		</div>
		{/if}
		<br>
		<h3>Limit access to installation by IP address</h3>
		<div class="form">
			<div class="row">
				<div class="h">Enable IP limit: </div>
				<div class="v"><input type="checkbox" value="1" name="installer_ip_protect" {if $data.installer_ip_protect}checked{/if}></div>
			</div>
			<div class="row">
				<div class="h">IP address:<br><i>(delimiter ',')</i> </div>
				<div class="v"><textarea name="installer_ip">{$data.installer_ip}</textarea></div>
			</div>
		</div>
	</div>
	{if $data.config_writeable}
	<div class="btn"><div class="l"><input type="submit" name="save_install_login" value="Save data"></div></div>
	<div class="btn gray fright"><div class="l"><a href="{$site_url}admin/install/installer_settings">Refresh</a></div></div>
	{else}
	<div class="btn gray"><div class="l"><input type="button" name="save_install_login" value="Save data" disabled></div></div>
	<div class="btn fright"><div class="l"><a href="{$site_url}admin/install/installer_settings">Refresh</a></div></div>
	{/if}
</form>
<div class="clr"></div>
{include file="footer.tpl"}