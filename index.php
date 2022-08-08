<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.shl2
 *
 * @copyright   (C) 2022 Bigriseinc
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * This is a heavily stripped down/modified version of the default Cassiopeia template, designed to build new templates off of.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app = Factory::getApplication();
$wa  = $this->getWebAssetManager();
$doc = JFactory::getDocument();


// Add Favicon from images folder
$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'icon', 'rel', ['type' => 'image/x-icon']);


// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$menu     = $app->getMenu()->getActive();
$pageclass = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';

// Get this template's path
$templatePath = 'templates/' . $this->template;

// Load our frameworks
JHtml::_('bootstrap.framework');
JHtml::_('jquery.framework');

//Register our web assets (Css/JS)
$wa->useStyle('template.shl2.mainstyles');
$wa->useScript('template.shl2.scripts');

//Set viewport
$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>

 <jdoc:include type="metas" />
 <?php $doc->addStyleSheet('/media/templates/site/'.$this->template.'/css/bootstrap.min.css'); ?>
 <jdoc:include type="styles" />
 <jdoc:include type="scripts" />

</head>
<?php
$menu2 = $app->getMenu();
if ($menu2->getActive() == $menu2->getDefault()) {
  $tag = 'h1';
}
else {
  $tag = 'h6';
};
 ?>
<body>
 <section class="header">
  <span class="screen-darken"></span>
  <div class="navbar">
   <div class="container">
    <div class="logo">
     <a class="site_logo" href="/">
      <<?php echo $tag ?> class="big_text_logo"><?php echo $this->params->get('Site_Title'); ?> <span class="smal_text_logo"><?php echo $this->params->get('Site_Town'); ?></span></<?php echo $tag ?>>
      <img class="img_logo_site" src="<?php echo $this->params->get('site_logo_Image'); ?>" alt="logo">
    </a>
  </div>
  <div class="nemu">
   <nav id="navbar_main" class="mobile-offcanvas navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="offcanvas-header">
     <button class="btn-close float-end"></button>
   </div>
   <?php if ($this->countModules('menu')) : ?>
     <div class="collapse navbar-collapse"><jdoc:include type="modules" name="menu" style="none" /></div>
   <?php endif; ?>
 </nav>
</div>
</div>
</div>
</section>
<section class="breadcrumb">
  <div class="container">
   <nav style="--bs-breadcrumb-divider: '/';" aria-label="breadcrumb">
     <?php if ($this->countModules('breadcrumbs')) : ?>
       <div class="breadcrumbs">
         <jdoc:include type="modules" name="breadcrumbs" style="none" />
       </div>
     <?php endif; ?>
   </nav>
 </div>
</section>
  <?php if ($this->countModules('content-top')) : ?>
   <div class="content-top">
     <jdoc:include type="modules" name="content-top" style="none" />
   </div>
 <?php endif; ?>
<section class="box_content">
  <div class="container">
   <div class="row">
    <?php if ($this->countModules('sidebar-left')) : ?>
      <div class="box_left">
       <jdoc:include type="modules" name="sidebar-left" style="none" />
     </div>
   <?php endif; ?>
   <div class="box_content_centre">
    <main>
          <?php if ($this->countModules('center-top')) : ?>
      <div class="center-top">
       <jdoc:include type="modules" name="center-top" style="none" />
     </div>
   <?php endif; ?>
      <jdoc:include type="component" />
                <?php if ($this->countModules('center-bottom')) : ?>
      <div class="center-bottom">
       <jdoc:include type="modules" name="center-bottom" style="none" />
     </div>
   <?php endif; ?>
    </main>
  </div>
  <?php if ($this->countModules('sidebar-right')) : ?>
   <div class="box_right">
    <jdoc:include type="modules" name="sidebar-right" style="none" />
  </div>
<?php endif; ?>
</div>

<section class="news_botton">
 <?php if ($this->countModules('pre-footer')) : ?>
   <div class="pre-footer">
    <jdoc:include type="modules" name="pre-footer" style="none" />
  </div>
<?php endif; ?>
</section>

</div>
</section>

<section class="footer">
 <div class="menu_footer">
  <div class="container">
   <?php if ($this->countModules('menu-footer')) : ?>
     <div class="menu-footer">
      <jdoc:include type="modules" name="menu-footer" style="none" />
    </div>
  <?php endif; ?>
</div>
</div>
   <?php if ($this->countModules('footer')) : ?>
      <jdoc:include type="modules" name="footer" style="none" />
  <?php endif; ?>
</section>

<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
