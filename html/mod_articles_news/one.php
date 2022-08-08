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

 include_once 'function.php';

$images  = json_decode($item->images);
$no_image = $images->image_intro? ' not_img': '';
?>

<div class="box_news version_two <?php echo $no_image ?>">
	<?php if ($item->params->get('show_category') == '1') : ?>
		<div class="category_news"><a href="/index.php/<?= $item->category_route?>"><?= $item->category_title?></a></div>
	<?php endif;?>
	<div class="box_content_news row">
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


		<?php if ($params->get('show_readmore') != '0'  && $item->readmore) : ?>
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
</div>
<div class="box_img_news">
	<?php $sh=0; if ($images->image_intro) : $sh=1;?>
	<img src="<?= $images->image_intro ?>" alt="<?= $images->image_intro_alt ?>">

<?php endif;?>
</div>
	<?php  if (($params->get('show_create_date') != '0' && $attribs->show_create_date != '0') || ($params->get('show_modified_date') != '0' && $attribs->show_modify_date != '0') || ($params->get('show_publish_date') != '0' && $attribs->show_publish_date != '0') || ($params->get('show_author') != '0' && $attribs->show_author != '0')) :?>
<div class="box_info">
	<?php if ($item->params->get('show_create_date') == '1') : ?>
		<span>Erstellt: <?= date('d.m.Y',strtotime($item->created)) ?></span>
	<?php endif;?>
	<?php if ($item->params->get('show_modified_date') == '1') : ?>
		<span>Aktualisiert: <?= date('d.m.Y',strtotime($item->modified)) ?></span>
	<?php endif;?>

	<?php if ($item->params->get('show_publish_date') == '1') : ?>
		<span>Veröffentlicht: <?= date('d.m.Y',strtotime($item->publish_up)) ?></span>
	<?php endif;?>
	<?php if ($item->params->get('show_author') == '1') : ?>
		<span>Autor: <?= $item->author ?></span>
	<?php endif;?>
</div>
	<?php endif;?>
</div>
