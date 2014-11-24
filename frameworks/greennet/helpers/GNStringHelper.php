<?php
/**
 * GNStringHelper - This helper is used to process string
 * 
 * @author HuyTBT
 * @date 2012-06-26
 * @version 1.0
 */
class GNStringHelper
{
	/**
	 * This method is used to trim text
	 */
	public static function trim($str)
	{
		$str = trim($str, " \t\n\r\0\x0B");
		return $str;
	}

	/**
	 * Word Limiter
	 *
	 * Limits a string to X number of words.
	 *
	 * @access	public
	 * @param	string
	 * @param	integer
	 * @param	string	the end character. Usually an ellipsis
	 * @return	string
	 */
	public static function word_limiter($str, $limit = 100, $end_char = '&#8230;')
	{
		if (trim($str) == '')
		{
			return $str;
		}

		preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);

		if (strlen($str) == strlen($matches[0]))
		{
			$end_char = '';
		}

		return rtrim($matches[0]).$end_char;
	}

	/**
	 * Charactor Limiter by word
	 *
	 * Limits a string to X number of words.
	 *
	 * @access	public
	 * @param	string
	 * @param	integer
	 * @param	string	the end character. Usually an ellipsis
	 * @return	string
	 */
	public static function char_limiter_word($str, $limit = 100, $end_char = '&#8230;')
	{
		$lengthShow = $limit;
		@mb_internal_encoding("UTF-8");
		$intContentLength = mb_strlen($str);
		if ($intContentLength < $lengthShow) return $str;
		else {
			while ($lengthShow > 0 && mb_substr($str, $lengthShow, 1) != ' ') $lengthShow--;
			return mb_substr($str, 0, $lengthShow) . $end_char;
		}
	}
	
	/**
	 * This method is used to html purify
	 */
	public static function htmlPurify($strHtml, $arrOptions = array())
	{
		$p = new CHtmlPurifier();
		$p->options = CMap::mergeArray($arrOptions, array(
				// 'URI.AllowedSchemes'=>array(
				// 	'http' => true,
				// 	'https' => true,
				// ),
				'HTML.Allowed'=>'',
		));
		$text = $p->purify($strHtml);
		return $text;
	}
}
