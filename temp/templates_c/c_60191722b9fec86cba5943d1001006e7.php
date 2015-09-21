<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.pagination.php');
$this->register_function("pagination", "tpl_function_pagination"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seotag.php');
$this->register_function("seotag", "tpl_function_seotag"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 02:46:05 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
	<div class="content-block">
		<h1><?php echo tpl_function_seotag(array('tag' => 'header_text'), $this);?></h1>
		<div class="edit_block">
			<div class="tabs tab-size-15 noPrint">
				<ul>
					<li<?php if ($this->_vars['method'] == 'friendlist'): ?> class="active"<?php endif; ?>>
						<a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'friendlist','method' => 'index'), $this);?>">
							<?php echo l('friendlist', 'friendlist', '', 'text', array()); ?>
							<?php if ($this->_vars['counts']['friendlist']): ?>&nbsp;(<?php echo $this->_vars['counts']['friendlist']; ?>
)<?php endif; ?>
						</a>
					</li>
					<li<?php if ($this->_vars['method'] == 'friends_requests'): ?> class="active"<?php endif; ?>>
						<a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'friendlist','method' => 'friends_requests'), $this);?>">
							<?php echo l('friends_requests', 'friendlist', '', 'text', array()); ?>
							<?php if ($this->_vars['counts']['friends_requests']): ?>&nbsp;(<?php echo $this->_vars['counts']['friends_requests']; ?>
)<?php endif; ?>
						</a>
					</li>
					<li<?php if ($this->_vars['method'] == 'friends_invites'): ?> class="active"<?php endif; ?>>
						<a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'friendlist','method' => 'friends_invites'), $this);?>">
							<?php echo l('friends_invites', 'friendlist', '', 'text', array()); ?>
							<?php if ($this->_vars['counts']['friends_invites']): ?>&nbsp;(<?php echo $this->_vars['counts']['friends_invites']; ?>
)<?php endif; ?>
						</a>
					</li>
				</ul>
			</div>
			<div class="view-user">
				<div class="sorter short-line" id="sorter_block">
					<div class="search-line fleft">
						<form method="POST" action="">
							<input type="text" placeholder="<?php echo l('search', 'friendlist', '', 'text', array()); ?>" 
								   name="search" value="<?php echo $this->_vars['search']; ?>
" />&nbsp;
							<input type="submit" value="<?php echo l('btn_search', 'start', '', 'text', array()); ?>" />
						</form>
					</div>
					<?php if ($this->_vars['list']): ?>
						<div class="fright"><?php echo tpl_function_pagination(array('data' => $this->_vars['page_data'],'type' => 'cute'), $this);?></div>
					<?php endif; ?>
				</div>
				
				<div class="user-gallery big w-actions">
					<?php if (is_array($this->_vars['list']) and count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['item']): ?>
						<div class="item">
							<div class="user">
								<div class="photo">
									<div class="actions">
										<div class="text fright">
											<span><?php echo $this->_run_modifier($this->_vars['item']['date_update'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_time_format']); ?>
</span>
										</div>
										<div class="btns black">
											<?php if ($this->_vars['method'] == 'friends_requests'): ?>
												<a href="<?php echo tpl_function_seolink(array('module' => 'friendlist','method' => 'accept','destination_user_id' => $this->_vars['item']['id_user']), $this);?>" 
												   class="btn-link link-r-margin" title="<?php echo l('action_accept', 'friendlist', '', 'text', array()); ?>">
													<i class="fa-check icon-big edge hover w"></i>
												</a>
												<a href="<?php echo tpl_function_seolink(array('module' => 'friendlist','method' => 'decline','destination_user_id' => $this->_vars['item']['id_user']), $this);?>" 
												   class="btn-link link-r-margin" title="<?php echo l('action_decline', 'friendlist', '', 'text', array()); ?>">
													<i class="fa-remove icon-big edge hover w"></i>
												</a>
											<?php endif; ?>
											<?php if ($this->_vars['method'] != 'friends_requests'): ?>
												<a href="<?php echo tpl_function_seolink(array('module' => 'friendlist','method' => remove,'destination_user_id' => $this->_vars['item']['id_dest_user']), $this);?>" 
												   class="btn-link link-r-margin" title="<?php echo l('action_remove', 'friendlist', '', 'text', array()); ?>">
													<i class="fa-trash icon-big edge hover w zoom30"></i>
												</a>
											<?php endif; ?>
										</div>
									</div>
									<a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => view,'data' => $this->_vars['item']['user']), $this);?>">
										<img alt="" src="<?php echo $this->_vars['item']['user']['media']['user_logo']['thumbs']['great']; ?>
" />
									</a>
									<div class="info">
										<div class="text-overflow"><a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['item']['user']), $this);?>"><?php echo $this->_vars['item']['user']['output_name']; ?>
</a>, <?php echo $this->_vars['item']['user']['age']; ?>
</div>
										<?php if ($this->_vars['item']['user']['location']): ?><div class="text-overflow"><?php echo $this->_vars['item']['user']['location']; ?>
</div><?php endif; ?>
										<?php if (( $this->_vars['method'] == 'friends_requests' || $this->_vars['method'] == 'friends_invites' ) && $this->_vars['item']['comment']): ?>
											<div class="oh comment" title="<?php echo $this->_run_modifier($this->_vars['item']['comment'], 'escape', 'plugin', 1, html); ?>
"><?php echo $this->_run_modifier($this->_vars['item']['comment'], 'nl2br', 'PHP', 1); ?>
</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; else: ?>
						<div class="item empty"><?php echo l('empty_result', 'friendlist', '', 'text', array()); ?></div>
					<?php endif; ?>
				</div>
				<?php if ($this->_vars['list']): ?><div id="pages_block_2"><?php echo tpl_function_pagination(array('data' => $this->_vars['page_data'],'type' => 'full'), $this);?></div><?php endif; ?>
			</div>
		</div>
	</div>
	<div class="clr"></div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
<script><?php echo '
	$(\'.user-gallery\').not(\'.w-descr\').find(\'.photo\')
			.off(\'mouseenter\').on(\'mouseenter\', function() {
		$(this).find(\'.info\').stop().slideDown(100);
	}).off(\'mouseleave\').on(\'mouseleave\', function() {
		$(this).find(\'.info\').stop(true).delay(100).slideUp(100);
	});
</script>'; ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
