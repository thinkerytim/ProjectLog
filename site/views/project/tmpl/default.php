<?php
/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2016 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

jimport('joomla.html.html.bootstrap');

// Create shortcuts to some parameters.
$params  = $this->project->params;
$canEdit = $params->get('access-edit');

// @todo - set itemtype data -- https://support.google.com/webmasters/answer/164506?hl=en&ref_topic=1088474
?>

<div class="pl-project<?php echo $this->pageclass_sfx?>" itemscope itemtype="http://data-vocabulary.org/Event">
    <div class="row-fluid">
        <?php // display page heading ?>
        <?php if ($this->params->get('show_page_heading')) : ?>
            <h1>
                <?php echo $this->escape($this->params->get('page_heading')); ?>
            </h1>
        <?php endif; ?>

        <?php // display project name ?>
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
        
        <?php //if print layout, show print button; otherwise, show email,print,edit dropdown ?>
        <?php if (!$this->print) : ?>
            <?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
                <?php echo JLayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->project, 'print' => false)); ?>
            <?php endif; ?>
            <div class="clearfix"></div>
        <?php else : ?>
            <div id="pop-print" class="btn hidden-print">
                <?php echo JHtml::_('icon.print_screen', $this->item, $params); ?>
            </div>
            <div class="clearfix"></div>
        <?php endif; ?>

        <?php // render drop down for other projects ?>
        <?php if ($this->params->get('show_project_list') && count($this->projects) > 1 && !$this->print) : ?>
            <form action="#" method="get" name="selectForm" id="selectForm">
                <?php echo JText::_('COM_PROJECTLOG_SELECT_PROJECT'); ?>
                <?php echo JHtml::_('select.genericlist', $this->projects, 'id', 'class="inputbox" onchange="document.location.href = this.value"', 'link', 'name', $this->project->link);?>
            </form>
        <?php endif; ?>

        <?php //load identifiers template to show release id, job id, etc ?>
        <?php echo $this->loadTemplate('identifiers'); ?>
    </div>
            
    <div class="row-fluid">
        <div class="span8 pl-project-main-container">
            <?php // show project misc ?>
            <?php if ($this->project->misc && $this->params->get('show_misc')) : ?>
                <?php echo '<h3>'. JText::_('COM_PROJECTLOG_OTHER_INFORMATION').'</h3>';  ?>
                <?php if ($this->project->image && $this->params->get('show_image')) : ?>
                    <div class="thumbnail pull-right">
                        <?php echo JHtml::_('image', $this->project->image, JText::_('COM_PROJECTLOG_IMAGE_DETAILS'), array('align' => 'middle', 'itemprop' => 'image')); ?>
                    </div>
                <?php endif; ?>
                <?php echo $this->project->misc; ?>
            <?php endif; ?> 
            
            <?php // show general location ?>
            <?php if ($this->project->general_loc) : ?>
                <h3><?php echo JText::_('COM_PROJECTLOG_GEN_LOC'); ?></h3> 
                <?php echo nl2br ( $this->project->general_loc, true ); ?>
            <?php endif; ?>
            
            <?php // show specific location ?>
            <?php if ($this->project->specific_loc && $this->params->get('show_specific_loc')) : ?>
                <h3><?php echo JText::_('COM_PROJECTLOG_SPEC_LOC'); ?></h3> 
                <?php echo nl2br ( $this->project->specific_loc, true ); ?>
            <?php endif; ?>
                
            <hr class="pl-project-divider"/>

            <?php echo JHtml::_('bootstrap.startTabSet', 'projectTab', array('active' => (count($this->logs)) ? 'logs' : 'contact')); ?>
            
                <?php // load logs template ?>
                <?php echo $this->loadTemplate('logs'); ?>
            
                <?php // if not print layout, show documents ?>
                <?php if(!$this->print): ?>
                    <?php echo $this->loadTemplate('docs'); ?>
                <?php endif; ?>

                <?php // show project contact form ?>
                <?php if ($this->params->get('show_email_form') && ($this->project->email_to || $this->project->manager) && !$this->print) : ?>                    
                    <?php  echo $this->loadTemplate('form');  ?>                    
                <?php endif; ?>
                
            <?php echo JHtml::_('bootstrap.endTabSet'); ?>
        </div>
        <div class="span4 pl-project-sidebar-container">
            <?php echo $this->loadTemplate('sidebar'); ?>
        </div>
    </div>
</div>
<?php if($this->params->get('show_footer')) echo projectlogHTML::buildThinkeryFooter();  ?>

<?php //@todo: remove the script from the page and load externally ?>
<?php if(!$this->print): ?>
    <?php echo $this->loadTemplate('script'); ?>
<?php endif; ?>