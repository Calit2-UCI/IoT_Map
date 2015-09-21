<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-01 10:11:51 Pacific Daylight Time */ ?>

<div class="load_content_controller">
	<h1><?php echo l('admin_header_delete_user', 'users', '', 'text', array()); ?></h1>
	<div class="inside">
		
		<form name="delete_user" class="edit-form n100" action="<?php echo $this->_vars['data']['action']; ?>
" method="post" enctype="multipart/form-data"  >
			<div class="row zebra">
				<div class="plr10" id="nickname_list">
				<?php if (isset($this->_sections['s'])) unset($this->_sections['s']);
$this->_sections['s']['loop'] = is_array($this->_vars['data']['user_names']) ? count($this->_vars['data']['user_names']) : max(0, (int)$this->_vars['data']['user_names']);
$this->_sections['s']['name'] = 's';
$this->_sections['s']['show'] = true;
$this->_sections['s']['max'] = $this->_sections['s']['loop'];
$this->_sections['s']['step'] = 1;
$this->_sections['s']['start'] = $this->_sections['s']['step'] > 0 ? 0 : $this->_sections['s']['loop']-1;
if ($this->_sections['s']['show']) {
	$this->_sections['s']['total'] = $this->_sections['s']['loop'];
	if ($this->_sections['s']['total'] == 0)
		$this->_sections['s']['show'] = false;
} else
	$this->_sections['s']['total'] = 0;
if ($this->_sections['s']['show']):

		for ($this->_sections['s']['index'] = $this->_sections['s']['start'], $this->_sections['s']['iteration'] = 1;
			 $this->_sections['s']['iteration'] <= $this->_sections['s']['total'];
			 $this->_sections['s']['index'] += $this->_sections['s']['step'], $this->_sections['s']['iteration']++):
$this->_sections['s']['rownum'] = $this->_sections['s']['iteration'];
$this->_sections['s']['index_prev'] = $this->_sections['s']['index'] - $this->_sections['s']['step'];
$this->_sections['s']['index_next'] = $this->_sections['s']['index'] + $this->_sections['s']['step'];
$this->_sections['s']['first']	  = ($this->_sections['s']['iteration'] == 1);
$this->_sections['s']['last']	   = ($this->_sections['s']['iteration'] == $this->_sections['s']['total']);
?>
					<?php echo $this->_vars['data']['user_names'][$this->_sections['s']['index']];  if (! $this->_sections['s']['last']): ?>,&nbsp;<?php endif; ?>
				<?php endfor; endif; ?>
				</div>
			</div>
			<?php if (is_array($this->_vars['data']['user_ids']) and count((array)$this->_vars['data']['user_ids'])): foreach ((array)$this->_vars['data']['user_ids'] as $this->_vars['item']): ?>
				<input type="hidden" name="user_ids[]" value="<?php echo $this->_vars['item']; ?>
">
			<?php endforeach; endif; ?>
			<?php if (! $this->_vars['data']['deleted']): ?>
			<div class="row">
				<div class="h">
					<input type="radio" name="action_user" value="block_user" id="block_user">
				</div>
				<div class="v"><label><?php echo l('link_deactivate_user', 'users', '', 'text', array()); ?></label></div>
			</div>
			<?php endif; ?>
			<div class="row">
				<div class="h">
					<input type="radio" name="action_user" value="delete_user" id="delete_user">
				</div>
				<div class="v"><label><?php echo l('delete', 'users', '', 'text', array()); ?></label>:</div> 
			</div>
			<div>

				<?php if (is_array($this->_vars['callbacks_data']) and count((array)$this->_vars['callbacks_data'])): foreach ((array)$this->_vars['callbacks_data'] as $this->_vars['key'] => $this->_vars['item']): ?>
				<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
					<div class="row<?php if (!($this->_vars['counter'] % 2)): ?> zebra<?php endif; ?>">
						<div class="h">
							<input type="checkbox" name="module[]" value="<?php echo $this->_vars['item']['callback_gid']; ?>
" <?php echo $this->_vars['item']['disabled_attr']; ?>
>
						</div>
						<div class="v"><label><?php echo $this->_vars['item']['name']; ?>
</label></div>
					</div>
				<?php endforeach; endif; ?>
			</div>
			<div class="btn"><div class="l"><input type="submit" id="lie_delete" name="btn_confirm_del" value="<?php echo l('btn_confirm_del', 'users', '', 'text', array()); ?>" disabled></div></div>
		</form>
	</div>
</div>
<script type="text/javascript">
var user_deleted = '<?php echo $this->_vars['data']['deleted']; ?>
';
<?php echo '
$(function(){
	if(user_deleted == 0){
		$(\'#block_user\').bind(\'click\', function(){
			$(\'input[name^=module]\').prop("disabled", true);
			$(\'#lie_delete\').prop("disabled", false);
		});
		$(\'#delete_user\').bind(\'click\', function(){
			$(\'input[name^=module]\').prop("disabled", false);
			$(\'input[value=users_delete]\').prop(\'checked\',true);
			if($(\'input[name^=module]:checked\').val()){
				$(\'#lie_delete\').prop("disabled", false);
			}else{
				$(\'#lie_delete\').prop("disabled", true);
			}
		});
	}
	$(\'input[name^=module]\').bind(\'click\', function(){
		$(\'#delete_user\').prop(\'checked\', true);
		if($(\'input[name^=module]:checked\').val()){
			$(\'#lie_delete\').prop("disabled", false);
		}else{
			$(\'#lie_delete\').prop("disabled", true);
		}
	});
	var nickname_list  =$(\'#nickname_list\').text();
	var crop_list = nickname_list.substr(0,100)+\' ...\';
	$(\'#nickname_list\').text(crop_list);
	$(\'#nickname_list\').hover(
		function(){$(this).text(nickname_list);},
		function(){$(this).text(crop_list);
	});
});
'; ?>

</script>