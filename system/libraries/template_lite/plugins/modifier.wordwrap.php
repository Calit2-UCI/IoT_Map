<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty wordwrap modifier plugin
 *
 * Type:     modifier<br>
 * Name:     wordwrap<br>
 * Purpose:  wrap a string of text at a given length
 * @link http://smarty.php.net/manual/en/language.modifier.wordwrap.php
 *          wordwrap (Smarty online manual)
 * @param string
 * @param integer
 * @param string
 * @param boolean
 * @return string
 */
function tpl_modifier_wordwrap($string, $length = 80, $break = "\n", $cut = false) {
	if ($cut) {
		// Match anything 1 to $length chars long followed by whitespace or EOS,
		// otherwise match anything $width chars long
		$search = '/(.{1,' . $length . '})(?:\s|$)|(.{' . $length . '})/uS';
		$replace = '$1$2' . $break;
	} else {
		// Anchor the beginning of the pattern with a lookahead
		// to avoid crazy backtracking when words are longer than $width
		$replace = '$1' . $break;
	}
	return preg_replace($search, $replace, $string);
}