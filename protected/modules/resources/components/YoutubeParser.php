<?php
class YoutubeParser extends CComponent {
	public function parse($url) {
		if(!function_exists('curl_init')) {
			ajaxOut(array(
				'error'=>true,
				'message'=>'Curl PHP package not installedn'
			));
		}
		
		$pattern = "/(&|\?)v=([a-zA-Z0-9\-_]+)/";
		preg_match($pattern, $url, $matches);
		$vid = null;
		if (!empty($matches) && isset($matches[2])) {
			$vid = $matches[2];
		}
		if(empty($vid))
			return false;
		
		//$url = "http://www.youtube.com/oembed?url={$url}&format=json";
		$url = "http://gdata.youtube.com/feeds/api/videos/" . $vid;
		// $url = "http://mp3.zing.vn/";
		$content = file_get_contents($url);
		
		$doc = new DOMDocument();
		$doc->loadXML($content);
// 		$uri = $doc->documentElement->lookupnamespaceURI(NULL);
// 		dump($uri);

// 		$simpleDoc = simplexml_load_string($doc->saveXML());
// 		dump($simpleDoc);
		$xpath = new DOMXPath($doc);
		$xpath->registerNamespace('root', "http://www.w3.org/2005/Atom");
		$xpath->registerNamespace('media', "http://search.yahoo.com/mrss/");
// 		$xpath->registerNamespace('gd', "http://schemas.google.com/g/2005");
// 		$xpath->registerNamespace('yt', "http://gdata.youtube.com/schemas/2007");
		
		$descriptionNode = $xpath->query("//root:entry/root:content");
		
		if ($descriptionNode->length) {
			$description = $descriptionNode->item(0)->nodeValue;
		}
		
		$thumbnails = array();
		$thumbnailNode = $xpath->query("*//media:thumbnail");
		
		for ($i = 0; $i < $thumbnailNode->length; $i++) {
			$thumbnails[] = array(
				'url'		=> $thumbnailNode->item($i)->getAttribute('url'),
				'height'	=> $thumbnailNode->item($i)->getAttribute('height'),
				'width'		=> $thumbnailNode->item($i)->getAttribute('width'),
			);
		}
		
		$titleNode = $xpath->query("//root:entry/root:title");
		$title = "";
		if ($titleNode->length) {
			$title = $titleNode->item(0)->nodeValue;
		}
		
		$mediaNode = $xpath->query("//root:entry/media:group/media:content[@isDefault='true']");
		$media = array();
		if ($mediaNode->length) {
			$media = array(
				'length'	=> (int) $mediaNode->item(0)->getAttribute('duration')
			);
		} else {
			$media = array(
				'length'	=> 0
			);
		}
		
		$out = array(
			'title'			=> $title,
			'description'	=> $description,
			'thumbnails'	=> $thumbnails,
			'media'			=> $media
		);
		return $out;
	}
}