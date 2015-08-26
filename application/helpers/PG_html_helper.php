<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* HTML helper
* 
* @package PG_Core
* @subpackage application
* @category	helpers
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Irina Lebedeva <irina@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2009-12-02 15:07:07 +0300 (Ср, 02 дек 2009) $ $Author: irina $
**/

if ( ! function_exists('content_block_start'))
{
	/**
	 * Generate starting html-code for the content block
	 *
	 */
	function content_block_start($id = '')
	{
		echo '<tr'.($id ? ' id="'.$id.'"' : '').'>
					<td>
						<div class="content_block">
								<div class="content_top"><!-- --></div>
								<div class="content">';
	}
}

if ( ! function_exists('content_block_end'))
{
	/**
	 * Generate ending html-code for the content block
	 *
	 */
	function content_block_end()
	{
		echo '	</div>
				<div class="content_btm"><!-- --></div>
				</div>
			</td>
		</tr>';
	}
}




/* End of file html_helper.php */
/* Location: ./application/helpers/html_helper.php */