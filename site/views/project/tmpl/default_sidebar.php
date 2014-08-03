<?php
/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php echo $this->loadTemplate('details'); //show details ?>

<?php if ($this->params->get('show_links')) : //show links if enabled ?>
    <?php echo $this->loadTemplate('links'); ?>
<?php endif; ?>           

<?php if ($this->params->get('show_articles') && $this->project->manager && $this->project->articles) : //show articles created by manager if enabled ?>
    <?php echo $this->loadTemplate('articles'); ?>
<?php endif; ?>

<?php if ($this->params->get('show_tags', 1) && !empty($this->item->tags)) : //render tags if enabled and not empty ?>
    <?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
    <?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
<?php endif; ?>