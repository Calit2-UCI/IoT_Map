<?php


class VideoEmbed{
	private $settings = array(
		"youtube.com" => array(
			"regexp" => array(
				array('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', array(1=>'video')),
				array('/width="([0-9]+)" height="([0-9]+)".*youtube\.com\/embed\/([a-z0-9_\-]+)/i', array(1=>'width', 2=>'height', 3=>'video')),
				array('/width="([0-9]+)" height="([0-9]+)".*youtube\.com\/v\/([a-z0-9_\-]+)/i', array(1=>'width', 2=>'height', 3=>'video')),
				array('/youtube\.com\/embed\/([a-z0-9_\-]+)/i', array(1=>'video')),
				array('/youtube\.com\/v\/([a-z0-9_\-]+)/i', array(1=>'video')),
			),
			"embed" => '<iframe width="[width]" height="[height]" src="http://www.youtube.com/embed/[video]?wmode=transparent" frameborder="0" allowfullscreen></iframe>',
			"replace" => array('width'=>560, 'height'=>315, 'video'=>'')
		),

		"youtu.be" => array(
			"regexp" => array(
				array('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', array(1=>'video')),
				array('/youtu\.be\/([a-z0-9_\-]+)/i', array(1=>'video')),
			),
			"embed" => '<iframe width="[width]" height="[height]" src="http://www.youtube.com/embed/[video]?wmode=transparent" frameborder="0" allowfullscreen></iframe>',
			"replace" => array('width'=>560, 'height'=>315, 'video'=>'')
		),

		///// yahoo.com
		"yimg.com" => array(
			"regexp" => array(
				array('/width="([0-9]+)" height="([0-9]+)".*yimg\.com\/nl\/vyc\/site\/.*vid=([0-9]+)/i', array(1=>'width', 2=>'height', 3=>'video')),
				array('/yimg\.com\/nl\/vyc\/site\/.*vid=([0-9]+)/i', array(1=>'video')),
			),
			"embed" => '<iframe frameborder="0" width="[width]" height="[height]" src="http://d.yimg.com/nl/vyc/site/player.html?wmode=transparent#vid=[video]"></iframe></div>',
			"replace" => array('width'=>576, 'height'=>324, 'video'=>'')
		),

		///// yahoo.com
		"veoh.com" => array(
			"regexp" => array(
				array('/width="([0-9]+)" height="([0-9]+)".*veoh\.com\/swf\/webplayer\/WebPlayer\.swf.*permalinkId=([a-z0-9]+)/i', array(1=>'width', 2=>'height', 3=>'video')),
				array('/veoh\.com\/watch\/([a-z0-9]+)/i', array(1=>'video')),
			),
			"embed" => '<object width="[width]" height="[height]" id="veohFlashPlayer" name="veohFlashPlayer"><param name="movie" value="http://www.veoh.com/swf/webplayer/WebPlayer.swf?version=AFrontend.5.7.0.1331&permalinkId=[video]&player=videodetailsembedded&videoAutoPlay=0&id=anonymous&wmode=transparent"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.veoh.com/swf/webplayer/WebPlayer.swf?version=AFrontend.5.7.0.1331&permalinkId=[video]&player=videodetailsembedded&videoAutoPlay=0&id=anonymous&wmode=transparent" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="[width]" height="[height]" id="veohFlashPlayerEmbed" name="veohFlashPlayerEmbed"></embed></object>',
			"replace" => array('width'=>576, 'height'=>324, 'video'=>'')
		),
		///// aol.com
		"aol.com" => array(
			"regexp" => array(
				array('/aol\.com\/video\/(.*\/)?([0-9]+)/i', array(2=>'video')),
			),
			"embed" => "<object width='[width]' height='[height]' id='FiveminPlayer' classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000'><param name='allowfullscreen' value='true'/><param name='allowScriptAccess' value='always'/><param name='movie' value='http://embed.5min.com/[video]/?wmode=transparent'/><param name='wmode' value='opaque' /><embed name='FiveminPlayer' src='http://embed.5min.com/[video]/?wmode=transparent' type='application/x-shockwave-flash' width='[width]' height='[height]' allowfullscreen='true' allowScriptAccess='always' wmode='opaque'></embed></object>",
			"replace" => array('width'=>576, 'height'=>324, 'video'=>'')
		),

		///// aol.com
		"5min.com" => array(
			"regexp" => array(
				array("/width=(\"|')([0-9]+)(\"|') height=(\"|')([0-9]+)(\"|').*5min\.com\/([0-9]+)/i", array(2=>'width', 5=>'height', 7=>'video')),
				array('/5min\.com\/([0-9]+)/i', array(1=>'video')),
			),
			"embed" => "<object width='[width]' height='[height]' id='FiveminPlayer' classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000'><param name='allowfullscreen' value='true'/><param name='allowScriptAccess' value='always'/><param name='movie' value='http://embed.5min.com/[video]/?wmode=transparent'/><param name='wmode' value='opaque' /><embed name='FiveminPlayer' src='http://embed.5min.com/[video]/?wmode=transparent' type='application/x-shockwave-flash' width='[width]' height='[height]' allowfullscreen='true' allowScriptAccess='always' wmode='opaque'></embed></object>",
			"replace" => array('width'=>576, 'height'=>324, 'video'=>'')
		),

		///// blip.tv
		"blip.tv" => array(
			"regexp" => array(
				array('/width="([0-9]+)" height="([0-9]+)".*blip\.tv\/.*\#([a-z0-9]+)/i', array(1=>'width', 2=>'height', 3=>'video')),
				array('/blip\.com\/([0-9]+)/i', array(1=>'video')),
			),
			"embed" => '<iframe src="http://blip.tv/play/[video].html?p=1&wmode=transparent" width="[width]" height="[height]" frameborder="0" allowfullscreen></iframe><embed type="application/x-shockwave-flash" src="http://a.blip.tv/api.swf?wmode=transparent#[video]" style="display:none"></embed>',
			"replace" => array('width'=>550, 'height'=>443, 'video'=>'')
		),

		////// exposureroom.com
		"exposureroom.com" => array(
			"regexp" => array(
				array('/(id="xrP([a-z0-9]+)" )?width="([0-9]+)" height="([0-9]+)".*exposureroom\.com\/flash\/.*assetId=([a-z0-9]+).*size=([a-z]{2})/i', array(2=>'video', 3=>'width', 4=>'height', 5=>'video', 6=>'size')),
			),
			"embed" => '<object id="xrP[video]" width="[width]" height="[height]" type="application/x-shockwave-flash" data="http://exposureroom.com/flash/XRVideoPlayer2.swf?domain=exposureroom.com/&amp;assetId=[video]&amp;size=[size]&amp;titleColor=%23ffffff&amp;wmode=transparent"><param name="movie" value="http://exposureroom.com/flash/XRVideoPlayer2.swf?domain=exposureroom.com/&amp;assetId=[video]&amp;size=[size]&amp;titleColor=%23ffffff&amp;wmode=transparent" /><param name="allowNetworking" value="all" /><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="True" /><param name="wmode" value="opaque" /></object>',
			"replace" => array('width'=>480, 'height'=>270, 'video'=>'', 'size'=>'sm')
		),

		//////pandora.tv
		"pandora.tv" => array(
			"regexp" => array(
				array('/width="([0-9]+)" height="([0-9]+)".*flvr\.pandora\.tv\/flv2pan\/flvmovie\.dll\/.*userid=([a-z0-9]+).*prgid=([0-9]+)/i', array(1=>'width', 2=>'height', 3=>'userid', 4=>'video')),
				array('/pandora\.tv\/.*ch_userid=([a-z0-9]+)&prgid=([0-9]+)/i', array(1=>'userid', 2=>'video')),
			),
			"embed" => '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="[width]" height="[height]" id="movie" align="middle"> <param name="quality" value="high" /> <param name="movie" value="http://flvr.pandora.tv/flv2pan/flvmovie.dll/userid=[userid]&prgid=[video]&skin=1&wmode=transparent" /> <param name="allowScriptAccess" value="always" /> <param name="allowFullScreen" value="true" /> <param name="wmode" value="transparent" /> <embed src="http://flvr.pandora.tv/flv2pan/flvmovie.dll/userid=[userid]&prgid=[video]&skin=1&wmode=transparent" type="application/x-shockwave-flash" wmode="transparent" allowScriptAccess="always" allowFullScreen="true" pluginspage="http://www.macromedia.com/go/getflashplayer" width="[width]" height="[height]" /></embed> </object>',
			"replace" => array('width'=>500, 'height'=>402, 'video'=>'', 'userid'=>'')
		),

		//////vimeo.com
		"vimeo.com" => array(
			"regexp" => array(
				array('/vimeo\.com\/video\/([0-9]+).*width="([0-9]+)" height="([0-9]+)"/i', array(1=>'video', 2=>'width', 3=>'height')),
				array('/vimeo\.com\/([0-9]+)/i', array(1=>'video')),
			),
			"embed" => '<iframe src="http://player.vimeo.com/video/[video]?title=0&amp;byline=0&amp;portrait=0&amp;wmode=transparent" width="[width]" height="[height]" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
			"replace" => array('width'=>400, 'height'=>225, 'video'=>'')
		),

		///// zoomby.ru
		"zoomby.ru" => array(
			"regexp" => array(
				array('/width="([0-9]+)" height="([0-9]+)".*zoomby\.ru\/v\/([0-9]+)/i', array(1=>'width', 2=>'height', 3=>'video')),
				array('/zoomby\.ru\/watch\/([0-9]+)/i', array(1=>'video')),
			),
			"embed" => '<object id="ZoombyPlayer" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="[width]" height="[height]"><param name="movie" value="http://www.zoomby.ru/v/[video]?wmode=transparent" /><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="true" /><param name="bgcolor" value="#000000" /><param name="wmode" value="opaque" /><embed src="http://www.zoomby.ru/v/[video]?wmode=transparent" quality="high" allowscriptaccess="always" allowfullscreen="true" wmode="opaque" width="[width]" height="[height]" type="application/x-shockwave-flash"></embed></object>',
			"replace" => array('width'=>640, 'height'=>360, 'video'=>'')
		),

		///// tvigle.ru
		"tvigle.ru" => array(
			"regexp" => array(
				array('/width="([0-9]+)" height="([0-9]+)".*tvigle\.ru\/resource\/rf\/swf\/([0-9a-z]{2})\/([0-9a-z]{2})\/([0-9a-z]{2})\/([0-9a-z]+)\.swf/i', array(1=>'width', 2=>'height', 3=>'p1', 4=>'p2', 5=>'p3', 6=>'p4')),
			),
			"embed" => '<object id="v[p1][p2][p3][p4]" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="[width]" height="[height]" align="middle"><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><param name="movie" value="http://photo.tvigle.ru/resource/rf/swf/[p1]/[p2]/[p3]/[p4].swf"></param><embed src="http://photo.tvigle.ru/resource/rf/swf/[p1]/[p2]/[p3]/[p4].swf" width="[width]" height="[height]"  allowfullscreen="true" allowscriptaccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>',
			"replace" => array('width'=>720, 'height'=>405, 'p1'=>'', 'p2'=>'', 'p3'=>'', 'p4'=>'')
		),

		///// rutube.ru
		"rutube.ru" => array(
			"regexp" => array(
				array('/width="([0-9]+)" height="([0-9]+)".*rutube\.ru\/([0-9a-z]+)/i', array(1=>'width', 2=>'height', 3=>'video')),
			),
			"embed" => '<OBJECT width="[width]" height="[height]"><PARAM name="movie" value="http://video.rutube.ru/[video]?wmode=transparent"></PARAM><PARAM name="wmode" value="window"></PARAM><PARAM name="allowFullScreen" value="true"></PARAM><EMBED src="http://video.rutube.ru/[video]?wmode=transparent" type="application/x-shockwave-flash" wmode="window" width="[width]" height="[height]" allowFullScreen="true" ></EMBED></OBJECT>',
			"replace" => array('width'=>470, 'height'=>353, 'video'=>'')
		),

		///// kinostok.tv
		"kinostok.tv" => array(
			"regexp" => array(
				array('/kinostok\.tv\/v\/([0-9a-z]+).*width="([0-9]+)" height="([0-9]+)"/i', array(2=>'width', 3=>'height', 1=>'video')),
			),
			"embed" => '<embed src="http://kinostok.tv/v/[video]?wmode=transparent" wmode="transparent" FlashVars="skin=skins/minimal" width="[width]" height="[height]" allowscriptaccess="always" allowfullscreen="true" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" /></embed>',
			"replace" => array('width'=>640, 'height'=>480, 'video'=>'')
		),

		///// smotri.com
		"smotri.com" => array(
			"regexp" => array(
				array('/id="smotriComVideoPlayer([0-9a-z_\.]*)".*width="([0-9]+)" height="([0-9]+).*smotri\.com\/player\.swf\?file=([0-9a-z]+)/i', array(1=>"id", 2=>'width', 3=>'height', 4=>'video')),
			),
			"embed" => '<object id="smotriComVideoPlayer[id]" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="[width]" height="[height]"><param name="movie" value="http://pics.smotri.com/player.swf?file=[video]&wmode=transparent&bufferTime=3&autoStart=false&str_lang=rus&xmlsource=http%3A%2F%2Fpics.smotri.com%2Fcskins%2Fblue%2Fskin_color.xml&xmldatasource=http%3A%2F%2Fpics.smotri.com%2Fskin_ng.xml" /><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="true" /><param name="bgcolor" value="#ffffff" /><embed src="http://pics.smotri.com/player.swf?file=[video]&wmode=transparent&bufferTime=3&autoStart=false&str_lang=rus&xmlsource=http%3A%2F%2Fpics.smotri.com%2Fcskins%2Fblue%2Fskin_color.xml&xmldatasource=http%3A%2F%2Fpics.smotri.com%2Fskin_ng.xml" quality="high" allowscriptaccess="always" allowfullscreen="true" wmode="opaque"  width="[width]" height="[height]" type="application/x-shockwave-flash"></embed></object>',
			"replace" => array('width'=>640, 'height'=>480, 'video'=>'', "id"=>'')
		),

		///// myvi.ru
		"myvi.ru" => array(
			"regexp" => array(
				array('/myvi\.ru\/ru\/flash\/player\/pre\/([0-9a-z\-_]+).*width="([0-9]+)" height="([0-9]+)/i', array(1=>"video", 2=>'width', 3=>'height')),
			),
			"embed" => '<object style="width: [width]px; height: [height]px"><param name="allowFullScreen" value="true"/><param name="allowScriptAccess" value="always" /><param name="movie" value="http://myvi.ru/ru/flash/player/pre/[video]?wmode=transparent" /><param name="flashVars" value="kgzp=replace" /><embed src="http://myvi.ru/ru/flash/player/pre/[video]?wmode=transparent" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="[width]" height="[height]" flashVars="kgzp=replace"></object>',
			"replace" => array('width'=>640, 'height'=>390, 'video'=>'')
		),

		//// dailymotion.com
		"dailymotion.com" => array(
			"regexp" => array(
				array('/width="([0-9]+)" height="([0-9]+)".*dailymotion\.com\/embed\/video\/([a-z_\-0-9]+)/i', array(1=>'width', 2=>'height', 3=>'video')),
				array('/dailymotion\.com\/video\/([a-z_\-0-9]+)/i', array(1=>'video')),
			),
			"embed" => '<iframe frameborder="0" width="[width]" height="[height]" src="http://www.dailymotion.com/embed/video/[video]?wmode=transparent"></iframe>',
			"replace" => array('width'=>480, 'height'=>270, 'video'=>'')
		),

		//// metacafe.com
		"metacafe.com" => array(
			"regexp" => array(
				array('/metacafe\.com\/fplayer\/([0-9]+)\/([a-z0-9_\-]+)\.swf.*width="([0-9]+)" height="([0-9]+)"/i', array(1=>'video', 2=>'gid', 3=>'width', 4=>'height')),
				array('/metacafe\.com\/watch\/([0-9]+)\/([a-z0-9_\-]+)/i', array(1=>'video', 2=>'gid')),
			),
			"embed" => '<embed flashVars="playerVars=autoPlay=no" src="http://www.metacafe.com/fplayer/[video]/[gid].swf?wmode=transparent" width="[width]" height="[height]" wmode="transparent" allowFullScreen="true" allowScriptAccess="always" name="Metacafe_[video]" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>',
			"replace" => array('width'=>440, 'height'=>248, 'video'=>'', 'gid'=>'')
		),

		//// myspace.com
		"myspace.com" => array(
			"regexp" => array(
				array('/width="([0-9]+)px" height="([0-9]+)px".*myspace\.com\/services\/media\/embed\.aspx\/m=([0-9]+)/i', array(1=>'width', 2=>'height', 3=>'video')),
				array('/myspace\.com\/video\/.*\/([0-9]+)/i', array(1=>'video')),
			),
			"embed" => '<object width="[width]px" height="[height]px" ><param name="allowFullScreen" value="true"/><param name="wmode" value="transparent"/><param name="movie" value="http://mediaservices.myspace.com/services/media/embed.aspx/m=[video]?wmode=transparent,t=1,mt=video"/><embed src="http://mediaservices.myspace.com/services/media/embed.aspx/m=[video]?wmode=transparent,t=1,mt=video" width="[width]" height="[height]" allowFullScreen="true" type="application/x-shockwave-flash" wmode="transparent"></embed></object>',
			"replace" => array('width'=>425, 'height'=>360, 'video'=>'')
		),

		//// liveleak.com
		"liveleak.com" => array(
			"regexp" => array(
				array('/width="([0-9]+)" height="([0-9]+)".*liveleak\.com\/e\/([a-z0-9_]+)/i', array(1=>'width', 2=>'height', 3=>'video')),
				array('/liveleak\.com\/view\?i=([a-z0-9_]+)/i', array(1=>'video')),
			),
			"embed" => '<object width="[width]" height="[height]"><param name="movie" value="http://www.liveleak.com/e/[video]?wmode=transparent"></param><param name="wmode" value="transparent"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.liveleak.com/e/[video]?wmode=transparent" type="application/x-shockwave-flash" wmode="transparent" allowscriptaccess="always" width="[width]" height="[height]"></embed></object>',
			"replace" => array('width'=>450, 'height'=>370, 'video'=>'')
		),

		//// vbox7.com
		"vbox7.com" => array(
			"regexp" => array(
				array('/width="([0-9]+)" height="([0-9]+)".*vbox7\.com\/player\/ext\.swf\?vid=([a-z0-9]+)/i', array(1=>'width', 2=>'height', 3=>'video')),
				array('/vbox7\.com\/play:([a-z0-9]+)/i', array(1=>'video')),
			),
			"embed" => '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="[width]" height="[height]"><param name="movie" value="http://i48.vbox7.com/player/ext.swf?vid=[video]&wmode=transparent"><param name="quality" value="high"><embed src="http://i48.vbox7.com/player/ext.swf?vid=[video]&wmode=transparent" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="[width]" height="[height]"></embed></object>',
			"replace" => array('width'=>450, 'height'=>403, 'video'=>'')
		),
	);

	private $services = array();

	function __construct(){
		$this->services = array_keys($this->settings);
	}


	public function get_video_data($embed){
		///// смортим, чей может быть предположительно код
		$services = array();
		foreach($this->services as $service){
			if(strpos($embed, $service) !== false){
				$services[] = $service;
			}
		}

		///// Если пустой массив - то дальше искать бесполезно
		if(empty($services)){
			return false;
		}

		///// проверяем по регуляркам
		$video_data = array();
		foreach($services as $service){
			$settings = $this->settings[$service];
			foreach($settings["regexp"] as $regexp_data){
				$regexp = $regexp_data[0];
				$params = $regexp_data[1];
				if(preg_match($regexp, $embed, $matches)){
					foreach($params as $pattern_num => $param){
						if(!empty($matches[$pattern_num])){
							$video_data[$param] = $matches[$pattern_num];
						}
					}
					break;
				}
			}
			if(!empty($video_data)){
				foreach($settings["replace"] as $param => $default_value){
					if(!isset($video_data[$param]) || empty($video_data[$param])){
						$video_data[$param] = $default_value;
					}
				}
				$video_data["service"] = $service;
				break;
			}
		}

		if(!empty($video_data)){
			return $video_data;
		}else{
			return false;
		}
	}

	public function get_embed_code($video_data){
		$service = $video_data["service"];
		if(!$service || !$this->settings[$service]){
			return false;
		}

		$settings = $this->settings[$service];
		$return = $settings["embed"];
		foreach($settings["replace"] as $param => $default){
			$value = (!empty($video_data[$param]))?$video_data[$param]:$default;
			$return = str_replace('['.$param.']', $value, $return);
		}
		return $return;
	}

	public function get_string_from_video_data($video_data){
		return http_build_query($video_data);
	}

	public function get_video_data_from_string($str){
		parse_str($str, $output);
		return $output;
	}

	public function get_services(){
		return $this->services;
	}
	
	public function replace_urls_to_embed_in_text($text, $prefix = '<br/>', $postfix = '<br/>'){
		$parse_result = $this->get_embed_from_urls_in_text($text);
		foreach($parse_result['embeds'] as $key => $embed){
			if($embed){
				$text = str_replace($parse_result['urls'][$key], $prefix.$embed.$postfix, $text);
			}
		}
		return $text;
	}

	public function get_embed_from_urls_in_text($text){
		preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $text, $match);
		$embeds = array();
		$urls = $match[0];
		foreach($urls as $key => $url){
			$embed_data = $this->get_video_data($url);
			$embeds[$key] = $embed_data ? $this->get_embed_code($embed_data) : false;
		}
		return array('urls' => $urls, 'embeds' => $embeds);
	}
}
