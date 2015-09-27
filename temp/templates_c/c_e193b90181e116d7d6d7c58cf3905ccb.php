<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.lower.php');
$this->register_modifier("lower", "tpl_modifier_lower"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-22 02:32:07 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<?php echo tpl_function_js(array('file' => 'admin-multilevel-sorter.js'), $this);?>

<?php echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_kisses_menu'), $this);?>

<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/kisses/add/" id="btn_add" /><?php echo l('btn_add', 'kisses', '', 'text', array()); ?></a></div></li>
		<li><div class="l"><a id="delete_select_block" href="javascript:void(0)"><?php echo l('btn_link_delete', 'kisses', '', 'text', array()); ?></a></div></li>
		<!--li><div class="l"><a href="#" onclick="javascript: mlSorter.update_sorting(); return false"><?php echo l('link_save_sorter', 'kisses', '', 'text', array()); ?></a></div></li-->
		<!--li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/kisses/index/"><?php echo l('sort_order', 'kisses', '', 'text', array()); ?></a></div></li-->
	</ul>
	&nbsp;
</div>
<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first w50"><input type="checkbox" id="grouping_all"></th>
		<th class=""><?php echo l('images', 'kisses', '', 'text', array()); ?></th>
		<!--th>
			<a href="<?php echo $this->_vars['sort_links']['date_created']; ?>
"<?php if ($this->_vars['order'] == 'date_created'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>>
				<?php echo l('field_date_created', 'kisses', '', 'text', array()); ?>
			</a>
		</th-->
		<th class="w30">&nbsp;</th>
	</tr>
</table>

<div id="pages">
	
	<ul name="parent_0" class="sort connected" id="clsr0ul">
		<?php if (is_array($this->_vars['kisses']) and count((array)$this->_vars['kisses'])): foreach ((array)$this->_vars['kisses'] as $this->_vars['kiss']): ?>
		<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
			<li id="item_<?php echo $this->_vars['kiss']['id']; ?>
">
				<div><input type="checkbox" class="grouping" value="<?php echo $this->_vars['kiss']['id']; ?>
" name="ids[]" id="kisses-<?php echo $this->_vars['kiss']['id']; ?>
"></div>
				<div><img src="<?php echo $this->_vars['file_url'];  echo $this->_run_modifier($this->_vars['kiss']['image'], 'escape', 'plugin', 1); ?>
" alt="<?php echo $this->_vars['kiss']['id']; ?>
"></div>
				<!--div><?php echo $this->_vars['kiss']['date_created']; ?>
</div-->
				<div class="icons">
					<!--a href="<?php echo $this->_vars['site_url']; ?>
admin/kisses/edit/<?php echo $this->_vars['kiss']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_kisses', 'kisses', '', 'text', array()); ?>" title="<?php echo l('link_edit_kisses', 'kisses', '', 'text', array()); ?>"></a-->
					<a class="delete_select_file" data-id="<?php echo $this->_vars['kiss']['id']; ?>
" href="javascript:void(0)"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_kisses', 'kisses', '', 'text', array()); ?>" title="<?php echo l('link_delete_kisses', 'kisses', '', 'text', array()); ?>"></a>
				</div>
			</li>
		<?php endforeach; else: ?>
			<li><?php echo l('no_kisses', 'kisses', '', 'text', array()); ?></li>
		<?php endif; ?>
	</ul>
	
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<script type="text/javascript">

var reload_link = "<?php echo $this->_vars['site_url']; ?>
admin/kisses/index/";
var order = '<?php echo $this->_vars['order']; ?>
';
var order_direction = '<?php echo $this->_vars['order_direction']; ?>
';
var mlSorter;

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
		$(\'#delete_selected\').bind(\'click\', function(){
			if(!$(\'input[type=checkbox].grouping\').is(\':checked\')) return false; 
			if(!confirm(\'';  echo l('note_alerts_delete_all', 'kisses', '', 'js', array());  echo '\')) return false;
			$(\'#kisses_form\').attr(\'action\', $(this).attr(\'href\')).submit();		
			return false;
		});
		mlSorter = new multilevelSorter({
			siteUrl: \'';  echo $this->_vars['site_url'];  echo '\', 
			itemsBlockID: \'pages\',
			urlSaveSort: \'admin/kisses/ajax_save_sorter\',
			onActionUpdate: true,
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
		var id_kisses = $(this).attr(\'data-id\');
		var data = new Array();
		
		var checked = $(\'input#kisses-\'+id_kisses).is(\':checked\');
		if(checked){
			$(\'input#kisses-\'+id_kisses).prop(\'checked\', false);
			$(\'input#kisses-\'+id_kisses).prop(\'checked\', true);
		}else{
			$(\'input#kisses-\'+id_kisses).prop(\'checked\', true);
		}
			
			data[0] = id_kisses;
			
			if(data.length > 0){
				$.ajax({
					url: site_url + \'admin/kisses/ajax_confirm_delete_select\',
					cache: false,
					success: function(data){
						delete_select_block.show_load_block(data);
					}
				});
			}else{
				error_object.show_error_block(\'';  echo l("no_select_kisses", "kisses", '', "js", array());  echo '\', \'error\');
			}
		
	});
	
	$(\'#delete_select_block\').unbind(\'click\').click(function(){
		var data = new Array();
		
		$(\'.grouping:checked\').each(function(i){
			data[i] = $(this).val();
		});
		if(data.length > 0){
			$.ajax({
				url: site_url + \'admin/kisses/ajax_confirm_delete_select\',
				cache: false,
				success: function(data){
					delete_select_block.show_load_block(data);
				}
			});
		}else{
			error_object.show_error_block(\'';  echo l("no_select_kisses", "kisses", '', "js", array());  echo '\', \'error\');
		}
	});
	
function reload_this_page(value){
	var link = reload_link + \'/\' + order + \'/\' + order_direction;
	location.href=link;
}
'; ?>
</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

