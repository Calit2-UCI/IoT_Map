<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 00:02:15 Pacific Daylight Time */ ?>

<form class="poll_form" name="poll_form" action="" method="POST">
	<input name="poll_id" type="hidden" value="<?php echo $this->_vars['poll_data']['id']; ?>
" />
	<div class="question_<?php echo $this->_vars['poll_data']['id']; ?>
">
		<?php if (is_array($this->_vars['poll_data']['answers_colors']) and count((array)$this->_vars['poll_data']['answers_colors'])): foreach ((array)$this->_vars['poll_data']['answers_colors'] as $this->_vars['key'] => $this->_vars['item']): ?>
			<div>
				<?php if ($this->_vars['poll_data']['answer_type']): ?>
					<input id="a_<?php echo $this->_vars['poll_data']['id']; ?>
_<?php echo $this->_vars['key']; ?>
" class="answer answer_<?php echo $this->_vars['poll_data']['id']; ?>
_<?php echo $this->_vars['key']; ?>
" type="checkbox" value="<?php echo $this->_vars['key']; ?>
" name="answer[<?php echo $this->_vars['key']; ?>
]">
				<?php else: ?>
					<input id="a_<?php echo $this->_vars['poll_data']['id']; ?>
_<?php echo $this->_vars['key']; ?>
" class="answer answer_<?php echo $this->_vars['poll_data']['id']; ?>
_<?php echo $this->_vars['key']; ?>
" type="radio" value="<?php echo $this->_vars['key']; ?>
" name="answer">
				<?php endif; ?>
				<?php if ($this->_vars['language']): ?>
					<?php $this->assign('language_item', $this->_vars['key'].'_'.$this->_vars['language']); ?>
				<?php else: ?>
					<?php $this->assign('language_item', $this->_vars['key'].'_'.$this->_vars['cur_lang']); ?>
				<?php endif; ?>
				<?php if (! $this->_vars['poll_data']['answers_languages'][$this->_vars['language_item']]): ?> 
					<?php $this->assign('language_item', $this->_vars['key'].'_default'); ?>
				<?php endif; ?>
				<label for="a_<?php echo $this->_vars['poll_data']['id']; ?>
_<?php echo $this->_vars['key']; ?>
"><?php echo $this->_vars['poll_data']['answers_languages'][$this->_vars['language_item']]; ?>
</label>
			</div>
		<?php endforeach; endif; ?>
		<?php if ($this->_vars['poll_data']['use_comments']): ?>
			<br />
			<div class="r">
				<div class="f"><?php echo l('add_comment', 'polls', '', 'text', array()); ?></div>
				<div class="v"><input type="text" name="poll_comment" value=""></div>
			</div>
		<?php endif; ?>
	</div>
	<div class="poll_inputs">
		<input class="respond" type="button" value="<?php echo l('respond', 'polls', '', 'text', array()); ?>" name="respond">
		<?php if (! $this->_vars['one_poll_place'] && 1 < $this->_vars['polls_count']): ?>
			<div class="poll_action">
				<a class="poll_link next_poll" href="javascript:void(0);"><?php echo l('next_poll', 'polls', '', 'text', array()); ?></a>
			</div>
		<?php endif; ?>
	</div>
</form>