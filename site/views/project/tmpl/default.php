<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$cparams = JComponentHelper::getParams('com_media');

jimport('joomla.html.html.bootstrap');

// @todo - set itemtype data -- https://support.google.com/webmasters/answer/164506?hl=en&ref_topic=1088474
?>

<div class="pl-project<?php echo $this->pageclass_sfx?>" itemscope itemtype="http://data-vocabulary.org/Event">
    <div class="row-fluid">
        <!-- display page heading -->
        <?php if ($this->params->get('show_page_heading')) : ?>
            <h1>
                <?php echo $this->escape($this->params->get('page_heading')); ?>
            </h1>
        <?php endif; ?>

        <!-- display project name -->
        <?php if ($this->project->name && $this->params->get('show_name')) : ?>
            <div class="page-header">
                <h2>                
                    <span class="pl-project-name" itemprop="name"><?php echo $this->project->name; ?></span>
                    <?php if ($this->item->published == 0) : ?>
                        <span class="label label-warning"><span class="icon-edit"></span> <?php echo JText::_('JUNPUBLISHED'); ?></span>
                    <?php elseif ($this->item->featured): ?>
                        <span class="label label-success"><span class="icon-star"></span> <?php echo JText::_('JFEATURED'); ?></span>
                    <?php endif; ?>
                </h2>
            </div>
        <?php endif;  ?>

        <!-- display category with or without links -->
        <?php if ($this->params->get('show_project_category') == 'show_no_link') : ?>
            <h3>
                <span class="project-category"><?php echo $this->project->category_title; ?></span>
            </h3>
        <?php elseif ($this->params->get('show_project_category') == 'show_with_link') : ?>
            <?php $projectLink = ProjectlogHelperRoute::getCategoryRoute($this->project->catid); ?>
            <h3>
                <span class="project-category">
                    <a href="<?php echo $projectLink; ?>"><?php echo $this->escape($this->project->category_title); ?></a>
                </span>
            </h3>
        <?php endif; ?>

        <!-- render drop down for other projects -->
        <?php if ($this->params->get('show_project_list') && count($this->projects) > 1) : ?>
            <form action="#" method="get" name="selectForm" id="selectForm">
                <?php echo JText::_('COM_PROJECTLOG_SELECT_PROJECT'); ?>
                <?php echo JHtml::_('select.genericlist', $this->projects, 'id', 'class="inputbox" onchange="document.location.href = this.value"', 'link', 'name', $this->project->link);?>
            </form>
        <?php endif; ?>
        <?php echo $this->loadTemplate('identifiers'); ?>
    </div>
            
    <div class="row-fluid">
        <div class="span8">
            <!-- show project misc -->
            <?php if ($this->project->misc && $this->params->get('show_misc')) : ?>
                <?php echo '<h3>'. JText::_('COM_PROJECTLOG_OTHER_INFORMATION').'</h3>';  ?>
                <?php if ($this->project->image && $this->params->get('show_image')) : ?>
                    <div class="thumbnail pull-right">
                        <?php echo JHtml::_('image', $this->project->image, JText::_('COM_PROJECTLOG_IMAGE_DETAILS'), array('align' => 'middle', 'itemprop' => 'image')); ?>
                    </div>
                <?php endif; ?>
                <?php echo $this->project->misc; ?>
            <?php endif; ?>

            <!-- show project details -->
            <?php  echo '<h3>'. JText::_('COM_PROJECTLOG_DETAILS').'</h3>';  ?>
            <?php echo $this->loadTemplate('details'); ?>    

            <!-- show manager profile -->
            <?php if ($this->params->get('show_profile') && $this->project->manager && JPluginHelper::isEnabled('user', 'profile')) : ?>
                <?php echo $this->loadTemplate('profile'); ?>
            <?php endif; ?>            

            <!-- show email form -->
            <?php if ($this->params->get('show_email_form') && ($this->project->email_to || $this->project->manager)) : ?>
                <?php  echo $this->loadTemplate('form');  ?>
            <?php endif; ?>
        </div>
        <div class="span4 well">
            <!-- show links -->
            <?php if ($this->params->get('show_links')) : ?>
                <?php echo $this->loadTemplate('links'); ?>
            <?php endif; ?>           

            <!-- show articles created by manager -->
            <?php if ($this->params->get('show_articles') && $this->project->manager && $this->project->articles) : ?>
                <?php echo $this->loadTemplate('articles'); ?>
            <?php endif; ?>

            <!-- render tags -->
            <?php if ($this->params->get('show_tags', 1) && !empty($this->item->tags)) : ?>
                <?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
                <?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
            <?php endif; ?>
        </div>
    </div>
</div>