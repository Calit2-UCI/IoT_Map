<?php
/**
* Get queries count
* 
* @package PG_Core
* @subpackage application
* @category	libraries
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Irina Lebedeva <irina@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2009-12-02 15:07:07 +0300 (Ср, 02 дек 2009) $ $Author: irina $
**/

class Query_counter {
	var $CI;	
	var $total_queries = array();
	
	function Query_counter()
	{
		$this->CI =& get_instance();
		$this->total_queries = $this->CI->db->queries;
	}
		
	/**
	 * Get queries count for the last time segment
	 *
	 * @return integer
	 */
	function get_segment_count($query_type = 'INSERT')
	{
		$prev_queries = $this->total_queries;
		$this->total_queries = $this->CI->db->queries;
		
		$segment_queries = array_diff($this->total_queries, $prev_queries);
		
		if (!empty($query_type))
		{
			$count = 0;
			foreach ($segment_queries as $query)
			{			
				if (preg_match('/^('.strtolower($query_type).'|'.strtoupper($query_type).')/', $query))
				{
					$count++;
				}
			}
			return $count;
		}
		else 
		{
			return count($segment_queries);
		}
	}
	
}