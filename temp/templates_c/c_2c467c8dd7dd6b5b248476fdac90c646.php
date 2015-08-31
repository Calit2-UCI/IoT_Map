<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.ld_option.php');
$this->register_function("ld_option", "tpl_function_ld_option"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:15:39 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="actions">
	<ul>
		<li><div class="l"><a id="mark_adult_select_block" href="" onclick="return false;"><?php echo l('btn_mark_adult', 'media', '', 'text', array()); ?></a></div></li>
		<li><div class="l"><a id="unmark_adult_select_block" href="javascript:void(0)"><?php echo l('btn_unmark_adult', 'media', '', 'text', array()); ?></a></div></li>
		<li><div class="l"><a id="delete_select_block" href="javascript:void(0)"><?php echo l('btn_link_delete', 'media', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>

<?php echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'media_menu_item'), $this); $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first w50"><input type="checkbox" id="grouping_all"></th>
		<th class="w110"><?php echo l('field_files', 'media', '', 'text', array()); ?></th>
		<th><?php echo l('media_info', 'media', '', 'text', array()); ?></th>
		<th><?php echo l('media_owner', 'media', '', 'text', array()); ?></th>
		<th class="w100">&nbsp;</th>
	</tr>
	<?php if (is_array($this->_vars['media']) and count((array)$this->_vars['media'])): foreach ((array)$this->_vars['media'] as $this->_vars['item']): ?>
	<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
	<tr class="<?php if (!($this->_vars['counter'] % 2)): ?>zebra<?php endif;  if ($this->_vars['item']['is_adult']): ?> adult<?php endif; ?>">
		<td class="first w20 center"><input type="checkbox" class="grouping" value="<?php echo $this->_vars['item']['id']; ?>
" id="media-<?php echo $this->_vars['item']['id']; ?>
"></td>
		<td>
			<?php if ($this->_vars['item']['upload_gid'] == 'gallery_audio'): ?>
				 <audio src="<?php echo $this->_vars['item']['media']['mediafile']['file_url']; ?>
" controls></audio>
			<?php elseif ($this->_vars['item']['media']): ?>
				<a href="<?php echo $this->_vars['item']['media']['mediafile']['file_url']; ?>
" target="_blank"><img src="<?php echo $this->_vars['item']['media']['mediafile']['thumbs']['small']; ?>
"/></a>
			<?php endif; ?>

			<?php if ($this->_vars['item']['video_content']): ?>
				<span onclick="vpreview = new loadingContent(<?php echo '{\'closeBtnClass\': \'w\'}'; ?>
); vpreview.show_load_block('<?php echo $this->_run_modifier($this->_vars['item']['video_content']['embed'], 'escape', 'plugin', 1); ?>
');"><img class="pointer" src="<?php echo $this->_vars['item']['video_content']['thumbs']['small']; ?>
"/></span>
				
			<?php endif; ?>
		</td>
		<td>
			<b><?php echo l('media_user', 'media', '', 'text', array()); ?></b>: <?php echo $this->_vars['item']['user_info']['output_name']; ?>
<br>
			<b><?php echo l('field_permitted_for', 'media', '', 'text', array()); ?></b>: <?php echo tpl_function_ld_option(array('i' => 'permissions','gid' => 'media','option' => $this->_vars['item']['permissions']), $this);?>
		</td>
		<td>
                    <?php if (empty ( $this->_vars['item']['owner_info']['is_user_deleted'] )): ?><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/edit/personal/<?php echo $this->_vars['item']['id_owner']; ?>
" target="_blank"><?php echo $this->_vars['item']['owner_info']['output_name']; ?>
</a><?php else:  echo $this->_vars['item']['owner_info']['output_name'];  endif; ?>
		</td>
		<td class="icons">
			<?php if ($this->_vars['item']['is_adult'] == 0): ?>
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/media/mark_adult_media/<?php echo $this->_vars['item']['id']; ?>
" class="adult_icon green"><div title="<?php echo l('mark_adult', 'media', '', 'text', array()); ?>">18+</div></a>
			<?php else: ?>
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/media/unmark_adult_media/<?php echo $this->_vars['item']['id']; ?>
" class="adult_icon red"><div title="<?php echo l('unmark_adult', 'media', '', 'text', array()); ?>">18+</div></a>
			<?php endif; ?>
			
			<a class="delete_select_file" data-id="<?php echo $this->_vars['item']['id']; ?>
" href="javascript:void(0)"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_service', 'packages', '', 'text', array()); ?>" title="<?php echo l('link_delete_service', 'packages', '', 'text', array()); ?>"></a>
		</td>
	</tr>
	<?php endforeach; else: ?>
	<tr><td colspan="4" class="center"><?php echo l('no_media', 'media', '', 'text', array()); ?></td></tr>
	<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script type="text/javascript">
var reload_link = "<?php echo $this->_vars['site_url']; ?>
admin/media/";
var param = "<?php echo $this->_vars['param']; ?>
";

	<?php echo '
		$(function(){
			$(\'#grouping_all\').bind(\'click\', function(){
				var checked = $(this).is(\':checked\');
				if(checked){
					$(\'input.grouping\').prop(\'checked\', true);
				}else{
					$(\'input.grouping\').prop(\'checked\', false);
				}
			});
			
			$(\'#grouping_all\').bind(\'click\', function(){
				var checked = $(this).is(\':checked\');
				if(checked){
					$(\'input[type=checkbox].grouping\').prop(\'checked\', true);
				}else{
					$(\'input[type=checkbox].grouping\').prop(\'checked\', false);
				}
			});
		});
		
		delete_select_block = new loadingContent({
		loadBlockWidth: \'620px\',
		loadBlockLeftType: \'center\',
		loadBlockTopType: \'center\',
		loadBlockTopPoint: 100,
		closeBtnClass: \'w\'
		}).update_css_styles({\'z-index\': 2000}).update_css_styles({\'z-index\': 2000}, \'bg\');
		
		$(\'.delete_select_file\').unbind(\'click\').click(function(){
			var id_media = $(this).attr(\'data-id\');
			var data = new Array();
			
			var checked = $(\'input#media-\'+id_media).is(\':checked\');
			if(checked){
				$(\'input#media-\'+id_media).prop(\'checked\', false);
				$(\'input#media-\'+id_media).prop(\'checked\', true);
			}else{
				$(\'input#media-\'+id_media).prop(\'checked\', true);
			}
			
			data[0] = id_media;
			
			if(data.length > 0){
				$.ajax({
					url: site_url + \'admin/media/ajax_confirm_select/delete_select_block\',
					cache: false,
					success: function(data){
						delete_select_block.show_load_block(data);
					}
				});
			}else{
				error_object.show_error_block(\'';  echo l("no_media", "media", '', "js", array());  echo '\', \'error\');
			}
			
		});
		
		$(\'#delete_select_block\').unbind(\'click\').click(function(){
			var data = new Array();
			
			$(\'.grouping:checked\').each(function(i){
				data[i] = $(this).val();
			});
			if(data.length > 0){
				$.ajax({
					url: site_url + \'admin/media/ajax_confirm_select/delete_select_block\',
					cache: false,
					success: function(data){
						delete_select_block.show_load_block(data);
					}
				});
			}else{
				error_object.show_error_block(\'';  echo l("no_media", "media", '', "js", array());  echo '\', \'error\');
			}
		});
		
		$(\'#mark_adult_select_block\').unbind(\'click\').click(function(){
			var data = new Array();
			
			$(\'.grouping:checked\').each(function(i){
				data[i] = $(this).val();
			});
			if(data.length > 0){
				$.ajax({
					url: site_url + \'admin/media/ajax_mark_adult_select\',
					cache: false,
					type: "POST",
					data: {file_ids : data},
					success: function(data){
						reload_this_page(\'index/\'+param);
					}
				});
			}else{
				error_object.show_error_block(\'';  echo l("no_media", "media", '', "js", array());  echo '\', \'error\');
			}
		});
		
		$(\'#unmark_adult_select_block\').unbind(\'click\').click(function(){
			var data = new Array();
			
			$(\'.grouping:checked\').each(function(i){
				data[i] = $(this).val();
			});
			if(data.length > 0){
				$.ajax({
					url: site_url + \'admin/media/ajax_unmark_adult_select\',
					cache: false,
					type: "POST",
					data: {file_ids : data},
					success: function(data){
						reload_this_page(\'index/\'+param);
					}
				});
			}else{
				error_object.show_error_block(\'';  echo l("no_media", "media", '', "js", array());  echo '\', \'error\');
			}
		});
		
		function reload_this_page(value){
			var link = reload_link + value;
			location.href=link;
		}
	'; ?>

</script>

<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
