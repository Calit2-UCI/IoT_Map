<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.pagination.php');
$this->register_function("pagination", "tpl_function_pagination"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 02:38:25 Pacific Daylight Time */ ?>

	<table class="list vmiddle">
		<tr id="sorter_block">
			<th class="w30"><?php echo l('field_number', 'banners', '', 'text', array()); ?></th>
			<th class="w30">&nbsp;</th>
			<th><?php echo l('field_name', 'banners', '', 'text', array()); ?></th>
			<th><?php echo l('field_approve', 'banners', '', 'text', array()); ?></th>
			<th class="w150">&nbsp;</th>
		</tr>
		<?php if (is_array($this->_vars['banners']) and count((array)$this->_vars['banners'])): foreach ((array)$this->_vars['banners'] as $this->_vars['banner']): ?>
			<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
			<tr>
				<td class="centered"><?php echo $this->_vars['counter']; ?>
</td>
				<td class="view-banner">
					<a href='#' onclick="return false;" id="view_<?php echo $this->_vars['banner']['id']; ?>
" class="icon-eye-open icon-big edge hover zoom10" title="<?php echo l('link_view_banner', 'banners', '', 'text', array()); ?>"></a>
					<div id="view_<?php echo $this->_vars['banner']['id']; ?>
_content" style="display: none">
						<?php if ($this->_vars['banner']['banner_type'] == 1): ?><img src="<?php echo $this->_vars['banner']['media']['banner_image']['file_url']; ?>
" width="<?php echo $this->_vars['banner']['banner_place_obj']['width']; ?>
" height="<?php echo $this->_vars['banner']['banner_place_obj']['height']; ?>
" alt="<?php echo $this->_run_modifier($this->_vars['banner']['alt_text'], 'escape', 'plugin', 1); ?>
" /><?php else:  echo $this->_vars['banner']['html'];  endif; ?>
					</div>
				</td>
				<td>
					<b><?php echo $this->_vars['banner']['name']; ?>

					<?php if ($this->_vars['banner']['banner_place_obj']): ?>
					(<?php echo $this->_vars['banner']['banner_place_obj']['name']; ?>
 <?php echo $this->_vars['banner']['banner_place_obj']['width']; ?>
X<?php echo $this->_vars['banner']['banner_place_obj']['height']; ?>
)
					<?php endif; ?></b><br>
					<?php $this->assign('limit', ''); ?>
					<?php if ($this->_vars['banner']['number_of_views']): ?>
					<?php $this->assign('limit', 1); ?>
					<?php echo l('shows', 'banners', '', 'text', array()); ?> - <?php echo $this->_vars['banner']['number_of_views']; ?>

					<br/>
					<?php endif; ?>
					<?php if ($this->_vars['banner']['number_of_clicks']): ?>
					<?php $this->assign('limit', 1); ?>
					<?php echo l('clicks', 'banners', '', 'text', array()); ?> - <?php echo $this->_vars['banner']['number_of_clicks']; ?>

					<br/>
					<?php endif; ?>
					<?php if ($this->_vars['banner']['expiration_date'] && $this->_vars['banner']['expiration_date'] != '0000-00-00 00:00:00'): ?>
					<?php $this->assign('limit', 1); ?>
					<?php echo l('till', 'banners', '', 'text', array()); ?> - <?php echo $this->_run_modifier($this->_vars['banner']['expiration_date'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format']); ?>

					<?php endif; ?>
					<?php if (! $this->_vars['limit']):  if ($this->_vars['banner']['status']):  echo l('never_stop', 'banners', '', 'text', array());  else: ?>&nbsp;<?php endif;  endif; ?>
				</td>
				<td>
					<?php if ($this->_vars['banner']['approve'] == '1'): ?><span class="status"><i class="icon-ok-sign icon-big"></i>&nbsp;<?php if ($this->_vars['banner']['status']):  echo l('text_banner_activated', 'banners', '', 'text', array());  else:  echo l('approved', 'banners', '', 'text', array());  endif; ?></span>
					<?php elseif ($this->_vars['banner']['approve'] == '-1'): ?><span class="status"><i class="icon-ban-circle icon-big"></i>&nbsp;<?php echo l('declined', 'banners', '', 'text', array()); ?></span>
					<?php else: ?><span class="status wait"><i class="icon-time g icon-big"></i>&nbsp;<?php echo l('not_approved', 'banners', '', 'text', array()); ?></span><?php endif; ?>
				</td>
				<td class="r righted">
					<?php if ($this->_vars['banner']['approve'] == '1'): ?>
						<?php if (! $this->_vars['banner']['status']): ?><a href="<?php echo $this->_vars['site_url']; ?>
banners/activate/<?php echo $this->_vars['banner']['id']; ?>
" class="icon-play icon-big edge hover mr10" title="<?php echo l('link_banner_activate', 'banners', '', 'text', array()); ?>"></a><?php endif; ?>
						<a href="<?php echo $this->_vars['site_url']; ?>
banners/statistic/<?php echo $this->_vars['banner']['id']; ?>
" class="icon-bar-chart icon-big edge hover mr10" title="<?php echo l('link_banner_stat', 'banners', '', 'button', array()); ?>"></a>
					<?php endif; ?>
					<a href="javascript:;" onclick="javascript: if(!confirm('<?php echo l('note_delete_banner', 'banners', '', 'js', array()); ?>')) return false; locationHref('<?php echo $this->_vars['site_url']; ?>
banners/delete/<?php echo $this->_vars['banner']['id']; ?>
');" class="icon-trash icon-big edge hover zoom30"></a>
				</td>
			</tr>
		<?php endforeach; else: ?>
			<tr>
				<td class="empty" colspan=5><?php echo l('no_banners', 'banners', '', 'text', array()); ?></td>
			</tr>
		<?php endif; ?>
	</table>
		
	<?php echo tpl_function_pagination(array('data' => $this->_vars['page_data'],'type' => 'full'), $this);?>


<script type='text/javascript'><?php echo '
	$(function(){
		loadScripts(
			"';  echo tpl_function_js(array('file' => 'easyTooltip.min.js','return' => 'path'), $this); echo '", 
			function(){
				$("td.view-banner > a").each(function(){
					var id = $(this).attr(\'id\')+\'_content\';
					$(this).easyTooltip({useElement: id});
				});
			}
		);
	});
</script>'; ?>


<div class="clr"></div>
