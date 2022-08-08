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
if (!$list)
{
	return;
}
include_once 'function.php';

$params = $list[0]->params;
$images  = json_decode($list[0]->images);
$attribs = json_decode($list[0]->attribs);
$no_image = $images->image_intro? '': ' not_img';
?>
<div class="box_news version_two <?php echo $no_image ?>">
	<?php if ($params->get('show_category') == '1') : ?>
		<div class="category_news"><a href="<?php echo Route::_(RouteHelper::getCategoryRoute($list[0]->catid, $list[0]->language)); ?>"><?= $list[0]->category_title?></a></div>
	<?php endif;?>
	<div class="box_content_news row">
		<?php if ($params->get('show_title')) : ?>
			<h3 class="item-title title" itemprop="headline">
				<?php echo $list[0]->title; ?>
		</h3>
	<?php endif; ?>

	<?php	$show_introtext = $params->get('show_intro');
	if ($show_introtext == '1')
	{
		$list[0]->introtext = cleanIntrotext($list[0]->introtext);
	}

$list[0]->displayIntrotext = strlen($list[0]->introtext) > 1000 ? truncate($list[0]->introtext, 1000) : $list[0]->introtext;
echo '<p>'.$list[0]->displayIntrotext.'</p>';?>


		<?php if ($params->get('show_readmore') != '0' && $list[0]->readmore) : ?>
	<div><a class="btn_news" href="<?php echo Route::_(RouteHelper::getArticleRoute($list[0]->slug, $list[0]->catid, $list[0]->language)); ?>" itemprop="url">
		<?php if ($params->get('access-view') == false) : ?>
			<?php echo Text::_('MOD_ARTICLES_CATEGORY_REGISTER_TO_READ_MORE'); ?>
		<?php elseif ($list[0]->alternative_readmore) : ?>
			<?php echo $list[0]->alternative_readmore; ?>
		<?php elseif ($params->get('show_readmore_title', 0)) : ?>
			<?php echo HTMLHelper::_('string.truncate', $list[0]->title, $params->get('readmore_limit')); ?>
		<?php elseif ($params->get('show_readmore_title', 0)) : ?>
			<?php echo Text::_('MOD_ARTICLES_CATEGORY_READ_MORE'); ?>
			<?php echo HTMLHelper::_('string.truncate', $list[0]->title, $params->get('readmore_limit')); ?>
		<?php else : ?>
			<?php echo Text::_('MOD_ARTICLES_CATEGORY_READ_MORE_TITLE'); ?>
		<?php endif; ?>
	</a></div>
<?php endif; ?>
</div>
	<?php $sh=0; if ($images->image_intro) : $sh=1;?>
<div class="box_img_news">
	<img src="<?= $images->image_intro ?>" alt="<?= $images->image_intro_alt ?>">
</div>
<?php endif;?>

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