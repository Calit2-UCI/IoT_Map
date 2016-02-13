<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-10-13 09:35:52 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
">
	<div class="filter-form">
		<h3>Welcome | Admin panel</h3>
		<div class="form">
			<br>
			<div class="row">
				<div class="h">Login: </div>
				<div class="v"><input type="text" value="<?php echo $this->_vars['data']['login']; ?>
" name="login"></div>
			</div>
			<div class="row">
				<div class="h">Password: </div>
				<div class="v"><input type="password" value="" name="password"></div>
			</div>
		
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_login" value="Sign in"></div></div>
</form>
<div class="clr"></div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>