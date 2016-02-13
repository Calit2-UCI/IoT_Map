<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.selectbox.php');
$this->register_function("selectbox", "tpl_function_selectbox");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-05 02:13:12 Pacific Standard Time */ ?>


		

		<div style="display: inline-block; position: relative; top: -1px; left: 7pt; border:1px;
		/*
		    background-color: #eaf8fc;
    background-image: linear-gradient(#fff, #d4e8ec);
    border-width: 1px;
    border-style: solid;
    border-color: #c4d9df #a4c3ca #83afb7;            
    padding: 10px;
	*/
		">
			<div style="display: inline-block; width: 222px; position: relative; top: 10px; "><!--border: 0.1em solid #1e90ff;-->
				<?php echo tpl_function_selectbox(array('input' => 'user_type','id' => 'looking_user_type','value' => $this->_vars['user_types']['option'],'default' => $this->_vars['all_select_lang']), $this);?>
			</div>
			<input type="text" name="search" placeholder="<?php echo l('search_people', 'start', '', 'text', array()); ?>" style="display: inline-block; width: 222px; height: 25px; padding: 3px 8px; "/>
			<div style="display: inline-block; width: 400px;">
				<?php echo tpl_function_block(array('name' => 'location_select','module' => 'countries','select_type' => 'city','placeholder' => $this->_vars['location_lang'],'id_country' => $this->_vars['data']['id_country'],'id_region' => $this->_vars['data']['id_region'],'id_city' => $this->_vars['data']['id_city']), $this);?>
			</div>
			<input type="submit" value="Search" id="main_search_button_<?php echo $this->_vars['form_settings']['form_id']; ?>
" name="search_button" style="display: inline-block;">
		</div>

			


