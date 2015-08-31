<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 23:09:08 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<ul id="user_top_menu">
	<?php if (is_array($this->_vars['menu']) and count((array)$this->_vars['menu'])): foreach ((array)$this->_vars['menu'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<li <?php if ($this->_vars['item']['active'] || ! empty ( $this->_vars['item']['in_chain'] )): ?>class="active"<?php endif; ?>><span class="a"><?php echo $this->_vars['item']['value'];  if ($this->_vars['item']['indicator']): ?><span class="num"><?php echo $this->_vars['item']['indicator']; ?>
</span><?php endif; ?></span>
			<?php if ($this->_vars['item']['sub']): ?>
				<ul class="submenu noPrint">
					<?php if (is_array($this->_vars['item']['sub']) and count((array)$this->_vars['item']['sub'])): foreach ((array)$this->_vars['item']['sub'] as $this->_vars['key'] => $this->_vars['s']): ?>
						<li <?php if ($this->_vars['s']['active']): ?>class="active"<?php endif; ?>><a id="user_top_<?php echo $this->_vars['item']['gid']; ?>
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
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
