<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Service functions for organizing pagination and sorting
* 
* @package PG_Core
* @subpackage application
* @category	helpers
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Irina Lebedeva <irina@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2009-12-02 15:07:07 +0300 (Ср, 02 дек 2009) $ $Author: irina $
**/

if ( ! function_exists('get_exists_page_number'))
{	
	/**
	 * Get exists page number
	 *
	 * @param integer $page
	 * @param integer $items_count
	 * @param integer $items_on_page
	 * @return integer
	 */
    function get_exists_page_number($page, $items_count, $items_on_page)
    {
    	$pages_cnt = ceil($items_count/$items_on_page);
    	if ($page > $pages_cnt)
    	{
    		$page = $pages_cnt;
    	}	
    	return $page ? $page : 1;
    }
}    

if ( ! function_exists('attrs_to_get_string'))
{	
	/**
	 * Generate string from the attributes array
	 * 
	 * @param array $attrs
	 * @param boolean $format_attrs (format or not $attrs values to the object model proper attrs values)
	 * @return string
	 */
   function attrs_to_get_string($attrs, $format_attrs = false)
    {
    	$attr_str = "";
		if (!empty($attrs))
    	{    		
    		$attr_settings = array();
    		if ($format_attrs)
    		{    			
	    		$CI = & get_instance();
				$CI->load->model('Attrs_handler_model');
								
				$formated_attrs = array();				
				$CI->Attrs_handler_model->get_validate_attrs($attrs, null, $formated_attrs, null, 'gid');				
				$CI->system_messages->clean_messages();
				/**
				 * system format only existing system attributes and remove all other parameters,
				 * so restore removed parametres(e.g. order, order_direction, page)
				 */
				$attrs = array_merge($attrs, $formated_attrs);
				$attr_settings = $CI->Form_model->get_attr_settings(array_keys($attrs));
    		}				
    		foreach ($attrs as $a_gid=>$a_value)
    		{
    			if (!empty($a_value))
    			{
    				if (isset($attr_settings[$a_gid]) && $attr_settings[$a_gid]['control_type'] == 'file')
    				{
    					continue;
    				}
    				if (is_array($a_value))
    				{
    					foreach ($a_value as $arr_key=>$arr_val)
    					{
    						if ($arr_key == ADO_OTHER_VALUE)
    						{
    							$attr_str .= '&'.$a_gid.'[]=-1&ado_other_'.$a_gid.'='.urlencode($arr_val);
    						}
    						elseif ($arr_key == 'min')
    						{
    							$attr_str .= '&'.$a_gid.'[min]='.urlencode($arr_val);
    						}
    						elseif ($arr_key == 'max')
    						{
    							$attr_str .= '&'.$a_gid.'[max]='.urlencode($arr_val);
    						}
    						elseif ($arr_key == 'compare')
    						{
    							//compare attributes declared in the code, so ignore them here
    						}
    						else 
    						{
    							$attr_str .= '&'.$a_gid.'[]='.urlencode($arr_key);
    						}	
    					}	
    				}
    				else 
    				{
    					$attr_str .= '&'.$a_gid.'='.urlencode($a_value);
    				}
    			}
    		}	    		
    	}
	    return $attr_str;	
    }
}

if ( ! function_exists('get_order_icon'))
{	    
	/**
	 * Get icon for the current objects order
	 *
	 * @param string $order_direction
	 * @return string
	 */
    function get_order_icon($order_direction)
    {    	
    	return (strtoupper($order_direction) == "ASC") ? l('arrow_up', 'start') : l('arrow_down', 'start');
    }
}

if ( ! function_exists('attr_sorter_cmp'))
{
		function attr_sorter_cmp($a, $b){
			if($a["sorter"] == $b["sorter"]){
				return 0;
			}
			return ($a["sorter"] < $b["sorter"]) ? -1 : 1;
		}
}