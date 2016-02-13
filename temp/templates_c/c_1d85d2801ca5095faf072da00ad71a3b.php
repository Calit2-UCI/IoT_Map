<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.selectbox.php');
$this->register_function("selectbox", "tpl_function_selectbox"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-07 03:42:53 Pacific Standard Time */ ?>



	<div style="display:inline-block; margin-left:none; margin-right:none; padding:0; background-color:#ff751a; width:100%;">
		<div style="display: inline-block; width: 160px; position: relative; top: 5pt;">
			<a href="<?php echo $this->_vars['site_url']; ?>
" class="logo-block">
				<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['logo_settings']['path']; ?>
" alt="<?php echo tpl_function_helper(array('func_name' => 'seo_tags_default','func_param' => 'header_text'), $this);?>" width="<?php echo $this->_vars['logo_settings']['width']; ?>
" height="<?php echo $this->_vars['logo_settings']['height']; ?>
">
			</a>
		</div>
		
		
		<div style="display: inline-block; width: 660px; position: relative; top: -36px; border:1px;
		<!--/*
		    background-color: #eaf8fc;
    background-image: linear-gradient(#fff, #d4e8ec);
    border-width: 1px;
    border-style: solid;
    border-color: #c4d9df #a4c3ca #83afb7;            
    padding: 10px;
	*/-->
		">
			<div style="display: inline-block; width: 270px; position: relative; top: 10px; "><!--border: 0.1em solid #1e90ff;-->
			<?php echo tpl_function_selectbox(array('input' => 'user_type','id' => 'looking_user_type','value' => $this->_vars['user_types']['option'],'default' => $this->_vars['all_select_lang']), $this);?>
			</div>
			<input type="text" name="search" placeholder="<?php echo l('search_people', 'start', '', 'text', array()); ?>" style="display: inline-block; width: 270px; height: 25px; padding: 3px 8px; "/>
			<input type="submit" value="Search" id="main_search_button_<?php echo $this->_vars['form_settings']['form_id']; ?>
" name="search_button" style="height: 33px;">
		</div>
		
		
					<ul style="display: inline-block; position: relative; top: -22px; left: 30px; height: 29px;"  align="center">
				<!-- <div style="	height: 25px; width: 110px;
	background-color: #C0DFDE;
	margin: 0px 0px 2px 0px;
	border-radius: 1px;"> -->
				<div style="line-height:30%;">
				<li>
				<a href="<?php echo $this->_vars['site_url']; ?>
users/registration" style="font-size:1px"><button type="button">REGISTER</button></a>
				<!-- <a style="font-size:18px; font-family:Arial; font-weight: normal; text-decoration: none; border-radius: 0px; height: 29px; margin: auto; !--background-color: green; border-color: green;-- position: relative; top: 0px;" href="<?php echo $this->_vars['site_url']; ?>
users/registration" style="">REGISTER</a> -->
				</li>
				<br>
				</br>
				<!-- </div> -->
				<!-- <div style="	height: 26px; width: 80px;
	background-color: #C0DFDE;
	margin: auto;
	border-radius: 1px;"> -->
				<!-- <div> -->
				<li>
				<a href="<?php echo $this->_vars['site_url']; ?>
users/login_form"><button type="button">LOGIN</button></a>
				<!-- <a style="font-size:18px; font-family:Arial; font-weight: normal; text-decoration: none; border-radius: 0px; height: 30px; margin: 0px 0px 0px 0px; !--background-color: green; border-color: green;-- position: relative; top: -2px;" href="<?php echo $this->_vars['site_url']; ?>
users/login_form">LOGIN</a> -->
				</li>
				</div>
			</ul>
	</div>


