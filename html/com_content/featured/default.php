<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


?>
<div class="blog-featured" itemscope itemtype="https://schema.org/Blog">
	<?php if ($this->params->get('show_page_heading') != 0) : ?>
		<div class="page-header">
			<h2>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h2>
		</div>
	<?php endif; ?>
	<?php
	// $numLeading = (int)$this->params->get('num_leading_articles', 1);
	// $numIntro   = (int) $this->params->def('num_intro_articles', 4);

	// $items      = $this->get('Items');
	?>

	<?php $leadingcount = 0; ?>
	<?php if (!empty($this->lead_items)) :?>
		<div class="blog-items items-leading <?php echo $this->params->get('blog_class_leading'); ?>">
			<?php foreach ($this->lead_items as $key => &$item) : ?>
				<div class="blog-item"
				itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
				<?php
				$this->item = & $item;
				if(json_decode($this->item->images)->float_intro == 'right'){
					echo $this->loadTemplate('one2');
				}else{
					echo $this->loadTemplate('one');
				}
				?>
			</div>
			<?php $leadingcount++; ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<?php if (!empty($this->intro_items)) :

	$num_columns      = (int) $this->params->get('num_columns');
	$class_34 = in_array($num_columns,[1])  ?'': ($num_columns == 3 ?' box_news version_thee': ($num_columns == 2 ?' box_news columns_two':' box_news version_four'));
$start_end = "start";
	?>
	<?php $blogClass = $this->params->get('blog_class', ''); ?>
	<?php if ((int) $this->params->get('num_columns') > 1) : ?>
	<?php $blogClass .= (int) $this->params->get('multi_column_order', 0) === 0 ? ' masonry-' : ' columns-'; ?>
	<?php $blogClass .= (int) $this->params->get('num_columns'); ?>
<?php endif; ?>
<div class="<?php echo $class_34.$blogClass; ?>">
	<?php foreach ($this->intro_items as $key => &$item) : ?>

			<?php
			$this->item = & $item;
			if($num_columns == 1) {
				if(json_decode($this->item->images)->float_intro == 'right'){
					echo $this->loadTemplate('one2');
				}else{
					echo $this->loadTemplate('one');
				}
			}
			if($num_columns == 2) echo $this->loadTemplate('two');
			if($num_columns == 3) echo $this->loadTemplate('one3');
			if($num_columns == 4) echo $this->loadTemplate('one4');

endforeach; ?>
</div>
<?php endif; ?>

<?php if (!empty($this->link_items)) : ?>
	<div class="items-more">
		<?php echo $this->loadTemplate('links'); ?>
	</div>
<?php endif; ?>

<?php if ($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2 && $this->pagination->pagesTotal > 1)) : ?>
<div class="w-100">
	<?php if ($this->params->def('show_pagination_results', 1)) : ?>
		<p class="counter float-end pt-3 pe-2">
			<?php echo $this->pagination->getPagesCounter(); ?>
		</p>
	<?php endif; ?>
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
<?php endif; ?>

</div>
