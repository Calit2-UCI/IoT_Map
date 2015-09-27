<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-25 23:18:56 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div<?php if (! $this->_vars['dynamic_block_info_pages_data']['params']['transparent']): ?> class="bg-html_bg"<?php endif; ?>>
	<?php if ($this->_vars['dynamic_block_info_pages_data']['section']['title']): ?>
	<h2 title="<?php echo $this->_run_modifier($this->_vars['dynamic_block_info_pages_data']['section']['title'], 'escape', 'plugin', 1); ?>
"><?php echo $this->_vars['dynamic_block_info_pages_data']['section']['title']; ?>
</h2>
	<?php endif; ?>
	<?php if ($this->_vars['dynamic_block_info_pages_data']['section']): ?>
		<div>
			<?php if ($this->_vars['dynamic_block_info_pages_data']['section']['img']): ?>
			<a href="<?php echo tpl_function_seolink(array('module' => 'content','method' => 'view','data' => $this->_vars['dynamic_block_info_pages_data']['section']), $this);?>">
				<img src="<?php echo $this->_vars['dynamic_block_info_pages_data']['section']['media']['img']['thumbs']['small']; ?>
" alt="">
			</a><br>
			<?php endif; ?>
			<?php if ($this->_vars['dynamic_block_info_pages_data']['section']['annotation']): ?>
				<?php echo $this->_vars['dynamic_block_info_pages_data']['section']['annotation']; ?>

			<?php else: ?>
				<?php echo $this->_vars['dynamic_block_info_pages_data']['section']['content']; ?>

			<?php endif; ?>
			<div class="ptb10"><button onclick="locationHref('<?php echo tpl_function_seolink(array('module' => 'content','method' => 'view','data' => $this->_vars['dynamic_block_info_pages_data']['section']), $this);?>');"><?php echo l('btn_view_more', 'start', '', 'text', array()); ?></button></div>
		</div>
	<?php endif; ?>
	<?php if ($this->_vars['dynamic_block_info_pages_data']['pages']): ?>
		<div class="dynamic-subsections" data-count="<?php echo $this->_run_modifier($this->_vars['dynamic_block_info_pages_data']['pages'], 'count', 'PHP', 0); ?>
">
			<?php if (is_array($this->_vars['dynamic_block_info_pages_data']['pages']) and count((array)$this->_vars['dynamic_block_info_pages_data']['pages'])): foreach ((array)$this->_vars['dynamic_block_info_pages_data']['pages'] as $this->_vars['key'] => $this->_vars['item']): ?>
				<div class="content-dyn-block-item box-sizing">
					<h3 class="text-overflow" title="<?php echo $this->_run_modifier($this->_vars['item']['title'], 'escape', 'plugin', 1); ?>
"><?php if ($this->_vars['item']['title']):  echo $this->_vars['item']['title'];  else: ?>&nbsp;<?php endif; ?></h3>
					<div><?php if ($this->_vars['dynamic_block_info_pages_data']['params']['trim_subsections_text']):  echo $this->_vars['item']['short_content'];  else:  echo $this->_vars['item']['content'];  endif; ?></div>
					<div class="ptb10"><button class="inline-btn" onclick="locationHref('<?php echo tpl_function_seolink(array('module' => 'content','method' => 'view','data' => $this->_vars['item']), $this);?>');"><?php echo l('btn_view_more', 'start', '', 'text', array()); ?></button></div>
				</div>
			<?php endforeach; endif; ?>
		</div>
	<?php endif; ?>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
