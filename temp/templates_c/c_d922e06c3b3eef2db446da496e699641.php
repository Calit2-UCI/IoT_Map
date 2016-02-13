<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-08 11:12:51 Pacific Standard Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start();  
$this->assign('default_select_lang', l('select_default', 'start', '', 'text', array()));
  
$this->assign('all_select_lang', l('filter_all', 'users', '', 'text', array()));
  
$this->assign('location_lang', l('field_search_country', 'users', '', 'text', array()));
 ?>
<form action="<?php echo $this->_vars['form_settings']['action']; ?>
" method="POST" id="main_search_form_<?php echo $this->_vars['form_settings']['form_id']; ?>
">
	<div >
		<?php if ($this->_vars['form_settings']['type'] == 'line'): ?>         <!--The one on the right top of the user/search page-->
			
			
			<!--JL commented for no need to have the search bar on the header-->
			<!--div class="search-form <?php echo $this->_vars['form_settings']['type']; ?>
">  
			<div class="inside">
				<div id="line-search-form_<?php echo $this->_vars['form_settings']['form_id']; ?>
">
					<input type="text" name="search" placeholder="<?php echo l('search_people', 'start', '', 'text', array()); ?>" />
					<button type="submit" id="main_search_button_<?php echo $this->_vars['form_settings']['form_id']; ?>
" class="search"><i class="fa-search w"></i></button>
				</div>
			</div>
			</div-->
			
		<?php elseif ($this->_vars['form_settings']['type'] == 'index'): ?>    <!--The one on the homepage-->
			<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('', '"users"'). "homepage_header.html", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>


			
		<?php else: ?>									   <!--The one on the middle of the user/search page-->
			<div class="search-form <?php echo $this->_vars['form_settings']['type']; ?>
">
			<div>
				<div class="fields-block aligned-fields">
					<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('', '"users"'). "search.html", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
				
				
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
			</div>
		<?php endif; ?>
	</div>
</form>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>

