<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-30 23:43:00 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<?php if ($this->_vars['dynamic_block_news_data']['news_count']): ?>
	<div<?php if (! $this->_vars['dynamic_block_news_data']['params']['transparent']): ?> class="bg-html_bg"<?php endif; ?>>
		<h2 class="text-overflow p0"><?php echo l('header_news', 'news', '', 'text', array()); ?></h2>
		<div class="dynamic-subsections" data-count="<?php echo $this->_vars['dynamic_block_news_data']['news_count']; ?>
">
			<?php if (is_array($this->_vars['dynamic_block_news_data']['news']) and count((array)$this->_vars['dynamic_block_news_data']['news'])): foreach ((array)$this->_vars['dynamic_block_news_data']['news'] as $this->_vars['item']): ?>
				<div class="news-dyn-block-item box-sizing">
					<h3 class="text-overflow" title="<?php echo $this->_run_modifier($this->_vars['item']['name'], 'escape', 'plugin', 1); ?>
"><?php if ($this->_vars['item']['name']):  echo $this->_vars['item']['name'];  else: ?>&nbsp;<?php endif; ?></h3>
					<div>
						<?php if ($this->_vars['item']['img']): ?>
						<?php 
$this->assign('text_news_photo', l('text_news_photo', 'news', '', 'button', array_merge(array(),$this->_vars['item'])));
 ?>
						<img class="fleft mr5 mb5" src="<?php echo $this->_vars['item']['media']['img']['thumbs']['small']; ?>
" alt="<?php echo $this->_vars['text_news_photo']; ?>
" title="<?php echo $this->_vars['text_news_photo']; ?>
" />
						<?php endif; ?>
						<?php echo $this->_vars['item']['annotation']; ?>

					</div>
					<div class="ptb10"><button class="inline-btn" onclick="locationHref('<?php echo tpl_function_seolink(array('module' => 'news','method' => 'view','data' => $this->_vars['item']), $this);?>');"><?php echo l('btn_view_more', 'start', '', 'text', array()); ?></button></div>
				</div>
			<?php endforeach; endif; ?>
		</div>
	</div>
<?php endif; ?>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
