<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 01:47:33 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" id="install_langs">
	<table class="data" width="100%" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<th class="first">Name</th>
			<th class="w50">Install</th>
			<th class="w50">Default</th>
		</tr>
		<?php if (is_array($this->_vars['data']['available']) and count((array)$this->_vars['data']['available'])): foreach ((array)$this->_vars['data']['available'] as $this->_vars['lang']): ?>
		<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
		<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
			<td class="first"><?php echo $this->_vars['lang']['name']; ?>
</td>
			<td class="center"><input type="checkbox" <?php if (! $this->_vars['data']['install'] || $this->_run_modifier($this->_vars['lang']['code'], 'in_array', 'PHP', 1, $this->_vars['data']['install'])): ?> checked="checked"<?php endif; ?> name="install[<?php echo $this->_vars['lang']['id']; ?>
]" value="<?php echo $this->_vars['lang']['code']; ?>
"/></td>
			<td class="center"><input type="radio" <?php if ($this->_vars['data']['default'] == $this->_vars['lang']['code']): ?> checked="checked"<?php endif; ?> name="default" value="<?php echo $this->_vars['lang']['code']; ?>
" /></td>
		</tr>
		<?php endforeach; endif; ?>
	</tbody>
	</table>
	<?php if ($this->_vars['data']['available']): ?>
	<div class="btn"><div class="l"><input type="submit" name="save_install_langs" value="Next"></div></div>
	<?php else: ?>
	<div class="btn gray"><div class="l"><input type="button" name="save_install_langs" value="Next" disabled="disabled"></div></div>
	<?php endif; ?>
</form>
<div class="clr"></div>
<?php echo tpl_function_js(array('module' => 'install','file' => 'product_install.js'), $this);?>
<script type="text/javascript">
	var productInstall = new productInstall();
	<?php echo '
		$(function(){
			productInstall.langs_init();
		});
	'; ?>

</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>