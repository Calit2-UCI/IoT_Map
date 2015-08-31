<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:15:53 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<?php echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_banners_menu'), $this);?>
<div class="actions">
	<ul>
		<li><div class="l"><a id="banners_link_add" href="<?php echo $this->_vars['site_url']; ?>
admin/banners/edit"><?php echo l('link_add_banner', 'banners', '', 'text', array()); ?></a></div></li>
		<li><div class="l"><a id="banners_link_update" href="<?php echo $this->_vars['site_url']; ?>
admin/banners/update_hour_statistic"><?php echo l('update_statistic_manually', 'banners', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>

<div class='menu-level3'>
	<ul>
		<li<?php if ($this->_vars['page_data']['view_type'] == 'admin'): ?> class="active"<?php endif; ?>><a href="<?php echo $this->_vars['site_url']; ?>
admin/banners/index/admin"><?php echo l('filter_admin_banners', 'banners', '', 'text', array()); ?></a></li>
		<li<?php if ($this->_vars['page_data']['view_type'] == 'user'): ?> class="active"<?php endif; ?>><a href="<?php echo $this->_vars['site_url']; ?>
admin/banners/index/user"><?php echo l('filter_users_banners', 'banners', '', 'text', array()); ?></a></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><?php echo l('field_number', 'banners', '', 'text', array()); ?></th>
	<th>&nbsp;</th>
	<th><?php echo l('field_name', 'banners', '', 'text', array()); ?></th>
	<th><?php echo l('field_location', 'banners', '', 'text', array()); ?></th>
	<th><?php echo l('field_limitations', 'banners', '', 'text', array()); ?></th>
	<th>&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['banners']) and count((array)$this->_vars['banners'])): foreach ((array)$this->_vars['banners'] as $this->_vars['banner']): ?>
<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first w20 center"><?php echo $this->_vars['counter']; ?>
</td>
	<td class="center view-banner">
		<a href="javascript:void(0)" id="view_<?php echo $this->_vars['banner']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-view.png" width="16" height="16" alt="<?php echo l('link_view_banner', 'banners', '', 'text', array()); ?>" title="<?php echo l('link_view_banner', 'banners', '', 'text', array()); ?>"></a>
		<div id="view_<?php echo $this->_vars['banner']['id']; ?>
_content" class="preview"></div>
	</td>
	<td><?php echo $this->_vars['banner']['name']; ?>
 <?php if ($this->_vars['page_data']['view_type'] == 'user'): ?>(<?php echo $this->_vars['banner']['user']['output_name']; ?>
)<?php endif; ?></td>
	<td class="w150 center">
		<?php if ($this->_vars['banner']['banner_place_obj']): ?>
		<?php echo $this->_vars['banner']['banner_place_obj']['name']; ?>
 <?php echo $this->_vars['banner']['banner_place_obj']['width']; ?>
X<?php echo $this->_vars['banner']['banner_place_obj']['height']; ?>

		<?php endif; ?>
	</td>
	<td class="w150 center">&nbsp;
		<?php if ($this->_vars['banner']['approve'] == -1): ?>
			<?php echo l('declined', 'banners', '', 'text', array()); ?>
		<?php else: ?>
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
			<?php if (! $this->_vars['limit']):  if ($this->_vars['banner']['status']):  echo l('never_stop', 'banners', '', 'text', array());  else:  echo l('text_banner_inactivated', 'banners', '', 'text', array());  endif;  endif; ?>
		<?php endif; ?>
	</td>
	<td class="w150 icons">
		<a href='<?php echo $this->_vars['site_url']; ?>
admin/banners/statistic/<?php echo $this->_vars['banner']['id']; ?>
/'><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-stats.png" width="16" height="16" alt="<?php echo l('link_view_statistic', 'banners', '', 'text', array()); ?>" title="<?php echo l('link_view_statistic', 'banners', '', 'text', array()); ?>"></a>
	<?php if ($this->_vars['page_data']['view_type'] == "admin"): ?>
		<?php if ($this->_vars['banner']['status'] == 1): ?>
		<a class="tooltip" id="banner_deactivate_<?php echo $this->_vars['banner']['id']; ?>
" href='<?php echo $this->_vars['site_url']; ?>
admin/banners/activate/<?php echo $this->_vars['banner']['id']; ?>
/0'><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" alt="<?php echo l('link_deactivate_banner', 'banners', '', 'text', array()); ?>" title="<?php echo l('link_deactivate_banner', 'banners', '', 'text', array()); ?>"></a>
		<span id="span_banner_deactivate_<?php echo $this->_vars['banner']['id']; ?>
" class="hide"><div class="tooltip-info">
			<?php if ($this->_vars['banner']['views_left']): ?><b><?php echo l('shows_left', 'banners', '', 'text', array()); ?>:</b> <?php echo $this->_vars['banner']['views_left']; ?>
<br><?php endif; ?>
			<?php if ($this->_vars['banner']['clicks_left']): ?><b><?php echo l('clicks_left', 'banners', '', 'text', array()); ?>:</b> <?php echo $this->_vars['banner']['clicks_left']; ?>
<br><?php endif; ?>
			<?php if ($this->_vars['banner']['expiration_date'] && $this->_vars['banner']['expiration_date'] != '0000-00-00 00:00:00'): ?><b><?php echo l('till', 'banners', '', 'text', array()); ?>:</b> <?php echo $this->_run_modifier($this->_vars['banner']['expiration_date'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format']); ?>
<br><?php endif; ?>
		</div></span>
		<?php else: ?>
		<a href='<?php echo $this->_vars['site_url']; ?>
admin/banners/activate/<?php echo $this->_vars['banner']['id']; ?>
/1'><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" alt="<?php echo l('link_activate_banner', 'banners', '', 'text', array()); ?>" title="<?php echo l('link_activate_banner', 'banners', '', 'text', array()); ?>"></a>
		<?php endif; ?>
	<?php endif; ?>
	<?php if ($this->_vars['page_data']['view_type'] == "user" && $this->_vars['banner']['status'] == 1): ?>
		<a href='<?php echo $this->_vars['site_url']; ?>
admin/banners/view/<?php echo $this->_vars['banner']['id']; ?>
'><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-view.png" width="16" height="16" alt="<?php echo l('link_view_banner', 'banners', '', 'text', array()); ?>" title="<?php echo l('link_view_banner', 'banners', '', 'text', array()); ?>"></a>
	<?php else: ?>
		<?php if ($this->_vars['banner']['approve'] != -1): ?>
		<a href='<?php echo $this->_vars['site_url']; ?>
admin/banners/edit/<?php echo $this->_vars['banner']['id']; ?>
'><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" alt="<?php echo l('link_edit_banner', 'banners', '', 'text', array()); ?>" title="<?php echo l('link_edit_banner', 'banners', '', 'text', array()); ?>"></a>
		<?php endif; ?>
	<?php endif; ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/banners/delete/<?php echo $this->_vars['banner']['id']; ?>
" onClick="return confirm('<?php echo l('note_delete_banner', 'banners', '', 'js', array()); ?>');"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" alt="<?php echo l('link_delete_banner', 'banners', '', 'text', array()); ?>" title="<?php echo l('link_delete_banner', 'banners', '', 'text', array()); ?>"></a>
	<?php if ($this->_vars['page_data']['view_type'] == "user" && $this->_vars['banner']['approve'] == 0): ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/banners/approve/<?php echo $this->_vars['banner']['id']; ?>
/1"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-approve.png" width="16" height="16" border="0" alt="<?php echo l('link_banner_approve', 'banners', '', 'text', array()); ?>" title="<?php echo l('link_banner_approve', 'banners', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/banners/approve/<?php echo $this->_vars['banner']['id']; ?>
/-1"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-decline.png" width="16" height="16" border="0" alt="<?php echo l('link_banner_decline', 'banners', '', 'text', array()); ?>" title="<?php echo l('link_banner_decline', 'banners', '', 'text', array()); ?>"></a>
	<?php endif; ?>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="8" class="center"><?php echo l('no_banners', 'banners', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<?php echo tpl_function_js(array('file' => 'easyTooltip.min.js'), $this);?>
<script type='text/javascript'>
<?php echo '
$(function(){
	var tt_id;
	$(".tooltip").each(function(){
		tt_id = \'span_\' + $(this).attr(\'id\');
		if($(\'#\' + tt_id + \'>div\').html().trim()) {
			$(this).easyTooltip({
				useElement: tt_id
			});
		};
	});
	$("td.view-banner > a").click(function(){
		$("td.view-banner > .preview").html(\'\');
		var banner_id =  $(this).attr(\'id\').replace(/\\D+/g, \'\');
		$.ajax({
			url: \'';  echo $this->_vars['site_url'];  echo 'admin/banners/preview/\' + banner_id,
			success: function(data){
				$(\'#view_\' + banner_id + \'_content\').html(data).show();
			}
		});
	});
	$(document).click(function(){$("td.view-banner > .preview").html(\'\')});
});
'; ?>

</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
