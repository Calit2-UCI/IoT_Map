<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.pagination.php');
$this->register_function("pagination", "tpl_function_pagination"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seotag.php');
$this->register_function("seotag", "tpl_function_seotag"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 02:04:20 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
	<h1><?php echo tpl_function_seotag(array('tag' => 'header_text'), $this);?> - <?php echo $this->_vars['page_data']['total_rows']; ?>
 <?php echo l('header_users_found', 'users', '', 'text', array()); ?></h1>

	<div class="tabs tab-size-15">
		<ul>
			<li<?php if ($this->_vars['views_type'] == 'my_guests'): ?> class="active"<?php endif; ?>><a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'my_guests'), $this);?>"><?php echo l('header_my_guests', 'users', '', 'text', array()); ?></a></li>
			<li<?php if ($this->_vars['views_type'] == 'my_visits'): ?> class="active"<?php endif; ?>><a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'my_visits'), $this);?>"><?php echo l('header_my_visits', 'users', '', 'text', array()); ?></a></li>
		</ul>
	</div>
	
	<div class="sorter line">
		<select name="period" onchange="locationHref(('<?php echo tpl_function_seolink(array('module' => 'users','method' => $this->_vars['views_type'],'period' => '[period]'), $this);?>').replace('[period]', this.value));">
			<option value="all"<?php if ($this->_vars['period'] == 'all'): ?> selected<?php endif; ?>><?php echo l('all_time', 'users', '', 'text', array()); ?></option>
			<option value="month"<?php if ($this->_vars['period'] == 'month'): ?> selected<?php endif; ?>><?php echo l('last_month', 'users', '', 'text', array()); ?></option>
			<option value="week"<?php if ($this->_vars['period'] == 'week'): ?> selected<?php endif; ?>><?php echo l('last_week', 'users', '', 'text', array()); ?></option>
			<option value="today"<?php if ($this->_vars['period'] == 'today'): ?> selected<?php endif; ?>><?php echo l('today', 'users', '', 'text', array()); ?></option>
		</select>
		<?php if ($this->_vars['users']): ?><div class="fright"><?php echo tpl_function_pagination(array('data' => $this->_vars['page_data'],'type' => 'cute'), $this);?></div><?php endif; ?>
	</div>
	

	<div id="users_block" class="user-gallery big w-subtext">
	<?php if (is_array($this->_vars['users']) and count((array)$this->_vars['users'])): foreach ((array)$this->_vars['users'] as $this->_vars['item']): ?>
		<?php $this->assign('viewer_id', $this->_vars['item']['id']); ?>
		<?php if ($this->_vars['viewer_id']): ?>
		<div id="item-block-<?php echo $this->_vars['item']['id']; ?>
" class="item">
			<div class="user">
				<div class="photo">
					<?php 
$this->assign('text_user_logo', l('text_user_logo', 'users', '', 'button', array_merge(array(),$this->_vars['item'])));
 ?>
					<a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['item']), $this);?>"><img src="<?php echo $this->_vars['item']['media']['user_logo']['thumbs']['great']; ?>
" alt="<?php echo $this->_vars['text_user_logo']; ?>
" title="<?php echo $this->_vars['text_user_logo']; ?>
" /></a>
					<div class="subtext"><span><?php echo $this->_run_modifier($this->_vars['view_dates'][$this->_vars['viewer_id']], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_time_format']); ?>
</span></div>
					<div class="info">
						<div class="text-overflow"><a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['item']), $this);?>"><?php echo $this->_vars['item']['output_name']; ?>
</a>, <?php echo $this->_vars['item']['age']; ?>
</div>
						<?php if ($this->_vars['item']['location']): ?><div class="text-overflow"><?php echo $this->_vars['item']['location']; ?>
</div><?php endif; ?>
					</div>
				</div>
			</div>
		</div>
					
		<script><?php echo '
			$(\'#users_block\').not(\'.w-descr\').find(\'.photo\')
				.off(\'mouseenter\').on(\'mouseenter\', function(){
					$(this).find(\'.info\').stop().slideDown(100);
				}).off(\'mouseleave\').on(\'mouseleave\', function(){
					$(this).find(\'.info\').stop(true).delay(100).slideUp(100);
				});
		</script>'; ?>

		<?php endif; ?>
	<?php endforeach; else: ?>
		<div class="item empty"><?php echo l('empty_search_results', 'users', '', 'text', array()); ?></div>
	<?php endif; ?>
	</div>
	<?php if ($this->_vars['users']): ?><div id="pages_block_2"><?php echo tpl_function_pagination(array('data' => $this->_vars['page_data'],'type' => 'full'), $this);?></div><?php endif;  $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
