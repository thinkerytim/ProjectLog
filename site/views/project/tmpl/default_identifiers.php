<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<ul class="breadcrumb">
    <?php if ($this->project->release_id && $this->params->get('show_release_id')) : ?>
        <li>
            <b><?php echo JText::_('COM_PROJECTLOG_RELEASE_ID'); ?>:</b> 
            <span class="pl-identifier-item"><?php echo $this->project->release_id; ?></span>
            <span class="divider">/</span>
        </li>
    <?php endif; ?>
    <?php if ($this->project->job_id && $this->params->get('show_job_id')) : ?>
        <li>
            <b><?php echo JText::_('COM_PROJECTLOG_JOB_ID'); ?>:</b>
            <span class="pl-identifier-item"><?php echo $this->project->job_id; ?></span>
            <span class="divider">/</span>
        </li>
    <?php endif; ?>
    <?php if ($this->project->task_id && $this->params->get('show_task_id')) : ?>
        <li>
            <b><?php echo JText::_('COM_PROJECTLOG_TASK_ID'); ?>:</b> 
            <span class="pl-identifier-item"><?php echo $this->project->task_id; ?></span>
            <span class="divider">/</span>
        </li>
    <?php endif; ?>
    <?php if ($this->project->workorder_id && $this->params->get('show_workorder_id')) : ?>
        <li>
            <b><?php echo JText::_('COM_PROJECTLOG_WORKORDER_ID'); ?>:</b> 
            <span class="pl-identifier-item"><?php echo $this->project->workorder_id; ?></span>
        </li>
    <?php endif; ?>
</ul>
<div class="clearfix"></div>