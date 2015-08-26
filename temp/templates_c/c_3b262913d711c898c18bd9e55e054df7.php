<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 01:32:23 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
">
	<div class="filter-form">
		<h3>Database settings</h3>
		<div class="form">
			<div class="row">
				<div class="h">Permissions for config file:</div>
				<div class="v"><?php if ($this->_vars['data']['config_writeable']): ?><font class="success">file <b>'<?php echo $this->_vars['data']['config_file']; ?>
'</b> <br>is writable</font><?php else: ?><font class="error">Please change file permissions to 777 <br><b>'<?php echo $this->_vars['data']['config_file']; ?>
'</b></font><?php endif; ?></div>
			</div>
			<br>
			<div class="row">
				<div class="h">DB host: </div>
				<div class="v"><input type="text" value="<?php echo $this->_vars['data']['db_host']; ?>
" name="db_host"></div>
			</div>
			<div class="row">
				<div class="h">DB name: </div>
				<div class="v"><input type="text" value="<?php echo $this->_vars['data']['db_name']; ?>
" name="db_name"></div>
			</div>
			<div class="row">
				<div class="h">DB user: </div>
				<div class="v"><input type="text" value="<?php echo $this->_vars['data']['db_user']; ?>
" name="db_user"></div>
			</div>
			<div class="row">
				<div class="h">DB password: </div>
				<div class="v"><input type="text" value="<?php echo $this->_vars['data']['db_password']; ?>
" name="db_password"></div>
			</div>
			<div class="row">
				<div class="h">DB prefix: </div>
				<div class="v"><input type="text" value="<?php echo $this->_vars['data']['db_prefix']; ?>
" name="db_prefix"></div>
			</div>
		</div>
		<br><br>
		<h3>Server info</h3>
		<div class="form">
			<div class="row">
				<div class="h">Server Name: </div>
				<div class="v"><input type="text" value="<?php echo $this->_vars['data']['server']; ?>
" name="server" class="long"></div>
			</div>
			<div class="row">
				<div class="h">Site path: </div>
				<div class="v"><input type="text" value="<?php echo $this->_vars['data']['site_path']; ?>
" name="site_path" class="long"></div>
			</div>
			<div class="row">
				<div class="h">Subfolder: </div>
				<div class="v"><input type="text" value="<?php echo $this->_vars['data']['subfolder']; ?>
" name="subfolder" class="long"></div>
			</div>
		</div>
	</div>
	<?php if ($this->_vars['data']['config_writeable']): ?>
	<div class="btn"><div class="l"><input type="submit" name="save_install_db" value="Next"></div></div>
	<div class="btn gray fright"><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/install/install_database">Refresh</a></div></div>
	<?php else: ?>
	<div class="btn gray"><div class="l"><input type="button" name="save_install_db" value="Next" disabled></div></div>
	<div class="btn fright"><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/install/install_database">Refresh</a></div></div>
	<?php endif; ?>
</form>
<div class="clr"></div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>