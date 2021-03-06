<?php
/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2016 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Toggle onsite css class
$onsite_class = ($this->item->onsite) ? 'icon-thumbs-up' : 'icon-thumbs-down';
?>

<h3><?php echo JText::_('COM_PROJECTLOG_DETAILS'); ?></h3>
<ul class="pl-project-details">
    <li>
        <b><?php echo JText::_('COM_PROJECTLOG_STATUS'); ?>:</b>&nbsp;
        <?php echo JText::_('COM_PROJECTLOG_'.strtoupper($this->project->status)); ?>
    </li>
    <?php if ($this->project->release_date && $this->project->release_date != '0000-00-00 00:00:00' && $this->params->get('show_release_date')) : ?>
        <li>
            <b><?php echo JText::_('COM_PROJECTLOG_RELEASE_DATE'); ?>:</b>&nbsp;
            <?php echo JHTML::date($this->project->release_date, $this->params->get('pl_date_format')); ?>
        </li>
    <?php endif; ?>
    <?php if ($this->project->contract_from && $this->project->contract_from != '0000-00-00 00:00:00' && $this->params->get('show_contract_date')) : ?>
        <li>
            <b><?php echo JText::_('COM_PROJECTLOG_CONTRACT_DATE'); ?>:</b>&nbsp;
            <?php echo JHTML::date($this->project->contract_from, $this->params->get('pl_date_format')); ?>
            <?php echo ($this->project->contract_to) ? ' - '.JHTML::date($this->project->contract_to, $this->params->get('pl_date_format')) : ''; ?>
        </li>
    <?php endif; ?>
    <?php if ($this->project->deployment_from && $this->project->deployment_from != '0000-00-00 00:00:00' && $this->params->get('show_deployment_date')) : ?>
        <li>
            <b><?php echo JText::_('COM_PROJECTLOG_DEPLOYMENT_DATE'); ?>:</b>&nbsp;
            <?php echo JHTML::date($this->project->deployment_from, $this->params->get('pl_date_format')); ?>
            <?php echo ($this->project->deployment_to) ? ' - '.JHTML::date($this->project->deployment_to, $this->params->get('pl_date_format')) : ''; ?>
        </li>
    <?php endif; ?>
    <?php if ($this->project->project_type && $this->params->get('show_project_type')) : ?>
        <li>
            <b><?php echo JText::_('COM_PROJECTLOG_PROJECT_TYPE'); ?>:</b>&nbsp;
            <?php echo $this->project->project_type; ?>
        </li>
    <?php endif; ?>
    <?php if ($this->project->manager && $this->params->get('show_manager')) : ?>
        <li>
            <b><?php echo JText::_('COM_PROJECTLOG_MANAGER'); ?>:</b>&nbsp;
            <?php echo $this->project->manager_name; ?>
        </li>
    <?php endif; ?>
    <?php if ($this->project->chief && $this->params->get('show_chief')) : ?>
        <li>
            <b><?php echo JText::_('COM_PROJECTLOG_CHIEF'); ?>:</b>&nbsp;
            <?php echo $this->project->chief; ?>
        </li>
    <?php endif; ?>
    <?php if ($this->project->technicians && $this->params->get('show_technicians')) : ?>
        <li>
            <b><?php echo JText::_('COM_PROJECTLOG_TECHNICIAN'); ?>:</b>&nbsp;
            <?php echo $this->project->technicians; ?>
        </li>
    <?php endif; ?>
    <?php if ($this->project->client && $this->params->get('show_client')) : ?>
        <li>
            <b><?php echo JText::_('COM_PROJECTLOG_CLIENT'); ?>:</b>&nbsp;
            <?php echo $this->project->client; ?>
        </li>
    <?php endif; ?>

    <?php if ($this->project->webpage && $this->params->get('show_webpage')) : ?>
        <li>
            <b><?php echo JText::_('COM_PROJECTLOG_WEBSITE'); ?>:</b>&nbsp;
            <a href="<?php echo $this->project->webpage; ?>" target="_blank" itemprop="url">
            <?php echo JStringPunycode::urlToUTF8($this->project->webpage); ?></a>
        </li>
    <?php endif; ?>

    <!-- display category with or without links -->
    <?php if ($this->params->get('show_project_category') == 'show_no_link') : ?>
        <li>
            <b><?php echo JText::_('COM_PROJECTLOG_CATEGORY'); ?>:</b>&nbsp;
            <?php echo $this->project->category_title; ?>
        </li>
    <?php elseif ($this->params->get('show_project_category') == 'show_with_link') : ?>
        <?php $projectLink = ProjectlogHelperRoute::getCategoryRoute($this->project->catid); ?>
        <li>
            <b><?php echo JText::_('COM_PROJECTLOG_CATEGORY'); ?>:</b>&nbsp;
            <a href="<?php echo $projectLink; ?>"><?php echo $this->escape($this->project->category_title); ?></a>
        </li>
    <?php endif; ?>
    <li>
        <b><?php echo JText::_('COM_PROJECTLOG_CREW_ON_SITE'); ?>:</b>&nbsp;
        <span class="<?php echo $onsite_class; ?>"></span>
    </li>
    <li class="divider"></li>
</ul>
