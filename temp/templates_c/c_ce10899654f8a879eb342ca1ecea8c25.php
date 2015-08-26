<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:00:53 Pacific Daylight Time */ ?>

<ul>
<?php if (is_array($this->_vars['menu']) and count((array)$this->_vars['menu'])): foreach ((array)$this->_vars['menu'] as $this->_vars['key'] => $this->_vars['item']): ?>
<li<?php if ($this->_vars['key'] > 0):  if (!($this->_vars['key'] % 4)): ?> class="clr"<?php endif;  endif; ?>>
	<?php echo $this->_vars['item']['value']; ?>

	<?php if (! empty ( $this->_vars['item']['sub'] )): ?>
	<ul>
	<?php if (is_array($this->_vars['item']['sub']) and count((array)$this->_vars['item']['sub'])): foreach ((array)$this->_vars['item']['sub'] as $this->_vars['subitem']): ?><li><a id="footer_<?php echo $this->_vars['item']['gid']; ?>
_<?php echo $this->_vars['subitem']['gid']; ?>
" href="<?php echo $this->_vars['subitem']['link']; ?>
"><?php echo $this->_vars['subitem']['value']; ?>
</a></li><?php endforeach; endif; ?>	
	</ul>
	<?php endif; ?>
</li>
<?php endforeach; endif; ?>
</ul>
