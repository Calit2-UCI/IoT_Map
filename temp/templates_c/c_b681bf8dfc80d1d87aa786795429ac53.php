<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:00:53 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
	<?php switch($this->_vars['dynamic_block_users_view']): case 'big_thumbs':   $this->assign('block_class', 'big');  $this->assign('thumb_name', 'great'); ?>
	<?php break; case 'medium_thumbs':   $this->assign('block_class', 'medium');  $this->assign('thumb_name', 'big'); ?>
	<?php break; case 'small_thumbs':   $this->assign('block_class', 'small');  $this->assign('thumb_name', 'middle'); ?>
	<?php break; case 'small_thumbs_w_descr':   $this->assign('block_class', 'small w-descr');  $this->assign('thumb_name', 'middle'); ?>
	<?php break; case 'carousel':   $this->assign('block_class', 'small');  $this->assign('thumb_name', 'middle'); ?>
	<?php break; case 'carousel_w_descr':   $this->assign('block_class', 'small w-descr');  $this->assign('thumb_name', 'middle'); ?>
	<?php break; default:   $this->assign('block_class', 'medium');  $this->assign('thumb_name', 'big'); ?>
<?php break; endswitch; ?>
<h2 class="text-overflow" title="<?php echo $this->_run_modifier($this->_vars['dynamic_block_users_title'], 'escape', 'plugin', 1); ?>
"><?php echo $this->_vars['dynamic_block_users_title']; ?>
</h2>
<?php if ($this->_vars['dynamic_block_users_view'] == 'carousel' || $this->_vars['dynamic_block_users_view'] == 'carousel_w_descr'): ?>
	<?php echo tpl_function_block(array('name' => 'users_carousel','module' => 'users','users' => $this->_vars['dynamic_block_users'],'scroll' => auto,'class' => $this->_vars['block_class'],'thumb_name' => $this->_vars['thumb_name']), $this);?>
<?php else: ?>
	<div class="user-gallery <?php echo $this->_vars['block_class']; ?>
">
		<?php if (is_array($this->_vars['dynamic_block_users']) and count((array)$this->_vars['dynamic_block_users'])): foreach ((array)$this->_vars['dynamic_block_users'] as $this->_vars['item']): ?>
			<div class="item">
				<div class="user">
					<div class="photo">
						<?php 
$this->assign('text_user_logo', l('text_user_logo', 'users', '', 'button', array_merge(array(),$this->_vars['item'])));
 ?>
						<a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['item']), $this);?>"><img alt="<?php echo $this->_vars['text_user_logo']; ?>
" title="<?php echo $this->_vars['text_user_logo']; ?>
" src="<?php echo $this->_vars['item']['media']['user_logo']['thumbs'][$this->_vars['thumb_name']]; ?>
" /></a>
						<div class="info">
							<div class="text-overflow"><a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['item']), $this);?>" title="<?php echo $this->_run_modifier($this->_vars['item']['output_name'], 'escape', 'plugin', 1); ?>
"><?php echo $this->_vars['item']['output_name']; ?>
</a>, <?php echo $this->_vars['item']['age']; ?>
</div>
							<?php if ($this->_vars['item']['location']): ?><div class="text-overflow" title="<?php echo $this->_run_modifier($this->_vars['item']['location'], 'escape', 'plugin', 1); ?>
"><?php echo $this->_vars['item']['location']; ?>
</div><?php endif; ?>
						</div>
					</div>
				</div>
				<div class="descr hide">
					<div><a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['item']), $this);?>"><?php echo $this->_vars['item']['output_name']; ?>
</a>, <?php echo $this->_vars['item']['age']; ?>
</div>
					<?php if ($this->_vars['item']['location']): ?><div><?php echo $this->_vars['item']['location']; ?>
</div><?php endif; ?>
				</div>
			</div>
		<?php endforeach; else: ?>
			<div class="item empty"><?php echo l('empty_search_results', 'users', '', 'text', array()); ?></div>
		<?php endif; ?>
	</div>
<?php endif; ?>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>

<script><?php echo '
	$(\'.user-gallery\').not(\'.w-descr\')
		.off(\'mouseenter\', \'.photo\').on(\'mouseenter\', \'.photo\', function(){
			$(this).find(\'.info\').stop().slideDown(100);
		}).off(\'mouseleave\', \'.photo\').on(\'mouseleave\', \'.photo\', function(){
			$(this).find(\'.info\').stop(true).delay(100).slideUp(100);
		});
</script>'; ?>

