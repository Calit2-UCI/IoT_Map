<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.selectbox.php');
$this->register_function("selectbox", "tpl_function_selectbox"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.hlbox.php');
$this->register_function("hlbox", "tpl_function_hlbox"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:01:51 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start();  
$this->assign('default_select_lang', l('select_default', 'start', '', 'text', array()));
  
$this->assign('all_select_lang', l('filter_all', 'users', '', 'text', array()));
  
$this->assign('location_lang', l('field_search_country', 'users', '', 'text', array()));
 ?>
<form action="<?php echo $this->_vars['form_settings']['action']; ?>
" method="POST" id="main_search_form_<?php echo $this->_vars['form_settings']['form_id']; ?>
">
	<div class="search-form <?php echo $this->_vars['form_settings']['type']; ?>
">
		<?php if ($this->_vars['form_settings']['type'] == 'line'): ?>
			<div class="inside">
				<div id="line-search-form_<?php echo $this->_vars['form_settings']['form_id']; ?>
">
					<input type="text" name="search" placeholder="<?php echo l('search_people', 'start', '', 'text', array()); ?>" />
					<button type="submit" id="main_search_button_<?php echo $this->_vars['form_settings']['form_id']; ?>
" class="search"><i class="fa-search w"></i></button>
				</div>
			</div>
		<?php elseif ($this->_vars['form_settings']['type'] == 'index'): ?>
			<div class="fields-block aligned-fields">
				<div id="short-search-form_<?php echo $this->_vars['form_settings']['form_id']; ?>
">
					<div>
						<?php echo tpl_function_hlbox(array('input' => 'user_type','id' => 'looking_user_type','value' => $this->_vars['user_types']['option'],'multiselect' => true,'selected' => $this->_vars['data']['user_type']), $this);?>
					</div>
					<div class="table">
						<div class="search-fields">
							<div class="search-field age">
								<span class="inline vmiddle"><?php echo l('field_age', 'users', '', 'text', array()); ?>&nbsp;</span><div class="ib vmiddle"><?php echo tpl_function_selectbox(array('input' => 'age_min','id' => 'age_min','value' => $this->_vars['age_range'],'selected' => $this->_vars['data']['age_min']), $this);?></div>
								&nbsp;-&nbsp;<div class="ib vmiddle"><?php echo tpl_function_selectbox(array('input' => 'age_max','id' => 'age_max','value' => $this->_vars['age_range'],'selected' => $this->_vars['data']['age_max']), $this);?></div>
							</div>
							<div class="search-field country">
								<?php echo tpl_function_block(array('name' => 'location_select','module' => 'countries','select_type' => 'city','placeholder' => $this->_vars['location_lang'],'id_country' => $this->_vars['data']['id_country'],'id_region' => $this->_vars['data']['id_region'],'id_city' => $this->_vars['data']['id_city']), $this);?>
							</div>
							<div class="search-field search-btn righted">
								<button type="submit" id="main_search_button_<?php echo $this->_vars['form_settings']['form_id']; ?>
" name="search_button"><?php echo l('btn_search', 'start', '', 'button', array()); ?></button>
							</div>
						</div>
					</div>
				</div>
				<div class="clr"></div>
			</div>
		<?php else: ?>
			<div class="inside">
				<div class="btn-block">
					<div class="search-btn">
						<?php if ($this->_vars['form_settings']['use_advanced']): ?>
							<span class="collapse-links">
								<a href="#" class="hide btn-link" id="more-options-link_<?php echo $this->_vars['form_settings']['form_id']; ?>
"><i><?php echo l('link_more_options', 'start', '', 'text', array()); ?></i><i class="fa-caret-down icon-big text-icon"></i></a>
								<a href="#" class="hide btn-link" id="less-options-link_<?php echo $this->_vars['form_settings']['form_id']; ?>
"><i><?php echo l('link_less_options', 'start', '', 'text', array()); ?></i><i class="fa-caret-up icon-big text-icon"></i></a>
							</span>
							&nbsp;&nbsp;&nbsp;
						<?php endif; ?>
						<button type="submit" id="main_search_button_<?php echo $this->_vars['form_settings']['form_id']; ?>
" name="search_button">
							<?php if ($this->_vars['form_settings']['object'] == 'perfect_match'):  echo l('btn_refresh', 'start', '', 'button', array());  else:  echo l('btn_search', 'start', '', 'button', array());  endif; ?>
						</button>
					</div>
				</div>
				<div class="fields-block aligned-fields">
					<div id="short-search-form_<?php echo $this->_vars['form_settings']['form_id']; ?>
">
						<div class="search-field">
							<?php echo tpl_function_selectbox(array('input' => 'user_type','id' => 'looking_user_type','value' => $this->_vars['user_types']['option'],'selected' => $this->_vars['data']['user_type'],'default' => $this->_vars['all_select_lang']), $this);?>
						</div>
						<div class="search-field age">
							<div>
								<span class="inline vmiddle"><?php echo l('field_age', 'users', '', 'text', array()); ?>&nbsp;<?php echo l('from', 'users', '', 'text', array()); ?>&nbsp;</span><div class="ib vmiddle"><?php echo tpl_function_selectbox(array('input' => 'age_min','id' => 'age_min','value' => $this->_vars['age_range'],'selected' => $this->_vars['data']['age_min']), $this);?></div>
								<span class="inline vmiddle">&nbsp;<?php echo l('to', 'users', '', 'text', array()); ?>&nbsp;</span><div class="ib vmiddle"><?php echo tpl_function_selectbox(array('input' => 'age_max','id' => 'age_max','value' => $this->_vars['age_range'],'selected' => $this->_vars['data']['age_max']), $this);?></div>
							</div>
						</div>
						<div class="search-field country">
							<?php echo tpl_function_block(array('name' => 'location_select','module' => 'countries','select_type' => 'city','placeholder' => $this->_vars['location_lang'],'id_country' => $this->_vars['data']['id_country'],'id_region' => $this->_vars['data']['id_region'],'id_city' => $this->_vars['data']['id_city']), $this);?>
						</div>
					</div>
					<div class="clr"></div>
					<div id="full-search-form_<?php echo $this->_vars['form_settings']['form_id']; ?>
" <?php if ($this->_vars['form_settings']['type'] == 'short'): ?>class="hide"<?php endif; ?>>
						<?php if ($this->_vars['form_settings']['use_advanced']): ?>
							<div class="clr"></div>
							<?php if (is_array($this->_vars['advanced_form']) and count((array)$this->_vars['advanced_form'])): foreach ((array)$this->_vars['advanced_form'] as $this->_vars['item']): ?>
								<?php if ($this->_vars['item']['type'] == 'section'): ?>
									<?php if (is_array($this->_vars['item']['section']['fields']) and count((array)$this->_vars['item']['section']['fields'])): foreach ((array)$this->_vars['item']['section']['fields'] as $this->_vars['key'] => $this->_vars['field']): ?>
										<div class="search-field custom <?php echo $this->_vars['field']['field']['type']; ?>
 <?php echo $this->_vars['field']['settings']['search_type']; ?>
">
											<p><?php echo $this->_vars['field']['field_content']['name']; ?>
</p>
											<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('', '"users"'). "helper_search_field_block.tpl", array('field' => $this->_vars['field'],'field_name' => $this->_vars['field']['field_content']['field_name'],'load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
										</div>
									<?php endforeach; endif; ?>
								<?php else: ?>
									<div class="search-field custom <?php echo $this->_vars['item']['field']['type']; ?>
 <?php echo $this->_vars['item']['settings']['search_type']; ?>
">
										<p><?php echo $this->_vars['item']['field_content']['name']; ?>
</p>
										<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('', '"users"'). "helper_search_field_block.tpl", array('field' => $this->_vars['item'],'field_name' => $this->_vars['item']['field_content']['field_name'],'load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
									</div>
								<?php endif; ?>
							<?php endforeach; endif; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</form>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
