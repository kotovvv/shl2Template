<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

if (!$list)
{
	return;
}
var_dump($params);exit();
$count = count($list);
if ($count == 1) { ?>
<div class="box_news">
<?php  }
 if ($count == 3) {?>
<div class="box_news version_thee">
	<?php }
 if ($count == 4) {?>
<div class="box_news version_four">
	<?php } ?>


	<?php $i = 1; foreach ($list as $item) : ?>
		<?php if ($count <= 2) {
require ModuleHelper::getLayoutPath('mod_articles_news', 'one');
		}?>

		<?php if ($count == 3) { ?>
<?php require ModuleHelper::getLayoutPath('mod_articles_news', 'three'); ?>
	<?php	}?>
		<?php if ($count == 4) { ?>
<?php require ModuleHelper::getLayoutPath('mod_articles_news', 'four'); ?>
	<?php	}?>

	<?php $i++; endforeach; ?>

</div>
