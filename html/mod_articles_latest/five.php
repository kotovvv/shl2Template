<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_latest
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

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

	if (!$list)
	{
		return;
	}

	?>
	<section class="news_botton">
		<div class="container">
			<div class="row">

				<?php foreach ($list as $item) :
					$images  = json_decode($item->images);
					$no_image = $images->image_intro? '': ' not_img';
					$attribs = json_decode($item->attribs);
					?>
					<div class="news_b <?php echo $no_image?>">
						<?php if ($item->params->get('show_category') == '1') : ?>
							<a href="<?php echo Route::_(RouteHelper::getCategoryRoute($item->catid, $item->language)); ?>"><p class="category_news_b"><?= $item->category_title?></p></a>
						<?php endif;?>
						<div class="img_news_b">
						<?php $sh=0; if ($images->image_intro) : $sh=1;?>
							<img src="<?= $images->image_intro ?>" alt="<?= $images->image_intro_alt ?>">
									<?php else  :?>
					<div class="box_img_news">
			<img src="/media/templates/site/shl2/images/ready-back-school.jpg" alt="<?= $item->title ?> no">
		</div>
						<?php endif;?>

						</div>
					<?php if ($sh) : ?>
						<div class="copyright_news_b">
							<img src="/media/templates/site/shl2/images/!cpyrght_icon.svg" alt="">
							<span>Copyright</span>
						</div>
					<?php endif;?>
					<?php if ($item->params->get('show_create_date') == '1') : ?>
						<div class="date_news_b"><?= date('d.m.Y',strtotime("$item->created")) ?></div>
					<?php endif;?>
					<?php if ($item->params->get('show_title') == '1') : ?>
						<h4 class="title"><?= $item->title ?></h4>
					<?php endif;?>

					<?php	$show_introtext = $item->params->get('show_intro');
					if ($show_introtext == '1')
					{
						$item->introtext = cleanIntrotext($item->introtext);
					}

					$item->displayIntrotext = $show_introtext ? truncate($item->introtext, $item->params->get('readmore_limit')) : '';
					echo $item->displayIntrotext;?>

		<?php if ($item->params->get('show_readmore') != '0' && $item->readmore) : ?>
						<div class="link_news_b">

							<a class="mod-articles-category-title" href="<?php echo $item->link; ?>"><span>
								<?php if ($item->params->get('access-view') == false) : ?>
									<?php echo Text::_('MOD_ARTICLES_CATEGORY_REGISTER_TO_READ_MORE'); ?>
								<?php elseif ($item->alternative_readmore) : ?>
									<?php echo $item->alternative_readmore; ?>
								<?php elseif ($item->params->get('show_readmore_title', 0)) : ?>
									<?php echo HTMLHelper::_('string.truncate', $item->title, $item->params->get('readmore_limit')); ?>
								<?php elseif ($item->params->get('show_readmore_title', 0)) : ?>
									<?php echo Text::_('MOD_ARTICLES_CATEGORY_READ_MORE'); ?>
									<?php echo HTMLHelper::_('string.truncate', $item->title, $item->params->get('readmore_limit')); ?>
								<?php else : ?>
									<?php echo Text::_('MOD_ARTICLES_CATEGORY_READ_MORE'); ?>
								<?php endif; ?>
								</span><img src="/media/templates/site/shl2/images/read_more_link.svg" alt="more <?= $item->title ?>">
							</a>
						</div>
					<?php endif; ?>
				</div>

			<?php endforeach; ?>
		</div>
	</div>
</section>

