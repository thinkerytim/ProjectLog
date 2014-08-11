<?php
/**
 * @package     Projectlog.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'logs', JText::_('COM_PROJECTLOG_LOGS', true)); ?>
<div id="log-error-msg"></div>
    <?php if(!$this->item->id): ?>     
        <div class="alert alert-warning center"><?php echo JText::_('COM_PROJECTLOG_SAVE_FIRST'); ?></div>
    <?php else: ?>
        <?php if($this->canDo->get('projectlog.createlog')): ?>
            <div class="new-plitem-bt">
                <span><?php echo JText::_('COM_PROJECTLOG_WRITE_LOG'); ?></span>
            </div>
            <div class="new-plitem-cnt">
                <input type="text" id="title-plitem" name="title-plitem" value="" placeholder="<?php echo JText::_('COM_PROJECTLOG_LOG_TITLE'); ?>" />
                <textarea class="the-new-plitem"></textarea>
                <p>      
                    <div class="btn-group">
                    <a class="bt-add-plitem btn btn-success" disabled><?php echo JText::_('JSUBMIT'); ?></a>
                    <a class="bt-cancel-plitem btn btn-danger"><?php echo JText::_('JCANCEL'); ?></a>
                    </div>
                </p>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        <?php endif; ?>            
        <div class="plitem-container" id="plitem-log-container">            
            <?php
            foreach($this->logs as $log)
            {
                $log->date = JHtml::date($log->created,JText::_('DATE_FORMAT_LC2'));

                // Get gravatar Image 
                $log->gravatar = projectlogHtml::getGravatar($log->logger_email); 
                $delete_btn = ($this->canDo->get('projectlog.deletelog')) ? '<div class="bt-delete-log-plitem btn btn-danger" data-log-id="'.$log->log_id.'">'.JText::_('JACTION_DELETE').'</div>' : '';
                $edit_btn   = ($this->canDo->get('projectlog.editlog') || ($this->canDo->get('projectlog.editlog.own') && $log->created_by == $this->user->id)) ? '<a href="'.JRoute::_('index.php?option=com_projectlog&task=log.edit&id='.$log->log_id).'" class="btn btn-info" target="blank">'.JText::_('JACTION_EDIT').'</a>' : '';

                echo 
                    '<div class="plitem-cnt" id="logid-'.$log->log_id.'">
                        '.$log->gravatar['image'].'
                        <div class="pull-right btn-group">'.$edit_btn.$delete_btn.'</div>
                        <div class="theplitem">
                            <h5>'.$log->title.'</h5>
                            <br/>
                            <p>'.$log->description.'</p>
                            <p data-utime="1371248446" class="small plitem-dt">
                                '.$log->logger_name.' - '.$log->date.'
                            </p>
                        </div>
                        <div class="clearfix"></div>
                    </div>';                   
            }
            ?>
            
            <div class="clearfix"></div>
        </div>        
    <?php endif; ?>
<?php echo JHtml::_('bootstrap.endTab'); ?>

