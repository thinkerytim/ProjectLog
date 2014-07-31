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
            
            <?php if ($this->project->general_loc) : ?>
                <h3><?php echo JText::_('COM_PROJECTLOG_GEN_LOC'); ?></h3> 
                <?php echo nl2br ( $this->project->general_loc, true ); ?>
            <?php endif; ?>
            
            <?php if ($this->project->specific_loc && $this->params->get('show_specific_loc')) : ?>
                <h3><?php echo JText::_('COM_PROJECTLOG_SPEC_LOC'); ?></h3> 
                <?php echo nl2br ( $this->project->specific_loc, true ); ?>
            <?php endif; ?>
                
            <hr class="pl-project-divider"/>

            <?php echo JHtml::_('bootstrap.startTabSet', 'projectTab', array('active' => (count($this->logs)) ? 'logs' : 'contact')); ?>
                <?php if(count($this->logs)): ?>
                    <?php echo JHtml::_('bootstrap.addTab', 'projectTab', 'logs', JText::_('COM_PROJECTLOG_LOGS', true)); ?>
                        <?php
                        foreach($this->logs as $log)
                        {
                            $log->date = JHtml::date($log->created,JText::_('DATE_FORMAT_LC2'));

                            // Get gravatar Image 
                            $log->gravatar = projectlogHtml::getGravatar($log->logger_email); 
                            $delete_btn = ($this->canDo->get('projectlog.deletelog')) ? '<div class="bt-delete-log btn btn-danger" data-log-id="'.$log->log_id.'">'.JText::_('JACTION_DELETE').'</div>' : '';
                            $edit_btn   = ($this->canDo->get('projectlog.editlog') || ($this->canDo->get('projectlog.editlog.own') && $log->created_by == $this->user->id)) ? '<a href="'.JRoute::_('index.php?option=com_iproperty&task=log.edit&id='.$log->log_id).'" class="btn btn-info">'.JText::_('JACTION_EDIT').'</a>' : '';

                            echo 
                                '<div class="log-cnt" id="logid-'.$log->log_id.'">
                                    '.$log->gravatar['image'].'
                                    <div class="pull-right btn-group">'.$edit_btn.$delete_btn.'</div>
                                    <div class="thelog">
                                        <h5>'.$log->title.'</h5>
                                        <br/>
                                        <p>'.$log->description.'</p>
                                        <p data-utime="1371248446" class="small log-dt">
                                            '.$log->logger_name.' - '.$log->date.'
                                        </p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>';                  
                        }
                        ?>
                    <?php echo JHtml::_('bootstrap.endTab'); ?>
                <?php endif; ?>

                <!-- show email form -->
                <?php if ($this->params->get('show_email_form') && ($this->project->email_to || $this->project->manager)) : ?>
                    <?php echo JHtml::_('bootstrap.addTab', 'projectTab', 'contact', JText::_('COM_PROJECTLOG_CONTACT', true)); ?>
                        <?php  echo $this->loadTemplate('form');  ?>
                    <?php echo JHtml::_('bootstrap.endTab'); ?>
                <?php endif; ?>
            <?php echo JHtml::_('bootstrap.endTabSet'); ?>
        </div>
        <div class="span4 pl-project-sidebar-container">
            <?php echo $this->loadTemplate('sidebar'); ?>
        </div>
    </div>
</div>
<?php if($this->params->get('show_footer')) echo projectlogHTML::buildThinkeryFooter();  ?>