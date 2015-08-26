<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:03:43 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<?php if (is_array($this->_vars['options']) and count((array)$this->_vars['options'])): foreach ((array)$this->_vars['options'] as $this->_vars['item']): ?>
<div id="<?php echo $this->_vars['item']['gid']; ?>
_block" class="settings-block<?php if ($this->_vars['item']['gid']): ?> with-<?php echo $this->_vars['item']['gid'];  endif; ?>" onclick="javascript: location.href='<?php echo $this->_vars['item']['link']; ?>
';">
	<a id="<?php echo $this->_vars['item']['gid']; ?>
" href="<?php echo $this->_vars['item']['link']; ?>
"><h6><?php echo $this->_vars['item']['value']; ?>
</h6></a>
	<div><?php echo $this->_vars['item']['tooltip']; ?>
</div>
</div>
<?php endforeach; endif; ?>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>