<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:01:51 Pacific Daylight Time */ ?>

<ul>
	<?php if (is_array($this->_vars['menu']) and count((array)$this->_vars['menu'])): foreach ((array)$this->_vars['menu'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<li <?php if (! empty ( $this->_vars['item']['active'] ) || ! empty ( $this->_vars['item']['in_chain'] )): ?>class="active"<?php endif; ?>><a id="user_<?php echo $this->_vars['item']['gid']; ?>
" href="<?php echo $this->_vars['item']['link']; ?>
"><span class="a"><?php echo $this->_vars['item']['value'];  if ($this->_vars['item']['indicator']): ?><span class="num"><?php echo $this->_vars['item']['indicator']; ?>
</span><?php endif; ?></span></a>
			<?php if (! empty ( $this->_vars['item']['sub'] )): ?>
				<ul class="submenu noPrint">
					<?php if (is_array($this->_vars['item']['sub']) and count((array)$this->_vars['item']['sub'])): foreach ((array)$this->_vars['item']['sub'] as $this->_vars['key'] => $this->_vars['s']): ?>
						<li <?php if ($this->_vars['s']['active']): ?>class="active"<?php endif; ?>><a id="user_<?php echo $this->_vars['item']['gid']; ?>
_<?php echo $this->_vars['s']['gid']; ?>
" href="<?php echo $this->_vars['s']['link']; ?>
"><?php echo $this->_vars['s']['value'];  if ($this->_vars['s']['indicator']): ?><span class="num"><?php echo $this->_vars['s']['indicator']; ?>
</span><?php endif; ?></a></li>
					<?php endforeach; endif; ?>
				</ul>
			<?php endif; ?>
		</li>
	<?php endforeach; endif; ?>
</ul>