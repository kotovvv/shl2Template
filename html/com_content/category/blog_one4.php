<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\CMS\HTML\HTMLHelper;

include_once 'function.php';

// Create a shortcut for params.
$params  = &$this->item->params;
$canEdit = $this->item->params->get('access-edit');
$info    = $this->item->params->get('info_block_position', 0);

// Check if associations are implemented. If they are, define the parameter.
$assocParam = (Associations::isEnabled() && $params->get('show_associations'));

$currentDate       = Factory::getDate()->format('Y-m-d H:i:s');
$isExpired         = !is_null($this->item->publish_down) && $this->item->publish_down < $currentDate;
$isNotPublishedYet = $this->item->publish_up > $currentDate;
$isUnpublished     = $this->item->state == ContentComponent::CONDITION_UNPUBLISHED || $isNotPublishedYet || $isExpired;
$images  = json_decode($this->item->images);
$no_image = $images->image_intro? '': ' not_img';
?>

<?php if ($canEdit) : ?>
	<?php echo LayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item)); ?>
<?php endif; ?>

<div class="box_news_ver_four <?php echo $no_image ?>"
itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
<?php if ($images->image_intro) :?>
	<div class="box_img_news">
		<img src="<?= $images->image_intro ?>" alt="<?= $images->image_intro_alt ?>">
	</div>
<?php endif;?>

<?php if ($params->get('show_title')) : ?>
	<h3 class="item-title title" itemprop="headline">
		<?php echo $this->escape($this->item->title); ?>
	</h3>
<?php endif; ?>
<?php	$show_introtext = $params->get('show_intro');
if ($show_introtext == '1')
{
	$this->item->introtext = cleanIntrotext($this->item->introtext);
}

	$this->item->displayIntrotext = strlen($this->item->introtext) > 1000 ? truncate($this->item->introtext, 1000) : $this->item->introtext;
echo '<p>'.$this->item->displayIntrotext.'</p>';?>
<?php if ($params->get('show_readmore') != '0'  && $this->item->readmore) : ?>
	<div><a class="btn_news" href="<?php echo Route::_(RouteHelper::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language)); ?>" itemprop="url">
		<?php if ($params->get('access-view') == false) : ?>
			<?php echo Text::_('COM_CONTENT_REGISTER_TO_READ_MORE'); ?>
		<?php elseif ($this->item->alternative_readmore) : ?>
			<?php echo $this->item->alternative_readmore; ?>
		<?php elseif ($params->get('show_readmore_title', 0)) : ?>
			<?php echo HTMLHelper::_('string.truncate', $this->item->title, $params->get('readmore_limit')); ?>
		<?php elseif ($params->get('show_readmore_title', 0)) : ?>
			<?php echo Text::_('COM_CONTENT_FEED_READMORE'); ?>
			<?php echo HTMLHelper::_('string.truncate', $this->item->title, $params->get('readmore_limit')); ?>
		<?php else : ?>
			<?php echo Text::_('COM_CONTENT_FEED_READMORE'); ?>
		<?php endif; ?>
	</a></div>
<?php endif; ?>
<?php if($params->get('show_create_date') || $params->get('show_modified_date') || $params->get('show_publish_date') || $params->get('show_author')) :?>
<div class="box_info">
	<?php if ($params->get('show_create_date') == '1') : ?>
		<span>Erstellt: <?= date('d.m.Y',strtotime($this->item->created)) ?></span>
	<?php endif;?>
	<?php if ($params->get('show_modified_date') == '1') : ?>
		<span>Aktualisiert: <?= date('d.m.Y',strtotime($this->item->modified)) ?></span>
	<?php endif;?>

	<?php if ($params->get('show_publish_date') == '1') : ?>
		<span>Veröffentlicht: <?= date('d.m.Y',strtotime($this->item->publish_up)) ?></span>
	<?php endif;?>
	<?php if ($params->get('show_author') == '1') : ?>
		<span>Autor: <?= $this->item->author ?></span>
	<?php endif;?>
</div>
<?php endif;?>
</div>