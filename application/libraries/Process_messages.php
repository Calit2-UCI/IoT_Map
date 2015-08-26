<?php
/**
* Print system messages while script in processing
* 
* @package PG_Core
* @subpackage application
* @category	libraries
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Irina Lebedeva <irina@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2009-12-02 15:07:07 +0300 (Ср, 02 дек 2009) $ $Author: irina $
**/

class Process_messages extends System_messages {
	function Process_messages()
	{
		ob_implicit_flush();
		parent::System_messages();
	}
	
	function add_message($target_id, $reference_id, $replace_params = array(), $gid = 'message', $set_flashdata = false)
	{
		$message_id = parent::add_message($reference_id, $replace_params, $gid, $set_flashdata);		
		
		print('<script>$("#' . $target_id . '").append(\'<div class="sys_msg">'.$this->messages[$message_id].'</div>\');</script>');
		unset($this->messages[$message_id]);
	}
	
	function add_error($target_id, $reference_id, $replace_params = array(), $gid = 'error', $set_flashdata = false)
	{		
		$error_id = parent::add_error($reference_id, $replace_params, $gid, $set_flashdata);		
		
		print('<script>$("#' . $target_id. '").append(\'<div class="sys_err">'.$this->errors[$error_id].'</div>\');</script>');
		unset($this->errors[$error_id]);
	}
}
