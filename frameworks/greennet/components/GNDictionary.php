<?php
/**
 * GNDictionary
 * 
 * @author HuyTBT
 * @date 2012-08-23
 * @version 1.0
 */
class GNDictionary
{
	// badwords: can cap nhat dong bo badwords o file wwwroot/jlwebroot/justlook/js/jlbd.badwords.js
	const BADWORDS = 'sex,fuck,shit,anus,asshole,assholes,bitch,bitches,crap,fart,flipping the bird,fucker,fuckin,fucking,penis,semen,sexy,vagina,vulva,asshole,masturbate,pussy,testicle';
	
	/**
	 * This method is used to check badwords
	 *
	 * @param $content	String content to check badwords
	 * @return Integer	Number match found
	 */
	public static function hasBadwords($content, $returnBadwords = false)
	{
		$matches = array();
		$matchFound = preg_match_all(
			"/\b(" . str_replace(',', '|', self::BADWORDS) . ")\b/i", 
			$content, 
			$matches
		);
		if ($returnBadwords) return $matches[0];
		
		return $matchFound > 0;
	}

	/**
	 * This method is used to highlight badwords
	 *
	 * @param $content	String content to highlight
	 * @return String	New content after hightlight badwords
	 */
	public static function highlightBadwords($content, $classHighlight = 'highlight')
	{
		$new = preg_replace("/\b(" . str_replace(',', '|', self::BADWORDS) . ")\b/i", "<span class='{$classHighlight}'>\$1</span>", $content);
		return $new;
	}
}
