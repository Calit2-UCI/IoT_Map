<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-10-17 01:33:29 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_moderation_menu'), $this);?>
<div class="actions">&nbsp;</div>

<div id='link' class='menu-level3'>
	<ul>
		<li id="base_link" <?php if (! $this->_vars['type'] || $this->_vars['type'] == 'add'): ?> class="active"<?php endif; ?>><a href='#' onclick="javascript: openBW('base'); return false;"><?php echo l('header_badwords_base', 'moderation', '', 'text', array()); ?></a></li>
		<li id="check_link"<?php if ($this->_vars['type'] == 'check_text'): ?> class="active"<?php endif; ?>><a href='#' onclick="javascript: openBW('check'); return false;"><?php echo l('header_check_text', 'moderation', '', 'text', array()); ?></a></li>
	</ul>
	<div class="clr"></div>
</div>

<div id="content" class="filter-form">
	<div id="base_content" <?php if ($this->_vars['type'] == 'check_text'): ?>style="display: none"<?php endif; ?>>
		<br>
		<form action="<?php echo $this->_vars['site_url']; ?>
admin/moderation/badwords/add" method="POST">
			<?php echo l('header_add_badword', 'moderation', '', 'text', array()); ?>: <input type="text" value="" name="word" class="middle">
			<div class="btn inline">
				<div class="l">
					<input type="submit" name="submit" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>">
				</div>
			</div>
			<br><i><?php echo l('add_badword_hint', 'moderation', '', 'text', array()); ?></i>
		</form>
		<div>
			<?php if (is_array($this->_vars['badwords']) and count((array)$this->_vars['badwords'])): foreach ((array)$this->_vars['badwords'] as $this->_vars['item']): ?>
			<div class="badw">
				<div><a href="<?php echo $this->_vars['site_url']; ?>
admin/moderation/delete_badword/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_object', 'moderation', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" alt="<?php echo l('delete_word', 'moderation', '', 'text', array()); ?>" title="<?php echo l('delete_word', 'moderation', '', 'text', array()); ?>"></a><?php echo $this->_vars['item']['original']; ?>
</div>
			</div>
			<?php endforeach; endif; ?>
			<div class="clr"></div>
		</div>
		<div class="clr"></div>
		<br><br><br>
	</div>

	<div class="edit-form" id="check_content" <?php if ($this->_vars['type'] != 'check_text'): ?>style="display: none"<?php endif; ?>>
		<br>
		<form action="<?php echo $this->_vars['site_url']; ?>
admin/moderation/badwords/check_text" method="POST">
			<textarea name="text" class="long"><?php echo $this->_vars['check_data']['text']; ?>
</textarea><br>
			<div class="btn">
				<div class="l">
					<input type="submit" name="submit" value="<?php echo l('header_check_text', 'moderation', '', 'button', array()); ?>"><br>
				</div>
			</div>
		</form>
		<br><br><br>
		<?php if ($this->_vars['check_data']['modified']): ?>
		<?php echo l('header_badword_found', 'moderation', '', 'text', array()); ?>: <b><?php echo $this->_vars['check_data']['modified']['count']; ?>
</b>
		<div class="bwresult"><?php echo $this->_vars['check_data']['modified']['text']; ?>
</div>
		<?php endif; ?>
	</div>
</div>

<script><?php echo '
function openBW(type){
	$(\'#link li\').removeClass(\'active\');
	$(\'#\'+type+\'_link\').addClass(\'active\');
	$(\'#content > div\').hide();
	$(\'#\'+type+\'_content\').show();
}
'; ?>
</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
