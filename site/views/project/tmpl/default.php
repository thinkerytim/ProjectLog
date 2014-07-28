<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
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

        <!-- render drop down for other projects -->
        <?php if ($this->params->get('show_project_list') && count($this->projects) > 1) : ?>
            <form action="#" method="get" name="selectForm" id="selectForm">
                <?php echo JText::_('COM_PROJECTLOG_SELECT_PROJECT'); ?>
                <?php echo JHtml::_('select.genericlist', $this->projects, 'id', 'class="inputbox" onchange="document.location.href = this.value"', 'link', 'name', $this->project->link);?>
            </form>
        <?php endif; ?>
        
        <!-- load identifiers template to show release id, job id, etc -->
        <?php echo $this->loadTemplate('identifiers'); ?>
    </div>
            
    <div class="row-fluid">
        <div class="span8 pl-project-main-container">
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
            
            <?php if ($this->project->general_loc && $this->params->get('show_general_loc')) : ?>
                <h3><?php echo JText::_('COM_PROJECTLOG_GENERAL_LOC'); ?></h3> 
                <?php echo nl2br ( $this->project->general_loc, true ); ?>
            <?php endif; ?>
            
            <?php if ($this->project->specific_loc && $this->params->get('show_specific_loc')) : ?>
                <h3><?php echo JText::_('COM_PROJECTLOG_LOCATION'); ?></h3> 
                <?php echo nl2br ( $this->project->specific_loc, true ); ?>
            <?php endif; ?>

            <!-- show email form -->
            <?php if ($this->params->get('show_email_form') && ($this->project->email_to || $this->project->manager)) : ?>
                <?php  echo $this->loadTemplate('form');  ?>
            <?php endif; ?>
        </div>
        <div class="span4 pl-project-sidebar-container">
            <?php echo $this->loadTemplate('sidebar'); ?>
        </div>
    </div>
</div>