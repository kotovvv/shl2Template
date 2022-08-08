<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Component\Content\Site\Helper\AssociationHelper;
use Joomla\Component\Content\Site\Helper\RouteHelper;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('com_content.articles-list');

// Create some shortcuts.
$n          = count($this->items);
$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
$langFilter = false;

// Tags filtering based on language filter
if (($this->params->get('filter_field') === 'tag') && (Multilanguage::isEnabled()))
{
	$tagfilter = ComponentHelper::getParams('com_tags')->get('tag_list_language_filter');

	switch ($tagfilter)
	{
		case 'current_language':
		$langFilter = Factory::getApplication()->getLanguage()->getTag();
		break;

		case 'all':
		$langFilter = false;
		break;

		default:
		$langFilter = $tagfilter;
	}
}

// Check for at least one editable article
$isEditable = false;

if (!empty($this->items))
{
	foreach ($this->items as $article)
	{
		if ($article->params->get('access-edit'))
		{
			$isEditable = true;
			break;
		}
	}
}else{
	echo Text::_('COM_CONTENT_NO_ARTICLES');
}


$currentDate = Factory::getDate()->format('Y-m-d H:i:s');
?>

<?php
foreach ($this->items as $i => $article) : ?>
	<?php
	$images = json_decode($article->images);
	$attribs = json_decode($article->attribs);

 if($i == 0) : ?>
					<div class="box_filter_news">
						<div class="filter"></div>
					<?php endif; ?>
					<?php if($i >= 0) : ?>
						<div class="box_news_search">
							<?php if ($article->params->get('show_publish_date') == '1') : ?>
								<div class="date_news"><?= date('d.m.Y',strtotime("$article->publish_up")) ?></div>
							<?php endif;?>
							<?php if ($images->image_intro) : ?>
								<div class="img_news">
									<img src="<?= $images->image_intro ?>" alt="<?= $images->image_intro_alt ?>">
								</div>
							<?php endif;?>
							<div class="content_news">

								<?php if ($article->params->get('show_title') == '1') : ?>
									<h3 class="title">
										<?= $article->title ?>
									</h3>
								<?php endif;?>

								<?php if ($article->params->get('show_modify_date') == '1') : ?>
									<span class="date_one">Aktualisiert: <?= date('d.m.Y',strtotime("$article->modified")) ?></span>
								<?php endif;?>
								<?php if ($article->params->get('show_publish_date') == '1') : ?>
									<span class="date_two">Veröffentlicht: <?= date('d.m.Y',strtotime("$article->publish_up")) ?></span>
								<?php endif;?>
								<?php if ($article->params->get('show_author') == '1') : ?>
									<span class="autor">Autor: <?php echo $article->author ?></span>
								<?php endif;?>
							</div>
													<?php if ($article->params->get('show_readmore') == '1') : ?>
							<a href="<?php echo Route::_(RouteHelper::getArticleRoute($article->slug, $article->catid, $article->language)); ?>">
						</a>
						<?php endif; ?>
						</div>
					<?php endif; ?>
			<?php	endforeach; ?>
				</div>


<?php /**
	 * Strips unnecessary tags from the introtext
	 *
	 * @param   string  $introtext  introtext to sanitize
	 *
	 * @return mixed|string
	 *
	 * @since  1.6
	 */
function _cleanIntrotext($introtext)
{
	$introtext = str_replace(array('<p>', '</p>'), ' ', $introtext);
	$introtext = strip_tags($introtext, '<em><strong><joomla-hidden-mail>');
	$introtext = trim($introtext);

	return $introtext;
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