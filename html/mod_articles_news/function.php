<?php
use Joomla\CMS\HTML\HTMLHelper;

/**
	 * Strips unnecessary tags from the introtext
	 *
	 * @param   string  $introtext  introtext to sanitize
	 *
	 * @return mixed|string
	 *
	 * @since  1.6
	 */

 if(!function_exists("cleanIntrotext")) {
 	function cleanIntrotext($introtext)
 	{
 		$introtext = str_replace(array('<p>', '</p>'), ' ', $introtext);
 		$introtext = strip_tags($introtext, '<em><strong><joomla-hidden-mail>');
 		$introtext = trim($introtext);

 		return $introtext;
 	}
 }

/**
	 * Method to truncate introtext
	 *
	 * The goal is to get the proper length plain text string with as much of
	 * the html intact as possible with all tags properly closed.
	 *
	 * @param   string   $html       The content of the introtext to be truncated
	 * @param   integer  $maxLength  The maximum number of characters to render
	 *
	 * @return  string  The truncated string
	 *
	 * @since   1.6
	 */
if(!function_exists("truncate")) {
	function truncate($html, $maxLength = 0)
	{
		$baseLength = \strlen($html);

		// First get the plain text string. This is the rendered text we want to end up with.
		$ptString = HTMLHelper::_('string.truncate', $html, $maxLength, $noSplit = true, $allowHtml = false);

		for ($maxLength; $maxLength < $baseLength;)
		{
			// Now get the string if we allow html.
			$htmlString = HTMLHelper::_('string.truncate', $html, $maxLength, $noSplit = true, $allowHtml = true);

			// Now get the plain text from the html string.
			$htmlStringToPtString = HTMLHelper::_('string.truncate', $htmlString, $maxLength, $noSplit = true, $allowHtml = false);

			// If the new plain text string matches the original plain text string we are done.
			if ($ptString === $htmlStringToPtString)
			{
				return $htmlString;
			}

			// Get the number of html tag characters in the first $maxlength characters
			$diffLength = \strlen($ptString) - \strlen($htmlStringToPtString);

			// Set new $maxlength that adjusts for the html tags
			$maxLength += $diffLength;

			if ($baseLength <= $maxLength || $diffLength <= 0)
			{
				return $htmlString;
			}
		}

		return $html;
	}
}