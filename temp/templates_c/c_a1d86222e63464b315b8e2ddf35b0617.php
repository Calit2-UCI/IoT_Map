<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-09 00:47:56 Pacific Standard Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<?php echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_payments_menu'), $this);?>

<div class="actions">
	<ul>
		<li>
			<div class="l">
				<a href="<?php echo $this->_vars['site_root']; ?>
admin/memberships/create"><?php echo l('btn_add', 'start', '', 'text', array()); ?></a>
			</div>
		</li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data memberships">
	<tr>
		<th class="first"><?php echo l('field_name', 'memberships', '', 'text', array()); ?></th>
		<th class="w100"><?php echo l('field_price', 'memberships', '', 'text', array()); ?></th>
		<th class="w100"><?php echo l('field_status', 'memberships', '', 'text', array()); ?></th>
		<th class="w100">&nbsp;</th>
	</tr>
	<?php if (is_array($this->_vars['memberships']) and count((array)$this->_vars['memberships'])): foreach ((array)$this->_vars['memberships'] as $this->_vars['item']): ?>
	<tr>
		<td class="first center"><?php echo $this->_vars['item']['name']; ?>
</td>
		<td class="center"><?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start','value' => $this->_vars['item']['price']), $this);?></td>
		<td class="center">
			<?php if ($this->_vars['item']['is_active']): ?>
			<a class="btn-activity" data-activity="true" data-id="<?php echo $this->_vars['item']['id']; ?>
" 
			   href="<?php echo $this->_vars['site_url']; ?>
admin/memberships/deactivate/<?php echo $this->_vars['item']['id']; ?>
"
			   title="<?php echo l('link_item_deactivate', 'memberships', '', 'button', array()); ?>">
				<i class="fa fa-circle"></i>
			</a>
			<?php else: ?>
			<a class="btn-activity" data-activity="false" data-id="<?php echo $this->_vars['item']['id']; ?>
" 
			   href="<?php echo $this->_vars['site_url']; ?>
admin/memberships/activate/<?php echo $this->_vars['item']['id']; ?>
"
			   title="<?php echo l('link_item_activate', 'memberships', '', 'button', array()); ?>">
				<i class="inactive fa fa-circle"></i>
			</a>
			<?php endif; ?>
		</td>
		<td class="icons">
			<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
			<a class="btn-edit" data-id="<?php echo $this->_vars['item']['id']; ?>
" href="<?php echo $this->_vars['site_url']; ?>
admin/memberships/edit/<?php echo $this->_vars['item']['id']; ?>
">
				<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" 
					 alt="<?php echo l('link_item_edit', 'memberships', '', 'button', array()); ?>" 
					 title="<?php echo l('link_item_edit', 'memberships', '', 'button', array()); ?>">
			</a>
			<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
			<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
			<a class="btn-delete" data-id="<?php echo $this->_vars['item']['id']; ?>
" href="<?php echo $this->_vars['site_url']; ?>
admin/memberships/delete/<?php echo $this->_vars['item']['id']; ?>
">
				<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16"
					 alt="<?php echo l('link_item_delete', 'memberships', '', 'button', array()); ?>" 
					 title="<?php echo l('link_item_delete', 'memberships', '', 'text', array()); ?>">
			</a>
			<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
		</td>
	</tr>
	<?php endforeach; else: ?>
	<tr>
		<td colspan="4" class="center">
			<?php echo l('no_items', 'memberships', '', 'text', array()); ?>
		</td>
	</tr>
	<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_js(array('file' => 'memberships-admin.js','module' => 'memberships'), $this);?>
<script><?php echo '
	$(function(){
		new membershipsAdmin({
			siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
			msgConfirmDeletion: \'';  echo l('confirm_delete_membership', 'memberships', '', 'text', array());  echo '\'
		});
	});
'; ?>
</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
