<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.json_encode.php');
$this->register_function("json_encode", "tpl_function_json_encode"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:01:51 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<?php if ($this->_vars['users_carousel_data']['users']): ?>
	<?php if ($this->_vars['users_carousel_data']['header']): ?><h2><?php echo $this->_vars['users_carousel_data']['header']; ?>
</h2><?php endif; ?>

	<script type="text/javascript"><?php echo '
		$(function(){
			loadScripts(
				["';  echo tpl_function_js(array('file' => 'jquery.jcarousel.min.js','return' => 'path'), $this); echo '", "';  echo tpl_function_js(array('file' => 'init_carousel_controls.js','return' => 'path'), $this); echo '"],
				function(){
					var data = ';  echo tpl_function_json_encode(array('data' => $this->_vars['users_carousel_data']['carousel']), $this); echo ';
					$(\'#users_carousel_\'+data.rand).removeClass(\'hide\');
					
					carousel';  echo $this->_vars['users_carousel_data']['rand'];  echo ' = $(\'#users_carousel_\'+data.rand).find(\'.jcarousel\').jcarousel({
						animation: 250
					});

					carousel_controls';  echo $this->_vars['users_carousel_data']['rand'];  echo ' = new init_carousel_controls({
						carousel: carousel';  echo $this->_vars['users_carousel_data']['rand'];  echo ',
						carousel_images_count: data.visible,
						carousel_total_images: data.users_count,
						btnNext: \'#directionright_\'+data.rand,
						btnPrev: \'#directionleft_\'+data.rand,
						scroll: data.scroll
					});
				},
				[\'carousel_controls';  echo $this->_vars['users_carousel_data']['rand'];  echo '\', \'carousel';  echo $this->_vars['users_carousel_data']['rand'];  echo '\']
			);
		});
	</script>'; ?>


	<?php $this->assign('users_carousel_thumb_name', $this->_vars['users_carousel_data']['carousel']['thumb_name']); ?>
	<div id="users_carousel_<?php echo $this->_vars['users_carousel_data']['rand']; ?>
" class="user-gallery carousel-wrapper hide<?php if ($this->_vars['users_carousel_data']['carousel']['class']): ?> <?php echo $this->_vars['users_carousel_data']['carousel']['class'];  endif; ?>">
		<div id="directionleft_<?php echo $this->_vars['users_carousel_data']['rand']; ?>
" class="op direction left hover-icon">
			<div class="fa-chevron-left icon-big edge hover"></div>
		</div>
		<div class="dimp100 box-sizing plr50">
			<div class="jcarousel" dir="<?php echo $this->_vars['_LANG']['rtl']; ?>
">
				<ul>
					<?php if (is_array($this->_vars['users_carousel_data']['users']) and count((array)$this->_vars['users_carousel_data']['users'])): foreach ((array)$this->_vars['users_carousel_data']['users'] as $this->_vars['item']): ?>
						<li<?php if (! empty ( $this->_vars['item']['carousel_data']['class'] )): ?> class="<?php echo $this->_vars['item']['carousel_data']['class']; ?>
"<?php endif;  if (! empty ( $this->_vars['item']['carousel_data']['id'] )): ?> id="<?php echo $this->_vars['item']['carousel_data']['id']; ?>
"<?php endif; ?>>
							<div class="user">
								<div class="photo">
									<a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['item']), $this);?>"><img src="<?php echo $this->_vars['item']['media']['user_logo']['thumbs'][$this->_vars['users_carousel_thumb_name']]; ?>
"/></a>
									<?php if (! empty ( $this->_vars['item']['carousel_data']['icon_class'] )): ?>
										<ins><i class="<?php echo $this->_vars['item']['carousel_data']['icon_class']; ?>
"></i></ins>
									<?php else: ?>
										<div class="info">
											<div class="text-overflow"><a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['item']), $this);?>" title="<?php echo $this->_run_modifier($this->_vars['item']['output_name'], 'escape', 'plugin', 1); ?>
"><?php echo $this->_vars['item']['output_name']; ?>
</a>, <?php echo $this->_vars['item']['age']; ?>
</div>
											<?php if (! empty ( $this->_vars['item']['location'] )): ?><div class="text-overflow" title="<?php echo $this->_run_modifier($this->_vars['item']['location'], 'escape', 'plugin', 1); ?>
"><?php echo $this->_vars['item']['location']; ?>
</div><?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<div class="descr hide">
								<div><a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['item']), $this);?>"><?php echo $this->_vars['item']['output_name']; ?>
</a>, <?php echo $this->_vars['item']['age']; ?>
</div>
								<?php if ($this->_vars['item']['location']): ?><div><?php echo $this->_vars['item']['location']; ?>
</div><?php endif; ?>
							</div>
						</li>
					<?php endforeach; endif; ?>
				</ul>
			</div>
		</div>
		<div id="directionright_<?php echo $this->_vars['users_carousel_data']['rand']; ?>
" class="op direction right hover-icon">
			<div class="fa-chevron-right icon-big edge hover"></div>
		</div>
	</div>
<?php endif; ?>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
<script><?php echo '
	$(\'#users_carousel_';  echo $this->_vars['users_carousel_data']['rand'];  echo '\').not(\'.w-descr\')
		.off(\'mouseenter\', \'.photo\').on(\'mouseenter\', \'.photo\', function(){
			$(this).find(\'.info\').stop().slideDown(100);
		}).off(\'mouseleave\', \'.photo\').on(\'mouseleave\', \'.photo\', function(){
			$(this).find(\'.info\').stop(true).delay(100).slideUp(100);
		});
	$(\'.info\', \'#users_carousel_';  echo $this->_vars['users_carousel_data']['rand'];  echo '\').hide();
</script>'; ?>

