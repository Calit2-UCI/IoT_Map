<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-22 02:32:21 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<table id="chatsList" cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first"><?php echo l('name', 'chats', '', 'text', array()); ?></th>
		<th class="w100"><?php echo l('status', 'chats', '', 'text', array()); ?></th>
		<th class="w100"><?php echo l('activity', 'chats', '', 'text', array()); ?></th>
	</tr>
	<?php if (is_array($this->_vars['chats']) and count((array)$this->_vars['chats'])): foreach ((array)$this->_vars['chats'] as $this->_vars['item']): ?>
		<tr>
			<td class="first">
				<?php if ($this->_vars['item']['installed']): ?>
					<a href="<?php echo $this->_vars['site_url']; ?>
admin/chats/settings/<?php echo $this->_vars['item']['gid']; ?>
">
						<?php echo $this->_vars['item']['name']; ?>

					</a>
				<?php else: ?>
					<?php echo $this->_vars['item']['name']; ?>

				<?php endif; ?>
			</td>
			<td>
				<?php if ($this->_vars['item']['installed']): ?>
					<a href="<?php echo $this->_vars['site_url']; ?>
admin/chats/installation/<?php echo $this->_vars['item']['gid']; ?>
">
						<?php echo l('installed', 'chats', '', 'text', array()); ?>
					</a>
				<?php elseif ($this->_vars['item']['has_files']): ?>
					<a href="<?php echo $this->_vars['site_url']; ?>
admin/chats/installation/<?php echo $this->_vars['item']['gid']; ?>
">
						<?php echo l('install', 'chats', '', 'text', array()); ?>
					</a>
				<?php elseif ($this->_vars['item']['vendor_url']): ?>
					<a href="<?php echo $this->_vars['item']['vendor_url']; ?>
">
						<?php echo l('get_files', 'chats', '', 'text', array()); ?>
					</a>
				<?php else: ?>
					<?php echo l('no_files', 'chats', '', 'text', array()); ?>
				<?php endif; ?>
			</td>
			<td class="center">
				<?php if ($this->_vars['item']['installed']): ?>
					<?php if ($this->_vars['item']['active']): ?>
						<a data-id="<?php echo $this->_vars['item']['id']; ?>
" href="<?php echo $this->_vars['site_url']; ?>
admin/chats/deactivate/<?php echo $this->_vars['item']['gid']; ?>
"
						   class="deactivate" href="javascript:void(0);">
							<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0">
						</a>
					<?php else: ?>
						<a data-id="<?php echo $this->_vars['item']['id']; ?>
" href="<?php echo $this->_vars['site_url']; ?>
admin/chats/activate/<?php echo $this->_vars['item']['gid']; ?>
"
						   class="activate" href="javascript:void(0);">
							<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0">
						</a>
					<?php endif; ?>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; else: ?>
		<tr><td colspan="7" class="center"><?php echo l('no_chats', 'chats', '', 'text', array()); ?></td></tr>
		<?php endif; ?>
</table>
<?php echo tpl_function_js(array('file' => 'admin-chats.js','module' => 'chats'), $this); echo '
	<script type="text/javascript">
		var chats;
		$(function() {
			chats = new adminChats({
				siteUrl: \'';  echo $this->_vars['site_url'];  echo '\'
			});
		});
	</script>
'; ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
