<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:12:56 Pacific Daylight Time */ ?>

<div class="menu-level2">
	<ul>
	<?php if (is_array($this->_vars['menu']) and count((array)$this->_vars['menu'])): foreach ((array)$this->_vars['menu'] as $this->_vars['item']): ?>
		<li<?php if ($this->_vars['item']['active'] == 1): ?> class="active"<?php endif; ?>>
			<div class="l">
				<a id="<?php echo $this->_vars['item']['gid']; ?>
" href="<?php echo $this->_vars['item']['link']; ?>
"><?php echo $this->_vars['item']['value']; ?>

					<?php if ($this->_vars['item']['indicator']): ?><span class="num"><?php echo $this->_vars['item']['indicator']; ?>
</span><?php endif; ?>
				</a>
			</div>
		</li>
	<?php endforeach; endif; ?>
	</ul>
	&nbsp;
</div>