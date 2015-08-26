<?php

class Rssfeed {

	private $channel_url;
	private $channel_title;
	private $channel_description;
	private $channel_lang;
	private $channel_copyright;
	private $channel_date;
	private $channel_creator;
	private $channel_subject;

	private $image_url;
	private $image_title;
	private $image_link;

	private $items = array();
	private $nritems;

	function __construct() {
		$this->nritems=0;
		$this->channel_url='';
		$this->channel_title='';
		$this->channel_description='';
		$this->channel_lang='';
		$this->channel_copyright='';
		$this->channel_date='';
		$this->channel_creator='';
		$this->channel_subject='';

		$this->image_url='';
		$this->image_title='';
		$this->image_link='';
	}

	// set channel vars
	public function set_channel($url, $title, $description, $lang='en-us', $copyright='', $creator='', $subject='') {
		$this->channel_url=$url;
		$this->channel_title=htmlspecialchars($title);
		$this->channel_description= htmlspecialchars($description);
		$this->channel_lang=$lang;
		$this->channel_copyright=htmlspecialchars($copyright);
		$this->channel_date=date("Y-m-d").'T'.date("H:i:s").'+01:00';
		$this->channel_creator=htmlspecialchars($creator);
		$this->channel_subject=htmlspecialchars($subject);
	}

	// set image
	public function set_image($url, $title='', $link='') {
		$this->image_url=$url;
		$this->image_title=htmlspecialchars($title);
		$this->image_link=urlencode($link);
	}

	// set item
	public function set_item($url, $title, $description, $date) {
		$this->items[$this->nritems]['url']=$url;
		$this->items[$this->nritems]['title']=htmlspecialchars($title);
		$this->items[$this->nritems]['description']=htmlspecialchars($description);
		$this->items[$this->nritems]['date']= date("Y-m-d", strtotime($date)).'T'.date("H:i:s", strtotime($date)).'+01:00';
		$this->nritems++;
	}

	// output feed
	public function output_v1() {
		$output =  '<?xml version="1.0" encoding="utf-8"?>'."\n";
		$output .= '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://purl.org/rss/1.0/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/" xmlns:taxo="http://purl.org/rss/1.0/modules/taxonomy/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:syn="http://purl.org/rss/1.0/modules/syndication/" xmlns:admin="http://webns.net/mvcb/" xmlns:feedburner="http://rssnamespace.org/feedburner/ext/1.0">'."\n";
		$output .= '<channel rdf:about="'.$this->channel_url.'">'."\n";
		$output .= '<title>'.$this->channel_title.'</title>'."\n";
		$output .= '<link>'.$this->channel_url.'</link>'."\n";
		$output .= '<description>'.$this->channel_description.'</description>'."\n";
		$output .= '<dc:language>'.$this->channel_lang.'</dc:language>'."\n";
		$output .= '<dc:rights>'.$this->channel_copyright.'</dc:rights>'."\n";
		$output .= '<dc:date>'.$this->channel_date.'</dc:date>'."\n";
		$output .= '<dc:creator>'.$this->channel_creator.'</dc:creator>'."\n";
		$output .= '<dc:subject>'.$this->channel_subject.'</dc:subject>'."\n";

		$output .= '<items>'."\n";
		$output .= '<rdf:Seq>';
		for($k=0; $k<$this->nritems; $k++) {
			$output .= '<rdf:li rdf:resource="'.$this->items[$k]['url'].'"/>'."\n"; 
		};	  
		$output .= '</rdf:Seq>'."\n";
		$output .= '</items>'."\n";
		$output .= '<image rdf:resource="'.$this->image_url.'"/>'."\n";
		$output .= '</channel>'."\n";
		for($k=0; $k<$this->nritems; $k++) {
			$output .= '<item rdf:about="'.$this->items[$k]['url'].'">'."\n";
			$output .= '<title>'.$this->items[$k]['title'].'</title>'."\n";
			$output .= '<link>'.$this->items[$k]['url'].'</link>'."\n";
			$output .= '<description>'.$this->items[$k]['description'].'</description>'."\n";
			$output .= '<feedburner:origLink>'.$this->items[$k]['url'].'</feedburner:origLink>'."\n";
			$output .= '</item>'."\n";  
		};
		$output .= '</rdf:RDF>'."\n";
		return $output;
	}

	public function output_v2() {
		$output =  '<?xml version="1.0" encoding="utf-8"?>'."\n";
		$output .= '<rss version="2.0">'."\n";

		$output .= '<channel>'."\n";
		$output .= '<title>'.$this->channel_title.'</title>'."\n";
		$output .= '<link>'.$this->channel_url.'</link>'."\n";
		$output .= '<description>'.$this->channel_description.'</description>'."\n";
		$output .= '<language>'.$this->channel_lang.'</language>'."\n";
		$output .= '<pubDate>'.$this->channel_date.'</pubDate>'."\n";
		$output .= '<lastBuildDate>'.$this->channel_date.'</lastBuildDate>'."\n";

		if($this->image_url){
			$output .= '<image>'."\n";
			$output .= '<title>'.$this->image_title.'</title>'."\n";
			$output .= '<url>'.$this->image_url.'</url>'."\n";
			$output .= '<link>'.$this->image_link.'</link>'."\n";
			$output .= '</image>'."\n";
		}

		for($k=0; $k<$this->nritems; $k++) {
			$output .= '<item>'."\n";
			$output .= '<title>'.$this->items[$k]['title'].'</title>'."\n";
			$output .= '<link>'.$this->items[$k]['url'].'</link>'."\n";
			$output .= '<description>'.$this->items[$k]['description'].'</description>'."\n";
			$output .= '<pubDate>'.$this->items[$k]['date'].'</pubDate>'."\n";
			$output .= '<guid>'.$this->items[$k]['url'].'</guid>'."\n";
			$output .= '</item>'."\n";
		};
		$output .= '</channel>'."\n";
		$output .= '</rss>'."\n";

		return $output;
	}

	public function send($version="v2"){
		if($version == "v2"){
			$output = $this->output_v2();
		}else{
			$output = $this->output_v1();
		}
		header("Content-Type: application/xml; charset=UTF-8");
		echo $output;
	}
}