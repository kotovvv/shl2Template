<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   (C) 2010 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;
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

include_once 'function.php';

?>
<div class="box_news version_four">
	<?php
	$i = 1;
	foreach ($list as $item) :
$images  = json_decode($item->images);
$no_image = $images->image_intro? '': ' not_img';
$attribs = json_decode($item->attribs);?>
<div class="box_news_ver_four <?php echo $no_image?>">
		<?php if ($images->image_intro) :?>
	<div class="box_img_news">
		<img src="<?= $images->image_intro ?>" alt="<?= $images->image_intro_alt ?>">
</div>
	<?php endif;?>
<?php if ($item->params->get('show_category') == '1') : ?>
	<div class="category_news"><a href="<?php echo Route::_(RouteHelper::getCategoryRoute($item->catid, $item->language)) ?>"><?= $item->category_title?></a></div>
<?php endif;?>
<?php if ($item->params->get('show_title')) : ?>
	<h3 class="item-title title" itemprop="headline">
		<?php echo $item->title; ?>
	</h3>
<?php endif; ?>
<?php	$show_introtext = $item->params->get('show_intro');

if ($show_introtext == '1')
{
	$item->introtext = cleanIntrotext($item->introtext);
}

	$item->displayIntrotext = strlen($item->introtext) > 1000 ? truncate($item->introtext, 1000) : $item->introtext;
echo '<p>'.$item->displayIntrotext.'</p>';?>

		<?php if ($item->params->get('show_readmore') != '0' && $item->readmore  ) : ?>
	<div><a class="btn_news" href="<?php echo Route::_(RouteHelper::getArticleRoute($item->slug, $item->catid, $item->language)); ?>" itemprop="url">
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
			<?php echo Text::_('MOD_ARTICLES_CATEGORY_READ_MORE_TITLE'); ?>
		<?php endif; ?>
	</a></div>
<?php endif; ?>

	<?php  if (($params->get('show_create_date') != '0' && $attribs->show_create_date != '0') || ($params->get('show_modified_date') != '0' && $attribs->show_modify_date != '0') || ($params->get('show_publish_date') != '0' && $attribs->show_publish_date != '0') || ($params->get('show_author') != '0' && $attribs->show_author != '0')) :?>
	<div class="box_info">
		<?php if ($params->get('show_create_date') != '0' && $attribs->show_create_date != '0') : ?>
			<span>Erstellt: <?= date('d.m.Y',strtotime($list[0]->created)) ?></span>
		<?php endif;?>
		<?php if ($params->get('show_modified_date') != '0' && $attribs->show_modify_date != '0') : ?>
			<span>Aktualisiert: <?= date('d.m.Y',strtotime($list[0]->modified)) ?></span>
		<?php endif;?>

		<?php if ($params->get('show_publish_date') != '0' && $attribs->show_publish_date != '0') : ?>
			<span>Veröffentlicht: <?= date('d.m.Y',strtotime($list[0]->publish_up)) ?></span>
		<?php endif;?>
		<?php if ($params->get('show_author') != '0' && $attribs->show_author != '0') : ?>
			<span>Autor: <?= $list[0]->author ?></span>
		<?php endif;?>
	</div>
<?php endif; ?>

</div>
<?php $i++; endforeach; ?>
</div>