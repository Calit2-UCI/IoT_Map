<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 23:09:08 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<menu class="header-item settings-menu">
	<a href="<?php echo $this->_vars['site_url']; ?>
users/profile"><?php echo $this->_vars['user_session_data']['output_name']; ?>
</a>&nbsp;
	<i class="fa-cog icon hover-spin"></i>
	<div class="drop w150">
		<menu>
			<?php if (is_array($this->_vars['menu']) and count((array)$this->_vars['menu'])): foreach ((array)$this->_vars['menu'] as $this->_vars['key'] => $this->_vars['item']): ?>
				<li class="righted<?php if (! empty ( $this->_vars['item']['active'] ) || ! empty ( $this->_vars['item']['in_chain'] )): ?> active<?php endif; ?>">
					<a id="settings_<?php echo $this->_vars['item']['gid']; ?>
" href="<?php echo $this->_vars['item']['link']; ?>
"><?php echo $this->_vars['item']['value']; ?>
</a>
				</li>
			<?php endforeach; endif; ?>
		</menu>
	</div>
</menu>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>