<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.truncate.php');
$this->register_modifier("truncate", "tpl_modifier_truncate"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.pagination.php');
$this->register_function("pagination", "tpl_function_pagination"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-08 06:13:27 Pacific Standard Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "mailbox". $this->module_templates.  $this->get_current_theme_gid('"default"', '"mailbox"'). "mailbox_menu.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "mailbox". $this->module_templates.  $this->get_current_theme_gid('user', '"mailbox"'). "mailbox_top_panel.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<?php if ($this->_vars['messages']): ?>
	<div class="sorter short-line" id="sorter_block">
		<div class="fright"><?php echo tpl_function_pagination(array('data' => $this->_vars['page_data'],'type' => 'cute'), $this);?></div>
	</div>
<?php endif; ?>
<div class="mailbox-list table-div wp100 list">
	<?php if (is_array($this->_vars['messages']) and count((array)$this->_vars['messages'])): foreach ((array)$this->_vars['messages'] as $this->_vars['item']): ?>
		<dl class="pointer<?php if (( $this->_vars['folder'] == 'inbox' || $this->_vars['folder'] == 'spam' ) && $this->_vars['item']['is_new']): ?> bold<?php endif; ?> btn_<?php if ($this->_vars['folder'] == 'drafts'): ?>edit<?php else: ?>read<?php endif; ?>_message" data-id="<?php echo $this->_vars['item']['id']; ?>
">
			<dt class="w30"><input type="checkbox" name="select_message[<?php echo $this->_vars['item']['id']; ?>
]" data-role="msg-checkbox" data-id-msg="<?php echo $this->_vars['item']['id']; ?>
" value="<?php echo $this->_vars['item']['id']; ?>
" /></dt>
			<dt class="text-overflow w150 user"><?php if ($this->_vars['folder'] == 'inbox' || $this->_vars['folder'] == 'spam'):  echo $this->_run_modifier($this->_vars['item']['sender']['output_name'], 'truncate', 'plugin', 1, 150);  elseif (! $this->_vars['item']['recipient']):  echo l('no_user', 'mailbox', '', 'text', array());  else:  echo $this->_run_modifier($this->_vars['item']['recipient']['output_name'], 'truncate', 'plugin', 1, 150);  endif; ?></dt>
			<dt class="icons">
				<?php if ($this->_vars['item']['attaches_count']): ?><i class="icon-medium icon-paperclip<?php if (! $this->_vars['item']['is_new']): ?> g<?php endif; ?>"></i>&nbsp;<?php endif; ?>
				<?php if ($this->_vars['folder'] == 'inbox' || $this->_vars['folder'] == 'spam'): ?><i class="icon-medium <?php if ($this->_vars['item']['is_new']): ?>icon-envelope<?php else: ?>icon-envelope-alt g<?php endif; ?>"></i><?php endif; ?>
			</dt>
			<dt><div class="text-overflow user"><?php if ($this->_vars['item']['subject']):  echo $this->_run_modifier($this->_vars['item']['subject'], 'truncate', 'plugin', 1, 100);  else:  echo l('text_default_subject', 'mailbox', '', 'text', array());  endif; ?></div></dt>
			<dt class="righted w200"><?php echo $this->_run_modifier($this->_vars['item']['date_add'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_time_format']); ?>
</dt>
		</dl>
	<?php endforeach; else: ?>
		<div class="line top empty center"><?php echo l('no_messages', 'mailbox', '', 'text', array()); ?></div>
	<?php endif; ?>
</div>

<?php if ($this->_vars['messages']): ?><div><?php echo tpl_function_pagination(array('data' => $this->_vars['page_data'],'type' => 'full'), $this);?></div><?php endif; ?>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>

