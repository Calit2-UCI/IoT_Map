<?php
/**
* Social networking twitter widgets model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Twitter_widgets_model extends Model {

	public $CI;
	public $widget_types = array(
		'share',
	);

	function __construct() {
		parent::__construct();
		$this->CI = & get_instance();
	}

	public function get_share() {
		return <<<'EOD'
<a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
<script>
	!function(d,s,id){
		var js,
		fjs=d.getElementsByTagName(s)[0],
		p=/^http:/.test(d.location)?'http':'https';
		if(!d.getElementById(id)){
			js=d.createElement(s);
			js.id=id;
			js.src=p+'://platform.twitter.com/widgets.js';
			fjs.parentNode.insertBefore(js,fjs);
		}
	}(document, 'script', 'twitter-wjs');
	if(!$('iframe.twitter-share-button').length && 'object' === typeof twttr) {
		$(function(){
			twttr.widgets.load();
		});
	}
</script>
EOD;
	}

}
