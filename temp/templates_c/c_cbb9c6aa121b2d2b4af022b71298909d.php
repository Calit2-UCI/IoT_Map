<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.pagination.php');
$this->register_function("pagination", "tpl_function_pagination"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.sorter.php');
$this->register_function("sorter", "tpl_function_sorter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-09 01:37:14 Pacific Standard Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>

	<?php if ($this->_vars['users']): ?>
		<?php if (! empty ( $this->_vars['sort_data'] )): ?>
            <div class="sorter short-line" id="sorter_block">
                <?php echo tpl_function_sorter(array('links' => $this->_vars['sort_data']['links'],'order' => $this->_vars['sort_data']['order'],'direction' => $this->_vars['sort_data']['direction'],'url' => $this->_vars['sort_data']['url']), $this);?>
                <span class="h2"><?php if ($this->_vars['search_type'] == 'perfect_match'):  echo l('header_perfect_match_results', 'users', '', 'text', array());  else:  echo l('header_users_search_results', 'users', '', 'text', array());  endif; ?> - <?php echo $this->_vars['page_data']['total_rows']; ?>
 <?php echo l('header_users_found', 'users', '', 'text', array()); ?></span>
                <div class="fright"><?php echo tpl_function_pagination(array('data' => $this->_vars['page_data'],'type' => 'cute'), $this);?></div>
            </div>
        <?php endif; ?>
	<?php else: ?>
		<h2><?php if ($this->_vars['search_type'] == 'perfect_match'):  echo l('header_perfect_match_results', 'users', '', 'text', array());  else:  echo l('header_users_search_results', 'users', '', 'text', array());  endif; ?> - <?php echo $this->_vars['page_data']['total_rows']; ?>
 <?php echo l('header_users_found', 'users', '', 'text', array()); ?></h2>
	<?php endif; ?>

	<?php if ($this->_vars['hl_data']['service_highlight']['status']): ?>
		<div id="hl_service_container" class="highlight mtb10 ptb10">
			<input type="button" class="ml10 inline-btn" value="<?php echo $this->_vars['hl_data']['service_highlight']['name']; ?>
" onclick="highlight_in_search_available_view.check_available();" />
			<span class="ml10"><?php echo $this->_vars['hl_data']['service_highlight']['description']; ?>
</span>
			<script><?php echo '
				$(function(){
					loadScripts(
						"';  echo tpl_function_js(array('file' => 'available_view.js','return' => 'path'), $this); echo '", 
						function(){
							highlight_in_search_available_view = new available_view({
								siteUrl: site_url,
								checkAvailableAjaxUrl: \'users/ajax_available_highlight_in_search/\',
								buyAbilityAjaxUrl: \'users/ajax_activate_highlight_in_search/\',
								buyAbilityFormId: \'ability_form\',
								buyAbilitySubmitId: \'ability_form_submit\',
								formType: \'list\',
								success_request: function(message) {error_object.show_error_block(message, \'success\'); $(\'#hl_service_container\').remove();},
								fail_request: function(message) {error_object.show_error_block(message, \'error\');},
							});
						},
						[\'highlight_in_search_available_view\'],
						{async: false}
					);
				});
			</script>'; ?>

		</div>
	<?php endif; ?>
		
	<div<?php if ($this->_vars['page_data']['view_type'] == 'gallery'): ?> class="user-gallery medium"<?php endif; ?>>   <!-- make change on this line, big to medium -->
		<?php if (is_array($this->_vars['users']) and count((array)$this->_vars['users'])): foreach ((array)$this->_vars['users'] as $this->_vars['item']): ?>
			<?php 
$this->assign('text_user_logo', l('text_user_logo', 'users', '', 'button', array_merge(array(),$this->_vars['item'])));
 ?>
			<?php if ($this->_vars['page_data']['view_type'] == 'gallery'): ?>
				<div id="item-block-<?php echo $this->_vars['item']['id']; ?>
" class="item"> <!--<?php if ($this->_vars['item']['is_highlight_in_search'] || $this->_vars['item']['leader_bid'] || ( $this->_vars['item']['is_up_in_search'] && $this->_vars['page_data']['use_leader'] )): ?> highlight<?php endif; ?>"-->
					<div class="user">
						<div class="photo">													 <!--at the line below, change great to big-->
							<a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['item']), $this);?>"><img src="<?php echo $this->_vars['item']['media']['user_logo']['thumbs']['big']; ?>
" alt="<?php echo $this->_vars['text_user_logo']; ?>
" title="<?php echo $this->_vars['text_user_logo']; ?>
" /></a>
							<div class="info">
								<!--div class="text-overflow"><a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['item']), $this);?>"><?php echo $this->_vars['item']['output_name']; ?>
</a>, <?php echo $this->_vars['item']['age']; ?>
</div--> <!--remove age-->
								<div class="text-overflow"><a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['item']), $this);?>"><?php echo $this->_vars['item']['output_name']; ?>
</a></div>
								<?php if ($this->_vars['item']['location']): ?><div class="text-overflow"><?php echo $this->_vars['item']['location']; ?>
</div><?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			<?php else: ?>
				<div id="item-block-<?php echo $this->_vars['item']['id']; ?>
" class="item user">    <!--<?php if ($this->_vars['item']['is_highlight_in_search'] || $this->_vars['item']['leader_bid'] || ( $this->_vars['item']['is_up_in_search'] && $this->_vars['page_data']['use_leader'] )): ?> highlight<?php endif; ?>"-->

					<?php if ($this->_vars['item']['is_up_in_search'] && $this->_vars['page_data']['use_leader']): ?><div class="lift_up"><?php echo l('header_up_in_search', 'users', '', 'text', array()); ?></div><?php endif; ?>
					<?php if ($this->_vars['item']['leader_bid']): ?><div class="lift_up"><?php echo l('header_leader', 'users', '', 'text', array()); ?></div><?php endif; ?>
					<h3><a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['item']), $this);?>"><?php echo $this->_vars['item']['nickname']; ?>
</a>&nbsp;</h3>
					<div class="image">
						<a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['item']), $this);?>"><img src="<?php echo $this->_vars['item']['media']['user_logo']['thumbs']['middle']; ?>
" alt="<?php echo $this->_vars['text_user_logo']; ?>
" title="<?php echo $this->_vars['text_user_logo']; ?>
"></a>
					</div>
					<div class="body">
						<?php 
$this->assign('no_info_str', l('no_information', 'start', '', 'text', array()));
 ?>
						<h3><?php echo $this->_vars['item']['output_name']; ?>
</h3>
						<div class="t-1">
							<span><?php echo l('field_age', 'users', '', 'text', array()); ?>:</span> <?php echo $this->_vars['item']['age']; ?>

						</div>
						<div class="t-2">
							<span><?php echo l('field_date_created', 'users', '', 'text', array()); ?>:</span> <?php echo $this->_run_modifier($this->_vars['item']['date_created'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format']); ?>

						</div>
						<div class="t-2">
							<?php echo $this->_vars['item']['user_type_str']; ?>
.&nbsp;<span><?php echo l('field_looking_user_type', 'users', '', 'text', array()); ?>:</span>&nbsp;<?php echo $this->_vars['item']['looking_user_type_str']; ?>

						</div>
						<?php if ($this->_vars['item']['location']): ?>
							<div class="t-2">
								<span><?php echo l('field_location', 'users', '', 'text', array()); ?>:</span> <?php echo $this->_vars['item']['location']; ?>

							</div>
						<?php endif; ?>
					</div>
					<div class="clr"></div>
					<div class="actions">
						<a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['item']), $this);?>"><?php echo l('header_view_profile', 'users', '', 'text', array()); ?></a>
					</div>
				</div>
			<?php endif; ?>
		<?php endforeach; else: ?>
			<div class="item empty"><?php echo l('empty_search_results', 'users', '', 'text', array()); ?></div>
		<?php endif; ?>
	</div>
	<?php if ($this->_vars['users']): ?><div id="pages_block_2"><?php echo tpl_function_pagination(array('data' => $this->_vars['page_data'],'type' => 'full'), $this);?></div><?php endif; ?>

<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>

<script><?php echo '
	$(\'.user-gallery\').not(\'.w-descr\').find(\'.photo\')
		.off(\'mouseenter\').on(\'mouseenter\', function(){
			$(this).find(\'.info\').stop().slideDown(100);
		}).off(\'mouseleave\').on(\'mouseleave\', function(){
			$(this).find(\'.info\').stop(true).delay(100).slideUp(100);
		});
</script>'; ?>

